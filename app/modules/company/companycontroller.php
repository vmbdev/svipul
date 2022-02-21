<?php

class CompanyController extends Module {
	public function __construct($db, $uri = null) {
		parent::__construct($db, $uri);

		$this->router->addRoute('edit', 'edit', 'edit');
		$this->router->addRoute('list', 'list', 'list');
		$this->router->addRoute('show', 'show', 'show');
	}

	public function edit() {
		if ($this->session->isLogged()) {
			try {
				$currentuser = $this->session->getProp('user');
				$this->model->findByParams(['user' => $currentuser->getId()]);
			} catch (Exception $e) {
				$this->addError(0, 'The current user is not a company');

				return;
			}

			try {
				if (!empty($_POST)) {
					$this->model->setProp('cif', $_POST['cif']);
					$this->model->setProp('address', $_POST['address']);

					$this->model->merge();
				}
			} catch (Exception $e) {
				$this->addError(1, 'The information introduced is not correct.');
			}
		}
	}

	public function list() {
		if ($this->session->isLogged() && ($this->session->getProp('user')->getProp('type') == 1)) {
			$cond = array();

			if (!empty($_POST['years_experience']))
				$cond['years_experience'] = $_POST['years_experience'];

			if (!empty($_POST['studies']))
				$cond['studies'] = $_POST['studies'];

			$cond['updated'] = 1;

			try {
				$this->content['list'] = Jobseeker::findAllByParams($cond);
			} catch (Exception $e) {
				$this->addError(0, 'No data found');
			}
		}

		else
			$this->addError(3, 'Insufficient permissions');
	}

	public function show() {
		if ($this->session->isLogged() && ($this->session->getProp('user')->getProp('type') == 1)) {
			$tosee = $this->getParam('profile');

			if (is_numeric($tosee)) {
				try {
					$jobseeker = new Jobseeker();
					$jobseeker->findById($tosee);
					$this->content['jobseeker'] = $jobseeker;
				} catch (Exception $e) {
					$this->addError(2, 'Profile not found');
				}
			}
		}
		else
			$this->addError(3, 'Insufficient permissions');
	}
}
