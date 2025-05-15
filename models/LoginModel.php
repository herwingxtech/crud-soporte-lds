<?php

class LoginModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsers($user, $pass)
    {

        $data = $this->_db->query(
                "
                    SELECT
                        idUser, name, apellidos, ocupation, role, estatus, avatar
                    FROM
                        users
                    WHERE
                        username = '$user'
                    AND
                        pass = '$pass'
                    LIMIT 1
                "
                );
        return $data->fetch();
    }
}
