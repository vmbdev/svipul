<?php

class News extends Model {
    protected $__model = [
        'number' => [ Model::TYPE_FLOAT, 'min:30', 'max:250' ],
        'title' => [ Model::TYPE_STRING, 'min: 5', 'max: 40'],
        'date' => [ Model::TYPE_DATE, 'null'],
        'author' => [ Model::TYPE_FOREIGNKEY, 'user' ]
    ];
}
