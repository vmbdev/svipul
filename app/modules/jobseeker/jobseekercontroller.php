<?php

class JobseekerController extends Module {
    public function __construct($db, $uri = null) {
        parent::__construct($db, $uri);

        $this->router->addRoute('show', 'show', 'show');
        $this->router->addRoute('edit', 'edit', 'edit');
    }

    public function edit() {
        if ($this->session->isLogged()) {
            try {
                $currentuser = $this->session->getProp('user');
                $this->model->findByParams(['user' => $currentuser->getId()]);
            } catch (Exception $e) {
                $this->addError(0, 'The current user is not a jobseeker');

                return;
            }

            $toremove = $this->getParam('removelanguage');
            if (is_numeric($toremove)) {
                $lang = new Language();
                $lang->delete($toremove);
            }

            $toadd = $this->getParam('addlanguage');
            if (!empty($toadd)) {
                $lang = new Language();
                $lang->setProp('jobseeker', $this->model->getId());
                $lang->setProp('language', $toadd);
                $lang->insert();
            }

            try {
                $this->content['languages'] = Language::findAllByParams(['jobseeker' => $this->model->getId()]);
            } catch (Exception $e) {
                $this->content['languages'] = array();
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
