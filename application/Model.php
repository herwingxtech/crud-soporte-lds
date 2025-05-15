<?php

class Model 
{
    private $_registry;
    protected $_db;
    protected $_db2;


    public function __construct()
    {
       // $this->_db = new Database();
        $this->_registry = Registry::getInstance();
        $this->_db = $this->_registry->_db;
        $this->_db2 = $this->_registry->_db2;
	
    }
}
