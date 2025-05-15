<?php


class PuntosController extends Controller
{
	
	private $_puntos;
	

	public function __construct()
	{
		parent::__construct();
		$this->urls('puntoventa');
		$this->_puntos = $this->loadModel('Puntos'); 
		
	}

	public function index()
	{
		$this->_view->setJs(array('ubicacion'));
		$this->_view->inicio = "Inicio";
		$this->_view->titulo = "Módulo de Punto Venta";
		$this->_view->navegacion ="puntoventa";
		$this->_view->estados = self::_estados();
		
		if($this->getInt('registrar')==4) {
			$this->_view->datos = $_POST;
			if($this->getPostParam('ciudad') == ''){
				$ciudad = 0;
			}/*
			if($this->_puntos->validarApiKey($this->getPostParam("apikey"))){
				$this->_view->alerta = "danger";
				$this->_view->_mensaje = "El nombre <strong>". $_POST['apikey']."</strong> ya está en uso";
			  //  $this->_view->nomPC=1;//para validar el nombre de la pc
				$this->_view->render('index','puntos');
				exit;
			}*/
			
			$datos = array(
				'apikey' => $this->getPostParam('apikey'),
				'operacion' => $this->getPostParam('operacion'),
				'identificador' => $this->getPostParam('identificador'),
				'nombreTienda' => $this->getPostParam('nomTienda'),
				'comercio' => $this->getPostParam('comercio'),
				'tipoCalle' => $this->getPostParam('tipoCalle'),
				'calle' => $this->getPostParam('calle'),
				'numeroExterior' => $this->getPostParam('numExterior'),
				'numeroInterior' => $this->getPostParam('numInterior'),
				'detalleDireccion' => $this->getPostParam('detalleDireccion'),
				'colonia' => $this->getPostParam('colonia'),
				'estado' => $this->getPostParam('estado'),
				'municipio' => $this->getPostParam('municipios'),
				'localidades' => $this->getPostParam('localidades'),
				'ciudad' => $ciudad,
				'cp' => $this->getPostParam('cp'),
				'iva' => $this->getPostParam('iva'),
				'subdivision' => $this->getPostParam('subdivision'),
				'entreCalle' => $this->getPostParam('entreCalle'),
				'yCalle' => $this->getPostParam('yCalle'),
				'longitud' => $this->getPostParam('longitud'),
				'latitud' => $this->getPostParam('latitud'),
				'altitud' => $this->getPostParam('altitud'),
				'inicioLabor' => $this->getPostParam('inicioLabor'),
				'finLabor' => $this->getPostParam('finLabor'),
				'horaInicioLaboral' => $this->getPostParam('horaInicioLaboral'),
				'horaFinLaboral' => $this->getPostParam('horaFinLaboral'),
				'status' => 1,
				'fecha' => $this->formatoFecha($this->getPostParam('fecha'),3),
			  );
			   self::_agregar($datos);
			 
		}
		$this->_view->render('index', 'puntos');

	}


		

	private function _agregar($datos){
	
		$stmt=$this->_puntos->registrarPuntoVenta($datos);
		return $stmt;
	}
	public function agregar()
	{
		
		$this->_view->setJs(array('ubicacion'));
		$this->_view->titulo = "Registro de puntos";
		$this->_view->estados = self::_estados();
		if($this->getInt('registrar')==4) {
			$this->_view->datos= $_POST;
			//print_r($_POST); exit;
			$municipio = $this->getInt('municipios');
			$estado  = $this->getInt('estado');
			$localidad = $this->getPostParam('localidades');
			$id = self::_sucursalId($estado, $municipio, $localidad);
			$datos = array(
				'nombre' => $this->getPostParam('nombre'),
				'dir' => $this->getPostParam('direccion'),
				'numExt' => $this->getPostParam('numExterior'),
				'telefono'=> $this->getPostParam('telefono'),
				'idUbicacion' => $id['id'],
				'fechaRegistro' => $this->formatoFecha($this->getPostParam('fechaIngreso'), 3),
                'status' => "Activo"

			);

			$row = self::_registrar($datos);
			if($row!=0){
				unset($this->_view->datos);
				$this->_view->alerta="success";
       			$this->_view->_mensaje="Los datos se grabaron correctamente";
        		$this->_view->render('agregar', 'puntos');
        		exit;
      		}else {
        		$this->_view->alerta="danger";
        		$this->_view->_mensaje="Los datos no se guardaron";
        		$this->_view->render('agregar', 'puntos');
        		exit;
      		}
	
		}
		  
		$this->_view->render('agregar', 'puntos');
	}

	public function catalogo()
	{
		$this->_view->titulo = "Catálogo de puntos";
		$this->_view->tdas = self::_obtenerpuntos();
		$this->_view->render('catalogo', 'puntos');
	
	}
	
	private function _obtenerpuntos()
	{
		$puntos = $this->_puntos->obtenerpuntos();
		return $puntos;
	}
	
	private function _registrar($datos)
	{

		$result = $this->_puntos->registrarTienda($datos);
		return $result;
	}

	private function _sucursalId($estado, $municipio, $localidad)
	{
		$id = $this->_puntos->obtenerIdEstado($estado, $municipio, $localidad);
		return $id;
	}

	private function _estados()
	{
		$estados = $this->_puntos->obtenerEstados();
		return $estados;
	}

	public function municipio()
	{

		if($this->getPostParam('estado')){
			$estado = $this->getPostParam('estado');
			echo json_encode($this->_puntos->obtenerMunicipios($estado));
		}
	}

	public function localidades()
	{
		
		if($this->getPostParam('municipio')){
			$municipio = $this->getPostParam('municipio');
			$estado = $this->getPostParam('estado');
			echo json_encode($this->_puntos->obtenerLocalidades($municipio, $estado));
		}

	}
	public function cp()
	{
		
		if($this->getPostParam('id_asenta')){
			$id_asenta = $this->getPostParam('id_asenta');
			$municipio = $this->getPostParam('municipio');
			$estado = $this->getPostParam('estado');
			echo json_encode($this->_puntos->obtenerCP($id_asenta, $municipio, $estado));
		}

	}


	private function _ubicacion()
	{
		$ubicacion = $this->_puntos->obtenerMunicipios();
		return $ubicacion;

	}
}