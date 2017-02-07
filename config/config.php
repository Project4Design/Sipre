<?
/*==========| Archivo de configuracion base - Beta |=========*/
if(!isset($_SESSION)){
  session_start();
}
/*
* Definicion de variables globales
*/

//Separador de directorios
if (!defined('DS')) {
  define('DS', DIRECTORY_SEPARATOR);
}
//Directorio base public_html/
if (!defined('ROOT')) {
  define('ROOT', dirname(dirname(dirname(__FILE__))).DS);
}
//Directorio de la aplicacion p4dweb.com.ve/
if (!defined('APP_DIR')) {
  define('APP_DIR', basename(dirname(dirname(__FILE__))).DS);
}
//Url base
if (!defined('BASE_URL')) {
  $whitelist = array(
    '127.0.0.1',
    '::1'
  );

  if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    $base = $_SERVER['HTTP_HOST'].DS;
  }else{
    $base = $_SERVER['HTTP_HOST'].DS.APP_DIR;
  }

  define('BASE_URL', "http://".$base);
}

/*
* Definicion de variables para conexion a la BD
*/

//Definir host de la BD
if (!defined('DB_HOST')) {
  define('DB_HOST',"localhost");
}
//Definir usuario de la BD
if (!defined('DB_USER')) {
  define('DB_USER',"root");
}
//Definir password de la BD
if (!defined('DB_PASS')) {
  define('DB_PASS', "");
}
//Definir tabla de la BD
if (!defined('DB_TABLE')) {
  define('DB_TABLE', "sipre");
}

//Definir zona horaria por defecto
date_default_timezone_set("America/Caracas");

//Helper de resupestas.
require_once ROOT.APP_DIR."helper/responsehelper.php";
//Funciones base.
require_once ROOT.APP_DIR."helper/basehelper.php";
//Funciones base de formulario.
require_once ROOT.APP_DIR."helper/formhelper.php";
//Conexion a la BD.
require_once ROOT.APP_DIR."funciones/conex.php";
//Autocargador.
require_once ROOT.APP_DIR."funciones/autoload.php";