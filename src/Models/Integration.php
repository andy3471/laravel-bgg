<?php

namespace AndyH\LaravelBgg\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    public static string $bggType = 'boardgameintegration';

//    Needs to relate one game to another, like frosthaven to gloomhaven
}
