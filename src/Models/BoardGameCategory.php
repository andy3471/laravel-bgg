<?php

namespace AndyH\Models;

use AndyH\Traits\BggLinkable;
use Illuminate\Database\Eloquent\Model;

class BoardGameCategory extends Model
{
    use BggLinkable;

    static string $bggType = 'boardgamecategory';

    public function boardGames()
    {
        return $this->morphedByMany(BoardGame::class, 'categorisable');
    }

    public function expansions()
    {
        return $this->morphedByMany(Expansion::class, 'categorisable');
    }
}
