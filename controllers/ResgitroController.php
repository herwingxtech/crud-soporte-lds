<?php
class RegistroController extends Controller
{
  public function __construct()
  {

  }

  public function index()
  {
    $this->_view->titulo ="Regitrar Usuario";
    $this->_view->render('registro', 'index');
  }
  
    public function registrar()
    {
        $datos = $_POST();
        print_r($datos);
        
    }
}
