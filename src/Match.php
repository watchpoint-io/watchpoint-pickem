<?php

class Match extends Model
{
    protected static $table_name = 'matches';
    protected static $primary_key = 'match_id';
    protected $data = [
        'match_id'     => Model::DEFAULT,
        'blizz_id'     => null,
        'away_team_id' => null,
        'home_team_id' => null,
        'game_time'    => null,
        'created_at'   => Model::DEFAULT,
        'updated_at'   => Model::DEFAULT,
    ];

    public static function findByBlizzId(int $blizz_id): ?Match
    {
        return self::findWhere("blizz_id = $1", [$blizz_id]);
    }
}
