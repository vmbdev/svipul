<?php

class Router {
    private $routes;

    public function __construct() {
        $routes = array();

        // by default, routes that don't match any will go to the run function
        // with the default view
        $this->addRoute('default', 'run', 'default');
    }

    public function addRoute($action, $function, $view) {
        $this->routes[$action] = array('function' => $function, 'view' => $view);
    }

    public function getRoute($action) {
        return (!empty($this->routes[$action])?$this->routes[$action]:null);
    }
}
