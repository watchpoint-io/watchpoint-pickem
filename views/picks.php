<a href="/pools/<?= $pool['slug'] ?>">&larr; Back to Pool</a>
<h3>Make your Picks</h3>
<form action="/pools/<?= $pool['slug'] ?>/picks" method="get">
    <div class="form-group col-3 col-md-2">
    <label for="week">Week</label>
    <select name="w" class="form-control week-selector-js" id="week">
    <?php foreach(range(1, $number_of_weeks) as $week): ?>
    <option value="<?= $week ?>"
        <?= ($selected_week == $week ? ' selected="selected"' : '') ?>><?= $week ?></option>
    <?php endforeach ?>
    </select>
    </div>
</form>

<?php foreach ($matches as $match): ?>
<hr>
<div class="container p-0">
    <div class="row">
        <div class="col">
            <span class="match-datetime-js"
                data-datetime="<?= $match['game_time'] ?>">&nbsp;</span>
        </div>
    </div>
    <div class="row mt-3 mb-3 align-items-center">
        <?= $this->partial(__DIR__ . '/_pick_team.php', [
            'abbr'            => $match['away_team_abbr'],
            'name'            => $match['away_team_name'],
            'team_id'         => $match['away_team_id'],
            'match_id'        => $match['match_id'],
            'match_started'   => $match['match_started'],
            'pick_team_id'    => $match['pick_team_id'],
            'winning_team_id' => $match['winning_team_id'],
        ]) ?>
        <div class="col-2 text-center align-middle">at</div>
        <?= $this->partial(__DIR__ . '/_pick_team.php', [
            'abbr'            => $match['home_team_abbr'],
            'name'            => $match['home_team_name'],
            'team_id'         => $match['home_team_id'],
            'match_id'        => $match['match_id'],
            'match_started'   => $match['match_started'],
            'pick_team_id'    => $match['pick_team_id'],
            'winning_team_id' => $match['winning_team_id'],
        ]) ?>
    </div>
</div>
<?php endforeach ?>

<script type="text/javascript">
$(function() {
    $('.team-pick-js').on('click', function(e) {
        e.preventDefault();
        var team_id = $(this).data('team_id');
        var match_id = $(this).data('match_id');

        $.post('/pools/<?= $pool['slug'] ?>/picks', {
            'match_id': match_id,
            'team_id': team_id
        }, function(res) {
            $('.team-pick-js[data-match_id=' + match_id + ']').each(function() {
                var e_team_id = $(this).data('team_id');
                if (e_team_id == team_id) {
                    $(this).html('Picked').addClass('bg-primary text-white');
                } else {
                    $(this).html('Pick').removeClass('bg-primary text-white');
                }
            });
        });
    });
    $('.week-selector-js').on('change', function(e) {
        this.form.submit();
    });

    $('.match-datetime-js').each(function() {
        var m_date = new Date($(this).data('datetime'));
        $(this).html(m_date.toLocaleString(undefined, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric'
        }));
    });
});
</script>
