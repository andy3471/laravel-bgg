<?php

namespace AndyH\Services;

class BggClient
{
    private string $endpoint = 'https://www.boardgamegeek.com/xmlapi2/';

    public function getThingById($id)
    {
        $url = $this->endpoint . 'thing?id=' . $id;
        $xml = simplexml_load_file($url);
        $boardGame = $xml->xpath('/items/item');
        return $boardGame[0];
    }

    public function getHotBoardGames()
    {
        $url = $this->endpoint . 'hot?type=boardgame';
        $xml = simplexml_load_file($url);
        return $xml->xpath('/items/item');
    }

    public function search($query, $type = 'boardgame')
    {
        $url = $this->endpoint . 'search?query=' . $query . '&type=' . $type;
        $xml = simplexml_load_file($url);
        return $this->transformSearchResults($xml->xpath('/items/item'));
    }

    public function transformSearchResults($xml)
    {
        $transformmedResults = collect();
        foreach ($xml as $result) {
            $transformedResult = [
                'bgg_id' => $result->attributes()->id->__toString(),
                'name' => $result->name->attributes()->value->__toString(),
            ];
            $transformmedResults->push($transformedResult);
        }
        return $transformmedResults;
    }

}
