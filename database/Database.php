<?php

namespace DatabaseConfig;

use PDO; 

require_once(__DIR__ . '/../config/ConfigDB.php');
use Config\ConfigDB;

class Database
{
    public static function getDB() {
        
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . ConfigDB::DB_HOST . ';dbname=' . ConfigDB::DB_NAME . ';charset=utf8';
            $db = new \PDO($dsn, ConfigDB::DB_USER, ConfigDB::DB_PASSWORD);

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }

}