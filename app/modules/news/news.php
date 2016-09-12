<?php

class News extends Module {
    public function __construct($db, $uri = null) {
        parent::__construct($db, $uri);

        $this->router->addRoute('get', 'getf', 'getv');
    }
    public function run() {
        dope("hei");
        $this->model->setProp('number', 35);
        dope($this->model->getProp('number'));
        //$this->model->insert();
        $u = $this->model->findById(15);
        dope($this->model->getId());
        //$this->model->delete(17);

    }

    public function getf() {
        dope("huihui");
    }
}
