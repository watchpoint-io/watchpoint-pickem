--TEST--
db query() runs a query
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

$result = $db->query('SELECT 1');
var_dump(get_class($result));
$result = $db->query('SELECT $1', ['1']);
var_dump(get_class($result));

try {
    @$db->query('SELECT 1 FROM fail');
} catch (DbException $e) {
    var_dump($e->getMessage());
}

?>
--EXPECTF--
string(%d) "DbResult"
string(%d) "DbResult"
string(%d) "Failed to run query"
