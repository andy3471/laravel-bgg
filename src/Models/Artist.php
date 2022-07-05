<?php

namespace AndyH\LaravelBgg\Models;

use AndyH\LaravelBgg\Traits\BggLinkable;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use BggLinkable;

    public static string $bggType = 'boardgameartist';

    public function boardGames()
    {
        return $this->morphedByMany(BoardGame::class, 'artistable');
    }

    public function expansions()
    {
        return $this->morphedByMany(Expansion::class, 'artistable');
    }
}
