<?php

namespace AndyH\Models;

use AndyH\Traits\BggApiable;
use AndyH\Traits\BggLinkable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Expansion extends Model
{
    use BggApiable, BggLinkable, Searchable;

    static string $bggType = 'boardgameexpansion';

    protected $fillable = ['description'];

    static function transformBggDetails($details)
    {
        return [
            'name' => $details->name->__toString(),
            'description' => $details->description->__toString(),
            'min_players' => $details->minplayers->attributes()->value->__toString(),
            'max_players' => $details->maxplayers->attributes()->value->__toString(),
            'play_time' => $details->playingtime->attributes()->value->__toString(),
            'min_play_time' => $details->minplaytime->attributes()->value->__toString(),
            'max_play_time' => $details->maxplaytime->attributes()->value->__toString(),
            'year_published' => $details->yearpublished->attributes()->value->__toString(),
//            TODO - categories, mechanics, designers, artists, publishers
        ];
    }

    // Relations
    public function artists()
    {
        return $this->morphToMany(Artist::class, 'artistable');
    }

    public function categories()
    {
        return $this->morphToMany(BoardGameCategory::class, 'categorisable');
    }

    public function designers()
    {
        return $this->morphToMany(Designer::class, 'designerable');
    }

    public function families()
    {
        return $this->morphToMany(Family::class, 'familyable');
    }

    public function mechanics()
    {
        return $this->morphToMany(Mechanic::class, 'mechanicable');
    }

    public function publishers()
    {
        return $this->morphToMany(Publisher::class, 'publisherable');
    }
}
