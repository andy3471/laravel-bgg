<?php

namespace AndyH\LaravelBgg\Models;

use AndyH\LaravelBgg\Contracts\BggApi;
use AndyH\LaravelBgg\Traits\BggApiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class BoardGame extends Model implements BggApi
{
    use BggApiable, Searchable;

    protected $fillable = ['description'];

    static string $bggType = 'boardgame';

    static function transformBggDetails($details)
    {
        // dd($details);
        return [
            'name' => $details->name->attributes()->value->__toString(),
            'description' => $details->description->__toString(),
            'min_players' => $details->minplayers->attributes()->value->__toString(),
            'max_players' => $details->maxplayers->attributes()->value->__toString(),
            'play_time' => $details->playingtime->attributes()->value->__toString(),
            'min_play_time' => $details->minplaytime->attributes()->value->__toString(),
            'max_play_time' => $details->maxplaytime->attributes()->value->__toString(),
            'year_published' => $details->yearpublished->attributes()->value->__toString(),
//            TODO - categories, mechanics, designers, artists, publishers, expansions, integration
        ];
    }

    static function getBggType()
    {
        return self::$bggType;
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
