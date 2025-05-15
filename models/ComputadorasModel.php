<?php


/*
 * ====================================================
 * = Nombre: Modelo de datos computadorasModel        =
 * = Directorio: Soporte/models/computadorasModel.php =
 * = Función: En está clase se encuentran los         =
 * = diferentes métodos para ingresar, consultar      =
 * = y actualizar datos al Catálogo de Computadoras.  =
 * ====================================================
 */
class ComputadorasModel extends Model
{
  public function __construct()
  {
    parent::__construct();
  }

  #Método para consultar el catálogo de computadoras
  public function getComputadoras()
  {
      $pcs = $this->_db->query(
          "
      SELECT
        idCOMPUTADORA, nomComputadora, numIP, statusIP, MAC,  nomStatus, depComputadora, serieComputadora, OS, nombreSucursal, municipio
      FROM
        COMPUTADORA
      INNER JOIN
        vista_tienda
	  ON
		lugarFisico= idSucursal
      INNER JOIN 
		STATUS
	  ON 
		STATUS_idSTATUS = idSTATUS
	  INNER JOIN 
		IP
	  ON 
		IP_idIP = idIP
    WHERE STATUS_idSTATUS != 3;
      
          "
      );
      return $pcs->fetchAll();
      $pcs->close();
      $pcs = null;
      

  }

  public function catalogoPCAjax()
  {
    $pcs = $this->_db->query(
      "
        SELECT
          idCOMPUTADORA, nomComputadora, numIP, statusIP, MAC,  nomStatus, depComputadora, serieComputadora, OS, nombreSucursal, municipio
        FROM
          COMPUTADORA
        INNER JOIN
          vista_tienda
      ON
      lugarFisico= idSucursal
        INNER JOIN 
      STATUS
      ON 
      STATUS_idSTATUS = idSTATUS
      INNER JOIN 
      IP
      ON 
      IP_idIP = idIP
        
      "
      );
      $pcs->setFetchMode(PDO::FETCH_ASSOC);
      return $pcs->fetchAll();
      $pcs->close();
      $pcs = null;
  }

  #Método para consultar si el nombre de la computadora está disponible
  public function nomCompDisp($nombre)
  {
    $nomPC = $this->_db->prepare(
      "
      SELECT
        idCOMPUTADORA
      FROM
        COMPUTADORA
      WHERE
        nomComputadora = :nomPC
      "
    );
    $nomPC->bindParam(":nomPC", $nombre, PDO::PARAM_STR);
    $nomPC->execute();
    return $nomPC->rowCount();
    $nomPC->close();
    $nomPC=null;
  }

  #Método para consultar que la serie no está en el sistema
  public function validaSerie($serie)
  {

    $serieDisp = $this->_db->prepare(
      "
      SELECT
        serieComputadora
      FROM
        COMPUTADORA
      WHERE
        serieComputadora= :serie
      "
    );
    $serieDisp->bindParam(":serie", $serie, PDO::PARAM_STR);
    $serieDisp->execute();
    return $serieDisp->rowCount();
    $serisDisp->close();
    $serieDisp=null;
  }

  #Método para consultar que la serie no está en el sistema
  public function validaMAC($mac)
  {

    $macDisp = $this->_db->prepare(
      "
      SELECT
        MAC
      FROM
        COMPUTADORA
      WHERE
        MAC= :mac
      "
    );
    $macDisp->bindParam(":mac", $mac, PDO::PARAM_STR);
    $macDisp->execute();
    return $macDisp->fetch();
    //return $macDisp->rowCount();
    $macDisp=null;
  }

  public function tiendas()
  {
      $stmt = $this->_db->query(
          "
          SELECT 
            *
          FROM  
            vista_tienda
         
        "
      );
      return $stmt->fetchAll();
      $stmt->close();
      $stmt=null;
      
      
  }

  #Método para verificar si la IP está disponible para poder asignarla
  public function disponibleIP($ip, $sucursal, $status)
  {
    /*7echo $ip.'<br/>';
    echo $sucursal.'<br/>';
    echo $status.'<br/>';
    exit;*/
    $consulta = $this->_db->prepare(
      "SELECT idIP, numIP FROM IP WHERE numIP=:ip && sucursal=:sucursal && statusIP=:estado"
    );
    $consulta->bindParam(":ip", $ip, PDO::PARAM_STR);
    $consulta->bindParam(":sucursal", $sucursal, PDO::PARAM_STR);
    $consulta->bindParam(":estado", $status, PDO::PARAM_STR);
    $consulta->execute();
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    
    return $consulta->fetch();
    $consulta=null;
  }

  public function totalPCS($status)
  {
    $stmt = $this->_db->prepare("SELECT idCOMPUTADORA FROM COMPUTADORA WHERE STATUS_idSTATUS=:estado");
    $stmt->bindParam(":estado", $status, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount();
    $stmt->close();
    $stmt=null;
  }

  #Método para ingresar registros al sistema
  public function agregarComputadora($args)
  {

    $pc = $this->_db->prepare(
      "
      INSERT INTO COMPUTADORA
      (
        nomComputadora,
        userComputadora,
        marcaComputadora,
        modeloComputadora,
        tipoComputadora,
        Procesador,
        RAM,
        HD,
        MAC, 
        OS,
        serieComputadora,
        depComputadora,
        otrasCaracteristicas,
        fechaIngresoComputadora,
        fechaActualizarComputadora,
        IP_idIP,
        STATUS_idSTATUS, 
        lugarFisico
    )
      VALUES
        (
          :nomPC,
          :userPC,
          :marcaPC,
          :modeloPC,
          :tipoPC,
          :procesador,
          :RAM,
          :HD,
          :MAC,
          :OS,
          :seriePC,
          :depPC,
          :descPC,
          :fechaIngreso,
          now(),
          :IP,
          :status,
          :sucursal
        )
      "
    );      
    $pc->bindParam(':nomPC', $args["nombre"], PDO::PARAM_STR);
    $pc->bindParam('userPC', $args["usuario"], PDO::PARAM_STR);
    $pc->bindParam(":marcaPC", $args["marca"], PDO::PARAM_STR);
    $pc->bindParam(":modeloPC", $args["modelo"], PDO::PARAM_STR);
    $pc->bindParam(":tipoPC", $args["tipo"], PDO::PARAM_STR);
    $pc->bindParam(":procesador", $args["procesador"], PDO::PARAM_STR);
    $pc->bindParam(":RAM", $args["ram"], PDO::PARAM_STR);
    $pc->bindParam(":HD", $args["hd"], PDO::PARAM_STR);
    $pc->bindParam(":MAC", $args["mac"], PDO::PARAM_STR);
    $pc->bindParam(":OS", $args["so"], PDO::PARAM_STR);
    $pc->bindParam(":seriePC", $args["serie"], PDO::PARAM_STR);
    $pc->bindParam(":depPC", $args["departamento"], PDO::PARAM_STR);
    $pc->bindParam(":descPC", $args["descripcion"], PDO::PARAM_STR);
    $pc->bindParam(":fechaIngreso", $args["fechaIngreso"], PDO::PARAM_STR);
    $pc->bindParam(":IP", $args["ip"], PDO::PARAM_INT);
    $pc->bindParam(":status", $args["status"], PDO::PARAM_INT);
    $pc->bindParam(":sucursal", $args["sucursal"], PDO::PARAM_INT);
    if($pc->execute()){
      return "ok";
    }else {
      return 'error';
    }

   // return $pc->rowCount();
    $pc->close();
    $pc=null;
  }

  #Método para consultar computadora en el sistema
  public function obtenerComputadoraId($pc)
  {
    $computadora = $this->_db->prepare(
      "
      SELECT
        idCOMPUTADORA, nomComputadora,  userComputadora,
            marcaComputadora, modeloComputadora, tipoComputadora,
            MAC, Procesador, OS, RAM, HD, idSUCURSAL, nombreSucursal,
            depComputadora, serieComputadora, otrasCaracteristicas,
            fechaIngresoComputadora, fechaActualizarComputadora, numIP, IP_idIP, lugarFisico
        FROM
            COMPUTADORA
        INNER JOIN
            vista_tienda
        ON
            lugarFisico= idSucursal
        INNER JOIN
          IP
        ON
          IP_idIP= idIp
        WHERE
            idCOMPUTADORA=:id
        LIMIT
            1
      "
    );
    $computadora->bindParam(":id", $pc, PDO::PARAM_INT);
    $computadora->execute();
    $computadora->setFetchMode(PDO::FETCH_ASSOC);
    return $computadora->fetch();
  }

  #Método para actualizar la información de una computadora en el sistema.
  public function actualizaComputadora($args)
  {
   // extract($args);
    $pc = $this->_db->prepare(
      "
      UPDATE
        COMPUTADORA
      SET
        nomComputadora  = :nomPC ,
        userComputadora  = :userPC,
        marcaComputadora = :marcaPC,
        modeloComputadora = :modeloPC,
        tipoComputadora  = :tipoPC,
        Procesador       = :procesador,
        RAM                  = :ram,
        HD                   = :hd,
        MAC                  = :mac,
        OS                   = :so,
        serieComputadora     = :serie,
        depComputadora       = :dep,
        lugarFisico  = :sucursal,
        otrasCaracteristicas = :otras,
        fechaActualizarComputadora=now()
      WHERE
        idCOMPUTADORA = :id
      "

    );      
    $pc->bindParam(':nomPC', $args["nombre"], PDO::PARAM_STR);
    $pc->bindParam('userPC', $args["usuario"], PDO::PARAM_STR);
    $pc->bindParam(":marcaPC", $args["marca"], PDO::PARAM_STR);
    $pc->bindParam(":modeloPC", $args["modelo"], PDO::PARAM_STR);
    $pc->bindParam(":tipoPC", $args["tipo"], PDO::PARAM_STR);
    $pc->bindParam(":procesador", $args["procesador"], PDO::PARAM_STR);
    $pc->bindParam(":ram", $args["ram"], PDO::PARAM_STR);
    $pc->bindParam(":hd", $args["hd"], PDO::PARAM_STR);
    $pc->bindParam(":mac", $args["mac"], PDO::PARAM_STR);
    $pc->bindParam(":so", $args["so"], PDO::PARAM_STR);
    $pc->bindParam(":serie", $args["serie"], PDO::PARAM_STR);
    $pc->bindParam(":dep", $args["departamento"], PDO::PARAM_STR);
    $pc->bindParam(":otras", $args["descripcion"], PDO::PARAM_STR);
    $pc->bindParam(":sucursal", $args["sucursal"], PDO::PARAM_INT);
    $pc->bindParam(":id", $args["id"], PDO::PARAM_INT);
    $pc->execute();
    return $pc->rowCount();
    $pc->close();
    $pc=null;

  }

  #Método para eliminar el registro de la base de datos
  public function eliminarRegistro($pc)
  {
    $stmt = $this->_db->prepare("DELETE FROM COMPUTADORA WHERE idCOMPUTADORA=:id");
    $stmt->bindParam(":id", $pc, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
    $stmt->close();
    $stmt = null;

  }

  #Método para reserva direcciones IP
  public function reservaIP($id, $comentario)
  {
    $stmt = $this->_db->prepare("UPDATE IP SET statusIP ='Reservada', comentarioIP=:comentario WHERE idIP=:id");
    $stmt->bindParam(":comentario", $comentario, PDO::PARAM_STR);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
    $stmt->close();
    $stmt->null;
  }

  #Método para liberar IP del sistema
  public function liberaIP($id, $ip)
  {
    echo $id;
  }
  /*public function liberaIP($id, $ip)
  {
    echo $id . " - "; $ip; exit;
    $libera = $this->_db->prepare("UPDATE COMPUTADORA SET IP_idIP=:ip WHERE idCOMPUTADORA=:id");
    $libera->bindParam(":ip", $ip, PDO::PARAM_INT);
    $libera->bindParam(":id", $id, PDO::PARAM_INT);
    $libera->execute();
    return $libera->rowCount();
    $libera->close();
    $libera=null;
  }*/
    
  #Método para modificar el status de la IP en función al método liberaIP.
  public function cambiarStatusIP($ip,$status)
  {  
    //echo $ip . "<br>";
    //echo $status; 
    $st =  $this->_db->prepare(
        "UPDATE IP SET comentarioIP='Ningún comentario', statusIP=:status, fechaActualizarIP=now() WHERE idIP=:ip"
    );
    $st->bindParam(":status", $status, PDO::PARAM_INT);
    $st->bindParam(":ip", $ip, PDO::PARAM_INT);
    $st->execute();
    return $st->rowCount();
    $st->close();
    $st=null;
  }

  #Método para asignar IP a la computadora
  public function asignaIP($pc, $ip)
  {
    $stmt = $this->_db->prepare(
      "UPDATE COMPUTADORA SET IP_idIP=:ip WHERE idCOMPUTADORA=:id LIMIT 1"
    );
    $stmt->bindParam(":id", $pc, PDO::PARAM_INT);
    $stmt->bindParam(":ip", $ip, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
    $stmt->close();
    $stmt=null;
  }

  #Método para visualizar el catálogo de direcciones IP
  public function catalgoIP()
  {
    $stmt =$this->_db->query(
      "SELECT * FROM IP WHERE statusIP!='Asignada'"
    );
    return $stmt->fetchAll();
  }

  #Método para obtener dirección IP por id
  public function obtenerIPId($id)
  {
    $ip = $this->_db->prepare(
      "SELECT numIP FROM IP WHERE idIP=:id"
    );
    $ip->bindParam(":id", $id, PDO::PARAM_INT);
    $ip->execute();
    $ip->setFetchMode(PDO::FETCH_ASSOC);
    return $ip->fetch();
  }

  public function agregarRerporteMantenimiento($datos)
  {
    //INSERT INTO `soporte`.`MANTENIMIENTO` (`nomMantenimiento`, `diagnosticoMantenimiento`, `solucionMantenimiento`, `fechaIngresoMantenimiento`, `fechaSalidaMantenimiento`, `COMPUTADORA_idCOMPUTADORA`) VALUES ('ksksk', 'sdsd', 'sdads', 'now()', 'now(9', '4');

      $reporte = $this->_db->prepare(
        "INSERT INTO MANTENIMIENTO 
          (
            nomMantenimiento,
            diagnosticoMantenimiento,
            solucionMantenimiento,
            fechaIngresoMantenimiento,
            fechaSalidaMantenimiento,
            COMPUTADORA_idCOMPUTADORA
          )
        VALUES
          (
            :nombre,
            :diagnostico,
            :solucion,
            now(),
            :fecha,
            :idPC
          )
      "
      );
    
      $reporte->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR); 
      $reporte->bindParam(":diagnostico", $datos["diagnostico"], PDO::PARAM_STR); 
      $reporte->bindParam(":solucion", $datos["solucion"], PDO::PARAM_STR); 
      $reporte->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR); 
      $reporte->bindParam("idPC", $datos["idPC"], PDO::PARAM_INT); 
      $reporte->execute();
      return $reporte->rowCount();
      $reporte->close();
      $reposte=null;
  }
    
  public function relacionUsuarioPC($pc)
  {
      $resguardo = $this->_db->prepare(
          "
          SELECT
            *
          FROM
            vista_resguardo
          WHERE 
            idCOMPUTADORA=:pc
          "
      );
      $resguardo->bindParam(":pc", $pc, PDO::PARAM_INT);
     $resguardo->execute();
     $resguardo->setFetchMode(PDO::FETCH_ASSOC);
     return $resguardo->fetch();
      
  }

}
