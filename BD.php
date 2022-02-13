<?php

/**
 * database implementation
 * Ing Miguel Echeverry
 */

const DNS = 'mysql:host=localhost;dbname=bdbiblioteca';
const USUARIO = 'root';
const PASSWORD = '';

class BD
{
    private $bd;

    public function __construct($dsn, $username, $password)
    {
        try
        {
            $this->bd = new PDO($dsn, $username, $password);
            $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch (PDOException $e)
        {
            echo('Error:' . $e->getMessage());
            die();
        }

    }

    public function executeStatement($sql)
    {
        $this->bd->exec($sql);
    }
}


