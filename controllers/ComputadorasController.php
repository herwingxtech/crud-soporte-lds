<?php
/* ================================================================================= * 
+ Nombre de la clase: computadorasController                                       + * 
+ ubicación: soporte/controllers/                                                  + *
+ Descripción: Esta clase es la encargada de controlar todos los procesos          + * 
+ referentes al catálogo de computadoras tales como agregar, + * + mostrar, editar + *
+ y eliminar. En esta clase se validan todos los datos que son recogidos de la     + *
+ vista y se tranfieren al modelo.                                                 + *                    
================================================================================= */
class ComputadorasController extends Controller
{
  private $_set;
  private $_computadoras;
  private $_hojaResguardo;
  private $_hoja;

  /*
   Contructor que inicializa el método loadModel para cargar el modelo
   a utilizar; loadModel se encuentra en application/Model.php
  */
  public function __construct()
  {
    parent::__construct();
    $this->_computadoras = $this->loadModel('Computadoras');
    $this->_set = $this->loadModel('Set');
    //self::_urls();
    $this->urls('computadoras');
    //$this->getLibrary('fpdf');
   // $this->getLibrary('html_table');
    //$this->getLibrary('/tcpdf/tcpdf');
    $this->getLibrary('/tcpdf/MyReporte');
   // $this->_hoja = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $this->_hoja = new MyReporte(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    //$this->_hojaResguardo = new PDF();
    $this->_view->sucursal = $this->_set->obtenerSucursal();
    
    $this->_view->_tipo = array('Laptop', 'Escritorio'); //arreglo para select del campo tipo de dispositivo
    $this->_view->_ram = array('1 Gb', '2 Gb', '3 Gb', '4 Gb', '6 Gb', '8 Gb', '12 Gb', '16 Gb', '32 Gb' );
    $this->_view->_hd = array('160 Gb', '250 Gb', '320 Gb', '500 Gb', '1 Tb', '2 Tb','3 Tb', '32 Gb SSD', '64 Gb SSD', '120 Gb SSD', '240 Gb SSD', '480 Gb SSD', '960 Gb SSD','1 Tb SSD');
  }

  private function _urls()
  {
    $modulo = BASE_URL . "computadoras" . DS;
    $this->_view->url = $modulo;
    $this->_view->urlAgregar = $modulo . "agregar" .DS;
    $this->_view->urlCatalogo =$modulo . "catalogo" .DS;
    $this->_view->urlIPS = $modulo . "controlIP" .DS;
    $this->_view->urlDetalles = $modulo . "detalles" .DS;
    $this->_view->urlActualizar = $modulo . "actualizar" .DS;
    $this->_view->urlliberarIP = $modulo. "liberarIP" .DS;
    $this->_view->urlasignarIP = $modulo. "asignarIP" .DS;
    $this->_view->urlasignarIP = $modulo. "reporte" .DS;
    $this->_view->pronavegacion = "Módulo Computadoras";
  }

  #Método para inicar el módulo de computadoras
  public function index()
  {

    $this->_view->setJs(array('script'));
    $this->_view->inicio = "Inicio";
    $this->_view->titulo = "Módulo de Computadoras";
    $this->_view->navegacion ="Computadoras";
    $this->_view->pcsAsignados = self::_totalPCSAsignada();
    if($this->getInt('registrar') == 4){
      $this->_view->setJs(array('script'));
      $this->_acl->controlAccess('root_access');
      $row = self::_agregar();
      $this->_view->_mensaje=$row;
    }
    $this->_view->render('index', 'computadoras');
  }

  #Método para contar computadoras asignadas
  private function _totalPCSAsignada()
  {
    $pcs = $this->_computadoras->totalPCS(2);
    return $pcs;
  }

  #Método para mostrar el catálogo en la vista.
  public function catalogo()
  {
    $this->_view->setJs(array('script'));
    $this->_view->pcs = self::_obtenerComputadoras();
    $this->_view->navegacion ="Catálogo de Computadoras";
    $this->_view->titulo = "Catálogo de computadoras";
    $this->_view->render('catalogo', 'computadoras');
  }
  public function catalogoAjax()
  {
    $data['data'] =$this->_computadoras->catalogoPCAjax();
    echo  json_encode($data);
  }

#Método para obtener el catálogo de computadoras del modelo
  private function _obtenerComputadoras()
  {
      $pcs = $this->_computadoras->getComputadoras();
      return $pcs;
  }

  #Método para agregar computadoras al sistema
  private function  _agregar()
  {
    //$this->_view->navegacion = "Agregar computadoras";
    //$this->_view->titulo ="Agregar computadoras ";

    //if($this->getInt('registrar') == 4){
      $this->_view->datos= $_POST;
      #Verificar si el nombre de la computadora está disponible
      if($this->_computadoras->nomCompDisp($this->getText('nombre')) !=0){
        $this->_view->alerta = "danger";
        $this->_view->_mensaje = "El nombre <strong>". $_POST['nombre']."</strong> ya está en uso";
      //  $this->_view->nomPC=1;//para validar el nombre de la pc
        $this->_view->render('agregar','computadoras');
        exit;
      }
      #Valida que el número de serie no está en el sistema
      if($this->_computadoras->validaSerie($this->getText('serie'))!=0){
        $this->_view->alerta = "danger";
        $this->_view->_mensaje = "Está computadora ya está registrada con el número de serie <strong>" . $_POST['serie'] . "</strong>";
        $this->_view->seriePC=1;
        $this->_view->render('agregar','computadoras');
        exit;
      }
      $sucursal=null;
      $ip=(int)255;
      $status = (int)1;

      #arreglo de datos que se enviarán al modelo
      $datos = array(
        "nombre"      => $this->getPostParam('nombre'),
        "usuario"     => $this->getPostParam('usuario'),
        "marca"       => $this->getPostParam('marca'),
        "modelo"      => $this->getPostParam('modelo'),
        "tipo"        => $this->getPostParam('tipo'),
        "serie"       => $this->getPostParam('serie'),
        "procesador"  => $this->getPostParam('procesador'),
        "ram"         => $this->getPostParam('ram'),
        "so"          => $this->getPostParam('so'),
        "hd"          => $this->getPostParam('hd'),
        "mac"         => "Pendiente",
        "departamento"=> $this->getPostParam('departamento'),
        "fechaIngreso"=> $this->formatoFecha($this->getPostParam('fechaIngreso'),3),
        "sucursal"    => $this->getPostParam('sucursal'),
        "descripcion" => $this->getPostParam('descripcion'),
        "ip"          => $ip,
        "status"      => $status
      );
      #enviado datos al método agregarComputadora para insertarlos a la Base de Datos
      $row = $this->_computadoras->agregarComputadora($datos);
      return $row;
     /* if($row !=0) {
        unset($this->_view->datos);
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->render('agregar', 'computadoras');
        exit;
      }else {
        $this->_view->alerta="danger";
        $this->_view->_mensaje="Los datos no se guardaron";
        $this->_view->render('agregar', 'computadoras');
        exit;
      }*/

    //}
   // $this->_view->render('agregar', 'computadoras');
  }

  #Método para actualizar información de las computadoras
  public function actualizar($pc)
  {
    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($pc))){
      $this->redirect('computadoras/catalogo/');
    }

    $pc = $this->filterInt(base64_decode($pc));
    $this->_view->titulo = "Actualizar Computadora";
    $this->_view->navCatalogo ="Catálogo de Computadoras";
    $this->_view->urlCatalago = BASE_URL . "computadoras" . DS . "catalogo" . DS;
    $this->_view->navegacion="Editar computadora";

    if(($this->getInt('actualizar') == 4)){
      $this->_view->datos= $_POST;
      #Validar MAC
     // $mac = $this->_consultaMAC($this->getPostParam('mac'));
     // echo $mac['MAC'];
     
     
    /*  if($mac['MAC'] !="pendiente" || $mac['MAC']!="Pendiente"){
        if($mac){
          $this->_view->alerta = "danger";
          $this->_view->_mensaje = "El dirección física <strong>". $_POST['mac']."</strong> ya está registrada";
      //  $this->_view->nomPC=1;//para validar el nombre de la pc
          $this->_view->render('actualizar','computadoras');
          exit;
        }
      }
     /* if( $mac!=0 && $mac!="Pendiente"){
        $this->_view->alerta = "danger";
        $this->_view->_mensaje = "El dirección física <strong>". $_POST['mac']."</strong> ya está registrada";
      //  $this->_view->nomPC=1;//para validar el nombre de la pc
        $this->_view->render('actualizar','computadoras');
        exit;
      }*/

      #arreglo de datos que se enviarán al modelo
      $datos = array(
        "nombre"      => $this->getPostParam('nombre'),
        "usuario"     => $this->getPostParam('usuario'),
        "marca"       => $this->getPostParam('marca'),
        "modelo"      => $this->getPostParam('modelo'),
        "tipo"        => $this->getPostParam('tipo'),
        "serie"       => $this->getPostParam('serie'),
        "procesador"  => $this->getPostParam('procesador'),
        "ram"         => $this->getPostParam('ram'),
        "so"          => $this->getPostParam('so'),
        "hd"          => $this->getPostParam('hd'),
        "mac"         => $this->getPostParam('mac'),
        "departamento"=> $this->getPostParam('departamento'),
        "sucursal"    => $this->getPostParam('sucursal'),
        "descripcion" => $this->getPostParam('descripcion'),
        "id"          => $pc

      );

      #enviado datos al método actualizarComputadora para actualizar  la Base de Datos
      $row = $this->_computadoras->actualizaComputadora($datos);
      if($row !=0) {
        unset($this->_view->datos);
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->pcs =self::_obtenerComputadoras();
        $this->_view->render('catalogo', 'computadoras');
        exit;
        $this->_view->_mensaje = "ok";
      }else {
        self::_consultarPC($pc);
        $this->_view->alerta="danger";
        $this->_view->_mensaje="Los datos no se guardaron";
        $this->_view->render('actualizar', 'computadoras');
        exit;
      }

    }
    self::_consultarPC($pc);
    $this->_view->render('actualizar', 'computadoras');
  }

  private function _consultaMAC($mac)
  {
    $row = $this->_computadoras->validaMAC($mac);
    return $row;
  }

  public function detalles($pc)
  {
    if(!$this->filterInt(base64_decode($pc))){
      $this->redirect('computadoras/catalogo/');
    }
    $pc = $this->filterInt(base64_decode($pc));
    $this->_view->titulo = "Información detallada";
    $this->_view->navCatalogo ="Catálogo de Computadoras";
    $this->_view->urlCatalago = BASE_URL . "computadoras" . DS . "catalogo" . DS;
    $this->_view->navegacion="detalle computadora";
    self::_consultarPC($pc);
    $this->_view->render('detalles', 'computadoras');


  }

  #Método para consultar computadoras en el sistema
  private function _consultarPC($pc)
  {
    $computadora = $this->_computadoras->obtenerComputadoraId($pc);
    $this->_view->_nombre = $computadora['nomComputadora'];
    $this->_view->_usuario = $computadora['userComputadora'];
    $this->_view->_marca= $computadora['marcaComputadora'];
    $this->_view->_modelo = $computadora['modeloComputadora'];
    $this->_view->_tipoPC = $computadora['tipoComputadora'];
    $this->_view->_serie = $computadora['serieComputadora'];
    $this->_view->_procesadorPC = $computadora['Procesador'];
    $this->_view->_ramPC = $computadora['RAM'];
    $this->_view->_hdPC = $computadora['HD'];
    $this->_view->_so = $computadora['OS'];
    $this->_view->_iconSistem = $this->seleccionSO($computadora['OS']);
    $this->_view->_mac = $computadora['MAC'];
    $this->_view->_ip = $computadora['numIP'];
    $this->_view->_idIP = $computadora['IP_idIP'];
    $this->_view->_dep = $computadora['depComputadora'];
    $this->_view->_sucursal = $computadora['idSucursal'];
    $this->_view->_nomSucursal = $computadora['nombreSucursal'];
    $this->_view->_caracteristicas = $computadora['otrasCaracteristicas'];
    $this->_view->_fecha = $this->formatoFechaEdad($computadora['fechaIngresoComputadora']);
  }

  #Mpetodp para dar de baja a computadora del catálogo
  public function baja($pc)
  {
    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($pc))){
      $this->redirect('computadoras/catalogo/');
    }
    $pc=$this->filterInt(base64_decode($pc));
    self::_consultarPC($pc);
    $this->_view->titulo = "Dar de baja a computadora";
    $this->_view->navCatalogo = "Catálogo de Computadoras";
    $this->_view->navegacion = "Dar de baja";
    if($this->getInt('baja') == 4){

      $row =self::_cambiarStatusPC($pc, 3);

      if($row != 0){
        self::_liberaIP($pc);
        $this->_computadoras->cambiarStatusIP($this->_view->_idIP, 'Inactiva' );
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->pcs = self::_obtenerComputadoras();
        $this->_view->render('catalogo', 'computadoras');
        exit;

      }else {
        self::_consultarPC($pc);
        $this->_view->alerta="danger";
        $this->_view->_mensaje="Los datos no se guardaron";
        $this->_view->render('actualizar', 'computadoras');
        exit;
      }
    }
    $this->_view->render('baja', 'computadoras');
  }

  #Método para elimnar registro del catálogo de computadoras
  public function eliminar($pc)
  {
    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($pc))){
      $this->redirect('computadoras/catalogo/');
    }
    $pc=$this->filterInt(base64_decode($pc));
    self::_consultarPC($pc);
    $this->_view->titulo = "Eliminar registro";
    $this->_view->navCatalogo = "Catálogo de Computadoras";
    $this->_view->navegacion = "Eliminar";
    if($this->getInt('eliminar') == 4){
      $row = self::_eliminar($pc);
      if($row != 0){
        $this->_view->alerta="success";
        $this->_view->_mensaje="El registro se elimino correctamente";
        $this->_view->pcs = self::_obtenerComputadoras();
        $this->_view->render('catalogo', 'computadoras');
        exit;
      }
    }
    $this->_view->render('eliminar', 'computadoras');
  }
  private function _eliminar($pc){
    $row = $this->_computadoras->eliminarRegistro($pc);
    return $row;
  }

  #Cambiár stauts PC;
  private function _cambiarStatusPC($idPC, $status)
  {
    $stmt = $this->_set->cambiarStatusPC($idPC, $status);
    return $stmt;
  }
  #Método para asignar direcciones IP
  public function asignarIP($pc)
  {
    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($pc))){
      $this->redirect('computadoras/catalogo/');
    }
    $this->_view->titulo="Asignar dirección IP";
    $this->_view->navCatalogo="Catálogo de Computadoras";
    //$this->_view->urlCatalago = BASE_URL . "computadoras" . DS . "catalogo" . DS;
    $this->_view->navegacion="Asignar dirección IP";
    $pc=$this->filterInt(base64_decode($pc));
    self::_consultarPC($pc);
    if($this->_view->_ip != "SIN IP"){
      $this->redirect('computadoras/catalogo/');
    }

    if($this->getInt('asignarip') == 4){
      $ip = $this->getPostParam('ip');
      $sucursal = $this->getPostParam('sucursal');
      
      if(!$this->formatoIP($ip)){
        $this->_view->alerta="danger";
        $this->_view->_mensaje = "La dirección IP no tiene un formato correcto";
        $this->_view->render('asignarIP', 'computadoras');
        exit;
      }
      $dirIP =$this->_computadoras->disponibleIP($ip, $sucursal, 'Inactiva');
      //echo $dirIP['idIP']; exit;
      if($dirIP['idIP']==0){
        $this->_view->alerta="danger";
        $this->_view->_mensaje = "La dirección IP ya está asignada ";
        $this->_view->render('asignarIP', 'computadoras');
        exit;
      }
      $row = $this->_computadoras->asignaIP($pc, (int)$dirIP['idIP']);
      
      if($row != 0){
        $this->_computadoras->cambiarStatusIP((int)$dirIP['idIP'],'Asignada' );
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->pcs = self::_obtenerComputadoras();
        $this->_view->render('catalogo', 'computadoras');
        exit;
      }

    }
    $this->_view->render('asignarIP', 'computadoras');

  }



  #Método para liberar IP
  public function liberarIP($id)
  {

    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($id))){
      $this->redirect('inicio/Computadora/');
    }
    $id = $this->filterInt(base64_decode($id));
    $row =$this->_computadoras->liberaIP($id, (int)255);
    if($row){

    }else{
      
    }


  }
 # public function liberarIP($id, $opcion)
 /* {
    $this->_acl->controlAccess('root_access');
    if($opcion!=1 && $opcion !=2){
      $this->redirect('computadoras/');
    }
    $id = $this->filterInt(base64_decode($id));
    if($opcion==1){
      if(!$id){
        $this->redirect('computadoras/catalogo/');
      }
      print_r($_POST);
       $this->_view->urlCatalogo;
    //  $this->_view->urlCatalago = BASE_URL . "computadoras" . DS . "catalogo" . DS;
      $this->_view->navControl ="Control de direcciones IP";
      $this->_view->navegacion="Liberar dirección IP";
      self::_consultarPC($id);
      if($this->getInt('liberaip') == 4){
        $row = self::_liberaIP($id);
        if($row !=0){
          self::_modificarStatusIP($this->_view->_idIP, 'Inactiva');
          $this->_view->titulo = "liberar  IP";
          $this->_view->alerta="success";
          $this->_view->_mensaje="Los datos se grabaron correctamente";
          $this->_view->pcs = self::_obtenerComputadoras();
          $this->_view->render('catalogo', 'computadoras');
          exit;
        }
        else {
          self::_consultarPC($id);
          $this->_view->alerta="danger";
          $this->_view->_mensaje="Los datos no se guardaron";
          $this->_view->render('catalogo', 'computadoras');
          exit;
        }
      }
    }
    else if($opcion==2){
      if(!$id){
        $this->redirect('computadoras/controlIP/');
      }
      //$this->_view->urlCatalago = BASE_URL . "computadoras" . DS . "controlIP" . DS;
      $this->_view->navControl ="Control de direcciones IP";
      $this->_view->navegacion="Liberar dirección IP";
      $ip = self::_obtenerDirIPId($id);
      $this->_view->_ip = $ip['numIP'];
      $this->_view->_nombre = "Reservado";
      if($this->getInt('liberaip') == 4){
        $row = self::_modificarStatusIP($id, 'Inactiva');
        if($row !=0){
          //$this->_computadoras->cambiarStatusIP($this->_view->_idIP, 'Inactiva');
          $this->_view->titulo = "Liberar IP";
          $this->_view->alerta="success";
          $this->_view->_mensaje="Los datos se grabaron correctamente";
          $this->_view->ips = self::_obtenerDirIP();
          $this->_view->render('controlIP', 'computadoras');
          exit;
        }
        else {
          $this->_view->ips = self::_obtenerDirIP();
          $this->_view->alerta="danger";
          $this->_view->_mensaje="Los datos no se guardaron";
          $this->_view->navegacion="Control IP";
          $this->_view->render('controlIP', 'computadoras');
          exit;
        }
      }
    }
    $this->_view->titulo="Liberar dirección IP";
    $this->_view->render('liberarIP', 'computadoras');
  }*/

  private function _obtenerDirIPId($id)
  {
    $ip = $this->_computadoras->obtenerIPId($id);
    return $ip;
  }

  public function reservarIP($id)
  {
    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($id))){
      $this->redirect('computadoras/controlIP/');
    }
    if($this->getPostParam('comentarioIP')==""){
      $comentario="Ningún comentario";
    }else{
        $comentario = $this->getPostParam('comentarioIP');
    }
    $id =$this->filterInt(base64_decode($id));

    $this->_view->titulo="Reservar IP";
    $this->_view->navCatalogo ="Control IP";
    $this->_view->urlCatalago = BASE_URL . "computadoras" . DS . "controlIP" . DS;
    $this->_view->advertencia="¿Estás seguro de reservar está dirección IP?";
    $ip = self::_obtenerDirIPId($id);
    $this->_view->_ip = $ip['numIP'];
    if($this->getInt('reservarip') == 4){
      $reserva = self::_reservaIP($id, $comentario);
      if($reserva != 0){
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->ips = self::_obtenerDirIP();
        $this->_view->render('controlIP', 'computadoras');
        exit;
      }
    }
    $this->_view->render('reservarIP', 'computadoras');
  }
  private function _relacionPC($pc)
  {
      $relacion = $this->_computadoras->relacionUsuarioPC($pc);
      return $relacion;
  }
  public function resguardo($pc)
  {
    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($pc))){
      $this->redirect('computadoras/catalogo/');
    }
    
    $pc=$this->filterInt(base64_decode($pc));
    $relacion = self::_relacionPC($pc);
    if($relacion['tipoComputadora'] == "Laptop"){
        $tipo = "Computadora Portátil";
    }else{
        $tipo = "Computadora de Escritorio";
    }
    $nombre = $relacion['nomUsuario'] ." ".$relacion['apellidosUsuario'] ;
   
      //$logo = ROOT.'libs/images/logolinea.png';
    //$this->_hoja->Image($logo, 10, 15, ''. 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    $this->_hoja->SetAuthor('Pablo Manga Pérez');
    $this->_hoja->SetTitle($nombre);
    $this->_hoja->SetSubject('Hoja de Resguardo');
    $this->_hoja->SetKeywords('Línea', 'Digital', 'Corporativo', 'Telcel');
    $this->_hoja->addPage();
    $this->_hoja->Ln(30);
    $texto =
        '<span style="text-align:justify;"><p style="text-align:justify;">Por medio de la presente</span> el(a) <b>C. '. $nombre.'</b> Quien labora para esta empresa en el área de <b>'.$relacion['depUsuario'].'</b> recibe bajo resguardo una <b>' .$tipo .' Nueva</b> con las siguientes características:</p></span>';
      // set core font
      $this->_hoja->SetFont('helvetica', '', 12);
      $this->_hoja->writeHTML($texto, true, 0, true, true);
      //$this->_hoja->writeHTML('Marca: '.$relacion['marcaComputadora'].'.')
      
      $this->_hoja->SetFont('helvetica', 'B', 12);
      $this->_hoja->SetLineStyle(array('width' => 0.1, 'cap' => 'squared', 'join' => 'miter', 'dash' => 4, 'color' => array(0, 0, 0)));
      $this->_hoja->SetFillColor(255,255,255);
      $this->_hoja->SetTextColor(0,0,0);
      $marca = $relacion["marcaComputadora"];
      $modelo = $relacion["modeloComputadora"];
      $serie = $relacion["serieComputadora"];
      $procesador = $relacion["Procesador"];
      $ram = $relacion["RAM"];
      $hd = $relacion["HD"];
      $OS = $relacion["OS"];
      $sucursal = $relacion['depComputadora']. ", ". $relacion["nombreSucursal"];
      
     // $cMarca = strlen($marca)+3;
      
      $this->_hoja->Cell(30, 5, 'Marca:',0,0, 'L',0, 0);
      $this->_hoja->Cell(60, 0, ' '.$marca.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $this->_hoja->Cell(30, 5, 'Modelo:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$msodelo.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $this->_hoja->Cell(30, 5, 'Serie:',0,0, 'L',0, 0);
      $this->_hoja->Cell(35, 0, ' '.$serie.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $this->_hoja->Cell(30, 5, 'Procesador:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$procesador.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(); 
      $this->_hoja->Cell(30, 5, 'RAM:',0,0, 'L',0, 0);
      $this->_hoja->Cell(15, 0, ' '.$ram.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      $this->_hoja->Cell(30, 5, 'HDD:',0,0, 'L',0, 0);
      $this->_hoja->Cell(20, 0, ' '.$hd.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      $this->_hoja->Cell(30, 5, 'OS:',0,0, 'L',0, 0);
      $this->_hoja->Cell(100, 0, ' '.$OS.'',1,1, 'L',1, 0);$this->_hoja->Ln();
      $this->_hoja->Cell(30, 5, 'Ubicación:',0,0, 'L',0, 0);
      $this->_hoja->Cell(100, 0, ' '.$sucursal.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $html = '<span style="text-align:justify;"><p style="text-align: justify;">Asimismo, está de acuerdo en haber recibido la <b>Computadora nueva en perfectas condiciones</b> de funcionamiento para las actividades que le fueron encomendadas. Sabiendo que la computadora que recibe es exclusiva de trabajo, se compromete a hacer buen uso de la misma, evitando el uso inapropiado y negligente que conduzca a la pérdida de información o daño de la computadora. De la misma forma se compromete a mantenerlo en las mejores condiciones físicas, reportando cualquier anomalía o mal funcionamiento al área de TI para su correspondiente mantenimiento. Cualquier desperfecto al entregar el equipo será responsabilidad de quien firma esta carta y se procederá como lo designe el área correspondiente.</p></span>';

      // set core font
      $this->_hoja->SetFont('helvetica', '', 12);

      // output the HTML content
      $this->_hoja->writeHTML($html, true, 0, true, true);

      $this->_hoja->output('reporte.pdf', 'I');
    
      
  }                     

  private function _reservaIP($id, $comentario)
  {
    $reserva = $this->_computadoras->reservaIP($id, $comentario);
    return $reserva;
  }

  private function _liberaIP($id)
  {
    $row =$this->_computadoras->liberaIP($id, (int)255);
    return $row;

  }
  private function _modificarStatusIP($id, $status)
  {
    $row = $this->_computadoras->cambiarStatusIP($id, $status);
    return $row;
  }

  private function _obtenerDirIP()
  {
    $ips = $this->_computadoras->catalgoIP();
    return $ips;
  }

  #Método para administrar las direcciones IP
  public function controlIP()
  {
    $this->_acl->controlAccess('root_access');
    $this->_view->ips = self::_obtenerDirIP();
    $this->_view->navegacion ="Control de direcciones IP";
    $this->_view->titulo = "Control de direcciones IP";
    $this->_view->render('controlIP', 'computadoras');
  }

  private function _agregarReporte($datos)
  {
    $row = $this->_computadoras->agregarRerporteMantenimiento($datos);
    return $row;
  }

  #Método para el levantamiento de reportes de mantenimiento
  public function reporte($id)
  {

    $id = $this->filterInt(base64_decode($id));
    if(!$id){
      $this->redirect('computadoras/catalogo/');
    }

    self::_consultarPC($id);
    if($this->getInt('reporte') == 4){
      $datos = array(
        'idPC' => $id,
        'nombre' => $this->getPostParam('nombreReporte'),
        'diagnostico' => $this->getPostParam("diagnostico"),
        'solucion' => $this->getPostParam("solucion"),
        'fecha' => $this->formatoFecha($this->getPostParam('fechaSalida'),2)
      );
      $row =self::_agregarReporte($datos);
      if($row != 0){
        $this->_view->titulo = "Catálogo de computadoras";
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->pcs = self::_obtenerComputadoras();
        $this->_view->render('catalogo', 'computadoras');
        exit;
      }else {
        $formulario = $_POST;
        $this->_view->_nombreReporte=$formulario['nombreReporte'];
        $this->_view->_diagnostico=$formulario['diagnostico'];
        $this->_view->_solucion=$formulario['solucion'];
        $this->_view->_fecha= $formulario['fechaSalida'];
        $this->_view->alerta="danger";
        $this->_view->titulo ="Agregar mantenimiento";
        $this->_view->_mensaje="Los datos no se guardaron";
        $this->_view->navegacion="Catálogo de computadoras";
        $this->_view->render('reporte', 'computadoras');
        exit;
      }
    }
    $this->_view->navCatalogo ="Catálogo de computadoras";
    $this->_view->urlCatalago = BASE_URL . "computadoras" . DS . "catalogo" . DS;
    $this->_view->titulo="Agregar mantenimiento";
    $this->_view->render('reporte', 'computadoras');
  }


}
