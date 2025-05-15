<?php

class UsuariosController extends Controller
{
  private $_computadoras;
  private $_usuarios;
  private $_set;

  public function __construct()
  {
    parent::__construct();
    //self::_urls();
    $this->urls('usuarios');
    $this->_usuarios = $this->loadModel('Usuarios');
    $this->_set = $this->loadModel('Set');
    $this->_computadoras = $this->loadModel('Computadoras');
    $this->_view->sucursal = $this->_set->obtenerSucursal();
    $this->getLibrary('/tcpdf/MyReporte');
    $this->_hoja = new MyReporte(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  }

/*  private function _urls()
  {
    $modulo = BASE_URL . "usuarios" . DS;
    $this->_view->url = $modulo;
    $this->_view->urlCatalago = $modulo. "catalogo" . DS;
    $this->_view->urlAgregar = $modulo . "agregar" .DS;
    $this->_view->pronavegacion = "Módulo de Usuarios";

  }*/

  public function index()
  {
    $this->_view->inicio = "Inicio";
    $this->_view->titulo = "Módulo de Usuarios";
    $this->_view->navegacion ="Usuarios";
    $this->_view->_usuariosActivos= self::_totalUsuarios('Activo') . " Usuarios Activos";
    $this->_view->render('index', 'usuarios');
  }
  private function _totalUsuarios($valor)
  {
    $row = $this->_usuarios->totalUsuarios($valor);
    return $row;
  }
  #Método para motrar formulario de agregar usuarios
  public function agregar()
  {
    $this->_acl->controlAccess('root_access');
    $this->_view->titulo ="Agregar usuarios";
    $this->_view->navegacion="Agregar Usuarios";
    if($this->getInt('registrar')==4){
      $this->_view->datos = $_POST;
      $email = $this->getPostParam('email');
      if(!$this->validaEmail($email)){
        $this->_view->alerta = "danger";
        $this->_view->_mensaje ="El correo electrónico no tiene el siguiente formato <strong>'usuario@dominio.com'</strong>";
        $this->_view->render('agregar', 'usuarios');
        exit;
      }
      $checarCorreo = self::_checarCorreo($email);
      if($checarCorreo!=0){
        $this->_view->alerta = "danger";
        $this->_view->_mensaje ="El correo electrónico <strong>". $_POST['email']."</strong> ya está en uso";
        $this->_view->render('agregar', 'usuarios');
        exit;
      }
      if($this->getPostParam('domicilio') == ""){
        $_POST['domicilio'] = "Conocida";
      }
      $datos = array(
        'nombre' => $this->getPostParam('nombre'),
        'apellidos' => $this->getPostParam('apellidos'),
        'email' => $this->getPostParam('email'),
        'domicilio' => $this->getPostParam('domicilio'),
        'fechaNacimiento' => $this->formatoFecha($this->getPostParam('fechaNacimiento'), 3),
        'fechaIngreso' => $this->formatoFecha($this->getPostParam('fechaIngreso'), 3),
        'departamento' => $this->getPostParam('departamento'),
        'comentario' => $this->getPostParam('comentario'),
        'sucursal' => $this->getPostParam('sucursal'),
        'avatar' => "avatar_small.png",
        'status' => "Activo",
        'clave' => "default",
      );
      $row = self::_agregarUsuarios($datos);
      if($row!=0){
        unset($this->_view->datos);
        $this->_view->titulo = "Agregar Usuarios";
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->render('agregar', 'usuarios');
        exit;
      }else{
        $this->_view->alerta="warning";
        $this->_view->_mensaje="Los datos no se guardaron";
        $this->_view->navegacion="Moduolo ¿";
        $this->_view->render('agregar', 'usuarios');
        exit;
      }
    }
    $this->_view->render('agregar', 'usuarios');

  }

  private function _checarCorreo($correo)
  {
    $row = $this->_usuarios->verificarCorreoElectronio($correo);
    return $row;
  }

  private function _agregarUsuarios($datos)
  {
    $row = $this->_usuarios->agregarUsuario($datos);
    return $row;
  }

  public function catalogo()
  {
    $this->_view->setJs(array('usuarios'));
    $this->_view->titulo="Catálogo de Usuarios";
    $this->_view->usuarios = self::_catalogoUsuarios();
    $this->_view->render('catalogo', 'usuarios');
  }
  private function _catalogoUsuarios()
  {
    $usuarios = $this->_usuarios->obtenerCatalogo();
    return $usuarios;
  }
  public function detalles($idUsuario){
    if(!$this->filterInt(base64_decode($idUsuario))){
      $this->redirect('usuarios/catalogo/');
    }
    $this->_view->setJs(array('usuarios'));
    $idUsuario = $this->filterInt(base64_decode($idUsuario));
      $this->_view->_id = $idUsuario;
    $this->_view->titulo = "Información detallada";
    $this->_view->navCatalogo ="Catálogo de Usuarios";

    $this->_view->navegacion="detalle usuario";
    self::_consultarUsuario($idUsuario);
    if($this->getInt('fotoPerfil') == 4){
        /*if(isset($_FILES['datosImagen']['tmp_name'])){
            $directorio = ROOT . "public/img/avatares" . DS . $idUsuario;
            $foto = $directorio . DS . $this->_view->_foto;
            if(!empty($foto)){
                unlink($foto);
            }else{
                mkdir($directorio, 0755);
            }
            $nombreFoto = $idUsuario . "_" . $this->_view->_email . uniqid();
            $ruta = $directorio . DS . $nombreFoto . ".jpg";
            list($ancho, $alto) = getimagesize($_FILES['datosImagen']['tmp_name']);
            $nAncho =400; // nuevo valor ancho
            $nAlto = 500; //nuevo valor alto
            $origen = imagecreatefromjpeg($_FILES['datosImagen']['tmp_name']);
            $destino = imagecreatetruecolor($nAncho, $nAlto);
            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nAncho, $nAlto, $ancho, $alto);
            imagejpeg($destino, $ruta);
            
        }*/
        $imagen=null;
        if(isset($_FILES['datosImagen']['tmp_name'])){
            $directorio = ROOT . 'public' . DS . 'img' . DS . 'avatares' . DS . $idUsuario . DS;
            $foto = $directorio . $this->_view->_foto;
            $thumb_img = $directorio . 'thumb_' . $this->_view->_foto;
            if(isset($foto)){
                if(!empty($foto)){
                    unlink($foto);
                    unlink($thumb_img);
                }
            }else{
                mkdir($directorio, 0755);
            }
            $upload = new upload($_FILES['datosImagen'], 'es_ES');
            $upload->allowed = array('image/*');
            $upload->file_new_name_body = strtolower($this->_view->_nombre) . uniqid();
            $upload->process($directorio);
            if($upload->processed){
                $imagen = $upload->file_dst_name;
                $thumb = new upload($upload->file_dst_pathname);
                $thumb->image_resize = true;
                $thumb->image_ratio = true;
                $thumb->image_x = 60;
                $thumb->image_y = 60;
                $thumb->file_name_body_pre = 'thumb_';
                $thumb->process($directorio);
                $row = self::_actualizarFotoPerfil($imagen, $idUsuario);
                if($row != 0){
                    echo 
				'
				<script>
					swal({
						title: "¡Ok!",
						text: "¡la foto se actualizo correctamente!",
						type: "success",
						confirmButtonText: "Cerrar",
						closeOnConfirm: false
					},

					function(isConfirm){
						if(isConfirm){
							history.back(); 
						}
					}
				)
				</script>';
                }else{
                    
                }
            }else{
                echo $upload->error;
            }
        }
    }

    $this->_view->render('detalles', 'usuarios');
  }

  public function actualizar($idUsuario)
  {
    if(!$this->filterInt(base64_decode($idUsuario))){
      $this->redirect('usuarios/catalogo/');
    }
    $idUsuario = $this->filterInt(base64_decode($idUsuario));
    $this->_view->titulo = "Actualizar Usuario";
    $this->_view->navCatalogo ="Catálogo de Usuarios";
    $this->_view->urlCatalago = BASE_URL . "usuarios" . DS . "catalogo" . DS;
    $this->_view->navegacion="actualizar usuario";
    self::_consultarUsuario($idUsuario);
    if(($this->getInt('actualizar') == 4)){

      #arreglo de datos que se enviarán al modelo
      $datos =array(
        'nombre' => $this->getPostParam('nombre'),
        'apellidos' => $this->getPostParam('apellidos'),
        'email' => $this->getPostParam('email'),
        'domicilio' => $this->getPostParam('domicilio'),
        'fechaNacimiento' => $this->formatoFecha($this->getPostParam('fechaNacimiento'), 3),
        'fechaIngreso' => $this->formatoFecha($this->getPostParam('fechaIngreso'), 3),
        'departamento' => $this->getPostParam('departamento'),
        'comentario' => $this->getPostParam('comentario'),
        'sucursal' => $this->getPostParam('sucursal'),
        'idUsuario' => $idUsuario
      );
      $row = self::_actualizarUsuarios($datos);
      if($row!=0){
        unset($this->_view->datos);
      //  $this->_view->titulo = "Cata Usuarios";
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->usuarios = self::_catalogoUsuarios();
        $this->_view->render('catalogo', 'usuarios');
        exit;
      }else{
        $this->_view->alerta="warning";
        $this->_view->_mensaje="Los datos no se guardaron";
        $this->_view->navegacion="Moduolo Usuarios";
        $this->_view->render('actualizar', 'usuarios');
        exit;
      }



    }
    $this->_view->render('actualizar', 'usuarios');
  }
  private function _actualizarFotoPerfil($foto, $idUsuario)
  {
      $row = $this->_usuarios->actualizarFotoPerfil($foto, $idUsuario);
      return $row;
  }
  private function _actualizarUsuarios($datos)
  {
    $row = $this->_usuarios->actualizaUsuario($datos);
    return $row;
  }
  private function _consultarUsuario($idUsuario)
  {

    $usr = $this->_usuarios->obtenerUsuario($idUsuario);
    $this->_view->_id = $idUsuario;
    $this->_view->_foto =$usr['avatarUsuario'];
    $this->_view->_nombre = $usr['nomUsuario'];
    $this->_view->_apellidos = $usr['apellidosUsuario'];
    $this->_view->_nombreCompleto= $usr['nomUsuario'] . ' '. $usr['apellidosUsuario'];
    $this->_view->_email= $usr['mailUsuario'];
    $this->_view->_clave= $usr['claveEmail'];
    $this->_view->_direccion= $usr['dirUsuario'];
    $this->_view->_fechaNacimientoFormato = $this->formatoFecha($usr['fechaNacimiento'],4);
    $this->_view->_fechaNacimiento= $this->formatoFechaEdad($usr['fechaNacimiento']);
    $this->_view->_dep = $usr['depUsuario'];
    $this->_view->_sucursal = $usr['idSucursal'];
    $this->_view->_nomSucursal = $usr['nombreSucursal'];
    $this->_view->_fechaIngreso = $this->formatoFechaEdad($usr['fechaIngreso']);
    $this->_view->_fechaIngresoFormato = $this->formatoFecha($usr['fechaIngreso'],4);
    $this->_view->_status = $usr['status'];
    $this->_view->_comentario = $usr['comentarios'];
   
  }

  public function registrarClave($idUsuario)
  {
    if(!$this->filterInt(base64_decode($idUsuario))){
      $this->redirect('usuarios/catalogo/');
    }
    $idUsuario = $this->filterInt(base64_decode($idUsuario));
    $this->_view->navCatalogo ="Catálogo de Usuarios";
    $this->_view->urlCatalago = BASE_URL . "usuarios" . DS . "catalogo" . DS;
    $this->_view->navegacion="Registrar contraseña";
    $this->_view->titulo='Registrar Contraseña';
    self::_consultarUsuario($idUsuario);
    if($this->getInt('agregar_clave') == 4){
      $clave = $this->getPostParam('contrasena');
      $row = self::_agregarClave($clave, $idUsuario);
      if($row != 0){
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->usuarios = self::_catalogoUsuarios();
        $this->_view->render('catalogo', 'usuarios');
        exit;
      }
      else{
        $this->_view->alerta="warning";
        $this->_view->_mensaje="Los datos no se guardaron";
        $this->_view->navegacion="Moduolo Usuarios";
        $this->_view->render('registrarClave', 'usuarios');
        exit;
      }
    }
      $this->_view->render('registrarClave', 'usuarios');
  }

  private function _agregarClave($clave, $idUsuario)
  {
    $row = $this->_usuarios->agregarClave($clave, $idUsuario);
    return $row;
  }

  public function asignar($idUsuario)
  {
    if(!$this->filterInt(base64_decode($idUsuario))){
      $this->redirect('usuarios/catalogo/');
    }
    $idUsuario = $this->filterInt(base64_decode($idUsuario));
    $this->_view->navCatalogo ="Catálogo de Usuarios";
    $this->_view->urlCatalago = BASE_URL . "usuarios" . DS . "catalogo" . DS;
    $this->_view->titulo = "Asignar Computadora";
    self::_consultarUsuario($idUsuario);
    self::_obtenerPCDisponible($this->_view->_sucursal, $this->_view->_dep);
    if($this->getInt('asignar') == 4){
      $pc = $this->getPostParam('pc');
      echo "COMPUTADORA: " . $pc;
      $observacion = $this->getPostParam('observacion');
      $row = self::_asignarPC($pc, $idUsuario, $observacion, "asignada");
      if($row != 0){
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        self::_cambiarStatusPC($pc, 2); //Cambiar el estatus a asignada
        $this->_view->usuarios = self::_catalogoUsuarios();
        $this->_view->render('catalogo', 'usuarios');
        exit;
      }
      else{
        $this->_view->alerta="warning";
        $this->_view->_mensaje="<h5>Los datos no se guardaron</h5>";
        $this->_view->navegacion="Modulo Usuarios";
        $this->_view->render('asignar', 'usuarios');
        exit;
      }
    }
    $this->_view->render('asignar', 'usuarios');
  }

  public function baja($id)
  {
    $this->_acl->controlAccess('root_access');
    
    if(!$this->filterInt(base64_decode($id))){
      $this->redirect('usuarios/');
    }
    
    $id = $this->filterInt(base64_decode($id));
    $row = $this->_usuarios->desactivar($id);
    echo $row; 
  }

  private function _asignarPC($pc, $idUsuario, $obs, $status)
  {
    $row = $this->_set->asignarPC($pc, $idUsuario,  $obs, $status);
    return $row;
  }
  private function _obtenerPCDisponible($sucursal, $departamento)
  {
    $computadoras = $this->_set->obtenerPCSucursal($sucursal, $departamento);
    return $this->_view->computadoras = $computadoras;
  }

  private function _cambiarStatusPC($idPC, $status)
  {
    $this->_set->cambiarStatusPC($idPC, $status);
  }

  #Método para ver la relacion de usuarios y las computadoras asignadas
  public function relacion()
  {
    $this->_view->titulo = "Relación Usuario-Computadora";
    $this->_view->relacion = self::_relacionPC();
    $this->_view->render('relacion', 'usuarios');
  }

  private function _relacionPC()
  {
    $relacion = $this->_usuarios->relacionPC();
    return $relacion;
  }
  private function _relacionarPC($pc){
    
    $relacion = $this->_computadoras->relacionUsuarioPC($pc);
    return $relacion;
  }

  public function liberar($idUsuario, $idComputadora)
  {
    
    
    if(!$this->filterInt(base64_decode($idUsuario))){
      $this->redirect('usuarios/relacion/');
    }
    if(!$this->filterInt(base64_decode($idComputadora))){
      $this->redirect('usuarios/relacion/');
    }
    $idUsuario = $this->filterInt(base64_decode($idUsuario));
    $idComputadora = $this->filterInt(base64_decode($idComputadora));
    $this->_view->navRelacion ="Relación Usuario-Computadora";
    //$this->_view->urlCatalagoR = BASE_URL . "usuarios" . DS . "relacion" . DS;
    $this->_view->titulo = "Liberar Computadora";
    self::_consultarUsuario($idUsuario);
    self::_consultarPC($idComputadora);
    if($this->getInt('liberar') == 4){
       $row = self::_statusRelacionPC($idUsuario, $idComputadora, "baja");
       if($row != 0){
         $this->_view->alerta="success";
         $this->_view->_mensaje="Los datos se grabaron correctamente";
         self::_cambiarStatusPC($idComputadora, 1); //Cambiar el estatus a asignada
         $this->_view->relacion = self::_relacionPC();
         $this->_view->render('relacion', 'usuarios');
         exit;
       }
       else{
         $this->_view->alerta="warning";
         $this->_view->_mensaje="<h5>Los datos no se guardaron</h5>";
         $this->_view->navegacion="Moduolo Usuarios";
         $this->_view->render('liberar', 'usuarios');
         exit;
       }
    }
    //$this->_view->idPC = $idComputadora;
    $this->_view->render('liberar', 'usuarios');
  }

  private function  _statusRelacionPC($idUsuario, $idComputadora, $status)
  {
    $row = $this->_usuarios->cambioStatusUsuarioPC($idUsuario, $idComputadora, $status);
    return $row;
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
  }

  public function eliminar($idUsuario, $idComputadora)
  {
    if(!$this->filterInt(base64_decode($idUsuario))){
      $this->redirect('usuarios/relacion/');
    }
    if(!$this->filterInt(base64_decode($idComputadora))){
      $this->redirect('usuarios/relacion/');
    }
    $idUsuario = $this->filterInt(base64_decode($idUsuario));
    $idComputadora = $this->filterInt(base64_decode($idComputadora));
    $this->_view->navRelacion ="Relación Usuario-Computadora";
    //$this->_view->urlCatalago = BASE_URL . "usuarios" . DS . "relacion" . DS;
    $this->_view->titulo = "Eliminar Registro";
    self::_consultarUsuario($idUsuario);
    self::_consultarPC($idComputadora);
    if($this->getInt('eliminar') == 4){
       $row = self::_eliminarRelacionPC($idUsuario, $idComputadora);
       if($row != 0){
         $this->_view->alerta="success";
         $this->_view->_mensaje="Los datos se grabaron correctamente";
         self::_cambiarStatusPC($idComputadora, 1); //Cambiar el estatus a asignada
         $this->_view->relacion = self::_relacionPC();
         $this->_view->render('relacion', 'usuarios');
         exit;
       }
       else{
         $this->_view->alerta="warning";
         $this->_view->_mensaje="<h5>Los datos no se guardaron</h5>";
         $this->_view->navegacion="Moduolo Usuarios";
         $this->_view->render('liberar', 'usuarios');
         exit;
       }
     }
       $this->_view->render('eliminar', 'usuarios');
  }

  private function _eliminarRelacionPC($idUsuario, $idComputadora)
  {
    $row  = $this->_usuarios->eliminarRelacion($idUsuario, $idComputadora);
    return $row;
  }
   
  public function resguardo($pc)
  {
    
    $this->_acl->controlAccess('root_access');
    if(!$this->filterInt(base64_decode($pc))){
      $this->redirect('computadoras/catalogo/');
    }
    
    $pc=$this->filterInt(base64_decode($pc));
    $relacion = self::_relacionarPC($pc);
    if($relacion['tipoComputadora'] == "Laptop"){
        $tipo = "Computadora Portátil";
    }else{
        $tipo = "Computadora de Escritorio";
    }
    $nombre = $relacion['nomUsuario'] ." ".$relacion['apellidosUsuario'] ;
    $fecha = substr($relacion["fechaIngresoComputadora"], 0, 4);
      $fechaActual = date("Y");
      if($fecha>=$fechaActual){
          $pc="Nueva";
      }else{
          $pc = "Usada";
      }  
   
      //$logo = ROOT.'libs/images/logolinea.png';
    //$this->_hoja->Image($logo, 10, 15, ''. 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    $this->_hoja->SetAuthor('Pablo Manga Pérez');
    $this->_hoja->SetTitle($nombre);
    $this->_hoja->SetSubject('Hoja de Resguardo');
    $this->_hoja->SetKeywords('Línea', 'Digital', 'Corporativo', 'Telcel');
    $this->_hoja->addPage();
    $this->_hoja->Ln(30);
    $texto =
        '<span style="text-align:justify;"><p style="text-align:justify;">Por medio de la presente</span> el(a) <b>C. '. $nombre.'</b>, quien labora para esta empresa en el área de <b>'.$relacion['depUsuario'].'</b> recibe bajo resguardo una <b>' .$tipo ." ".$pc.'</b> con las siguientes características:</p></span>';
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
      $observacion = $relacion['observacionComputadora'];
      $this->_hoja->Cell(30, 5, 'Marca:',0,0, 'L',0, 0);
      $this->_hoja->Cell(45, 0, ' '.$marca.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(3);  
      $this->_hoja->Cell(30, 5, 'Modelo:',0,0, 'L',0, 0);
      $this->_hoja->Cell(45, 0, ' '.$modelo.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(3);
      $this->_hoja->Cell(30, 5, 'Serie:',0,0, 'L',0, 0);
      $this->_hoja->Cell(45, 0, ' '.$serie.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(3);
      $this->_hoja->Cell(30, 5, 'Procesador:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$procesador.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(3); 
      $this->_hoja->Cell(30, 5, 'RAM:',0,0, 'L',0, 0);
      $this->_hoja->Cell(35, 0, ' '.$ram.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(3);
      $this->_hoja->Cell(30, 5, 'HDD:',0,0, 'L',0, 0);
      $this->_hoja->Cell(35, 0, ' '.$hd.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(3);
      $this->_hoja->Cell(30, 5, 'OS:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$OS.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(3);
      $this->_hoja->Cell(30, 5, 'Ubicación:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$sucursal.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(3);
      if($observacion !=""){
        $this->_hoja->Cell(30, 5, 'Observacion:',0,0, 'L',0, 0);
        $this->_hoja->Cell(120, 0, ' '.$observacion.'',1,1, 'L',1, 0);
      }
      
      $html = '<span style="text-align:justify;"><p style="text-align: justify;">Asimismo, está de acuerdo en haber recibido la <b>' .$tipo ." ".$pc.' en perfectas condiciones</b> de funcionamiento para las actividades que le fueron encomendadas. Sabiendo que la computadora que recibe es exclusiva de trabajo, se compromete a hacer buen uso de la misma, evitando el uso inapropiado y negligente que conduzca a la pérdida de información o daño de la computadora. De la misma forma se compromete a mantenerlo en las mejores condiciones físicas, reportando cualquier anomalía o mal funcionamiento al área de TI para su correspondiente mantenimiento. Cualquier desperfecto al entregar el equipo será responsabilidad de quien firma esta carta y se procederá como lo designe el área correspondiente.</p></span>';

      // set core font
      $this->_hoja->SetFont('helvetica', '', 12);

      // output the HTML content
      $this->_hoja->writeHTML($html, true, 0, true, true);
      
      $this->_hoja->Line(80,235,130,235);
      $this->_hoja->Cell(0, 60, $nombre,0,0, 'C',0, 0);

      $this->_hoja->output('reporte.pdf', 'I');
    
      
  }

  public function dispositivo($idUsuario)
  {
    if(!$this->filterInt(base64_decode($idUsuario))){
      $this->redirect('usuarios/catalogo/');
    }
    $idUsuario = $this->filterInt(base64_decode($idUsuario));
    $this->_view->navCatalogo ="Catálogo de Usuarios";
    $this->_view->urlCatalago = BASE_URL . "usuarios" . DS . "catalogo" . DS;
    $this->_view->titulo = "Asignar Dispositivo";
    self::_consultarUsuario($idUsuario);
    self::_obtenerDispositivoDisponible($this->_view->_sucursal, $this->_view->_dep);
    if($this->getInt('asignar') == 4){
      //print_r($_POST); exit;
      $dps = $this->getPostParam('dispositivo');
      $observacion = $this->getPostParam('observacion');
      $row = self::_asignarDispositivo( $idUsuario, $dps,  $observacion, "Asignado");
      if($row != 0){
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        self::_cambiarStatusDispositivo($dps, 2); //Cambiar el estatus a asignado
        $this->_view->usuarios = self::_catalogoUsuarios();
        $this->_view->render('catalogo', 'usuarios');
        exit;
      }
      else{
        $this->_view->alerta="warning";
        $this->_view->_mensaje="<h5>Los datos no se guardaron</h5>";
        $this->_view->navegacion="Modulo Usuarios";
        $this->_view->render('dispositivo', 'usuarios');
        exit;
      }
    }
    $this->_view->render('dispositivo', 'usuarios');
  }
  private function _obtenerDispositivoDisponible($sucursal, $departamento)
  {
    
      $dispositivos = $this->_set->obtenerDispositivosSucursal($sucursal, $departamento);
      return $this->_view->dispositivos = $dispositivos;
  }
  private function _cambiarStatusDispositivo($idDispositivo, $status)
  {
    $this->_set->cambiarStatusDipositivo($idDispositivo, $status);
  }
  private function _asignarDispositivo( $idUsuario, $dps,  $obs, $status)
  {
    $row = $this->_set->asignarDispositivo( $idUsuario, $dps,  $obs, $status);
    return $row;
  }
}
