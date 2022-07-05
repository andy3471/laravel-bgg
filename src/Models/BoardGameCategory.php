<?php

namespace AndyH\LaravelBgg\Models;

use AndyH\LaravelBgg\Traits\BggLinkable;
use Illuminate\Database\Eloquent\Model;

class BoardGameCategory extends Model
{
    use BggLinkable;

    public static string $bggType = 'boardgamecategory';

    public function boardGames()
    {
        return $this->morphedByMany(BoardGame::class, 'categorisable');
    }

    public function expansions()
    {
        return $this->morphedByMany(Expansion::class, 'categorisable');
    }
}
