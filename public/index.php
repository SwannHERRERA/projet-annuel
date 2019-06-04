<?php
session_start();
define('BASEPATH', dirname(__DIR__));
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
$controllerPath;
if ($uri_segments[1] == "back" || $uri_segments[1] == "bo") {
  $controllerPath =  BASEPATH . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Controllers"
   . DIRECTORY_SEPARATOR . 'back' . DIRECTORY_SEPARATOR;
   if (file_exists($controllerPath . $uri_segments[2] . '.php')){
        require $controllerPath . $uri_segments[2] . '.php';
        $class[] = new $uri_segments[2];
   } else {
        http_response_code(404);
        require BASEPATH . "/public/404.php";
   }
}else {
  $controllerPath =  BASEPATH . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Controllers"
   . DIRECTORY_SEPARATOR;
   if (file_exists($controllerPath . $uri_segments[1] . '.php')){
       require $controllerPath . $uri_segments[1] . '.php';
       $class[] = new $uri_segments[1];
    } else {
        require $controllerPath . 'page.php';
        $class[] = new Page;
   }
}
