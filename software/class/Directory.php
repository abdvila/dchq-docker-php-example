<?php

require 'PersistenceFactory.php';
/*
 * File: Persistence.php
 *
 * Copyright (c) 2015 Marcos Macedo <marcos@softdev.science>
 * Developed for client at Upwork.com
 * For usage permissions of this file please contact at marcos@softdev.science or the corresponding copyright holder.  
 */

/**
 * Description of MySQLPersistence
 *
 * @author Marcos
 */
class Persistence {

    private $connection;

    function getConnection() {
        return $this->connection;
    }

    function setConnection($connection) {
        $this->connection = $connection;
    }

    public function delete($id) {

        //Get default connection instance
        $this->setConnection(PersistenceFactory::getDefault());

        try {

            if (Config::getInstance()->getProvider() == "oraclexe") {
                $query = oci_parse($this->getConnection(), "UPDATE directories SET ACTIVE = 'no' WHERE ID='" . $id . "'");
                oci_execute($query);
            } else {

                $query = $this->getConnection()->prepare("UPDATE directories SET ACTIVE = 'no' WHERE ID=:id");
                $query->bindParam(':id', $id);
                $query->execute();
            }
        } catch (PDOException $ex) {
            echo "Couldn't delete Directory: " . $ex->getMessage();
        }
    }

    public function get($identifier) {

        //Unnecesary for this application..
        throw new NotImplementedException();
    }

    public function update($object) {

        //Unnecesary for this application..
        throw new NotImplementedException();
    }

    public function getList() {

        //Get default PDO connection instance
        $this->setConnection(PersistenceFactory::getDefault());

        try {

            if (Config::getInstance()->getProvider() == "oraclexe") {
                $query = oci_parse($this->getConnection(), "SELECT * FROM directories WHERE ACTIVE='yes'");
                oci_execute($query);
                return $query;
            } else {

                $query = $this->getConnection()->prepare("SELECT * FROM directories WHERE ACTIVE='yes'");
                $query->execute();
                return $query->fetchAll();
            }
        } catch (PDOException $ex) {
            echo "Couldn't list Directories: " . $ex->getMessage();
        }
    }

    public function save($firstName, $secondName) {

        //Get default PDO connection instance
        $this->setConnection(PersistenceFactory::getDefault());

        try {

            //You need to make a trigger for oracle autoincrement..
            if (Config::getInstance()->getProvider() == "oraclexe") {
                $randomID = uniqid(rand(), true);
                $query = oci_parse($this->getConnection(), "INSERT INTO directories (ID, FIRSTNAME,LASTNAME,ACTIVE) VALUES ('" . $randomID . "','" . $firstName . "','" . $secondName . "','yes')");
                oci_execute($query);
            } else {

                $query = $this->getConnection()->prepare("INSERT INTO directories (FIRSTNAME,LASTNAME,ACTIVE) VALUES (:firstName,:lastName,'yes')");
                $query->bindParam(":firstName", $firstName, PDO::PARAM_STR);
                $query->bindParam(":lastName", $secondName, PDO::PARAM_STR);
                $query->execute();
            }
        } catch (PDOException $ex) {
            echo "Couldn't save Directory: " . $ex->getMessage();
        }
    }

    public function populateDatabase() {

        //Get default PDO connection instance
        $this->setConnection(PersistenceFactory::getDefault());

        //Diferent creation strings based on provider..
        $providers = array(
            "mysql" => "CREATE TABLE IF NOT EXISTS `directories` (`ID` int(11) NOT NULL AUTO_INCREMENT,`FIRSTNAME` varchar(255) DEFAULT NULL,`LASTNAME` varchar(255) DEFAULT NULL,`ACTIVE` varchar(3) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1",
            "postgresql" => "CREATE TABLE directories (id SERIAL, FIRSTNAME varchar(255), LASTNAME varchar(255), ACTIVE varchar(3))",
            "oraclexe" => "CREATE TABLE directories (ID VARCHAR2(50) PRIMARY KEY NOT NULL,FIRSTNAME VARCHAR2(255), LASTNAME VARCHAR2(255), ACTIVE VARCHAR2(10))");

        try {

            $queryStr = $providers[Config::getInstance()->getProvider()];

            if ($queryStr == null) {
                echo "You have a syntax error in enviroment parameters.";
                exit();
            }

            if (Config::getInstance()->getProvider() == "oraclexe") {

                $query = oci_parse($this->getConnection(), $queryStr);
                @oci_execute($query);
            } else {
                $query = $this->getConnection()->prepare($queryStr);
                $query->execute();
            }
        } catch (PDOException $ex) {
            echo "There was an error creating 'directories' table: " . $ex->getMessage();
        }
    }

}
