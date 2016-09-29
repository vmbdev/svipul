<?php

class User extends Model {
    protected $__model = [
        'email' => [ Model::TYPE_STRING ],
        'name' => [ Model::TYPE_STRING ],
        'surname' => [ Model::TYPE_STRING ],
        'password' => [ Model::TYPE_STRING, 'max: 128' ],
        'type' => [ Model::TYPE_INT, 'min: 0', 'max: 1' ]
    ];
}
