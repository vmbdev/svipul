<?php

class JobseekerController extends Module {
    public function __construct($db, $uri = null) {
        parent::__construct($db, $uri);

        $this->router->addRoute('show', 'show', 'show');
        $this->router->addRoute('edit', 'edit', 'edit');
    }

    public function run() {
        try {
            $currentuser = ResManager::getSession()->getProp('user');
            $this->model->findByParams(['user' => $currentuser->getId()]);
            header('Location: edit/');
        } catch (Exception $e) {
            $this->addError(0, 'The current user is not a jobseeker');
        }
    }

    public function edit() {
        try {
            $currentuser = ResManager::getSession()->getProp('user');
            $this->model->findByParams(['user' => $currentuser->getId()]);
        } catch (Exception $e) {
            $this->addError(0, 'The current user is not a jobseeker');
        }

        try {
            if (!empty($_POST)) {
                $this->model->setProp('birthday', $_POST['birthday']);
                $this->model->setProp('city', $_POST['city']);
                $this->model->setProp('studies', $_POST['studies']);
                $this->model->setProp('years_experience', $_POST['years_experience']);
                $this->model->setProp('updated', 1);

                $this->model->merge();
            }
        } catch (Exception $e) {
            $this->addError(1, 'The information introduced is not correct.');
        }
    }

    public function show() {
        try {
            $c = ResManager::getSession()->isLogged();
            dope($c);
        } catch (Exception $e) {
            dope("hu");
        }
    }
}
