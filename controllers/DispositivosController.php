<?php
class DispositivosController extends Controller
{
  private $_dispositivos;
  private $_set;
  public function __construct()
  {
    parent::__construct();
    //$this->_view->url = BASE_URL . "dispositivos" . DS;
    //$this->_view->urlCatalago = BASE_URL . "dispositivos" . DS . "catalogo" .DS;
    //$this->_view->urlAgregar = BASE_URL . "dispositivos" . DS . "agregar" .DS;
    $this->urls('dispositivos');  
    //$this->_view->pronavegacion = "Módulo de dispositivos";
    $this->_set = $this->loadModel('Set');
    $this->_dispositivos = $this->loadModel('Dispositivos');
    $this->_view->sucursal = $this->_set->obtenerSucursal();
    $this->_view->_tipo = array(
      "Accesorios",
      "Almacenamiento",
      "Energía",
      "Impresoras",
      "Monitores y Pantalla",
      "Redes y Conectividad"
    );

    $this->getLibrary('/tcpdf/MyReporte');
    $this->_hoja = new MyReporte(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  }
  public function index()
  {
    $arreglo = array(43,21,2,1,9,24,2,99,23,8,7,114,92,5);
    $this->_view->_ordenado = self::quicksort($arreglo);
    //echo $arreglo; exit;
    $this->_view->inicio = "Inicio";
    //  $this->_view->navegacion ="dispositivos";
    $this->_view->titulo ="Módulo dispositivos";
    $this->_view->render('index', 'dispositivos');

  }
  public function catalogo()
  {
    $this->_view->titulo = "Catálogo de dispositivos";
    $this->_view->dps = self::_catalogo();
    $this->_view->render('catalogo','dispositivos');
  }

  private function _catalogo()
  {
    $catalogo = $this->_dispositivos->obtenerCatalogo();
    return $catalogo;
  }

  public function agregar()
  {
    $this->_acl->controlAccess('root_access');
    $this->_view->titulo = "Agregar dispositivos";
    $this->_view->navegacion = "Agregar dispositivos";

    if($this->getInt('registrar')==4){
      $this->_view->datos = $_POST;
      #Valida que el número de serie no está en el sistema
      if($this->_dispositivos->validaSerie($this->getText('serie'))!=0){
        $this->_view->alerta = "danger";
        $this->_view->_mensaje = "Este dispositivo ya está registrado con el número de serie <strong>" . $_POST['serie'] . "</strong>";
        $this->_view->render('agregar','dispositivos');
        exit;
      }
      $datos = array(
        'nombre' => $this->getPostParam('nombre'),
        'marca' => $this->getPostParam('marca'),
        'modelo' => $this->getPostParam('modelo'),
        'serie' => $this->getPostParam('serie'),
        'tipo' => $this->getPostParam('tipo'),
        'departamento' => $this->getPostParam('departamento'),
        'descripcion' => $this->getPostParam('descripcion'),
        'fechaIngreso' => $this->formatoFecha($this->getPostParam('fechaIngreso'), 3),
        'sucursal' => $this->getPostParam('sucursal'),
        'status' => "Activo"
      );
      $row = self::_agregarDispostivos($datos);
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
    $this->_view->render('agregar', 'dispositivos');
  }

  private function _agregarDispostivos($datos)
  {
    $row = $this->_dispositivos->agregarDispositivo($datos);
    return $row;
  }

  public function detalles($idDispositivo){
    if(!$this->filterInt(base64_decode($idDispositivo))){
      $this->redirect('dispositivos/catalogo/');
    }
    $idDispositivo = $this->filterInt(base64_decode($idDispositivo));
    $this->_view->titulo = "Información detallada";
    $this->_view->navCatalogo ="Catálogo de dispositivos";

    $this->_view->navegacion="Detalle dispositivo";
    self::_consultarDispositivo($idDispositivo);
    $this->_view->render('detalles', 'dispositivos');
  }

  private function _consultarDispositivo($idDispositivo)
  {
      
    $dispositivo = $this->_dispositivos->obtenerDispositivo($idDispositivo);
    $this->_view->_nombre = $dispositivo['nomDispositivo'];
    $this->_view->_marca= $dispositivo['marcaDispositivo'];
    $this->_view->_modelo = $dispositivo['modeloDispositivo'];
    $this->_view->_tipoDp= $dispositivo['tipoDispositivo'];
    $this->_view->_serie = $dispositivo['serieDispositivo'];
    $this->_view->_dep = $dispositivo['depDispositivo'];
    $this->_view->_nomSucursal = $dispositivo['nombreSucursal'];
    $this->_view->_fechaIngreso = $this->formatoFecha($dispositivo['fechaIngresoDispositivo'],4);
    $this->_view->_idSucursal = $dispositivo['idSucursal'];
    $this->_view->_caracteristicas = $dispositivo['detalleDispositivo'];

  }

  #Método para actualizar dispositivo del catálogo
  public function actualizar($idDispositivo)
  {
    if(!$this->filterInt(base64_decode($idDispositivo))){
      $this->redirect('dispositivos/catalogo/');
    }
    $idDispositivo = $this->filterInt(base64_decode($idDispositivo));
    $this->_view->titulo = "Actualizar Dispositivo";
    $this->_view->navCatalogo ="Catálogo de Dispositivos";
    $this->_view->urlCatalago = BASE_URL . "dispositivos" . DS . "catalogo" . DS;
    $this->_view->navegacion="actualizar dispositivo";
    if(($this->getInt('actualizar') == 4)){

      #arreglo de datos que se enviarán al modelo
      $datos = array(
          'nombre' => $this->getPostParam('nombre'),
          'marca' => $this->getPostParam('marca'),
          'modelo' => $this->getPostParam('modelo'),
          'serie' => $this->getPostParam('serie'),
          'tipo' => $this->getPostParam('tipo'),
          'departamento' => $this->getPostParam('departamento'),
          'descripcion' => $this->getPostParam('descripcion'),
          'fechaIngreso' => $this->formatoFecha($this->getPostParam('fechaIngreso'), 3),
          'sucursal' => $this->getPostParam('sucursal'),
          'status' => "Activo",
          "idDispositivo"          => $idDispositivo

      );

      #enviado datos al método actualizarComputadora para actualizar  la Base de Datos
      $row = self::_actualizarDispositivo($datos);
      if($row !=0) {
        unset($this->_view->datos);
        $this->_view->alerta="success";
        $this->_view->_mensaje="Los datos se grabaron correctamente";
        $this->_view->dps =self::_catalogo();
        $this->_view->render('catalogo', 'dispositivos');
        exit;
      }else {
        self::_consultarDispositivo($idDispositivo);
        $this->_view->alerta="danger";
        $this->_view->_mensaje="Los datos no se guardaron";
        $this->_view->render('actualizar', 'dispositivos');
        exit;
      }
    }
    self::_consultarDispositivo($idDispositivo);
    $this->_view->render('actualizar', 'dispositivos');

  }

  #Método privado para enviar datos a actualizar al modelo
  private function _actualizarDispositivo($datos)
  {
    $dps = $this->_dispositivos->actualizarDispositivo($datos);
    return $dps;

  }
  public function quicksort($desordenado)
  {
      $tamaño = count($desordenado);
      //echo $tamaño;
      if($tamaño <= 1){
        return $desordenado;
      }else {
        $pivote = $desordenado[0];
        $izqueirda = $deracha = array();

        for($i = 1; $i< count($desordenado); $i++){
          if($desordenado[$i] < $pivote){
            $izqueirda[] = $desordenado[$i];
          }else {
            $deracha[] = $desordenado[$i];
          }
        }
        return array_merge(self::quicksort($izqueirda), array($pivote), self::quicksort($deracha));
      }
  }

  public function hoja() {

    $this->_hoja->SetAuthor('Pablo Manga Pérez');
    $this->_hoja->SetTitle($nombre=null);
    $this->_hoja->SetSubject('Hoja de Resguardo');
    $this->_hoja->SetKeywords('Línea', 'Digital', 'Corporativo', 'Telcel');
    $this->_hoja->addPage();
    $this->_hoja->Ln(30);
    $nombre="Itzel Stephania Hernández García";
    $area="Tiendas Propias";
    $texto =
        '<span style="text-align:justify;"><p style="text-align:justify;">Por medio de la presente</span> el(a) <b>C. '. $nombre.'</b> 
         Quien labora para esta empresa en el área de <b>'.$area.'</b> recibe bajo resguardo una <b> Impresora Laser Multifuncional nueva</b> con las siguientes características:</p></span>';
      // set core font
      $this->_hoja->SetFont('helvetica', '', 12);
      $this->_hoja->writeHTML($texto, true, 0, true, true);
      //$this->_hoja->writeHTML('Marca: '.$relacion['marcaComputadora'].'.')
      
      $this->_hoja->SetFont('helvetica', 'B', 12);
      $this->_hoja->SetLineStyle(array('width' => 0.1, 'cap' => 'squared', 'join' => 'miter', 'dash' => 4, 'color' => array(0, 0, 0)));
      $this->_hoja->SetFillColor(255,255,255);
      $this->_hoja->SetTextColor(0,0,0);
      /*$marca = $relacion["marcaComputadora"];
      $modelo = $relacion["modeloComputadora"];
      $serie = $relacion["serieComputadora"];
      $procesador = $relacion["Procesador"];
      $ram = $relacion["RAM"];
      $hd = $relacion["HD"];
      $OS = $relacion["OS"];
      $sucursal = $relacion['depComputadora']. ", ". $relacion["nombreSucursal"];
      */
      $marca = "HP";
      $modelo = "LaserJet PROm MFP M28w";
      $serie = "VNB3J17681";
      //$cargador = "Adapator de CA/DC";
      //$serie_c = "6600149701LP";
      $sucursal = "Tienda Humberto, Tuxtla Gutiérrez";
      $accesorios="Cable de datos USB";

     // $cMarca = strlen($marca)+3;
      
      $this->_hoja->Cell(30, 4, 'Marca:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$marca.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $this->_hoja->Cell(30, 4, 'Modelo:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$modelo.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $this->_hoja->Cell(30, 4, 'Serie:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$serie.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      
      $this->_hoja->Ln();
      $this->_hoja->Cell(30, 4, 'Ubicación:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$sucursal.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      $this->_hoja->Cell(30, 4, 'Cable:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$accesorios.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $html = '<span style="text-align:justify;"><p style="text-align: justify;">Asimismo, está de acuerdo en haber recibido el <b>
      Impresora Laser Multifuncional nueva en perfectas condiciones</b> de funcionamiento para las actividades que le fueron encomendadas. 
      Sabiendo que el <b>
      Impresora Laser Multifuncional nueva </b> que recibe es exclusiva de trabajo, 
      se compromete a hacer buen uso de la misma, evitando el uso inapropiado y negligente que conduzca a la pérdida de información o daño del mismo. 
      De la misma forma se compromete a mantenerlo en las mejores condiciones físicas, reportando cualquier anomalía o mal funcionamiento al área de TI para su correspondiente 
      mantenimiento. 
      Cualquier desperfecto al entregar el equipo será responsabilidad de quien firma esta carta y se procederá como lo designe el área correspondiente.</p></span>';

      // set core font
      $this->_hoja->SetFont('helvetica', '', 12);

      // output the HTML content
      $this->_hoja->writeHTML($html, true, 0, true, true);

      $this->_hoja->Line(80,234,130,234);
      $this->_hoja->Cell(0, 76, $nombre,0,0, 'C',0, 0);

      $this->_hoja->output('reporte.pdf', 'I');
  }
  public function resguardo($dispositivo)
  {
    $this->_acl->controlAccess('root_access');
    /*if(!$this->filterInt(base64_decode($pc))){
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
   */
      //$logo = ROOT.'libs/images/logolinea.png';
    //$this->_hoja->Image($logo, 10, 15, ''. 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);


    if(!$this->filterInt(base64_decode($dispositivo))){
      $this->redirect('dispositivos/catalogo/');
    }
    /*
    $pc=$this->filterInt(base64_decode($pc));
    $relacion = self::_relacionPC($pc);
    if($relacion['tipoComputadora'] == "Laptop"){
        $tipo = "Computadora Portátil";
    }else{
        $tipo = "Computadora de Escritorio";
    }*/
    $nombre = $relacion['nomUsuario'] ." ".$relacion['apellidosUsuario'] ;
    $this->_hoja->SetAuthor('Pablo Manga Pérez');
    $this->_hoja->SetTitle($nombre=null);
    $this->_hoja->SetSubject('Hoja de Resguardo');
    $this->_hoja->SetKeywords('Línea', 'Digital', 'Corporativo', 'Telcel');
    $this->_hoja->addPage();
    $this->_hoja->Ln(30);

    $nombre="Adriana González López";
    $area="Tiendas Propias";
    $texto =
        '<span style="text-align:justify;"><p style="text-align:justify;">Por medio de la presente</span> el(a) <b>C. '. $nombre.'</b> 
         Quien labora para esta empresa en el área de <b>'.$area.'</b> recibe bajo resguardo un <b> NOBREAK/UPS NUEVO</b> con las siguientes características:</p></span>';
      // set core font
      $this->_hoja->SetFont('helvetica', '', 12);
      $this->_hoja->writeHTML($texto, true, 0, true, true);
      //$this->_hoja->writeHTML('Marca: '.$relacion['marcaComputadora'].'.')
      
      $this->_hoja->SetFont('helvetica', 'B', 12);
      $this->_hoja->SetLineStyle(array('width' => 0.1, 'cap' => 'squared', 'join' => 'miter', 'dash' => 4, 'color' => array(0, 0, 0)));
      $this->_hoja->SetFillColor(255,255,255);
      $this->_hoja->SetTextColor(0,0,0);
      /*$marca = $relacion["marcaComputadora"];
      $modelo = $relacion["modeloComputadora"];
      $serie = $relacion["serieComputadora"];
      $procesador = $relacion["Procesador"];
      $ram = $relacion["RAM"];
      $hd = $relacion["HD"];
      $OS = $relacion["OS"];
      $sucursal = $relacion['depComputadora']. ", ". $relacion["nombreSucursal"];
      */
      $marca = "CyberPower";
      $modelo = "UT750G";
      $serie = "3202509Z30001379";
      $sucursal = $area . ", Tienda Reloj Huixtla";
      $accesorios="Cable de datos USB";

     // $cMarca = strlen($marca)+3;
      
      $this->_hoja->Cell(30, 4, 'Marca:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$marca.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $this->_hoja->Cell(30, 4, 'Modelo:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$modelo.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $this->_hoja->Cell(30, 4, 'Serie:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$serie.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      $this->_hoja->Cell(30, 4, 'Ubicación:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$sucursal.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      $this->_hoja->Ln();
      
      $html = '<span style="text-align:justify;"><p style="text-align: justify;">Asimismo, está de acuerdo en haber recibido el <b>
      NOBREAK/UPS NUEVO en perfectas condiciones</b> de funcionamiento para las actividades que le fueron encomendadas. 
      Sabiendo que el <b>
      NOBREAK/UPS NUEVO </b> que recibe es exclusiva de trabajo, 
      se compromete a hacer buen uso de la misma, evitando el uso inapropiado y negligente que conduzca a la pérdida de información o daño del mismo. 
      De la misma forma se compromete a mantenerlo en las mejores condiciones físicas, reportando cualquier anomalía o mal funcionamiento al área de TI para su correspondiente 
      mantenimiento. 
      Cualquier desperfecto al entregar el equipo será responsabilidad de quien firma esta carta y se procederá como lo designe el área correspondiente.</p></span>';

      // set core font
      $this->_hoja->SetFont('helvetica', '', 12);

      // output the HTML content
      $this->_hoja->writeHTML($html, true, 0, true, true);

      $this->_hoja->Line(80,230,130,230);
      $this->_hoja->Cell(0, 87, $nombre,0,0, 'C',0, 0);

      $this->_hoja->output('reporte.pdf', 'I');
    
      
  }                     


}
