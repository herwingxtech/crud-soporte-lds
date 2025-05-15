<?php
/*  =================================================================================
 * +   Nombre de la clase: InicioController                                          +
 * +   ubicación: soporte/controllers/                                               +
 * +   Descripción: Esta clase es la encargada de controlar todos los procesos       +
 * +                referentes al menú rápdo de la aplicación, como son: agregar     +
 * +                computadoras, usuarios y dispositivos, así como agrenar notas    +
 * +                visualizarlas, ver la bitacora de mantenimiento entre otros.     +
 * +                                                                                 +
 *  =================================================================================
 */
class InicioController extends Controller
{
  /*Declaración de variables*/
  private $_set; 

  public function __construct()
  {
      parent::__construct();
      $this->_set = $this->loadModel('Set'); //Inicializando el modelo a utilizar.
      //$this->_computadoras = $this->loadModel('Computadoras');
      //$this->_view->urlAgregarComputadora = BASE_URL .  "computadoras" . DS . "agregar" .DS;
      $this->_view->urlAgregarUsuario = BASE_URL .  "usuarios" . DS . "agregar" .DS;
      $this->_view->urlAgregarDisposito = BASE_URL .  "dispositivos" . DS . "agregar" .DS;
      $this->_view->urlNotas = BASE_URL .  "inicio" . DS . "notas" .DS;
      $this->_view->urlAgregarNotas = BASE_URL .  "inicio" . DS . "agregar" .DS;
      $this->_view->urlBitacora = BASE_URL . "inicio" . DS ."bitacora". DS;
       
  }
//Método para mostrar la pagina de inicio
  public function index()
  {
    $this->_view->setJs(array('mensajes'));
    $this->_acl->controlAccess('consult_register');
    $this->_view->inicio = "Inicio";
    $this->_view->titulo = "Menú rápido";
    if($this->getInt('reporte') == 4){
      $this->_view->datos = $_POST;
      if($this->getPostParam('nombreReporte')==""){
        $this->_view->alerta = "danger";
        $this->_view->_mensaje = "El campo <strong>Nombre</strong> está vacío";
        $this->_view->render('agregar','computadoras');
        exit;  
      }
      $datos = array(
        'nombre' => $this->getPostParam('nombreReporte'),
        'desc' => $this->getPostParam('descripcion'),
        'url' => $this->getPostParam('url'),
      );
      $row = self::_agregar($datos);
      $this->_view->_mensaje=$row;


    }
    $this->_view->render('index', 'inicio');
  }

  #Método para mostrar la pagina de notas
  public function notas()
  {
   $this->_view->setJs(array('nota'));
    $this->_view->titulo = "Notas Informativas";
    $this->_view->navegacion ="Inicio";
    $this->_view->notas = self::_obtenerNotas();
    $this->_view->render('notas', 'inicio');
  }

  #Método para mostrar la página con el formulario de agregar notas.
  private function _agregar($datos)
  {
    $this->_acl->controlAccess('root_access');
    $stmt=$this->_set->agregarNota($datos);
    return $stmt;
  }

  #Método para mostrar la página de la bitácora de mantenimiento.
  public function bitacora()
  {
    $this->_view->setJs(array('bitacora'));
    $this->_view->titulo = "Bitácora de mantenimiento";
    $this->_view->bitacora = self::_bitacora();
    $this->_view->render('bitacora', 'inicio');
  }

  #Método para mostrar detallandemente el mantenimiento de un equipo
  public function detalles($id)
  {
    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($id))){
      $this->redirect('inicio/bitacora/');
    }
    $id = $this->filterInt(base64_decode($id));
    //$row = $this->_set->obtenerBitacoraId($id);
    $row =  self::_bitacoraId($id);
    echo json_encode($row);
    //var_dump($row);
     //var_dump($row);
    /*$this->_view->titulo = "Información del mantenimiento";*/
    /*$this->_view->render('detalles', 'inicio');*/
  }
  
  #Método para eliminar registro en el bitácora
  public function eliminar($id)
  {
    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($id))){
      $this->redirect('inicio/bitacora/');
    }
    $id = $this->filterInt(base64_decode($id));
    $row = self::_eliminar($id);
    echo $row;
  }
  private function _eliminar($id)
  {
      $stmt = $this->_set->eliminarRegistro($id);
      return $stmt;
  }

  #Método para ver la información de una nota.
  public function ver($id)
  {
    $this->_acl->controlAccess('consult_register');
    if(!$this->filterInt(base64_decode($id))){
      $this->redirect('inicio/notas/');
    }
    $id = $this->filterInt(base64_decode($id));
    $row = self::_notaId($id);
    echo json_encode($row);

  }
  
  public function eliminarNota($id)
  {
     $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($id))){
      $this->redirect('inicio/notas/');
    }
    $id = $this->filterInt(base64_decode($id));
    $row = self::_eliminarNota($id);
    echo  $row;
  }
  
  private function _eliminarNota($id)
  {
    $delete = $this->_set->eliminarNota($id);
    return $delete;
  }

  private function _bitacoraId($id)
  {
    $mantenimiento = $this->_set->obtenerBitacoraId($id);
    return $mantenimiento;
    /*$this->_view->_nombre=$mantenimiento['nomComputadora'];
    $this->_view->_marca=$mantenimiento['marcaComputadora'];
    $this->_view->_so=$mantenimiento['OS'];
    $this->_view->_iconSistem = $this->seleccionSO($mantenimiento['OS']);
    $this->_view->_nombreReporte=$mantenimiento['nomMantenimiento'];
    $this->_view->_diagnostico=$mantenimiento['diagnosticoMantenimiento'];
    $this->_view->_solucion=$mantenimiento['solucionMantenimiento'];
    $this->_view->_dep=$mantenimiento['depComputadora'];
    $this->_view->_ingreso=$this->formatoFechaEdad($mantenimiento['fechaIngresoMantenimiento']);
    $this->_view->_nomSucursal=$mantenimiento['nombreSucursal'];
    $this->_view->_salida=$this->formatoFechaEdad($mantenimiento['fechaSalidaMantenimiento']);*/
  }
  
  private function _notaId($id)
  {
    $nota = $this->_set->obtenerNotaId($id);
    return $nota;  
  }

  #Método para consultar a la base de datos las notas.
  private function _obtenerNotas()
  {
    $notas = $this->_set->obtenerNotas();
    return $notas;
  }

  #Método para obtener los registros de la bitacora de la base de datos.
  private function _bitacora()
  {
    $bitacora = $this->_set->obtenerBitacora();
    return $bitacora;
  }

  #Método para enviar los datos a la base de datos.
  private function _agregarNota()
  {
    $row = $this->_set->agregarNota();
    return $row;
  }

  private function _verNota($id)
  {
    $nota = $this->_set->obtenerNotaId($id);
  }  

}
