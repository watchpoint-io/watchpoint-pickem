<?php

$app = require_once 'app.php';
$app->run(function () {
    $this->option('session_read_only', false);
});

// Already logged in
if (!empty($_SESSION['user'])) {
    $app->redirect('/home');
}

$scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'https';
$host = $_SERVER['HTTP_HOST'];
$redirect_uri = "{$scheme}://{$host}/auth";

$bnet_auth = new BnetAuth([
    'key'          => getenv('BNET_API_KEY'),
    'secret'       => getenv('BNET_API_SECRET'),
    'redirect_uri' => $redirect_uri,
    'code'         => $_GET['code'] ?? '',
    'state'        => $_GET['state'] ?? '',
    'auth_handler' => function($auth_url) use ($app) {
        if (isset($_GET['prev_uri'])) {
            $_SESSION['prev_uri'] = $_GET['prev_uri'];
        }
        return $app->redirect($auth_url);
    },
]);

try {
    $bnet_user = $bnet_auth->getUser();
    $user = User::findByBnetAccountId($bnet_user['id']);
    if (!$user) {
        $user = User::create([
            'bnet_account_id' => $bnet_user['id'],
            'bnet_tag'        => $bnet_user['battletag'],
        ]);
    }
    $_SESSION['user'] = $user->getId();
    if (!empty($_SESSION['prev_uri'])) {
        $prev_uri = $_SESSION['prev_uri'];
        unset($_SESSION['prev_uri']);
        $app->redirect($prev_uri);
    } else {
        $app->redirect('/home');
    }
} catch (BnetAuthException $e) {
    $app->redirect('/auth/error');
}
