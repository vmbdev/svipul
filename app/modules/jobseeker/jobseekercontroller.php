<?php

class JobseekerController extends Module {
    public function __construct($db, $uri = null) {
        parent::__construct($db, $uri);

    }

    public function run() {
        try {
            $currentuser = ResManager::getSession()->getProp('user');
            $this->model->findByParams(['user' => $currentuser->getId()]);
        } catch (Exception $e) {
            $this->addError(0, 'The current user is not a jobseeker');
        }
    }
}
