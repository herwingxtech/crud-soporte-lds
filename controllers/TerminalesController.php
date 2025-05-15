<?php
class TerminalesController extends Controller 
{
    private $_set;
    private $_terminales;
    private $_hojaResguardo;
    private $_hoja;

    public function __construct()
    {
        parent::__construct();
        $this->_set = $this->loadModel('Set');
        $this->_view->sucursal = $this->_set->obtenerSucursal();
        $this->_terminales = $this->loadModel('Terminales');
        $this->urls('terminales');
        $this->getLibrary('/tcpdf/MyReporte');
        $this->_hoja = new MyReporte(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->_view->_ram = array('1 Gb', '2 Gb', '3 Gb', '4 Gb', '6 Gb');
        $this->_view->_hd = array('4 Gb', '8 Gb', '16 Gb', '32 Gb', '64 Gb');
        
    }

    #Método para inicar el módulo de Terminales
    public function index()
    {
        $this->_view->inicio = "Inicio";
        $this->_view->titulo = "Modulo de Terminales";
        $this->_view->render('index', 'terminales');
        
    }


    private function _urls()
    {
        $modulo = BASE_URL . "terminales" . DS;
        $this->_view->url = $modulo;
        $this->_view->urlAgregar = $modulo . "agregar" .DS;
        $this->_view->urlCatalogo =$modulo . "catalogo" .DS;
        $this->_view->urlIPS = $modulo . "controlIP" .DS;
        $this->_view->urlDetalles = $modulo . "detalles" .DS;
        $this->_view->urlActualizar = $modulo . "actualizar" .DS;
        $this->_view->urlliberarIP = $modulo. "liberarIP" .DS;
        $this->_view->urlasignarIP = $modulo. "asignarIP" .DS;
        $this->_view->urlasignarIP = $modulo. "reporte" .DS;
        $this->_view->pronavegacion = "Módulo Terminales";
    }

    public function catalogo()
    {
        $this->_view->terminales = self::_obtenerTerminales();
        $this->_view->navegacion ="Catálogo de Terminales";
        $this->_view->titulo = "Catálogo de Terminales";
        $this->_view->render('catalogo', 'terminales');
    }

    public function agregar()
    {

        $this->_acl->controlAccess('root_access');
        $this->_view->navegacion = "Agregar terminales";
        $this->_view->titulo ="Agregar Terminales";

        if($this->getInt('registrar') == 4){
          $this->_view->datos= $_POST;
          #Valida que el número de serie no está en el sistema
          if($this->_terminales->validaSerie($this->getText('serie'))!=0){
            $this->_view->alerta = "danger";
            $this->_view->_mensaje = "Está computadora ya está registrada con el número de serie <strong>" . $_POST['serie'] . "</strong>";
            $this->_view->serieTerminal=1;
            $this->_view->render('agregar','terminales');
            exit;
          }
          $sucursal=null;
          $status = (int)1;

          #arreglo de datos que se enviarán al modelo
          $datos = array(
            "nombre"      => $this->getPostParam('nombre'),
            "usuario"     => $this->getPostParam('usuario'),
            "marca"       => $this->getPostParam('marca'),
            "modelo"      => $this->getPostParam('modelo'),
            "serie"       => $this->getPostParam('serie'),
            "procesador"  => $this->getPostParam('procesador'),
            "ram"         => $this->getPostParam('ram'),
            "so"          => $this->getPostParam('so'),
            "hd"          => $this->getPostParam('hd'),
            "departamento"=> $this->getPostParam('departamento'),
            "fechaIngreso"=> $this->formatoFecha($this->getPostParam('fechaIngreso'),3),
            "sucursal"    => $this->getPostParam('sucursal'),
            "descripcion" => $this->getPostParam('descripcion'),
            "status"      => $status
          );
          #enviado datos al método agregarComputadora para insertarlos a la Base de Datos
          $row = self::_agregarTerminal($datos);
          if($row !=0) {
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
          }

        }
        $this->_view->render('agregar', 'computadoras');
    }

    private function _agregarTerminal($datos)
    {
      $stm = $this->_terminales->agregarTerminal($datos);
      return $stm;

    }

    private function _obtenerTerminales()
    {

      $terminales = $this->_terminales->getTerminales();
      return $terminales;
        
    }

  public function resguardo()
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
    $this->_hoja->SetAuthor('Pablo Manga Pérez');
    $this->_hoja->SetTitle($nombre);
    $this->_hoja->SetSubject('Hoja de Resguardo');
    $this->_hoja->SetKeywords('Línea', 'Digital', 'Corporativo', 'Telcel');
    $this->_hoja->addPage();
    $this->_hoja->Ln(30);

    $nombre="Jaime Daniel Samayoa Pinacho";
    $dispotivo="Terminal";
    $depa="Desarrollo de Asociados";
    $texto =
        '<span style="text-align:justify;"><p style="text-align:justify;">Por medio de la presente</span> el(a) <b>C. '. $nombre.'</b> Quien labora para esta empresa en el área de <b>'.$depa.'</b> recibe bajo resguardo un(a) <b> '.$dispotivo.'</b> con las siguientes características:</p></span>';
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
      $marca = " 	Shenzhen NUOYOUXUE Technology";
      $modelo = "Smart POS NB55";
      $serie = "NB552022060304";
      $procesador = "Deca-core MT6797";
      $sucursal = $depa. ", Frontera Comalapa";
      $ram = "3 Gb";
      $flash="16 Gb";
      $os = "Android 8.0";
      $accesorios="Cable AC/DC";

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

      $this->_hoja->Cell(30, 4, 'Procesador:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$procesador.'',1,1, 'L',1, 0);
      $this->_hoja->Ln(); 

      $this->_hoja->Cell(30, 4, 'RAM:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$ram.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();

      $this->_hoja->Cell(30, 4, 'Flash:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$flash.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();

      $this->_hoja->Cell(30, 4, 'SO:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$os.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      $this->_hoja->Cell(30, 4, 'Ubicación:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$sucursal.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      $this->_hoja->Cell(30, 4, 'Accesorios:',0,0, 'L',0, 0);
      $this->_hoja->Cell(120, 0, ' '.$accesorios.'',1,1, 'L',1, 0);
      $this->_hoja->Ln();
      
      $html = '<span style="text-align:justify;"><p style="text-align: justify;">Asimismo, está de acuerdo en haber recibido la <b>Terminal Android nueva en perfectas condiciones</b> de funcionamiento para las actividades que le fueron encomendadas. Sabiendo que la terminal que recibe es exclusiva de trabajo, se compromete a hacer buen uso de la misma, evitando el uso inapropiado y negligente que conduzca a la pérdida de información o daño de la terminal. De la misma forma se compromete a mantenerlo en las mejores condiciones físicas, reportando cualquier anomalía o mal funcionamiento al área de TI para su correspondiente mantenimiento. Cualquier desperfecto al entregar el equipo será responsabilidad de quien firma esta carta y se procederá como lo designe el área correspondiente.</p></span>';
     
     // $html = '<span style="text-align:justify;"><p style="text-align: justify;">Asimismo, está de acuerdo en haber recibido el <b>monitor nuevo en perfectas condiciones</b> de funcionamiento para las actividades que le fueron encomendadas. Sabiendo que el monitor que recibe es exclusivo de trabajo, se compromete a hacer buen uso del mismo, evitando el uso inapropiado y negligente que conduzca a la pérdida de información o daño del monitor. De la misma forma se compromete a mantenerlo en las mejores condiciones físicas, reportando cualquier anomalía o mal funcionamiento al área de TI para su correspondiente mantenimiento. Cualquier desperfecto al entregar el equipo será responsabilidad de quien firma esta carta y se procederá como lo designe el área correspondiente.</p></span>';

      // set core font
      $this->_hoja->SetFont('helvetica', '', 12);

      // output the HTML content
      $this->_hoja->writeHTML($html, true, 0, true, true);

      $this->_hoja->Line(80,250,130,250);
      $this->_hoja->Cell(0, 35, $nombre,0,0, 'C',0, 0);

      $this->_hoja->output('reporte.pdf', 'I');
    
      
  }             
  public function machote()
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
    $this->_hoja->SetAuthor('Pablo Manga Pérez');
    $this->_hoja->SetTitle($nombre);
    $this->_hoja->SetSubject('Hoja de Resguardo');
    $this->_hoja->SetKeywords('Línea', 'Digital', 'Corporativo', 'Telcel');
    $this->_hoja->addPage();
    $this->_hoja->Ln(30);

    $nombre="_________________________________________";
    $dispotivo="Centro de cómputo";
    $depa="_______________________";
    $texto =
        '<span style="text-align:justify;"><p style="text-align:justify;">Por medio de la presente</span> el(a) <b>C. '. $nombre.'</b> Quien labora para esta empresa en el área de <b>'.$depa.'</b> recibe bajo resguardo un(a) <b> '.$dispotivo.'</b> con las siguientes características:</p></span>';
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
      $compu = "";
      $modelo = "";
      $serie = "";
      $procesador = "";
      $sucursal =  "";
      $ram = "";
      $flash="";
      $os = "";
      $accesorios="Cable AC/DC";

     // $cMarca = strlen($marca)+3;
     $yy = 10; //Variable auxiliar para desplazarse 40 puntos del borde superior hacia abajo en la coordenada de las Y para evitar que el título este al nivel de la cabecera.
     $y = $this->_hoja->GetY(); 
     $x = 12;
     $this->_hoja->SetXY(0, $y + $yy); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
      
     $y = $this->_hoja->GetY(); 
     $this->_hoja->SetXY(0, $y); //Ubicación según coordenadas X, Y. X=0 porque empezará desde el borde izquierdo de la página
     $this->_hoja->Cell(220, 10, "Lista de Dispositivos", 0, 1, 'C');
      
     $y = $this->_hoja->GetY() + 8;
     $this->_hoja->SetXY(10, $y);
     $this->_hoja->MultiCell(12, 4, utf8_decode("Nº"), 1, 'C'); //Utilizamos el utf8_decode para evitar código basura o ilegible
     $this->_hoja->SetXY(22, $y); //El resultado 22 es la suma de la posición 10 y el tamaño del MultiCell de 12.
     $this->_hoja->MultiCell(73, 4, utf8_decode("Tipo Dispositivo"), 1, 'C');
     $this->_hoja->SetXY(95, $y);
     $this->_hoja->MultiCell(40, 4, utf8_decode("Marca"), 1, 'C');
     $this->_hoja->SetXY(135, $y);
     $this->_hoja->MultiCell(35, 4, utf8_decode("Modelo"), 1, 'C');
     $this->_hoja->SetXY(170, $y);
     $this->_hoja->MultiCell(30, 4, utf8_decode("Serie"), 1, 'C');        
     $n = 1;

     while($n <= 8) {            
         $y = $this->_hoja->GetY();
         $this->_hoja->SetXY(10, $y);
         $this->_hoja->MultiCell(12, 4, $n, 1, 'C');
         $this->_hoja->SetXY(22, $y);
         $this->_hoja->MultiCell(73, 4, "", 1, 'C');
         $this->_hoja->SetXY(95, $y);
         $this->_hoja->MultiCell(40, 4, "", 1, 'C');
         $this->_hoja->SetXY(135, $y);
         $this->_hoja->MultiCell(35, 4, "", 1, 'C');
         $this->_hoja->SetXY(170, $y);
         $this->_hoja->MultiCell(30, 4, "", 1, 'C');
         $n++;            
     }
      


      
      $html = '<span style="text-align:justify;"><p style="text-align: justify;">Debido a la contingencia generada por la enfermedad <b>COVID-19</b>, el empleado cuyo nombre aparece en este documento se llevará los dispositivos listados anteriormente a su domicilio para poder realizar sus labores. Sabiendo que los dispositivos que recibe son para uso exclusivo de trabajo, se compromete a hacer buen uso de los mismos, evitando el uso inapropiado y negligente que conduzca a la pérdida de información o daño. De la misma forma se compromete a mantenerlos en las mejores condiciones físicas. Cualquier desperfecto al entregar el equipo será responsabilidad de quien firma esta carta y se procederá como lo designe el área correspondiente.</p></span>';
     
     // $html = '<span style="text-align:justify;"><p style="text-align: justify;">Asimismo, está de acuerdo en haber recibido el <b>monitor nuevo en perfectas condiciones</b> de funcionamiento para las actividades que le fueron encomendadas. Sabiendo que el monitor que recibe es exclusivo de trabajo, se compromete a hacer buen uso del mismo, evitando el uso inapropiado y negligente que conduzca a la pérdida de información o daño del monitor. De la misma forma se compromete a mantenerlo en las mejores condiciones físicas, reportando cualquier anomalía o mal funcionamiento al área de TI para su correspondiente mantenimiento. Cualquier desperfecto al entregar el equipo será responsabilidad de quien firma esta carta y se procederá como lo designe el área correspondiente.</p></span>';

      // set core font
      $this->_hoja->SetFont('helvetica', '', 12);

      // output the HTML content
      $this->_hoja->writeHTML($html, true, 0, true, true);

      $this->_hoja->Line(80,230,130,230);
      $this->_hoja->Cell(0, 50, 'Firma',0,0, 'C',0, 0);

      $this->_hoja->output('reporte.pdf', 'I');
    
      
  }                             

}