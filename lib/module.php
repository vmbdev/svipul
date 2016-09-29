<?php

abstract class Module {
	protected $db, $content, $params, $action, $function, $view, $router;

	function __construct($db = null, $uri = null) {
        if (!$db)
            $this->__db = ResManager::getDatabase();

        else
            $this->__db = $db;

        $this->getParamsFromUri($uri);
        $this->content = array();
        $this->errors = array();
        $this->router = new Router();

        $modelclass = $this->getModuleName();
        if (class_exists($modelclass)) {
            $this->model = new $modelclass($db);
            $this->content['model'] = $this->model;
        }
	}

    public function startController() {
        // detect the action
        if (!$this->action) {
            if ($this->router->getRoute('default'))
                $this->action = 'default';

            else
                throw new Exception('Controller Exception: Action does not exists', 100);
        }

        $route = $this->router->getRoute($this->action);

        if (empty($route))
            throw new Exception('Controller Exception: Action not in route', 101);

        // check if the function exists
        $this->function = $route['function'];
        if (!method_exists($this, $this->function))
            $this->function = null;

        // pass the viewer back to the dispatcher
        $this->view = FileSystem::getModuleView($this->getModuleName(), $route['view']);
        if (!$this->view)
            $this->view = null;
    }

    public function getParamsFromUri($uri) {
        $param_list = array_values(array_filter(explode('/', $uri)));

        if (!empty($param_list)) {
            $this->params = array();

            // first element is the action
            $this->action = array_shift($param_list);

            // if the number of params after taking the action is odd
            // then the next parameter is the var for the action
            // i.e. localhost/news/view/51 => p(view): v(51)
            if ((count($param_list) % 2) != 0)
                $this->params[$this->action] = array_shift($param_list);

            // the rest are pars of /param/value
            // every parameter has to have a value
            // if not, the parameter is discarded
            for ($i = 0; $i < count($param_list)-1; $i+=2)
                $this->params[$param_list[$i]] = $param_list[$i+1];
        }
        else
            $this->action = null;
    }

    public function getModel() {
        return $this->model;
    }

    public function getView() {
        return $this->view;
    }

    public function getLayout() {
        if (FileSystem::getModuleView($this->getModuleName(), 'layout'))
            return FileSystem::getModuleView($this->getModuleName(), 'layout');

        else
            return false;
    }

    public function getFunction() {
        return $this->function;
    }

    public function getParams() {
        return $this->params;
    }

    public function getParam($param) {
        return ($this->params[$param] ? $this->params[$param] : null);
    }

    public function getModuleName() {
        return substr(get_class($this), 0, -strlen('Controller'));
    }

    public function getContent() {
        return $this->content;
    }

    public function addError($code, $msg) {
        $this->errors[$code] = $msg;
    }

    public function getError($code) {
        if (is_numeric($code) && ($code < count($this->errors)))
            return ($this->errors[$code] ? $this->errors[$code] : null);

        else
            return false;
    }

    public function existErrors() {
        return !empty($this->errors);
    }

}
