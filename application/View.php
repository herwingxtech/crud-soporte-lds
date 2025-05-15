<?php

/*
 * -------------------------------------------------------------
 * I.S.C. Pablo Manga Pérez
 * Basado en dlancedu
 * View.php
 * Miécoles 01 de Octubre 2014
 * Está clase nos ayuda a usar singleton al instanciar nuestras
 * clases y así  evitar el aumento de uso de memoria.
 * -------------------------------------------------------------
 */
class View
{
    private $_request;
    private $_js;
    private $_routes;
    private $_jsPlugins;
    private $_acl;
    private static $_item;
    private $_template;

    public function __construct(Request $request, Acl $acl)
    {
        $this->_request = $request;
        $this->_js = array();
        $this->_jsPlugins = array();
        $this->_acl = $acl;
        $this->_routes = array();
        //$this->_item='';
        $this->_template = DEFAULT_LAYOUT;
        $module = $this->_request->getModule();
        $controller = $this->_request->getController();
        $controller = strtolower($controller); 
        self::$_item=false;
        if($module){
            $this->_routes['view'] = ROOT . 'modules' . DS . $module . DS . 'view' . DS . $controller . DS;
            //$this->_routes['js'] = BASE_URL . 'modules' . DS . $module. DS . 'view' . DS . $controller . DS . 'js' . DS;
        }else {
            $this->_routes['view'] = ROOT . 'views' . DS . $controller . DS;
            $this->_routes['setJS'] = BASE_URL . 'views' . DS . $controller . DS . 'js' . DS;
        }

    }

    public static function getViewId()
    {
        return self::$_item;
    }

    public function render($view, $item = false, $noLayout = false)
    {


        if($item){
            self::$_item = $item;
        }
        $js = array();
        if(count($this->_js)){
            $js = $this->_js;
        }
        $_layoutParam = array(
            'titulo_principal' => 'SoportePC V5.0',
            'route_css' => BASE_URL . 'views/layout/' . $this->_template . '/css/',
            'route_js' => BASE_URL . 'views/layout/' . $this->_template . '/js/',
            'route_img' => BASE_URL . 'views/layout/' . $this->_template . '/img/',
            'js' => $js,


        );

        $widgets = $this->getWidgets();

        $route_view = $this->_routes['view'] . $view . '.phtml';
        if(is_readable($route_view)){
            if(!$noLayout){
                if(Session::get('autenticado')) {

                  Session::sesionSegura();
                    include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS .'plantilla.php';
                  /*include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.php';
                  include_once $route_view;
                  include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.php';*/
                }else {
                  header('location:' . BASE_URL. 'error/acceso/401/');
                }
            }else{
                
                include_once ROOT . 'views' . DS . 'index' . DS . 'login.php';
                /*
                include_once ROOT . 'views' . DS . 'index' . DS . 'header.php';
                include_once $route_view;
                include_once ROOT . 'views' . DS . 'index' . DS  . 'footer.php';*/
            }

           /*if(isset(Session::get('autenticado'))){
                Session::sesionSegura();
               include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.php';
               include_once $route_view;
               include_once ROOT . 'views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.php';
           }else {
               include_once ROOT . 'views' . DS . 'index' . DS . 'index.phtml';
               //include_once ROOT . 'views' . DS . 'login' . DS . DEFAULT_LAYOUT . DS . 'header.php';
              // include_once $route_view;
               //include_once ROOT . 'views' . DS . 'login' . DS . DEFAULT_LAYOUT . DS . 'footer.php';
           }*/

        }else{
            header('location:' . BASE_URL. 'error/acceso/404/');
        }
    }

    public function setJS(array $js)
    {

        if(is_array($js) && count($js)) {
            for($i=0; $i<count($js); $i++) {
                $this->_js[] = $this->_routes['setJS'] . $js[$i] . '.js';
            }
        }else {
            throw new Exception("Error de librería");
        }
    }

    public function setTemplate($template)
    {
        $this->_template = (string) $template;
    }

    public function widget($widget, $method, $options=array())
    {
        if(!is_array($options)){
            $options = array($options);
        }

        if(is_readable(ROOT . 'widgets' . DS . $widget . '.php')){
            include_once ROOT . 'widgets' . DS . $widget . '.php';
            $widgetClass = $widget . 'Widget';
            if(!class_exists($widgetClass)){
                throw new Exception('error clase widget');
            }

            if(is_callable($widgetClass, $method)){
                if(count($options)) {
                    return call_user_func_array(array(new $widgetClass, $method), $options);
                }else{
                    return call_user_func(array(new $widgetClass, $method));
                }
            }
            throw new Exception("Error en el método del widget");
        }
        throw new Exception("Error del widget");
    }

    public function getLayoutPositions()
    {
        if(is_readable(ROOT . 'views' . DS .'layout' . DS . $this->_template  . DS . 'configs.php')){
            include_once ROOT . 'views' . DS .'layout' . DS . $this->_template  . DS . 'configs.php';
            return get_layout_positions();
        }
        throw new Exception("Error de la configuración del layout");
    }

    public function getWidgets()
    {
        $widgets = array(
            'menuPrincipal' => array(
                'config' => $this->widget('menu', 'getConfig'),
                'content' => array('menu', 'getMenu')
            )
        );

        $positions = $this->getLayoutPositions();
        $keys = array_keys($widgets);
        foreach ($keys AS $k) {
            /*Verificar si la posición del widgets está presente*/
            if(isset($positions[$widgets[$k]['config']['position']])) {
                /*Verificar si está deshabilitado para la vista*/
                if(!isset($widgets[$k]['config']['hide']) || !in_array(self::$_item, $widgets[$k]['config']['hide'])) {
                    /*Verificar si está habilitado para la vista */
                    if($widgets[$k]['config']['show'] == 'all' || in_array($this->_item, $widgets[$k]['config']['show'])) {
                        /*Llenar la posición del layout*/
                        $positions[$widgets[$k]['config']['position']][] = $this->getWidgetsContent($widgets[$k]['content']);
                    }
                }
            }
        }
        return $positions;
    }
    
    public function getWidgetsContent(array $content)
    {
        if(!isset($content[0]) || !isset($content[1])) {
            throw new Exception("Error contenido Widget");
            return;
        }

        if(!isset($content[2])) {
            $content[2] = array();
        }

        return $this->widget($content[0], $content[1], $content[2]);
    }
}
