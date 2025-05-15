<?php

class Bootstrap
{

  public static function run(Request $request)
  {
    $module = $request->getModule();
    $controller = $request->getController() . 'Controller';
    $routeController = ROOT . 'controllers' .  DS . $controller . '.php';
    $method = $request->getMethod();
    $args = $request->getArgument();

    if($module){
      $routeModule = ROOT . 'controllers' . DS . $modula . 'Controller.php';
      if(is_readable($routeModule)) {
          require_once $routeModule;
          $routeController = ROOT . 'modules' . DS . 'controller' . DS . $controller . '.php';

      }else {
          throw new Exception("Error en el modulo");
      }
    }else {
      $routeController = ROOT . 'controllers' . DS . $controller . '.php';
    }

    if(is_readable($routeController)) {
      require_once $routeController;
      $controller = new $controller;
      if(is_callable(array($controller, $method))) {
          $method = $request->getMethod();
      }else {
          $method = DEFAULT_METHOD;
      }

      if(isset($args)){
          call_user_func_array(array($controller, $method), $args);
      }else{
          call_user_func(array($controller, $method));
      }
    }else{
      header('location:' . BASE_URL . 'errores/acceso/404/');
      exit;
    }
  }
}
