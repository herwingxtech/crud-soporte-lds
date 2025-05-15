<?php

abstract  class Controller
{
    private $_registry;
    protected $_view;
    protected $_request;
    protected $_acl;

    public function __construct()
    {
        $this->_registry = Registry::getInstance();
        $this->_acl = $this->_registry->_acl;
        $this->_request = new Request();
        $this->_view = new View($this->_request, $this->_acl);
    }

    abstract public function index();

    #Método para cargar los modelos en los controladores;
    protected function loadModel($model, $module=false)
    {
        $model = $model . 'Model';
        $routeModel = ROOT . 'models' . DS . $model . '.php';

        if(!$module) {

            $module = $this->_request->getModule();
        }
        if($module) {
            if($module != 'default') {
               //$routeModel = ROOT . 'modules' . DS . 'models' . Ds .$model . '.php'  ;
                $routeModel = ROOT . 'modules' . DS . $module . DS . 'models' . DS . $model . '.php';
                //print_r($routeModel); exit;
            }
        }

        if(is_readable($routeModel)) {

            require_once $routeModel;

            $model = new $model;
            //echo $model; exit;
            return  $model;
        }
        else {
            throw new Exception ('Error del Modelo');
        }
    }
    
    #Método para cargar librerías al proyecto...
    protected function getLibrary($library)
    {

        $routeLibrary = ROOT . 'libs' . DS . $library . '.php';

        if(is_readable($routeLibrary)) {
            require_once $routeLibrary;
        }
        else {
            throw new Exception('Error de Librerías');
        }
    }

    protected function urls($modulo)
    {
      
      $url = BASE_URL . $modulo . DS;
      $this->_view->url = $url;
      $this->_view->urlCatalogo = $url. "catalogo" . DS;
      $this->_view->urlAgregar = $url . "agregar" .DS;
      $this->_view->urlBaja = $url . "baja" .DS;
      $this->_view->urlDetalles = $url . "detalles" .DS;
      $this->_view->urlActualizar = $url . "actualizar" .DS;
      $this->_view->urlliberarIP = $url. "liberarIP" .DS;
      $this->_view->urlasignarIP = $url. "asignarIP" .DS;
      $this->_view->urlReporte = $url. "reporte" .DS;
      $this->_view->urlIPS = $url. "controlIP" .DS;
      $this->_view->urlEliminar = $url. "eliminar" .DS;
      $this->_view->urlAsignar = $url. "asignar" .DS;
      $this->_view->urlRegstrarlave = $url. "registrarClave" .DS;
      $this->_view->urlResguardo = $url. "resguardo" .DS;
      $this->_view->urlRelacion = BASE_URL . "usuarios" . DS . "relacion" . DS;
      $this->_view->pronavegacion = "Módulo  de " . ucfirst( $modulo);
    }

    protected function getText($key)
    {

        if(isset($_POST[$key]) && !empty($_POST[$key])) {

            $_POST[$key] = htmlspecialchars($_POST[$key], ENT_QUOTES);
            return $_POST[$key];

        }
        return '';
    }

    protected function getInt ($key)
    {

        if(isset($_POST[$key]) && !empty($_POST[$key])) {

            $_POST[$key] = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
            return $_POST[$key];
        }

        return '';
    }

    protected function redirect($route = false)
    {

        if($route) {
            header("location:" . BASE_URL . $route);
            exit;
        }
        else {
            header("location:" . BASE_URL);
            exit;
        }
    }

    protected function filterInt($int)
    {

        $int = (int) $int; 

        if(is_int($int)) {
            return $int;
        }
        else{
            return 0;
        }
    }

    protected function getPostParam($key)
    {

        if(isset($_POST[$key])) {
            $_POST[$key] = trim($_POST[$key]);
            return $_POST[$key];
        }
    }

    protected function getSQL($key)
    {
        $this->getLibrary('class.inputfilter');
        $filter= new InputFilter();
        $key_filter = $filter->process($_POST[$key]);
        return $key_filter;

    }
    protected function getAlphaNum($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
        }
    }

    protected function formatoFecha($fecha, $opcion)
    {

        switch ($opcion) {
            case '1':
                $dia = substr($fecha, 8, 2);
                $mes = substr($fecha, 5, 2);
                $anio = substr($fecha, 0, 4);
                $formato = $mes . "/" . $dia . "/" . $anio;
                break;
            case '2' :
                $mes = substr($fecha, 0, 2);
                $dia = substr($fecha, 3, 2);
                $anio = substr($fecha, 6, 4);
                $formato = $anio . "-" . $mes . "-" . $dia;
                break;
            case '3':
              $dia = substr($fecha, 0,2);
              $mes = substr($fecha, 3,2);
              $anio = substr($fecha, 6,4);
              $formato = $anio. "-" . $mes . "-" . "$dia";
              break;
            case '4':
              $dia = substr($fecha, 8, 2);
              $mes = substr($fecha, 5, 2);
              $anio = substr($fecha, 0, 4);
              $formato = $dia . "/" . $mes . "/" . $anio;
            break;
              break;
         }
        return $formato;
    }
    
    protected function formatoFechaEdad($fecha)
    {
      $anio = substr($fecha, 0, 4);
      $mes = substr($fecha, 5, 2);
      $dia = substr($fecha, 8, 2);
      switch ($mes) {
        case '01':
          $mes = "Enero";
          break;
        case '02':
          $mes = "Febrero";
          break;
        case '03':
          $mes ="Marzo";
          break;
        case '04':
          $mes = "Abril";
          break;
        case '05':
          $mes = "Mayo";
          break;
        case '06':
          $mes = "Junio";
          break;
        case '07':
          $mes = "Julio";
          break;
        case '08':
          $mes ="Agosto";
          break;
        case '09':
          $mes = "Septiembre";
          break;
        case '10':
          $mes = "Octubre";
          break;
        case '11':
          $mes = "Noviembre";
          break;
        case '12':
          $mes = "Diciembre";
          break;
      }
    $formato = $dia . " de " . $mes . " de " . $anio;
    return $formato;

    }
    protected function leyendas()
    {
        $this->_view->iconEdit = "icons/packs/silk/16x16/page_white_edit.png";
        $this->_view->iconBaja = "icons/packs/silk/16x16/cancel.png";
        $this->_view->iconDelete = "icons/packs/silk/16x16/delete.png";
        $this->_view->leyendaL = 'original-title="Editar" rel="tooltip-left" ';
        $this->_view->leyendaR = 'original-title="Eliminar registro" rel="tooltip-right"';
        $this->_view->leyendaB = 'original-title="Dar de baja" rel="tooltip-bottom"';

        $this->_view->iconAdd = "icons/packs/silk/16x16/page_white_add.png";
        $this->_view->leyendaAdd = 'original-title="Agregar Mantenimiento" rel="tooltip-top"';

        $this->_view->iconInfo = "icons/packs/silk/16x16/information.png";
        $this->_view->leyendaT = 'original-title="Ver Información" rel="tooltip-top"';

        $this->_view->iconLiberar = "icons/packs/silk/16x16/lock.png";
        $this->_view->leyendaLiberar='original-title="Liberar IP" rel="tooltip-top"';
        $this->_view->iconAsignar = "icons/packs/silk/16x16/lock_open.png";
        $this->_view->leyendaAsignar='original-title="Asignar IP" rel="tooltip-top"';

        $this->_view->leyendaKey = 'original-title="Asignar Clave" rel="tooltip-top"';
        $this->_view->iconKeyEmail="icons/packs/silk/16x16/key_add.png";
        $this->_view->leyendaKeyModif = 'original-title="Modificar Clave" rel="tooltip-top"';
        $this->_view->iconKeyModif="icons/packs/silk/16x16/key_go.png";
    }
    protected function formatoIP($ip)
    {
        if(!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }
        return true;
    }
    protected function validaEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return true;
    }

    protected  function backgroundRename($ruta, $nombre, $nuevo)
    {
        
      //$this->ruta();
        if(isset($ruta)){
            $oldname = $ruta.$nombre;
            echo $newname = $ruta.$nuevo;
            exit;
            rename($oldname, $newname);
        }
    }
    protected function seleccionSO($cadena)
    {
      //  $longitud = strlen($cadena);
      $cadena = substr($cadena,0, 1);
      if($cadena == "W"){
        return "windows8";
      }else if($cadena == "O"){
        return "apple";
      }else {
        return "tux";
      }
    }
}
