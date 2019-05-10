<?php
session_start();
define('BASEPATH', dirname(__DIR__));
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
$controllerPath;
if ($uri_segments[1] == "back" || $uri_segments[1] == "bo") {
  $controllerPath =  BASEPATH . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Controllers"
   . DIRECTORY_SEPARATOR . 'back' . DIRECTORY_SEPARATOR;
  require $controllerPath . $uri_segments[2] . '.php';
  $class[] = new $uri_segments[2];
}else {
  $controllerPath =  BASEPATH . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Controllers"
   . DIRECTORY_SEPARATOR;
   if (file_exists($controllerPath . $uri_segments[1] . '.php')){
     require $controllerPath . $uri_segments[1] . '.php';
     $class[] = new $uri_segments[1];
    } else {
      require $controllerPath . 'Page.php';
      $class[] = new Page;
   }
}
//unset($_SESSION['flashdata']);
