<?php
/*
 * Carga atuomatiza de archivo .php
 */

function autoLoadCore($class)
{

    if(file_exists(APP_PATH . ucfirst(strtolower($class)).'.php')) {
         //echo "<pre>".APP_PATH . ucfirst(strtolower($class)). '.php' . '</pre>';
        include APP_PATH . ucfirst(strtolower($class)).'.php';
    }
}
function autoLoadLibs($libs)
{
    if(file_exists(ROOT . 'libs' . DS . 'class.' . strtolower($libs) . '.php')) {
        include_once ROOT . 'libs' . DS . 'class.' . strtolower($libs) . '.php';
    }else {
        throw new Exception("Imposible cargar la librería ". $libs);
    }
}

spl_autoload_register('autoLoadCore');
spl_autoload_register('autoLoadLibs');
