<?php

class Acl 
{
    private $_registry;
    private $_db;
    private $_id;
    private $_role;
    private $_permission;
    
    public function __construct($id = false)
    {
       // print_r($_SESSION);
        //if(Session::get('autenticado')) {
            if($id){
                $this->_id = (int) $id;
            }
            else{
                if(Session::get('idUser')) {
                    $this->_id = Session::get('idUser'); 
                }else {
                    $this->_id =0;
                }
            }
      // }
        
        $this->_registry = Registry::getInstance();
        $this->_db = $this->_registry->_db;
        $this->_role = $this->getRole($this->_id);
        $this->_permission = $this->getPermisosRole($this->_role);
        $this->compilarAcl();
    }
    
    public function compilarAcl() 
    {
        $this->_permission = array_merge(
                    $this->_permission,
                    $this->getPermisosUsuario($this->_id)
                ); 
    }


    public function getRole($id) 
    {
        
       // echo $this->_id;
        $role = $this->_db->query(
                "
                SELECT 
                    role 
                FROM 
                    users 
                WHERE 
                    idUser = {$id}
                "
                );
                    
        $role = $role->fetch();
        return $role['role'];
    }
    
    public function getPermisosRoleId()
    {
    //echo $this->_role;
       
        $ids = $this->_db->query(
                "
                SELECT 
                    permiso 
                FROM 
                    permisos_role 
                WHERE 
                    role= '{$this->_role}'
                "
                );
                //print_r($ids);
       $ids = $ids->fetchAll(PDO::FETCH_ASSOC);
       
       $id=array();
       for($i = 0; $i<count($ids); $i++) {
           $id[] = $ids[$i]['permiso'];
       }
       return $id;
    }
    
    public function getPermisosRole($role) 
    {
        
       //echo "<pre>" ;print_r($this->_role); echo "</pre>";
        $permisos = $this->_db->query(
                "
                    SELECT 
                        * 
                    FROM 
                        permisos_role 
                    WHERE 
                        role = '{$role}'"
                    );
        $permisos=$permisos->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        
        for($i = 0; $i < count($permisos); $i++) {
            //print_r($permisos[$i]['permiso']);
            $key = $this->getPermisoKey($permisos[$i]['permiso']);
            if($key == ''){continue;}
            if($permisos[$i]['valor'] == 1) {
                $v =true;
            }else {
                $v = false;
            }
            
            $data[$key] = array(
                'key' => $key,
                'permiso' => $this->getPermisoNombre($permisos[$i]['permiso']),
                'valor' => $v,
                'heredado' => true,
                'id' => $permisos[$i]['permiso']
            );
        }
        
        return $data;
    }
    
    public function getPermisoKey($permisoID)
    {
        //echo $permisoID;
        $permisoID = (int) $permisoID;
        $key = $this->_db->query(
                "SELECT 
                    `key` 
                 FROM 
                    permisos 
                 WHERE 
                    idpermiso = {$permisoID}"
               );
        $key =$key->fetch();
        return $key['key'];
    }
    
    public function getPermisoNombre($permisoID)
    {
        $permisoID = (int) $permisoID;
        $name = $this->_db->query(
                "SELECT 
                    permiso 
                 FROM 
                    permisos 
                 WHERE 
                    idpermiso = {$permisoID}"
               );
        $name =$name->fetch();
        return $name['permiso'];
    }
    
    public function getPermisosUsuario($id) 
    {
       
         //$ids = $this->getPermisosRoleId();
        $ids = self::getPermisosRoleId();
       // echo $this->_id;
         if(count($ids)){
            $permisos = $this->_db->query(
                    "
                        SELECT 
                            * 
                        FROM 
                            permisos_usuario 
                        WHERE 
                            usuario={$id}
                        AND 
                            permiso in (". implode(",", $ids ) .")"

                        );
                        
             $permisos = $permisos->fetchAll(PDO::FETCH_ASSOC);
         }else {
             $permisos=array();
         }
         $data =array();
        
        for($i = 0; $i < count($permisos); $i++) {
            $key = $this->getPermisoKey($permisos[$i]['permiso']);
            if($key == ''){continue;}
            if($permisos[$i]['valor'] == 1) {
                $v =true;
            }else {
                $v = false;
            }
            
            $data[$key] = array(
                'key' => $key,
                'permiso' => $this->getPermisoNombre($permisos[$i]['permiso']),
                'valor' => $v,
                'heredado' => true,
                'id' => $permisos[$i]['permiso']
            );
        }
        return $data;
    }
    
    public function getPermisos() 
    {
        if(isset($this->_permission) && count($this->_permission)) {
            return $this->_permission;
        }
    }
    
    public function permiso($key) 
    {
       // echo $key;
        if(array_key_exists($key, $this->_permission)) {
            if($this->_permission[$key]['valor'] == true || $this->_permission[$key]['valor']==1) {
                return true;
            }
        }
        return false;
    }
    
    public function  controlAccess($key) 
    {
        //print_r($_SESSION);
        
        //if(Session::get('autenticado')) {
           // echo $key;
            if($this->permiso($key)) {
                return;
            }
            header('location:' . BASE_URL . "error/access/504");
        //}
    }
}
