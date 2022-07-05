<?php

namespace AndyH\LaravelBgg\Traits;

use AndyH\LaravelBgg\Services\BggClient;

trait BggUserable
{
    public function importLibrary()
    {
        $bgg = new BggClient();

//        dd($bgg->getItemsByUser($this->bgg_username));

        foreach ($bgg->getItemsByUser($this->bgg_username) as $item) {
            // if already associated, skip
            $this->boardGames()->attach($item);
        }
        $this->save();
    }
}
