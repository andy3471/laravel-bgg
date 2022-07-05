<?php

return [
    'cache_time' => env('BGG_CACHE_TIME', 60),
    'api_url' => env('BGG_API_URL', 'https://www.boardgamegeek.com/xmlapi2/'),

    'boardgame' => \AndyH\LaravelBgg\Models\BoardGame::class,
    'boardgameartist' => \AndyH\LaravelBgg\Models\Artist::class,
    'boardgamecategory' => \AndyH\LaravelBgg\Models\BoardGameCategory::class,
    'boardgamedesigner' => \AndyH\LaravelBgg\Models\Designer::class,
    'boardgameexpansion' => \AndyH\LaravelBgg\Models\Expansion::class,
    'boardgamefamily' => \AndyH\LaravelBgg\Models\Family::class,
    'boardgameintegration' => \AndyH\LaravelBgg\Models\Integration::class,
    'boardgamemechanic' => \AndyH\LaravelBgg\Models\Mechanic::class,
    'boardgamepublisher' => \AndyH\LaravelBgg\Models\Publisher::class,
];
