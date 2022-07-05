<?php

namespace AndyH\LaravelBgg\Services;

class BggClient
{
    private string $endpoint = 'https://www.boardgamegeek.com/xmlapi2/';

    public function construct()
    {
        $this->endpoint = config('laravel-bgg.endpoint');
    }

    public function getThingById($id)
    {
        $url = $this->endpoint.'thing?id='.$id;
        $xml = simplexml_load_file($url);
        $boardGame = $xml->xpath('/items/item');

        return $boardGame[0];
    }

    public function getHotBoardGames()
    {
        $url = $this->endpoint.'hot?type=boardgame';
        $xml = simplexml_load_file($url);

        return $xml->xpath('/items/item');
    }

    public function search($query, $type = 'boardgame')
    {
        $url = $this->endpoint.'search?query='.$query.'&type='.$type;
        $xml = simplexml_load_file($url);

        return $this->transformSearchResults($xml->xpath('/items/item'));
    }

    public function transformSearchResults($xml)
    {
        $transformedResults = collect();
        foreach ($xml as $result) {
            $transformedResult = [
                'bgg_id' => $result->attributes()->id->__toString(),
                'name' => $result->name->attributes()->value->__toString(),
            ];
            $transformedResults->push($transformedResult);
        }

        return $transformedResults;
    }

    public function getItemsByUser($username)
    {
        // TODO - Request may respond with a message saying to return again, in this case we need to retry
        $url = $this->endpoint.'collection?username='.$username.'&own=1&subtype=boardgame';
        $xml = simplexml_load_file($url);

        return $this->transformUserItems($xml->xpath('item'));
    }

    public function mapBggTypeToModel($type)
    {
        return app((config('laravel-bgg.'.$type)));
    }

    public function transformUserItems($xml)
    {
        $items = [];

        foreach ($xml as $item) {
            $items[] = $this->mapBggTypeToModel($item->attributes()->subtype->__toString())->findByBggId($item->attributes()->objectid->__toString());
        }

        return $items;
    }
}
