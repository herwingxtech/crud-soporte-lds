<?php
class UsuariosModel extends Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function verificarCorreoElectronio($email)
  {
    $correo = $this->_db->prepare("SELECT idUSUARIO FROM USUARIO WHERE mailUsuario=:email");
    $correo->bindParam(":email", $email, PDO::PARAM_STR);
    $correo->execute();
    return $correo->rowCount();
    $correo=null;
  }

  public function agregarUsuario($datos)
  {
      $usuario = $this->_db->prepare(
      '
      INSERT INTO
        USUARIO
      (
        nomUsuario, apellidosUsuario,mailUsuario,claveEmail,dirUsuario,fechaNacimiento,avatarUsuario,
        fechaIngreso,depUsuario,comentarios,status,fechaActualizar,sucursalUsuario
      )
      VALUES (
        :nombre,:apellidos,:correo,:claveEmail,:direccion,:fechaNacimiento,:avatar,
        :fechaIngreso, :departamento,:comentario,:status,now(),:sucursal
        )
      '
      );
      $usuario->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
      $usuario->bindParam(":apellidos", $datos["apellidos"], PDO::PARAM_STR);
      $usuario->bindParam(":correo", $datos["email"], PDO::PARAM_STR);
      $usuario->bindParam(":claveEmail", $datos["clave"], PDO::PARAM_STR);
      $usuario->bindParam(":direccion", $datos["domicilio"], PDO::PARAM_STR);
      $usuario->bindParam(":fechaNacimiento", $datos["fechaNacimiento"], PDO::PARAM_STR);
      $usuario->bindParam(":avatar", $datos["avatar"], PDO::PARAM_STR);
      $usuario->bindParam(":fechaIngreso", $datos["fechaIngreso"], PDO::PARAM_STR);
      $usuario->bindParam(":departamento", $datos["departamento"], PDO::PARAM_STR);
      $usuario->bindParam(":comentario", $datos["comentario"], PDO::PARAM_STR);
      $usuario->bindParam(":status", $datos["status"], PDO::PARAM_STR);
      $usuario->bindParam(":sucursal", $datos["sucursal"], PDO::PARAM_INT);
      $usuario->execute(); 
      return $usuario->rowCount();
      $usuario = null;
      
  }

  public function actualizaUsuario($datos)
  {
      $usuario = $this->_db->prepare(
      "
      UPDATE
        USUARIO
      SET
        nomUsuario = :nombre, apellidosUsuario = :apellidos,mailUsuario = :correo, dirUsuario = :direccion,fechaNacimiento =:fechaNacimiento,
        fechaIngreso =:fechaIngreso,depUsuario = :departamento,comentarios = :comentario, fechaActualizar = now(),sucursalUsuario = :sucursal
      WHERE
        idUSUARIO = :idUsuario
      "
      );
      $usuario->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
      $usuario->bindParam(":apellidos", $datos["apellidos"], PDO::PARAM_STR);
      $usuario->bindParam(":correo", $datos["email"], PDO::PARAM_STR);
      $usuario->bindParam(":direccion", $datos["domicilio"], PDO::PARAM_STR);
      $usuario->bindParam(":fechaNacimiento", $datos["fechaNacimiento"], PDO::PARAM_STR);
      $usuario->bindParam(":fechaIngreso", $datos["fechaIngreso"], PDO::PARAM_STR);
      $usuario->bindParam(":departamento", $datos["departamento"], PDO::PARAM_STR);
      $usuario->bindParam(":comentario", $datos["comentario"], PDO::PARAM_STR);
      $usuario->bindParam(":sucursal", $datos["sucursal"], PDO::PARAM_INT);
      $usuario->bindParam(":idUsuario", $datos["idUsuario"], PDO::PARAM_INT);
      $usuario->execute();
      return $usuario->rowCount();
      $usuario=null;
  }

  public function obtenerCatalogo()
  {
    $usuarios = $this->_db->query(
      "
      SELECT
        idUSUARIO, nomUsuario, apellidosUsuario, mailUsuario, depUsuario, nombreSucursal, avatarUsuario, status, claveEmail, fechaIngreso, sucursalUsuario, municipio
      FROM
        USUARIO
      INNER JOIN
        vista_tienda
      ON
        sucursalUsuario = idSucursal
      WHERE
        status=1;
      "
    );
   return $usuarios->fetchAll();
  }

  #Método para obtener información detallada de un usuario en la base de datos
  public function obtenerUsuario($idUsuario)
  {
    $usuario = $this->_db->prepare(
      "SELECT * FROM USUARIO INNER JOIN vista_tienda ON sucursalUsuario=idSucursal WHERE idUSUARIO=:id"
    );
    $usuario->bindParam(":id", $idUsuario, PDO::PARAM_INT);
    $usuario->execute();
    $usuario->setFetchMode(PDO::FETCH_ASSOC);
    return $usuario->fetch();
    $usuario=null;
  }

  #Método para asignar IP a la computadora
  public function agregarClave($clave, $idUsuario)
  {
    $stmt = $this->_db->prepare(
      "UPDATE USUARIO SET claveEmail=:clave WHERE idUSUARIO=:id LIMIT 1"
    );
    $stmt->bindParam(":clave", $clave, PDO::PARAM_STR);
    $stmt->bindParam(":id", $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
    $stmt=null;
  }

  public function totalUsuarios($status)
  {
    $stmt = $this->_db->prepare(" SELECT idUSUARIO FROM USUARIO WHERE status=:status");
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount();
    $stmt=null;
  }

  public function relacionPC()
  {
    $stmt = $this->_db->query(
      "SELECT * FROM vista_relacion_pc"
    );
    return $stmt->fetchAll();
    $stmt=null;
  }

  public function cambioStatusUsuarioPC($idUsuario, $idComputadora, $status)
  {
    $row = $this->_db->prepare(
      "UPDATE COMPUTADORA_has_USUARIO SET statusCompUsr=:status WHERE USUARIO_idUSUARIO=:idusr && COMPUTADORA_idCOMPUTADORA=:idPC"
    );
    $row->bindParam(":status", $status, PDO::PARAM_STR);
    $row->bindParam(":idPC", $idComputadora, PDO::PARAM_INT);
    $row->bindParam(":idusr", $idUsuario, PDO::PARAM_INT);
    $row->execute();
    return $row->rowCount();
    $row=null;
  }

  public function eliminarRelacion($idUsuario, $idComputadora)
  {
    $eliminar = $this->_db->prepare(
      "DELETE FROM COMPUTADORA_has_USUARIO WHERE USUARIO_idUSUARIO=:idusr && COMPUTADORA_idCOMPUTADORA=:idPC && statusCompUsr= 'baja'"
    );
    $eliminar->bindParam(":idusr", $idUsuario, PDO::PARAM_INT);
    $eliminar->bindParam(":idPC", $idComputadora, PDO::PARAM_INT);
    $eliminar->execute();
    return $eliminar->rowCount();
    $eliminar=null;
  }
  
  public function actualizarFotoPerfil($imagen, $idUsuario)
  {
      $row = $this->_db->prepare(
          "UPDATE USUARIO SET avatarUsuario=:img WHERE idUSUARIO=:id"
      );
      $row->bindParam(":img", $imagen, PDO::PARAM_STR);
      $row->bindParam(":id", $idUsuario, PDO::PARAM_INT);
      $row->execute();
      return $row->rowCount();
      $row=null;
  }

  public function desactivar($id)
  {
    
    $stm = $this->_db->prepare("UPDATE USUARIO SET status='Baja' WHERE idUSUARIO=:id");
    $stm->bindParam(":id", $id, PDO::PARAM_INT);
    $stm->execute();
    return $stm->rowCount();
    $stm=null;
  }
}