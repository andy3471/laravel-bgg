<?php

namespace AndyH\Models;

use AndyH\Traits\BggLinkable;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use BggLinkable;

    static string $bggType = 'boardgamemechanic';

    public function boardGames()
    {
        return $this->morphedByMany(BoardGame::class, 'mechanicable');
    }

    public function expansions()
    {
        return $this->morphedByMany(Expansion::class, 'mechanicable');
    }

}
