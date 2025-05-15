<?php

class Request
{
    private $_module;
    private $_controller;
    private $_method;
    private $_argument;
    private $_modules;
    
    public function __construct() {
        if(isset($_GET['url'])){
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $url = array_filter($url);
           
            //$this->_module = strtolower(array_shift($url));
            $this->_modules = array();
            $this->_module = ucfirst(array_shift($url));
            
            if(!$this->_module){
                $this->_module = false;
            }else{
                if(count($this->_modules)){
                    if(!in_array($this->_module, $this->_modules)){
                        $this->_controller = $this->_module;
                        $this->_module = false;
                    }else{
                        //$this->_controller = strtolower(array_shift($url));
                        $this->_controller = ucfirst(array_shift($url));
                        if(!$this->_controller){
                            $this->_controller = ucfirst(DEFAULT_CONTROLLER);
                        }
                    }
                }else {
                    $this->_controller = $this->_module;
                    $this->_module =false;
                }
            }
            
            $this->_method = strtolower(array_shift($url));
            $this->_argument = $url;
        }
        
        if(!$this->_controller){
            $this->_controller = ucfirst(DEFAULT_CONTROLLER) ;
        }
        if(!$this->_method){
            $this->_method = DEFAULT_METHOD;
        }
        if(!isset($this->_argument)) {
            $this->_argument = array();
        }
    }
    
    public function getModule()
    {
        return $this->_module;
    }
    
    public function getController() 
    {
        return $this->_controller;
    }
    
    public function getMethod()
    {
        return $this->_method;
    }
    
    public function getArgument()
    {
        return $this->_argument;
    }
}

