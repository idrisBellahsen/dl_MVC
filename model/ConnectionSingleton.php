<?php

class ConnectionSingleton {

    private static $host = 'localhost';
    private static $user = "root";
    private static $password = "root";
    private static $database = "dlCommu";
    private static $charset = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    //instance courante de la connexion
    private static $driver = 'mysql';
    private static $connex; // variable pour ce connecter a la base de donne
    private static $port = "3306";
    private static $instance;

    private function __construct() {
        self::$connex = self::connexion();
    }

    private static function connexion() {
        try {
            self::$connex = new PDO(self::$driver . ':host=' . self::$host . ';port='.self::$port.';dbname=' . self::$database, self::$user, self::$password, self::$charset);
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
        return self::$connex;
    }

    public static function close() {
        if (isset(self::$connex)) {
            self::$connex = NULL;
        }
    }

    public static function getInstance() {
        if (!isset(self::$connex)) {
            self::$instance = new ConnectionSingleton();
        }
        return self::$connex;
    }

}
