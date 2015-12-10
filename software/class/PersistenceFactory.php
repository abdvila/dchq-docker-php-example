<?php

require './config.php';
/*
 * File: PersistenceFactory.php
 *
 * Copyright (c) 2015 Marcos Macedo <marcos@softdev.science>
 * Developed for client at Upwork.com
 * For usage permissions of this file please contact at marcos@softdev.science or the corresponding copyright holder.  
 */

/**
 * Description of PersistenceFactory
 *
 * @author Marcos
 */
class PersistenceFactory {

    //PRE: Receives a call and fetches information from config.php
    //POST: Returns a new PDO connection object
    static function getDefault() {

        $providers = array(
            "mysql" => "mysql:host=hostRp;port=portRp;dbname=databaseRp",
            "postgresql" => "pgsql:dbname=databaseRp;port=portRp;host=hostRp",
            "oraclexe" => "hostRp:portRp/databaseRp");

        try {
            $connString = $providers[Config::getInstance()->getProvider()];

            if ($connString == null) {
                echo "You have a syntax error in enviroment parameters.";
                exit();
            }

            $hostReplaced = str_replace("hostRp", Config::getInstance()->getHost(), $connString);
            $databaseReplaced = str_replace("databaseRp", Config::getInstance()->getDatabase(), $hostReplaced);
            $finalString = str_replace("portRp", Config::getInstance()->getPort(), $databaseReplaced);

            if (Config::getInstance()->getProvider() == "oraclexe") {
                //Uses OCI8 Drivers instead of PDO.. uses EasyConnect Syntaxis.
                return oci_pconnect(Config::getInstance()->getUser(), Config::getInstance()->getPass(), $finalString);
            } else {
                return new PDO($finalString, Config::getInstance()->getUser(), Config::getInstance()->getPass());
            }
        } catch (Exception $ex) {
            echo "Something went wrong: " . $ex->getMessage();
        }
    }

}
