<?php
class SetModel extends Model
{
  public function __construct()
  {
    parent::__construct();
  }

  #Método para consultar la sucursal
  public function obtenerSucursal()
  {
    $sucursal = $this->_db->query(
      '
        SELECT
          idSucursal, nombreSucursal
        FROM
          vista_tienda
      '

    );
    return $sucursal->fetchall();
    $sucursal=null;
  }

  public function obtenerPCSucursal($sucursal, $departamento)
  {
    $PCSucursal = $this->_db->prepare(
      "
      SELECT
        idCOMPUTADORA, nomComputadora, nombreSucursal
      FROM
        COMPUTADORA
      INNER JOIN
        vista_tienda
      ON
        lugarFisico = idSucursal
      WHERE
        lugarFisico = :sucursal && depComputadora =:dep && STATUS_idSTATUS=1
      "
    );
    $PCSucursal->bindParam(":sucursal", $sucursal, PDO::PARAM_INT);
    $PCSucursal->bindParam(":dep", $departamento, PDO::PARAM_STR);
    $PCSucursal->execute();
    return $PCSucursal->fetchAll();
    $PCSucursal=null;
  }

  public function obtenerDispositivosSucursal($sucursal, $departamento)
  {
    
    $dispositivoSucursal = $this->_db->prepare(
      "
      SELECT
        idDISPOSITIVO, nomDispositivo, nombreSucursal, serieDispositivo
      FROM
        DISPOSITIVO
      INNER JOIN
        vista_tienda
      ON
        sucursalDispositivo = idSucursal
      WHERE
        sucursalDispositivo = :sucursal && depDispositivo =:dep && status='Activo'
      "
    );
    $dispositivoSucursal->bindParam(":sucursal", $sucursal, PDO::PARAM_INT);
    $dispositivoSucursal->bindParam(":dep", $departamento, PDO::PARAM_STR);
    $dispositivoSucursal->execute();
    return $dispositivoSucursal->fetchAll();
    $dispositivoSucursal=null;
  }

  #Método para registrar la asignación de PC a los usuarios
  public function asignarPC($pc, $idUsuario, $obs,  $status)
  {

 // echo $id . " - " .$idUsuario . " - " . $obs . " - " . $status;
     try {
      $asignar = $this->_db->prepare(
        "
          INSERT INTO
            COMPUTADORA_has_USUARIO
          VALUES
          (
            :pc, :idUsuario, :observacion, :status, now()
          )
        "
      );
      $asignar->bindParam(":pc", $pc, PDO::PARAM_INT);
      $asignar->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
      $asignar->bindParam(":observacion", $obs, PDO::PARAM_STR);
      $asignar->bindParam(":status", $status, PDO::PARAM_STR);
      $asignar->execute();
     // print_r($asignar->errorInfo());
      return $asignar->rowCount();
      $asignar=null;
    } catch (Exception $e) {
      echo $this->e;
    }
  }

  public function cambiarStatusPC($idPC, $status)
  {
    $statusPC = $this->_db->prepare("UPDATE COMPUTADORA SET STATUS_idSTATUS=:status WHERE idCOMPUTADORA=:idPC");
    $statusPC->bindParam(":status", $status, PDO::PARAM_INT);
    $statusPC->bindParam(":idPC", $idPC, PDO::PARAM_INT);
    $statusPC->execute();
    return $statusPC->rowCount();
    $statusPC=null;
  }

  #Método para consultar las notas Informativas
  public function obtenerNotas()
  {
    $notas = $this->_db->query(
    '
    SELECT
      idNotes, nameNote, urlNote, fechaNote
    FROM
      NOTES
    '
    );
    return $notas->fetchall();
  }

  public function obtenerBitacora()
  {
    $stmt = $this->_db->query(
    '
      SELECT
        idMANTENIMIENTO, nomComputadora, nomMantenimiento, depComputadora, serieComputadora
      FROM
        MANTENIMIENTO
      INNER JOIN
        COMPUTADORA
      ON
        COMPUTADORA_idCOMPUTADORA = idCOMPUTADORA
      WHERE
        STATUS_idSTATUS!=3
      ORDER BY fechaIngresoMantenimiento DESC
    '
    );
    return $stmt->fetchall();
  }

  public function obtenerBitacoraId($id)
  {
   
    try {
      $stmt = $this->_db->prepare(
    '
      SELECT
        nomComputadora, marcaComputadora, OS, nomMantenimiento, diagnosticoMantenimiento, solucionMantenimiento, fechaIngresoMantenimiento, fechaSalidaMantenimiento,depComputadora, nombreSucursal
      FROM
        MANTENIMIENTO
      INNER JOIN
        COMPUTADORA
      ON
        COMPUTADORA_idCOMPUTADORA = idCOMPUTADORA
      INNER JOIN
        vista_tienda
      ON
        lugarFisico = idSucursal
      WHERE
        idMANTENIMIENTO=:id
    '
    );
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetch();
    } catch (Exception $e) {
      echo $e->getMessage('Error en la base de da');
    }
  }
  public function eliminarRegistro($id)
  {
    $stmt = $this->_db->prepare(
        "DELETE FROM MANTENIMIENTO WHERE idMANTENIMIENTO = :id"
    );
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
  }
  
  public function obtenerNotaId($id)
  {
    $stmt = $this->_db->prepare(
      "SELECT * FROM NOTES WHERE idNotes = :id"
    );
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetch();
  }
  
  
  public function eliminarNota($id)
  {
    $stmt = $this->_db->prepare(
        "DELETE FROM NOTES WHERE idNotes = :id"
    );
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
  }

  public function agregarNota($datos)
  {
    $stmt = $this->_db->prepare(
      "INSERT INTO NOTES (nameNote, descNote, urlNote, fechaNote) VALUES(:name,:desc,:url, now())"
    );
    $stmt->bindParam(":name", $datos['nombre'], PDO::PARAM_STR);
    $stmt->bindParam(":desc", $datos['desc'], PDO::PARAM_STR);
    $stmt->bindParam(":url" , $datos['url'], PDO::PARAM_STR);
    if($stmt->execute()){
      return "ok";
    }else{
      return "error";
    }
    
  }

   #Método para registrar la asignación de Dispositivo a los usuarios
   public function asignarDispositivo($idUsuario, $dps,   $obs, $status)
   {
     
    // echo $dps . " - " .$idUsuario . " - " . $status . " - " . $obs;
 
      try {
       $asignar = $this->_db->prepare(
         "
           INSERT INTO
             Usuario_Dispositivo
           VALUES
           (
             null, :idUsuario, :dps,  :observacion, :status, now()
           )
         "
       );
       $asignar->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
       $asignar->bindParam(":dps", $dps, PDO::PARAM_INT);
       $asignar->bindParam(":observacion", $obs, PDO::PARAM_STR);
       $asignar->bindParam(":status", $status, PDO::PARAM_STR);
      // var_dump($asignar);
       $asignar->execute();
       //print_r($asignar->errorInfo());
       return $asignar->rowCount();
       $asignar=null;
     } catch (Exception $e) {
       echo $this->e;
     }
   }

   public function cambiarStatusDipositivo($idDispositivo, $status)
  {
    echo $idDispositivo . " - " . $status;
    $statusDP = $this->_db->prepare("UPDATE DISPOSITIVO SET status=:status WHERE idDISPOSITIVO=:idDispositivo");
    $statusDP->bindParam(":status", $status, PDO::PARAM_INT);
    $statusDP->bindParam(":idDispositivo", $idDispositivo, PDO::PARAM_INT);
    $statusDP->execute();
    print_r($statusDP->errorInfo());
    return $statusDP->rowCount();
    $statusDP=null;
  }
}