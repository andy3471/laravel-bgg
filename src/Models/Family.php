<?php

namespace AndyH\LaravelBgg\Models;

use AndyH\LaravelBgg\Traits\BggLinkable;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use BggLinkable;

    protected $fillable = ['bgg_id', 'name'];

    public static string $bggType = 'boardgamefamily';

    public function boardGames()
    {
        return $this->morphedByMany(BoardGame::class, 'familyable');
    }

    public function expansions()
    {
        return $this->morphedByMany(Expansion::class, 'familyable');
    }
}
