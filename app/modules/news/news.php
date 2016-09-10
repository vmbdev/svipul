<?php

class News extends Module {
    protected $model = [
        'id' => [ Model::TYPE_INT, 'pk' ],
        'number' => [ Model::TYPE_FLOAT, 'min:30', 'max:250' ],
        'title' => [ Model::TYPE_STRING, 'null', 'min: 20', 'max: 40'],
        'author' => [ Model::TYPE_STRING, 'fk: user'],
        'article' => [ Model::TYPE_TEXT ],
        'date' => [ Model::TYPE_DATE, 'null']
    ];

    public function run() {
        dope("hei");
        $this->router->addRoute('get', 'getf', 'getv');
        $this->model->setProp('number', 35);
        dope($this->model->getProp('number'));
        $this->model->findById('a');

    }
}
