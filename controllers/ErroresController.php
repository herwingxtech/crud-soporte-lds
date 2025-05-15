<?php
class ErroresController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->_view->_errores = 'class="full-width page-condensed"';
  }

  public function indeX()
  {
    $this->_view->titulo = "Error";
    $this->_view->mensaje = self::_getError();
    $this->_view->render('index', 'errores');
  }

  public function acceso($codigo)
  {

    $this->_view->titulo = "Error" . " " . $codigo;
    $this->_view->mensaje = $this->_getError($codigo);
    $this->_view->_codigo = $codigo;
    $this->_view->render('acceso', 'errores', $codigo);
  }

  private function _getError($codigo = false)
  {
    if($codigo){
      $codigo = $this->filterInt($codigo);
      if(is_int($codigo)){
        $codigo = $codigo;
      }
    } else {
      $codigo = "default";
    }
    $error['default'] = "Ha ocurrido un error y la pagina no puede mostrarse";
    $error['401'] = "Tu sesión se ha agotado";
    $error['404'] = "Recurso no encontrado";

    if(array_key_exists($codigo, $error)){
      return $error[$codigo];
    }else{
      return $error['default'];
    }
  }
}
