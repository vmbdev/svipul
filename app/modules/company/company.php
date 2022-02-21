<?php

class Company extends Model {
	protected $__model = [
		'cif' => [ Model::TYPE_STRING, 'max: 14', 'null' ],
		'address' => [ Model::TYPE_STRING, 'null' ],
		'user' => [ Model::TYPE_FOREIGNKEY, 'user', 'null' ]
	];
}
