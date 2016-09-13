<?php

class NewsController extends Module {
    public function __construct($db, $uri = null) {
        parent::__construct($db, $uri);

        $this->router->addRoute('get', 'getf', 'getv');
    }
    public function run() {
        dope("hei");
        $this->model->setProp('number', 250);
        $this->model->setProp('title', "test 4");
        $this->model->setProp('date', '01/20/2020 12:13:14');
        $this->model->getProp('author')->setProp('name', 'boboob');
        $this->model->getProp('author')->setProp('surname', 'llalala');

        //dope($this->model);
        $this->model->insert();

    }

    public function getf() {
        dope("huihui");
    }
}
