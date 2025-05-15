<?php


class TiendasController extends Controller
{
	
	private $_tiendas;

	public function __construct()
	{
		parent::__construct();
		$this->urls('tiendas');
        $this->_view->estados = self::_estados();
		$this->_tiendas = $this->loadModel('Tiendas'); 
		
	}

	public function index()
	{
		$this->_view->inicio = "Inicio";
		$this->_view->titulo = "Módulo de Tiendas";
		$this->_view->navegacion ="Tiendas";
		$this->_view->render('index', 'tiendas');

	}

	public function agregar() {
		$this->_view->setJs(array('ubicacion'));
		$this->_view->titulo = "Registro de tiendas";
		if($this->getInt('add') == 4 ) {

			print_r($_POST);
			
		}

		$this->_view->render('agregar', 'tienda');

	}

	

	public function agregar1()
	{
		
		$this->_view->setJs(array('ubicacion'));
		$this->_view->titulo = "Registro de tiendas";
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
        		$this->_view->render('agregar', 'tiendas');
        		exit;
      		}else {
        		$this->_view->alerta="danger";
        		$this->_view->_mensaje="Los datos no se guardaron";
        		$this->_view->render('agregar', 'tiendas');
        		exit;
      		}
	
		}
		  
		$this->_view->render('agregar', 'tiendas');
	}

	public function catalogo()
	{
		$this->_view->titulo = "Catálogo de tiendas";
		$this->_view->tdas = self::_obtenerTiendas();
		$this->_view->render('catalogo', 'tiendas');
	
	}
	
	private function _obtenerTiendas()
	{
		$tiendas = $this->_tiendas->obtenerTiendas();
		return $tiendas;
	}
	
	private function _registrar($datos)
	{

		$result = $this->_tiendas->registrarTienda($datos);
		return $result;
	}

	private function _sucursalId($estado, $municipio, $localidad)
	{
		$id = $this->_tiendas->obtenerIdEstado($estado, $municipio, $localidad);
		return $id;
	}

	private function _estados()
	{
		//$estados = $this->_tiendas->obtenerEstados();
		//return $estados;
	}

	public function municipio()
	{
		if($this->getInt('estado')){
			$estado = $this->getInt('estado');
			echo json_encode($this->_tiendas->obtenerMunicipios($estado));
		}
	}

	public function localidades()
	{
		if($this->getInt('municipio')){
			$municipio = $this->getInt('municipio');
			echo json_encode($this->_tiendas->obtenerLocalidades($municipio));
		}

	}

	private function _ubicacion()
	{
		$ubicacion = $this->_tiendas->obtenerMunicipios();
		return $ubicacion;

	}
}