<?php

/**
 * Implementation of an API for a library
 * REST architecture
 * the server will have token authentication
 * Ing Miguel Echeverry
 */

class Route
{
    private $matches;
    private const ENDPOINT_1 = '/\/([^\/]+)\/([^\/]+)/';
    private const ENDPOINT_2 = '/\/([^\/]+)\/?/';

    public function __construct($uri)
    {
        $this->routeSet($uri);
        $this->matches = array();
    }

    private function routeSet($uri)
    {
        if(preg_match(self::ENDPOINT_1, $uri, $this->matches))
        {
            $_GET['resource_type'] = $this->matches[1];
            $_GET['resource_id'] = $this->matches[2];

            require 'api.php';

        }else if(preg_match(self::ENDPOINT_2, $uri, $this->matches))
        {
            $_GET['resource_type'] = $this->matches[1];
            require 'api.php';

        }else
        {
            http_response_code(404);
        }
    }
}

new Route($_SERVER['REQUEST_URI']);









