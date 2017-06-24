<?php
/**
 * Created by PhpStorm.
 * User: SSI-Bruno
 * Date: 12/04/2016
 * Time: 10:58
 */



class ConexaoBD {


    /*private static $dbhost = 'dev';
    private static $dbuser = 'relestagio';
    private static $dbpasswd = '';
    private static $dbname = 'relatoriosestagio';*/


    /**
     * @return PDO
     */
    public static function getConnection() {
        try {
            require_once ("ConfigClass.php");
            return new PDO ('mysql:host=' . ConfigClass::dbHost . ';dbname=' . ConfigClass::dbName . ';charset=' . ConfigClass::mysqlCharset , ConfigClass::dbUser, ConfigClass::dbPasswd);
        } catch ( Exception $e ) {
            die($e->getMessage());
        }
    }
}

