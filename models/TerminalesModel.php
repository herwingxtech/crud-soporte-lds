<?php
class TerminalesModel extends Model 
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTerminales()
    {

        $terminales = $this->_db->query(
            "
            SELECT
                idTerminal, nomTerminal, userTerminal, marcaTerminal,  modeloTerminal 
                ,serieTerminal,procesadorTerminal, ramTerminal, almacenamientoTerminal,   
                soTerminal, depTerminal,nombreSucursal, descTerminal, municipio, statusTerminal, 
                fechaIngresoTerminal
            FROM
                Terminal
            INNER JOIN
                vista_tienda
            ON
                sucTerminal= idSucursal
        
            "
        );
        return $terminales->fetchAll();
        $terminales->close();
        $terminales = null;
    }

    #Método para consultar que la serie no está en el sistema
    public function validaSerie($serie)
    {
  
      $serieDisp = $this->_db->prepare(
        "
        SELECT
          serieTerminal
        FROM
          Terminal
        WHERE
          serieTerminal= :serie
        "
      );
      $serieDisp->bindParam(":serie", $serie, PDO::PARAM_STR);
      $serieDisp->execute();
      return $serieDisp->rowCount();
      $serisDisp->close();
      $serieDisp=null;
    }
    #Método para ingresar registros al sistema
  public function agregarTerminal($args)
  {
    //Array ( [nombre] => asdasd [usuario] => adasdasd [marca] => asdasdas [modelo] => adasd [tipo] => [serie] => asdasdas [procesador] => sadasda 
    //[ram] => 3 Gb [so] => sadsad [hd] => 8 Gb [mac] => Pendiente [departamento] => sadasd [fechaIngreso] => 1111-11-11 [sucursal] => 2 [descripcion] => asdadsa [ip] => 255 [status] => 1 ) 

    $ter = $this->_db->prepare(
      "
      INSERT INTO Terminal
      (
        nomTerminal, userTerminal,marcaTerminal,modeloTerminal,serieTerminal, 
        procesadorTerminal,ramTerminal,almacenamientoTerminal, soTerminal,serieTerminal,
        depTerminal,descTerminal,fechaIngresoTerminal, statusTerminal,
    )
      VALUES
        (
          :nomTerm,
          :userTerm,
          :marcaTerm,
          :modeloTerm,
          :tipoTerm,
          :procesador,
          :RAM,
          :HD,
          :MAC,
          :OS,
          :serieTerm,
          :depTerm,
          :descTerm,
          :fechaIngreso,
          now(),
          :IP,
          :status,
          :sucursal
        )
      "
    );      
    $term->bindParam(':nomTerm', $args["nombre"], PDO::PARAM_STR);
    $term->bindParam('userTerm', $args["usuario"], PDO::PARAM_STR);
    $term->bindParam(":marcaTerm", $args["marca"], PDO::PARAM_STR);
    $term->bindParam(":modeloTerm", $args["modelo"], PDO::PARAM_STR);
    $term->bindParam(":tipoTerm", $args["tipo"], PDO::PARAM_STR);
    $term->bindParam(":procesador", $args["procesador"], PDO::PARAM_STR);
    $term->bindParam(":RAM", $args["ram"], PDO::PARAM_STR);
    $term->bindParam(":HD", $args["hd"], PDO::PARAM_STR);
    $term->bindParam(":MAC", $args["mac"], PDO::PARAM_STR);
    $term->bindParam(":OS", $args["so"], PDO::PARAM_STR);
    $term->bindParam(":serieTerm", $args["serie"], PDO::PARAM_STR);
    $term->bindParam(":depTerm", $args["departamento"], PDO::PARAM_STR);
    $term->bindParam(":descTerm", $args["descriTermion"], PDO::PARAM_STR);
    $term->bindParam(":fechaIngreso", $args["fechaIngreso"], PDO::PARAM_STR);
    $term->bindParam(":IP", $args["ip"], PDO::PARAM_INT);
    $term->bindParam(":status", $args["status"], PDO::PARAM_INT);
    $term->bindParam(":sucursal", $args["sucursal"], PDO::PARAM_INT);
    $term->execute();
    return $term->rowCount();
    $term->close();
    $term=null;
  }

}