<?php

class MainController {
    private $db, $module_name, $module, $uri_rest;
    private $content; // vwr
    private $has_controller; // module is not static

    function __construct($db, $uri) {
        $this->db = $db;

        // get the module name from the URL
        $uri_model = '#^' . Config::$wwwroot . '/(?P<match>\w+)(?P<rest>/\S+)?#';
        preg_match($uri_model, $uri, $result);

        $uri_match = !empty($result['match']) ? $result['match'] : null;
        // uri with the params for the module
        $this->uri_rest = !empty($result['rest']) ? $result['rest'] : null;

        $module_name = strtolower($uri_match);

        // does the module exists in the filesystem? if not, use the default one
        if (!empty($module_name) && FileSystem::getModulePath($module_name))
            $this->module_name = $module_name;

        else
            $this->module_name = Config::$defaultModule;
    }

    public function run() {
        // we test if it has a controller
        // if not, it's just static content (only the view)

        $controller = FileSystem::getModuleController($this->module_name);
        if ($controller) {
            $controller_name = $this->module_name . 'Controller';
            if (class_exists($controller_name)) {
                $this->module = new $controller_name($this->db, $this->uri_rest);

                if (is_subclass_of($this->module, 'Module')) {
                    $this->module->startController();
                    $function = $this->module->getFunction();
                    $this->module->$function();
                    $this->has_controller = true;
                    $this->content = $this->module->getContent();
                }
                else // just as a security measure
                    $this->module = null;
            }

            // if it's not instantiated, it's not a controller
            if (!is_subclass_of($this->module, 'Module'))
                $this->has_controller = false;
        }

        $this->prepareContent();
        $this->runViewer();
    }

    private function prepareContent() {
        $this->content['module'] = $this->module;
        $this->content['module_name'] = $this->module_name;
    }

    private function runViewer() {
        /*
        $modulePath = $this->dirroot . '/themes/' . $this->theme . '/templates/' . $this->mod . '/' . $this->module;
        if (!empty($this->content))
            extract($this->content);

        // print the vwr
        ob_start();
        require_once $modulePath . '/index.php';
        $templateContent = ob_get_clean();

        if ($this->hasController && $this->module->useCustomLayout())
            require_once $modulePath . '/layout.php';

        else
            require_once $this->dirroot . '/themes/' . $this->theme . '/templates/layout.php';
            */
    }
}
