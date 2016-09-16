<?php

class Session extends Model {
    protected $__model = [
        'hash' => [ Model::TYPE_STRING, 'min: 128', 'max: 128'],
        'last_use' => [ Model::TYPE_DATE, 'null' ],
        'lang' => [ Model::TYPE_STRING, 'max: 5']
    ];

    function __construct($db) {
        if (class_exists('User') && is_subclass_of('User', 'Model'))
            $this->__model['user'] = [ Model::TYPE_FOREIGNKEY, 'user', 'null' ];

        parent::__construct($db);
	}

	function startSession($lang = null) {
		session_start();

        $this->hash = session_id();
        $this->user = null;

		if ($lang && array_key_exists($lang, Config::$languages))
			$this->lang = $lang;

		$this->createSessionData();
	}

	function createSessionData() {
        try {
            $this->find('hash = ' . $this->__db->quote($this->hash));
            $this->last_use = $this->__db->now();
            $this->merge('hash = ' . $this->__db->quote($this->hash));

        } catch (Exception $e) {
            if (!$this->lang)
                $this->lang = Config::$defaultLanguage;

            $this->insert();
        } finally {
    		    $this->sessionExists = true;
        }
	}

	function closeSession() {
        try {
            $this->delete();
        } catch(Exception $e) {
            return;
        }
	}

    function getSessionLang() {
        return $this->lang;
    }
}
