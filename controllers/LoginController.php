<?php

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
      $this->_view->titulo = "Registro de Usuario";
      $this->_view->render('index', 'login');
    }

    public function cerrarSesion()
    {
        Session::destroy();
        $this->redirect();
    }

}
