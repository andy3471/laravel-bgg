<?php

namespace AndyH\LaravelBgg\Traits;

use AndyH\LaravelBgg\Services\BggClient;
use Illuminate\Support\Arr;

trait BggApiable
{
    public static function bggSearchRaw($search)
    {
        $bgg = new BggClient();

        return $bgg->search($search);
    }

    public static function bggSearch($search, $limit = 25)
    {
        $results = self::bggSearchRaw($search);
        $collection = collect();
        $count = 1;

        foreach ($results as $result) {
            if ($count > $limit) {
                break;
            }

            $item = self::where('bgg_id', Arr::get($result, 'bgg_id'))->first();

            if (! $item) {
                $item = new self();
                $item->bgg_id = Arr::get($result, 'bgg_id');
                $item->name = Arr::get($result, 'name');
                $item->save();
            }

            $collection->push($item);
            $count++;
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

    public static function findByBggId($bggId)
    {
        $item = self::where('bgg_id', $bggId)->first();

        if (! $item) {
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

        $this->linkScraped($details);
        $details = self::transformBggDetails($details);
        $this->update($details);
        $this->bgg_last_scraped_at = now();
        $this->save();

        return $this;
    }

    public function linkScraped($details)
    {
        // Convert simpleXMLelement to array
        $details = json_decode(json_encode($details), true);

        foreach (Arr::get($details, 'link') as $link) {
            $link = Arr::get($link, '@attributes');

            // TODO: Tidy this up

            switch (Arr::get($link, 'type')) {
                case 'boardgamecategory':
                    $this->categories()->updateOrCreate([
                        'bgg_id' => Arr::get($link, 'id'),
                    ],
                    [
                        'name' => Arr::get($link, 'value'),
                    ]);
                    break;
                case 'boardgamemechanic':
                    $this->mechanics()->updateOrCreate([
                        'bgg_id' => Arr::get($link, 'id'),
                    ],
                    [
                        'name' => Arr::get($link, 'value'),
                    ]);
                    break;
                case 'boardgamefamily':
                    $this->families()->updateOrCreate([
                        'bgg_id' => Arr::get($link, 'id'),
                    ],
                    [
                        'name' => Arr::get($link, 'value'),
                    ]);
                    break;
                case 'boardgamepublisher':
                    $this->publishers()->updateOrCreate([
                        'bgg_id' => Arr::get($link, 'id'),
                    ],
                    [
                        'name' => Arr::get($link, 'value'),
                    ]);
                    break;
                // TODO
                case 'boardgameexpansion':
                    dd($link);

                    $this->expansions()->attach(Arr::get($link, 'id'));
                    break;
                // TODO
                case 'boardgameintegration':
                    dd($link);
                    $this->integrations()->attach(Arr::get($link, 'id'));
                    break;
                case 'boardgameartist':
                    $this->artists()->updateOrCreate([
                        'bgg_id' => Arr::get($link, 'id'),
                    ],
                    [
                        'name' => Arr::get($link, 'value'),
                    ]);
                    break;
                case 'boardgamedesigner':
                    $this->designers()->updateOrCreate([
                        'bgg_id' => Arr::get($link, 'id'),
                    ],
                    [
                        'name' => Arr::get($link, 'value'),
                    ]);
                    break;
                default:
                    dd($link);
                    break;
            }
        }
    }

    public function getDetailsFromBgg()
    {
        $bgg = new BggClient();

        return $bgg->getThingById($this->bgg_id);
    }

    public function shouldBeUpdatedFromBgg()
    {
//        TODO - Implement setting for cahcing time BGGs
        return $this->bgg_id && ($this->bgg_last_scraped_at == null || $this->bgg_last_scraped_at->diffInDays(now()) > 7);
    }
}
