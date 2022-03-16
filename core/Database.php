<?php
namespace core;

use \src\Config;

class Database {
    private static $_pdo;
    public static function getInstance() {
        if(!isset(self::$_pdo)) {
            self::$_pdo = new \PDO(Config::DB_DRIVER.":dbname=".Config::DB_DATABASE.";host=".Config::DB_HOST, Config::DB_USER, Config::DB_PASS, array(Config::DB_CHARSET => 'mysql_set_charset("utf8")'));
        }
        return self::$_pdo;
    }

    private function __construct() { }
    private function __clone() { }
    private function __wakeup() { }
}