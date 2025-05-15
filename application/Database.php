<?php

class Database extends PDO 
{
 
    public function __construct($host, $dbname, $username, $passwd, $options) 
    {
        parent::__construct(
                    'mysql:host=' . $host . ';dbname=' . $dbname,
                    $username,
                    $passwd,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $options)
                     
                );
    }
}

