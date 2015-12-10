<?php

/*
 * File: config.php
 * Database configuration details for application
 * 
 * Copyright (c) 2015 Marcos Macedo <marcos@softdev.science>
 * Developed for client at Upwork.com
 * For usage permissions of this file please contact at marcos@softdev.science or the corresponding copyright holder. 
 * 
 * The following variables are supposed to exist as enviroment variables provided by Docker.
 * YOU SHOULD NOT CHANGE THEM UNLESS YOU WANT TO SPECIFY EXPLICITLY. 
 */

/* LIST OF ENVIROMENT VARIABLES SUPPORTED
 *  Database server -> "DB_HOST"
 * Database provider -> "DB_PORT"
 *  Database user -> "DB_USER"
 *  Database password -> "DB_PASS"
 *  Database name -> "DB_NAME";
 *  Database provider -> "DB_PROVIDER"
 */

/* Specify the database provider you will be using in DB_PROVIDER enviroment variable.
 * LIST OF VALUES FOR DATABASE PROVIDERS SUPPORTED 
 * MySQL Server -> "mysql"
 * PostreSQL -> "postgresql"
 * Oracle XE -> "oraclexe"
 */

class Config {

    private function __construct() {
        //We are using a Singletton here..
    }

    private static $instance;
    private $host = "";
    private $user = "";
    private $pass = "";
    private $port = "";
    private $database = "";
    private $provider = "";

    function getHost() {
        return $this->host;
    }

    function getUser() {
        return $this->user;
    }

    function getPass() {
        return $this->pass;
    }

    function getDatabase() {
        return $this->database;
    }

    function getProvider() {
        return $this->provider;
    }

    function setHost($host) {
        $this->host = $host;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setPass($pass) {
        $this->pass = $pass;
    }

    function setDatabase($database) {
        $this->database = $database;
    }

    function setProvider($provider) {
        $this->provider = $provider;
    }
    
    function setPort($port) {
        $this->port = $port;
    }

    function getPort() {
        return $this->port;
    }

    static function getInstance() {

        if (self::$instance == null) {

            $obj = new Config();
            $obj->setHost($_ENV["DB_HOST"]);
            $obj->setUser($_ENV["DB_USER"]);
            $obj->setPass($_ENV["DB_PASS"]);
            $obj->setDatabase($_ENV["DB_NAME"]);
            $obj->setProvider($_ENV["DB_PROVIDER"]);
            $obj->setPort($_ENV["DB_PORT"]);
            self::$instance = $obj;
        }

        return self::$instance;
    }

    //PRE: Call
    //POST: Cleans the current configuration instance
    static function dispose() {
        self::$instance = null;
    }

}
