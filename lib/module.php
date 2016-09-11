<?php

abstract class Module {
	protected $db, $content, $params, $action, $function, $view, $content_vars, $router;

	function __construct($db, $uri = null) {
		$this->db = $db;
        $this->getParamsFromUri($uri);
        $this->content = array();
        $this->router = new Router();

        if (isset($this->content_vars) && is_array($this->content_vars))
            $this->setContentVars($this->content_vars);

        $modelclass = get_class($this) . 'Model';
        if (class_exists($modelclass)) {
            $this->model = new $modelclass($db);
            $this->model->setModelName(get_class($this));
        }
        $this->startController();
	}

	abstract public function run();

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

    public function startController() {
        // detect the action
        if (!$this->action) {
            if ($this->router->getRoute('default'))
                $this->action = 'default';

            else
                throw new Exception('Controller Exception: Action does not exists', 10);
        }

        $route = $this->router->getRoute($this->action);
        if (empty($route))
            throw new Exception('Controller Exception: Action not in route', 11);

        // check if the function exists
        $this->function = $route['function'];
        if (!method_exists($this, $this->function))
            throw new Exception('Controller Exception: Function does not exists', 12);

        // pass the viewer back to the dispatcher
        $this->view = FileSystem::getModuleView(get_class($this), $route['view']);
        if (!$this->view)
            throw new Exception('Controller Exception: View does not exists', 13);
    }

    public function getFunction() {
        return $this->function;
    }

    public function getParams() {
        return $this->params;
    }

    public function getView() {
        return $this->view;
    }

    public function getModel() {
        return $this->model;
    }

    public function setContentVars($vars) {
        foreach ($vars as $var)
        $this->content[$var] = false;
    }

    public function getContent() {
        return $this->content;
    }

    public function useCustomLayout() {
        return FileSystem::getModuleView(get_class($this), 'layout');
    }
}
