<?php

class DispositivosModel extends Model
{
  public function __construct()
  {
    parent::__construct();
  }

  #Método para consultar que la serie no está en el sistema
  public function validaSerie($serie)
  {

    $serieDisp = $this->_db->prepare(
      "
      SELECT
        idDISPOSITIVO
      FROM
        DISPOSITIVO
      WHERE
        serieDispositivo= :serie
      "
    );
    $serieDisp->bindParam(":serie", $serie, PDO::PARAM_STR);
    $serieDisp->execute();
    return $serieDisp->rowCount();
    $serieDisp =null;
  }

  #Método para registrar dispositivos en el sistema
  public function agregarDispositivo($datos)
  {
    //extract($datos);
    /*
    Array
(
    [nombre] => Nombre
    [marca] => Marca
    [modelo] => Modelo
    [serie] => Serie
    [tipo] => Almacenamiento
    [departamento] => Departamento
    [descripcion] => DESCr
    [fechaIngreso] => 1111-11-11
    [sucursal] => 6
    [status] => Activo
)
    */
    
    try {
      $dp = $this->_db->prepare(
        "
        INSERT INTO
          DISPOSITIVO
          (
            nomDispositivo, marcaDispositivo, modeloDispositivo, serieDispositivo, tipoDispositivo, depDispositivo,
            detalleDispositivo, fechaIngresoDispositivo, fechaActualizarDispositivo, status, sucursalDispositivo 
          )
        VALUES
          (
            :nombre,:marca,:modelo,:serie,:tipo,:dep,:desc,
            :fechaIngreso,now(),:status, :sucursal
          )
        "
      );
       $dp->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
       $dp->bindParam(':marca', $datos['marca'], PDO::PARAM_STR);
       $dp->bindParam(':modelo',$datos['modelo'], PDO::PARAM_STR);
       $dp->bindParam(':serie', $datos['serie'], PDO::PARAM_STR);
       $dp->bindParam( ':tipo', $datos['tipo'], PDO::PARAM_STR);
       $dp->bindParam(':dep', $datos['departamento'], PDO::PARAM_STR);
       $dp->bindParam(':desc', $datos['descripcion'] , PDO::PARAM_STR);
       $dp->bindParam(':fechaIngreso', $datos['fechaIngreso'], PDO::PARAM_STR);
       $dp->bindParam(':sucursal', $datos['sucursal'], PDO::PARAM_INT);
       $dp->bindParam(':status', $datos['status'], PDO::PARAM_STR);
      $dp->execute();
      return $dp->rowCount();
      $dp=null;
    } catch (Exception $e) {
      echo $e;
    }
  }

  #Método para obtener el catálogo de dispositivos
  public function obtenerCatalogo()
  {
    $dps = $this->_db->query(
      "
      SELECT
        idDISPOSITIVO, nomDispositivo, marcaDispositivo, modeloDispositivo, serieDispositivo, tipoDispositivo, depDispositivo, nombreSucursal, status
      FROM
        DISPOSITIVO
      INNER JOIN
        vista_tienda
      ON
        sucursalDispositivo = idSucursal
      ");
      return $dps->fetchAll();
  }

  #Método para consular dispositivo por ID
  public function obtenerDispositivo($idDispositivo)
  {

    try {
      $dispositivo = $this->_db->prepare(
        "
        SELECT
          idDISPOSITIVO, idSucursal, nomDispositivo, marcaDispositivo, modeloDispositivo, serieDispositivo, tipoDispositivo, depDispositivo, fechaIngresoDispositivo, nombreSucursal, sucursalDispositivo, detalleDispositivo, status
        FROM
          DISPOSITIVO
        INNER JOIN
          vista_tienda
        ON
          sucursalDispositivo = idSucursal
        WHERE
          idDISPOSITIVO = :id
        LIMIT
              1
        "
      );
      $dispositivo->bindParam(':id', $idDispositivo, PDO::PARAM_INT);
      $dispositivo->execute();
      $dispositivo->setFetchMode(PDO::FETCH_ASSOC);
      return $dispositivo->fetch();
    } catch (Exception $e) {
      echo $e;
    }

  }

  public function actualizarDispositivo($datos)
  {
    try {

      $dispositivo = $this->_db->prepare(

        "
        UPDATE
          DISPOSITIVO
        SET
          nomDispositivo=:nombre,
          marcaDispositivo=:marca,
          modeloDispositivo=:modelo,
          serieDispositivo=:serie,
          tipoDispositivo=:tipo,
          depDispositivo=:dep,
          detalleDispositivo=:desc,
          fechaIngresoDispositivo=:fechaIngreso,
          fechaActualizarDispositivo=now(),
          sucursalDispositivo=:sucursal,
          status=:status
      WHERE
          idDISPOSITIVO=:id
        "
      );
      
      $dispositivo->bindParam(':nombre',$datos['nombre'], PDO::PARAM_STR);
      $dispositivo->bindParam(':marca',$datos['marca'], PDO::PARAM_STR);
      $dispositivo->bindParam(':modelo',$datos['modelo'], PDO::PARAM_STR);
      $dispositivo->bindParam(':serie',$datos['serie'], PDO::PARAM_STR);
      $dispositivo->bindParam(':tipo',$datos['tipo'], PDO::PARAM_STR);
      $dispositivo->bindParam(':dep',$datos['departamento'], PDO::PARAM_STR);
      $dispositivo->bindParam(':desc',$datos['descripcion'], PDO::PARAM_STR);
      $dispositivo->bindParam(':fechaIngreso',$datos['fechaIngreso'], PDO::PARAM_STR);
      $dispositivo->bindParam(':sucursal',$datos['sucursal'], PDO::PARAM_INT);
      $dispositivo->bindParam(':status',$datos['status'], PDO::PARAM_STR);
      $dispositivo->bindParam(':id',$datos['idDispositivo'], PDO::PARAM_STR);
      $dispositivo->execute();
      return $dispositivo->rowCount();
      $dispositivo=null;
    } catch (Exception $e) {
      echo $e;
    }

  }

}
