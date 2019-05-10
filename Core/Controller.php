<?php
abstract class Controller
{
    protected const VIEW_PATH = BASEPATH . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR;
    protected const MODEL_PATH = BASEPATH . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR;

    public function __construct($child_class)
    {
        $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', $uri_path);
        if ($uri_segments[1] == 'back' || $uri_segments[1] == 'bo') {
            $i = 3;
        } elseif ($child_class == 'Page') {
            $i = 1;
        } else {
            $i = 2;
        }
        if (empty($uri_segments[$i])) {
            $uri_segments[$i] = 'index';
        };
        if (method_exists($child_class, $uri_segments[$i])) {
            //call_user_func_array([$child_class, $uri_segments[$i]], []);
            $this->{$uri_segments[$i]}();
        } else {
            require BASEPATH . "/public/404.php";
        }
    }
}
