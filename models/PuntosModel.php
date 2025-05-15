<?php
/**
* 
*/
class PuntosModel extends Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function obtenerTiendas()
  {
    $tiendas = $this->_db->query(
      "SELECT * FROM vista_tienda"
    );
    return $tiendas->fetchAll();
    $tiendas->null;
  }

  public function obtenerEstados()
  {

    $estados = $this->_db->query(
      '
        SELECT distinct d_estado, c_estado FROM CodigosPostales
      '
    );
  	return $estados->fetchAll();
    $estados=null;
  }

  public function registrarPuntoVenta($datos)
  {
  //print_r($datos); exit;
  	/*
				 [apikey] => 12345678901234567890123456789012
    [operacion] => 1
    [identificador] => 12345
    [nomTienda] => sadasd
    [comercio] => asdasd
    [tipoCalle] => 03
    [calle] => asdad
    [numExterior] => 34
    [numInterior] => 44
    [detalleDireccion] => sada
    [colonia] => 0004
    [estado] => 07
    [municipios] => 012
    [localidades] => 0004
    [ciudad] => 0
    [cp] => 29130
    [iva] => 15
    [subdivision] => subdia
    [entreCalle] => asd
    [yCalle] => asdad
    [longitud] => 1234
    [latitud] => 1232
    [altitud] => 123123
    [inicioLabor] => 1
    [finLabor] => 5
    [horaInicioLaboral] => 2
    [horaFinLaboral] => 9
    [status] => 1
    [fecha] => 2021-11-26
			*/
   
  	$punto  = $this->_db->prepare(
  		"
  		INSERT INTO 
  			estructura
            (
              apikey, fecha, identificador, nombreTienda,comercio, tipoCalle,
              calle, numeroExterior,numeroInterior,detalleDireccion,colonia,
              municipio,codigoPostal,ciudad,estado,iva,subdivision,entreCalle,
              yCalle,longitud,latitud,altitud,inicioLabores,finLabores,horaInicio,
              horafin, status,operacion
            )
  		VALUES 
            ( 
              :apikey, :fecha,:identificador,:nombreTienda,:comercio,:tipoCalle,
              :calle,:numeroExterior,:numeroInterior,:detalleDireccion,:colonia,
              :municipio,:cp,:ciudad,:estado,:iva,:subdivision,
              :entreCalle,:yCalle,:longitud,:latitud, :altitud, :inicioLabor,
              :finLabor,:horaInicioLaboral,:horaFinLaboral,:status, :operacion
               )
  		"
  	);
    $punto->bindParam(":apikey", $datos["apikey"], PDO::PARAM_STR);
    $punto->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
    $punto->bindParam(":identificador", $datos["identificador"], PDO::PARAM_STR);
    $punto->bindParam(":nombreTienda", $datos["nombreTienda"], PDO::PARAM_STR);
    $punto->bindParam(":comercio", $datos["comercio"], PDO::PARAM_STR);
    $punto->bindParam(":tipoCalle", $datos["tipoCalle"], PDO::PARAM_STR);
    $punto->bindParam(":calle", $datos["calle"], PDO::PARAM_STR);
    $punto->bindParam(":numeroExterior", $datos["numeroExterior"], PDO::PARAM_STR);
    $punto->bindParam(":numeroInterior", $datos["numeroInterior"], PDO::PARAM_STR);
    $punto->bindParam(":detalleDireccion", $datos["detalleDireccion"], PDO::PARAM_STR);
    $punto->bindParam(":colonia", $datos["colonia"], PDO::PARAM_STR);
    $punto->bindParam(":municipio", $datos["municipio"], PDO::PARAM_STR);
    $punto->bindParam(":cp", $datos["cp"], PDO::PARAM_STR);
    $punto->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
    $punto->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
    $punto->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
    $punto->bindParam(":subdivision", $datos["subdivision"], PDO::PARAM_STR);
    $punto->bindParam(":entreCalle", $datos["entreCalle"], PDO::PARAM_STR);
    $punto->bindParam(":yCalle", $datos["yCalle"], PDO::PARAM_STR);
    $punto->bindParam(":longitud", $datos["longitud"], PDO::PARAM_STR);
    $punto->bindParam(":latitud", $datos["latitud"], PDO::PARAM_STR);
    $punto->bindParam(":altitud", $datos["altitud"], PDO::PARAM_STR);
    $punto->bindParam(":inicioLabor", $datos["inicioLabor"], PDO::PARAM_STR);
    $punto->bindParam(":finLabor", $datos["finLabor"], PDO::PARAM_STR);
    $punto->bindParam(":horaInicioLaboral", $datos["horaInicioLaboral"], PDO::PARAM_STR);
    $punto->bindParam(":horaFinLaboral", $datos["horaFinLaboral"], PDO::PARAM_STR);
    $punto->bindParam(":status", $datos["status"], PDO::PARAM_STR);
    $punto->bindParam(":operacion", $datos["operacion"], PDO::PARAM_INT);
    //print_r($punto); exit;
    if($punto->execute()){
      return "ok";
    }else{
      return "error";
    }
  	$punto->close();
  	$punto=null;
  }

  #Método para consultar la sucursal
  public function obtenerMunicipios($estado)
  {
  	$municipios = $this->_db->prepare('
  		SELECT 
  			DISTINCT c_mnpio, D_mnpio
  		FROM 
  			CodigosPostales
  		WHERE 
  			c_estado = :estado');
    $municipios->bindParam(":estado", $estado, PDO::PARAM_INT);
    $municipios->execute();
  	return $municipios->fetchAll();
    $municipios->close();
    $municipios=null;
  }

  public function obtenerIdEstado($estado, $municipio, $localidad){
      

  	$id = $this->_db->prepare('
  			SELECT 
  				*
			FROM
				EstadosMX
			WHERE 
				idEstado = :estado && idMunicipio=:municipio && asentamiento=:localidad 

  		');
    $id->bindParam(":estado", $estado, PDO::PARAM_INT);
    $id->bindParam(":municipio", $municipio, PDO::PARAM_INT);
    $id->bindParam(":localidad", $localidad, PDO::PARAM_STR);
    $id->execute();
  	return $id->fetch();
  	$id->close();
  	$id=null;

  }

  public function obtenerLocalidades($municipio, $estado)
  {
    echo $municipio;
  	$localidades = $this->_db->prepare('
  		SELECT 
  			d_asenta, id_asenta_cpcons
  		FROM 
  			CodigosPostales
  		WHERE 
  			c_mnpio = :municipio && c_estado=:estado');
    $localidades->bindParam(":municipio", $municipio, PDO::PARAM_INT);
    $localidades->bindParam(":estado", $estado, PDO::PARAM_INT);
  	$localidades->execute();
    return $localidades->fetchAll();
    $localidades->close();
    $localidades=null;

  }
  public function obtenerCP($id_asenta, $municipio, $estado)
  {
    
  	$cp = $this->_db->prepare('
  		SELECT 
        *
  		FROM 
  			CodigosPostales
  		WHERE 
  			id_asenta_cpcons = :id_asenta
      &&
        c_mnpio=:municipio
      &&
        c_estado=:estado
        ');
    $cp->bindParam(":id_asenta", $id_asenta, PDO::PARAM_INT);
    $cp->bindParam(":municipio", $municipio, PDO::PARAM_INT);
    $cp->bindParam(":estado", $estado, PDO::PARAM_INT);
  	$cp->execute();
    $cp->setFetchMode(PDO::FETCH_ASSOC);
    return $cp->fetch();
    $cp->close();
    $cp=null;

  }

  public function validarApiKey($key){
    $apikey = $this->_db->prepare(
      "
      SELECT
        apikey
      FROM
        estructura
      WHERE
        apikey= :key
      "
    );
    $apikey->bindParam(":key", $key, PDO::PARAM_STR);
    $apikey->execute();
    return $apikey->rowCount();
    $apikey->close();
    $apikey=null;
  }

}