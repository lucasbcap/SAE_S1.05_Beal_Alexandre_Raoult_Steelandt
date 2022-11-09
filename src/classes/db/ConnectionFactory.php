<?php

namespace iutnc\netvod\db;

use PDO;

/**
 * Class ConnectionFactory vue en cours
 */

class ConnectionFactory
{

    static ?array $config = null;
    static ?PDO $pdo = null;


    static function setConfig(string $file){
        ConnectionFactory::$config = parse_ini_file($file);
    }

    static function makeConnection():PDO{
        if (ConnectionFactory::$pdo==null) {
            ConnectionFactory::$pdo = new PDO(ConnectionFactory::$config['driver'] . ':host=' . ConnectionFactory::$config['host'] . '; 
        dbname=' . ConnectionFactory::$config['base'] . '; charset=utf8', ConnectionFactory::$config['user'], ConnectionFactory::$config['password']);
        }
        return ConnectionFactory::$pdo;
    }
}