<?php

class Dispatcher {
	private $db, $module_name, $module, $uri_rest;
	private $content; // vwr

	function __construct($db = null, $uri) {
		if (!$db)
			$this->__db = ResManager::getDatabase();

		else
			$this->__db = $db;

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

					if ($function && method_exists($this->module, $function))
						$this->module->$function();

					$this->content = $this->module->getContent();
				}
				else // just as a security measure
					$this->module = null;
			}
		}

		$this->prepareContent();
		$this->runViewer();
	}

	private function prepareContent() {
		$this->content['controller'] = $this->module;
		$this->content['module_name'] = $this->module_name;
	}

	private function runViewer() {
		if ($this->module && is_subclass_of($this->module, 'Module')) {
			$view = $this->module->getView();
			$customlayout = $this->module->getLayout();
		}
		else
			$view = FileSystem::getModuleView($this->module_name, 'default');

		// module has custom layout ?
		if (!empty($customlayout))
			$layout = $customlayout;
		else if (FileSystem::getGlobalLayout())
			$layout = FileSystem::getGlobalLayout();
		else
			$layout = null;

		if (!empty($view)) {
			if (!empty($this->content))
				extract($this->content);

			ob_start();
			require_once($view);;
			$view_content = ob_get_clean();
		}

		if ($layout)
			require_once($layout);

	}
}
