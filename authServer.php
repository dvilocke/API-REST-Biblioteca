<?php

/**
 * implementation of a server with access token
 * authentication server implementation
 * Ing Miguel Echeverry
*/

require 'BD.php';

class AuthServer
{
    private const CLIENT_ID = 1;
    private const SECRET = 'password';
    private $bd;

    public function __construct($method)
    {
        $this->makeProcess($method);
    }

    private function makeProcess($method)
    {
        switch ($method)
        {
            case 'POST':
                $this->generateToken();
                break;

            case 'GET':
                break;

            default:
                echo('false');

        }
    }
    private function generateToken()
    {
        try
        {
            if(!array_key_exists('HTTP_CLIENT_ID', $_SERVER) || !array_key_exists('HTTP_SECRET', $_SERVER))
            {
                http_response_code(400);
                throw new Exception('missing arguments');
            }

            $clientID = (int) $_SERVER['HTTP_CLIENT_ID'];
            $secret = $_SERVER['HTTP_SECRET'];

            if($clientID != self::CLIENT_ID || $secret != self::SECRET)
            {
                http_response_code(403);
                throw new Exception('permission denied');
            }
            //we generate the token
            $code = strval(self::CLIENT_ID) . time() . self::SECRET;
            $token =  sha1($code);

            //we connect to the database

            $this->bd = new BD(DNS, USUARIO, PASSWORD);

            $sql = "INSERT INTO `tokens` (`id`, `tokenNumber`) VALUES (NULL, '$token');";

            $this->bd->executeStatement($sql);

            echo(json_encode(array(
                'token' => $token
            )));

        } catch (Exception $e)
        {
            echo('ERROR' . $e->getMessage());
            die();
        }

    }

}
new AuthServer($_SERVER["REQUEST_METHOD"]);






