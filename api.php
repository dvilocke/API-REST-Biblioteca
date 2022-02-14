<?php

/**
 * Implementation of an API for a library
 * REST architecture
 * the server will have token authentication
 * Ing Miguel Echeverry
 */

header('Content-Type: application/json');

class API
{
    private $requestMethod;
    private $resourceType;
    private $resourceId;

    private const SERVER_AUTH_URL = "http://localhost:8001";

    private const RESOURCE_TYPES = array(
        'books',
        'authors'
    );

    public function __construct($method)
    {
        $this->requestMethod = $method;
        if($this->checkToken())
        {
            $this->checkInputVariables();
            $this->apiProcess();
        }else
        {
            echo(json_encode(array(
                'response' => 'Invalid Token'
            )));
        }
    }

    private function checkToken()
    {
        //check if the token comes
        if(!array_key_exists('HTTP_TOKEN', $_SERVER))
        {

            die('arguments missing');
        }
        //we make a call via curl
        $ch = curl_init(self::SERVER_AUTH_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["TOKEN:{$_SERVER['HTTP_TOKEN']}"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch), true);


        curl_close ($ch);

        return $response['response'];

    }

    private function checkInputVariables()
    {
        //we make the input filters

        $this->resourceType = array_key_exists('resource_type', $_GET) ? $_GET['resource_type'] : '';

        if(!in_array($this->resourceType, self::RESOURCE_TYPES))
        {
            echo(json_encode(array(
                'response' => 'resource not available'
            )));
        }else
        {
            $this->resourceId = array_key_exists('resource_id', $_GET)  ? $_GET['resource_id'] : '';

            if($this->resourceId)
            {
                if(!is_numeric($this->resourceId))
                {
                    echo(json_encode(array(
                        'response' => 'resource not available'
                    )));
                }
            }

            echo "funciona";

        }

    }

    private function apiProcess()
    {
        switch ($this->requestMethod)
        {
            case 'GET':
                break;

            case 'POST':
                break;

            case 'PUT':
                break;

            case 'DELETE':
                break;

            default:
                echo(json_encode(array(
                    'response' => 'resource not available'
                )));
        }
    }

}
new API($_SERVER["REQUEST_METHOD"]);






