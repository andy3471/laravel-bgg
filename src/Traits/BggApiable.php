<?php

namespace AndyH\LaravelBgg\Traits;

use AndyH\LaravelBgg\Services\BggClient;

trait BggApiable
{
    static function bggSearchRaw($search)
    {
        $bgg = new BggClient();
        return $bgg->search($search);
    }

    static function bggSearch($search)
    {
        $results = self::bggSearchRaw($search);
        $collection = collect();

        foreach ($results as $result) {
            $item = self::findByBggId($result['bgg_id']);

            if (!$item) {
                $item = new self();
                $item->bgg_id = $result['bgg_id'];
                $item->name = $result['name'];
                $item->save();
            }

            $collection->push($item);
        }
        return $collection;
    }

    public function scopeToBeScraped($query)
    {
//        TODO - Implement a scraped at config
        return $query::where('bgg_id', '!=', null)
            ->where('bgg_last_scraped_at', '<', now()->subDays(7))
            ->orWhere('bgg_last_scraped_at', null);
    }

    static function findByBggId($bggId)
    {
        $item = self::where('bgg_id', $bggId)->first();

        if (!$item) {
            $bgg = new BggClient();

            try {
                $details = $bgg->getThingById($bggId);
                $details = self::transformBggDetails($details);
            } catch (\Exception $e) {
                return null;
            }

            $item = new self();
            $item->bgg_id = $bggId;
            $item->name = $details['name'];
            $item->update($details);
            $item->bgg_last_scraped_at = now();
            $item->save();
        }
        return $item;
    }

    public function scrapeFromBgg()
    {
        $bgg = new BggClient();
        $details = $bgg->getThingById($this->bgg_id);
        $details = self::transformBggDetails($details);
        $this->update($details);
        $this->bgg_last_scraped_at = now();
        $this->save();
        return $this;
    }

    public function getDetailsFromBgg()
    {
        $bgg = new BggClient();
        return $bgg->getThingById($this->bgg_id);
    }

    public function shouldBeUpdatedFromBgg()
    {
//        TODO - Implement setting for cahcing time BGGs
        return $this->bgg_id && ( $this->bgg_last_scraped_at == null || $this->bgg_last_scraped_at->diffInDays(now()) > 7  );
    }

}
