<?php

class Jobseeker extends Model {
    protected $__model = [
        'birthday' => [ Model::TYPE_DATE, 'null' ],
        'city' => [ Model::TYPE_STRING, 'null' ],
        'studies' => [ Model::TYPE_STRING, 'null' ],
        'years_experience' => [ Model::TYPE_INT, 'min:0', 'max:65', 'null' ],
        'updated' => [ Model::TYPE_INT, 'null' ],
        'language' => [ Model::TYPE_FOREIGNKEY, 'language', 'onetomany', 'null' ],
        'user' => [ Model::TYPE_FOREIGNKEY, 'user', 'null' ]
    ];
}
