<?php

class Session {
    public static function init() {
        session_start();
    }
    
    public static function destroy($key = false) {
        if($key) {
            if(is_array($key)) {
                for($i = 0; $i < count($key); $i++) {
                    if(isset($_SESSION[$key[$i]])) {
                        unset($_SESSION[$key[$i]]);
                    }
                }
            }
            else {
                if(isset($_SESSION[$key])) {
                    unset($_SESSION[$key]);
                }
            }
        }
        else {
            session_destroy();
        }
    }
    
    public static function set($key, $value) {
       // echo $key ."<br/>";
        if(!empty($key)) {
            $_SESSION[$key] = $value;
        }
    }
    
    public static function get($key) {
      //  echo $key; exit;
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
    
    public static function controlAccess($level) {
       // echo $level;
       if(!Session::get('autenticado')) {
          header('location:' . BASE_URL . 'error/access/503');
          exit;
       }
       Session::sesionSegura();   
       if(Session::getLevel($level) > Session::getLevel(Session::get('level'))){
           header('location:' . BASE_URL . 'error/access/403');
           exit;
       }
    }
    
    public static function accessView() {
        if(!Session::get('autenticado')) {
            return false;
        }
        return true;
    }

    public static function getLevel($level) {
      //echo $level; exit;
        $role['Guest'] = 3;
        $role['Manager'] = 2;
        $role['Root'] = 1;
      //  print_r($role); echo "<br/>";
      //  echo $level; exit;
        if(!array_key_exists($level, $role)) {
            header("location:". BASE_URL . 'error/access/403');
            exit;
        }else {
            return $role[$level];
        }
    }
    
    public static function restrictedAccess(array $level, $noRoot = false) {
        if(!Session::get('autenticado')) {
            header('location:' .BASE_URL . 'error/access/5050');
            exit;
        }
        //Session::tiempo();
       //s Session::sesionSegura();
        if($noRoot == false) {
            if(Session::get('level') == 'Root') {
                return;
            }
        }
        
        if(count($level)) {
            if(in_array(Session::get('level'), $level)) {
                return;
            }
        }
         header('location:' .BASE_URL . 'error/access/403');
        
    }
    
    public static function restrictedViewAccess (array $level, $noRoot=false) {
        if(!Session::get('autenticado')) {
            return false;
            
        }
        
        if($noRoot == false) {
            if(Session::get('level') == 'Guest') {
                return;
            }
        }
        
        if(count($level)) {
            if(in_array(Session::get($level), $level)) {
                return true;
            }
        }
        return false;
    }
    public static function sesionSegura() {
        if(!Session::get('autenticado')) {
            header('location:' . BASE_URL . 'error/access/503');
        }
        if(!Session::get('inicioSesion')) {
            throw new Exception("No se ha definido el tiempo de inicio de sesión");
        }
        $incioSesion = Session::get('inicioSesion');
        $fechaActual = date('Y-n-j H:i:s'); 
        $tiempoTranscurrido = (strtotime($fechaActual) - strtotime($incioSesion));
        //Session::set('tT', $tiempoTranscurrido);
        if($tiempoTranscurrido >= 600) {
            Session::destroy();
            header('location:' . BASE_URL . 'error/access/503');
        }else {
            Session::set('inicioSesion', $fechaActual);
        }
    }

    public static function tiempo() {
        if(!Session::get('tiempo') || !defined('SESSION_TIME')) {
            throw new Exception('No se ha definido el tiempo de sessión');
        }
        
        if(SESSION_TIME == 0) {
            return;
        }
        
        if(time() - Session::get('tiempo') > (SESSION_TIME * 60)) {
            Session::destroy();
            header('location:' . BASE_URL . 'error/access/8080');
        }else {
            Session::set('tiempo', time());
        }
    }
}
