<?php

namespace AndyH\LaravelBgg\Models;

use AndyH\LaravelBgg\Traits\BggLinkable;
use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
    use BggLinkable;

    static string $bggType = 'boardgamedesigner';

    public function boardGames()
    {
        return $this->morphedByMany(BoardGame::class, 'designerable');
    }

    public function expansions()
    {
        return $this->morphedByMany(Expansion::class, 'designerable');
    }
}
