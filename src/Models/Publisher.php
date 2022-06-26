<?php

namespace AndyH\LaravelBgg\Models;

use AndyH\LaravelBgg\Traits\BggLinkable;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use BggLinkable;

    static string $bggType = 'boardgamepublisher';

    public function boardGames()
    {
        return $this->morphedByMany(BoardGame::class, 'publisherable');
    }

    public function expansions()
    {
        return $this->morphedByMany(Expansion::class, 'publisherable');
    }

}
