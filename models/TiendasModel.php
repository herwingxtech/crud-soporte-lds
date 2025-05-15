<?php
/**
* 
*/
class TiendasModel extends Model
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
      "
      SELECT  *  FROM EstadosMX
      "

    );
    return $estados->fechaAll();
    $estados = null;

  }
  public function obtenerEstadosa()
  {

    $estados = $this->_db->query(
      '
        SELECT
         *
        FROM
          vista_estados 
      '
    );
  	return $estados->fetchAll();
    $estados=null;
  }

  public function registrarTienda($datos)
  {
  	//extract($datos);
  	
  	$tienda  = $this->_db->prepare(
  		"
  		INSERT INTO 
  			Sucursales
            (
                nombreSucursal,
                direccionSucursal,
                numSucursal,
                telefonoSucursal,
                ubicacionSucursal,
                fechaRegistro,
                statusSucursal
            )
  		VALUES 
            ( 
                :nombre,
                :direccion,
                :numero,
                :telefono,
                :ubicacion,
                :fecha,
                :status
            )
  		"
  	);
    $tienda->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
    $tienda->bindParam(":direccion", $datos["dir"], PDO::PARAM_STR);
    $tienda->bindParam(":numero", $datos["numExt"], PDO::PARAM_STR);
    $tienda->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
    $tienda->bindParam(":ubicacion", $datos["idUbicacion"], PDO::PARAM_INT);
    $tienda->bindParam(":fecha", $datos["fechaRegistro"], PDO::PARAM_STR);
    $tienda->bindParam(":status", $datos["status"], PDO::PARAM_STR);
    $tienda->execute();
  	return $tienda->rowCount();
  	$tienda->close();
  	$tienda=null;
  }

  #Método para consultar la sucursal
  public function obtenerMunicipios($estado)
  {
  	$municipios = $this->_db->prepare('
  		SELECT 
  			DISTINCT municipio, idMunicipio
  		FROM 
  			vista_municipios 
  		WHERE 
  			idEstado = :estado');
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

  public function obtenerLocalidades($municipio)
  {

  	$localidades = $this->_db->prepare('
  		SELECT 
  			asentamiento 
  		FROM 
  			vista_localidades
  		WHERE 
  			idMunicipio = :municipio');
    $localidades->bindParam(":municipio", $municipio, PDO::PARAM_INT);
  	$localidades->execute();
    return $localidades->fetchAll();
    $localidades->close();
    $localidades=null;

  }

}