<?php
/*  =================================================================================
 * +   Nombre de la clase: IndexController                                           +
 * +   ubicación: /soporte/controllers/                                              +
 * +   Descripción: Esta clase es la encargada de controlar el proceso de login al   +
 * +                sistema, haciendo las validaciones correspondiente y creando las +
 * +                las variables de sesión correspindientes                         +  
 *  =================================================================================
 */
class IndexController extends Controller
{
    private $_login;
    private $_sucursal;
    public $name;
    public $pass;
    public function __construct()
    {
        parent::__construct();
        $this->_login = $this->loadModel('Login');
        $this->_sucursal = $this->loadModel('Set');
    }

    #Método para el formulario de inicio de sesión
    public function index()
    {
        $this->_view->setJs(array('validar_reg'));
        
        $this->_view->titulo ="Iniciar Sesión ";
        $this->_view->sucursal = self::_obtenerSucursal();
        if(!Session::get('autenticado')){
            if($this->getInt('ingresar')== 4){
              //print_r($_POST); exit;
                if($this->getAlphaNum('nombre_user')==""){
                    $this->_view->_error = "El campo está vacío";
                    $this->_view->render('index', 'index');
                    exit;
                }
                 if($this->getAlphaNum('pass_user')==""){
                    $this->_view->_error = "El campo está vac�";
                    $this->_view->render('index', 'index');
                    exit;
                }
                $username = $this->getPostParam('nombre_user');
                $password = $this->getPostParam('pass_user');
                self::_login($username, $password);
                
            }
            if($this->getInt('registrar') == 4){
               
                $nombre = $this->getPostParam('nombre');
                $apellidos = $this->getPostParam('apellidos');
                
            }
            $this->_view->render('index', 'index', 'login');
        }else
        {
            $this->redirect('inicio/');
        }
    }

    private function _login($username, $password){
        $usuario = HASH::getHash('sha1', $username, HASH_KEY);
        $contrasenia = HASH::getHash('sha1', $password, HASH_KEY);
        
        $row = $this->_login->getUsers($usuario, $contrasenia);
        
        if(!$row){
            $this->_view->alerta = "danger";
            $this->_view->_error = 'Datos incorrectos, Verificar';
            $this->_view->icon ="icon-cancel-circle";
            $this->_view->render('index', 'index', 'login');
            exit;
        }
        else if($row['estatus'] != 'Activo'){
            $this->_view->alerta ="warning";
            $this->_view->_error = "Este Usuario no está habilitado";
            $this->_view->icon ="icon-warning";
            $this->_view->render('index', 'index', 'login');
            exit;
        }
        $usuario = $row['name'] . " " . $row['apellidos'];
        Session::set('autenticado', true);
        Session::set('level', $row['role']);
        Session::set('usuario', $usuario);
        Session::set('apellidos', $row['apellidos']);
        Session::set('ocupacion', $row['ocupation']);
        Session::set('idUser', $row['idUser']);
        Session::set('avatar', BASE_PUBLIC_IMG .'usuarios/thumb/thumb_' . $row['avatar']);
        Session::set('avatarReal', BASE_PUBLIC_IMG .'usuarios/' . $row['avatar']);
        Session::set('inicioSesion', date('Y-n-j H:i:s'));
        $this->redirect('inicio/');
    }
    public function validarUsuario(){
        if(empty($_POST)){
             header('location:' . BASE_URL. 'errores/acceso/404/');
        }
        $username = $this->getPostParam('name');
        $password = $this->getPostParam('pass');
        $usuario = HASH::getHash('sha1', $username, HASH_KEY);
        $contrasenia = HASH::getHash('sha1', $password, HASH_KEY);
       // echo $usuario .  "<br>";
       // echo $contrasenia; exit;
        $row = $this->_login->getUsers($usuario, $contrasenia);
        
        if(!$row){
            echo "nah";
        }else if($row['estatus'] != 'Activo'){
            echo"inactivo";
        }else {
            echo "yastas";
        }
        
    }
    public function cerrarSesion()
    {
        Session::destroy();
        $this->redirect();
    }
    
    private function _obtenerSucursal()
    {
        $sucursal = $this->_sucursal->obtenerSucursal();
        return $sucursal;
    }
    
}
