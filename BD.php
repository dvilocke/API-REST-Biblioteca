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
        try {
            $this->bd = new PDO($dsn, $username, $password);
            $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo('Error:' . $e->getMessage());
            die();
        }

    }

    public function executeStatement($sql)
    {
        $this->bd->exec($sql);
    }

    public function prepareExecution($sql)
    {
        $sth = $this->bd->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();

    }

    public function checkTokenExistence($token, $sql): bool
    {

        $sth = $this->bd->prepare($sql);
        $sth->execute();
        $res = $sth->fetchAll();

        return count($res) > 0;

    }

}


