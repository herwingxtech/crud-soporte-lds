<?php

class menuWidget extends Widget
{
    private $_model;
    
    public function __construct() 
    {
        $this->_model = $this->loadModel('menu');
    }
    
    public function getMenu()
    {
        $data['menuPrincipal'] = $this->_model->getMenuModel();
        return $this->renderWidgets('menuPrincipal', $data);
    }
    
    
    public function getConfig()
    {
        return array(
            'position' => 'header',
            'show' => 'all',
            'hide' => array()
        );
    }
}
