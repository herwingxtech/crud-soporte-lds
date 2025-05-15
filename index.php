<?php
ini_set('display_errors',0);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('APP_PATH', ROOT . 'application' . DS );

require_once APP_PATH . 'AutoLoad.php';
require_once APP_PATH . 'Config.php';

abstract class Index
{
    static function ejecutar()
    {
        try {

            Session::init();
          // echo Hash::getHash('sha1', '', HASH_KEY); exit;
            //Session::init();
            //Usando Singleton
            $registry = Registry::getInstance();
            $registry->_request = new Request();
            $registry->_db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHAR);
          //  $registry->_db2 = new Database(DB_HOST, DB_WEB, DB_USER2, DB_PASS2, DB_CHAR);
            $registry->_acl = new ACL();
            Bootstrap::run($registry->_request);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
Index::ejecutar();

