<?php
namespace src;

class Config {
    const BASE_DIR = '/php/devsbook/public';

    const DB_DRIVER = 'mysql';
    const DB_HOST = 'localhost';
    const DB_DATABASE = 'devsbook';
    const DB_CHARSET = 'mysql_set_charset("utf8")';
    const DB_USER = 'root';
    const DB_PASS = '';

    const ERROR_CONTROLLER = 'ErrorController';
    const DEFAULT_ACTION = 'index'; 
}