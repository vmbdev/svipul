<?php

class NewsModel extends Model {
    protected $__model = [
        'number' => [ Model::TYPE_FLOAT, 'min:30', 'max:250' ],
        'title' => [ Model::TYPE_STRING, 'min: 20', 'max: 40'],
        'date' => [ Model::TYPE_DATE, 'null']
    ];
}
