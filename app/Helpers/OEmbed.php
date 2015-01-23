<?php namespace Strimoid\Helpers; 

use Config, Guzzle;
use GuzzleHttp\Exception\RequestException;

class OEmbed {

    public function getHtml($url)
    {
        $host = Config::get('strimoid.oembed');
        $endpoint = $host .'/oembed';
        $query = ['url' => $url];

        try {
            $response = Guzzle::get($endpoint, compact($query));
            $json = $response->json();

            return $json['html'];
        } catch(RequestException $e) {}

        return false;
    }

}
