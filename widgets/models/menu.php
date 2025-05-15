<?php

class menuModelWidget extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMenuModel(){

        return array(
             array(
                'id'      => 'inicio',
                'titulo'   => 'Inicio',
                'url'    => BASE_URL . 'inicio' .DS,
                'icon'    => 'icon-home'
            ),
            array(
                'id' => 'computadoras',
                'titulo' => 'Computadoras',
                'url' => BASE_URL . 'computadoras' . DS,
                'icon' => 'icon-laptop',
            ),
            array(
                'id' => 'usuarios',
                'titulo' => 'Usuarios',
                'url' => BASE_URL . 'usuarios'. DS,
                'icon' => 'icon-users2'
            ),
            array(
                'id' => 'dispositivos',
                'titulo' => 'Dispositivos',
                'url' => BASE_URL . 'dispositivos' . DS,
                'icon' => 'icon-screen icon-print2',
            ),
            array(
                'id' => 'acl',
                'titulo' => 'ACL',
                'url' => BASE_URL . 'acl',
                'icon' => 'icon-user4',
                'tooltip' => 'Módulo para la lista de control de acceso'
            ),
             array(
                'id' => 'adminweb',
                'titulo' => 'Admin Web',
                'url' => BASE_URL . 'adminweb',
                'icon' => 'icon-chrome',
                'tooltip' => 'Módulo para la administración del sitio web'
            ),
            array(
                'id' => 'terminales',
                'titulo' => 'Terminales',
                'url' => BASE_URL . 'terminales/',
                'icon' => 'icon-config',
                'tooltip' => 'Módulo para ayuda de RH'
            ),
            array(
                'id' => 'tiendas',
                'titulo' => 'Tiendas',
                'url' => BASE_URL . 'tiendas',
                'icon' => 'icon-book',
                'tooltip' => 'Módulo para ubicar las tiendas y sucursales'
            ),
            array(
                'id' => 'ips',
                'titulo' => 'IPS',
                'url' => BASE_URL . 'ips',
                'icon' => 'icon-book',
                'tooltip' => 'Módulo para ubicar los servicios contratados'
            ),
            array(
                'id' => 'puntos',
                'titulo' => 'Puntos de Venta',
                'url' => BASE_URL . 'puntos',
                'icon' => 'icon-book',
                'tooltip' => 'Módulo para ubicar los Puntos de Venta'
            )
        
        );
    }
}
