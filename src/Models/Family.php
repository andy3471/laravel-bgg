<?php

namespace AndyH\Models;

use AndyH\Traits\BggLinkable;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use BggLinkable;

    static string $bggType = 'boardgamefamily';

    public function boardGames()
    {
        return $this->morphedByMany(BoardGame::class, 'familyable');
    }

    public function expansions()
    {
        return $this->morphedByMany(Expansion::class, 'familyable');
    }

}
