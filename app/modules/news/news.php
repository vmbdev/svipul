<?php

class News extends Module {
    public function run() {
        dope("hei");
        $this->router->addRoute('get', 'getf', 'getv');
        $this->model->setProp('number', 35);
        dope($this->model->getProp('number'));
        //$this->model->insert();
        $u = $this->model->findById(15);
        dope($this->model->getId());
        //$this->model->delete(17);

    }
}
