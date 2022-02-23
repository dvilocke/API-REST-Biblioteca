<?php

/**
 * Implementation of an API for a library
 * REST architecture
 * the server will have token authentication
 * Ing Miguel Echeverry
 */

header('Content-Type: application/json');
require 'BD.php';

class API
{
    private const SERVER_AUTH_URL = "http://localhost:8001";
    private const RESOURCE_TYPES = array(
        'books'
    );

    private const RESOURCE_TYPES_KEYS = array(
        'books' => ['name']
    );

    private $requestMethod;
    private $resourceType;
    private $resourceId;

    public function __construct($method)
    {

        $this->requestMethod = $method;
        if ($this->checkToken()) {
            if ($this->checkInputVariables()) {
                $this->apiProcess();
            }
        } else {
            echo(json_encode(array(
                'response' => 'Invalid Token'
            )));
        }
    }

    private function checkToken()
    {
        //check if the token comes
        if (!array_key_exists('HTTP_TOKEN', $_SERVER)) {

            die('arguments missing');
        }
        //we make a call via curl
        $ch = curl_init(self::SERVER_AUTH_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["TOKEN:{$_SERVER['HTTP_TOKEN']}"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $response['response'];

    }

    private function checkInputVariables()
    {
        //we make the input filters

        $this->resourceType = array_key_exists('resource_type', $_GET) ? $_GET['resource_type'] : '';

        if (!in_array($this->resourceType, self::RESOURCE_TYPES)) {
            echo(json_encode(array(
                'response' => 'resource not available'
            )));
            return false;
        } else {
            $this->resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';

            if ($this->resourceId) {
                if (!is_numeric($this->resourceId)) {
                    echo(json_encode(array(
                        'response' => 'resource not available'
                    )));
                    return false;
                }
            }
            return true;
        }

    }

    private function apiProcess()
    {
        $bd = new BD(DNS, USUARIO, PASSWORD);
        switch ($this->requestMethod) {
            case 'GET':
            {
                if ($this->resourceType && $this->resourceId) {
                    $sql = "SELECT name FROM books WHERE id = {$this->resourceId};";
                    $res = $bd->prepareExecution($sql);
                    if (!empty($res)) {
                        echo(json_encode(array(
                            'response' => "{$res[0]['name']}"
                        )));
                    } else {
                        echo(json_encode(array(
                            "response" => "no book with that id: {$this->resourceId} is found"
                        )));
                    }
                } else if ($this->resourceType) {
                    $sql = "SELECT name from books";
                    $res = $bd->prepareExecution($sql);
                    if (!empty($res)) {
                        echo(json_encode($res));
                    } else {
                        echo(json_encode(array(
                            "response" => "there are no books yet"
                        )));
                    }
                }
                break;
            }
            case 'POST':
                {
                    $jsonUser = json_decode(file_get_contents('php://input'), true);
                    if ($this->verify_json($jsonUser)) {
                        $sql = "INSERT INTO `books` (`id`, `name`) VALUES (NULL, '{$jsonUser['name']}');";
                        $bd->executeStatement($sql);
                        echo(json_encode(array(
                            "response" => "the change was a success"
                        )));
                    }
                }
                break;
            case 'PUT':
                {
                    if ($this->resourceType && $this->resourceId) {
                        $sql = "SELECT name FROM books WHERE id = {$this->resourceId};";
                        $res = $bd->prepareExecution($sql);
                        if (!empty($res)) {
                            $jsonUser = json_decode(file_get_contents('php://input'), true);
                            if ($this->verify_json($jsonUser)) {

                                $sql = "UPDATE books SET name = '{$jsonUser['name']}' WHERE id = {$this->resourceId}";
                                $bd->executeStatement($sql);
                                echo(json_encode(array(
                                    "response" => "the change was a success"
                                )));
                            } else {
                                echo(json_encode(array(
                                    "response" => "does not meet the appropriate keys"
                                )));
                            }
                        } else {
                            echo(json_encode(array(
                                "response" => "no book with that id: {$this->resourceId} is found"
                            )));
                        }
                    }
                }
                break;

            case 'DELETE':
                {
                    if ($this->resourceType && $this->resourceId) {
                        $sql = "DELETE from books where id = {$this->resourceId};";
                        $bd->executeStatement($sql);
                        echo(json_encode(array(
                            "response" => "the change was a success"
                        )));
                    }
                }
                break;

            default:
                echo(json_encode(array(
                    'response' => 'resource not available'
                )));
        }
    }

    private function verify_json($jsonUser): bool
    {
        foreach (array_keys($jsonUser) as $key) {
            if (!in_array($key, self::RESOURCE_TYPES_KEYS[$this->resourceType])) {
                return false;
            }
        }
        return true;
    }

}

new API($_SERVER["REQUEST_METHOD"]);






