<?php

class Config {
	public static $dbhost = 'localhost';
	public static $dbname = 'svipul';
	public static $dbuser = 'root';
	public static $dbpasswd = '';
	public static $salt = '23rifjejg29028ggj2i45r';

	public static $languages = array(
                                        'en_GB' => array('flag' => 'gb', 'name' => 'English'),
                                        'cs_CZ' => array('flag' => 'cz', 'name' => 'Czech'),
										'de_DE' => array('flag' => 'de', 'name' => 'German'),
                                        'el_GR' => array('flag' => 'gr', 'name' => 'Greek'),
										'it_IT' => array('flag' => 'it', 'name' => 'Italian'),
										'pt_PT' => array('flag' => 'pt', 'name' => 'Portuguese'),
                                        'es_ES' => array('flag' => 'es', 'name' => 'Spanish')
                                    );

	public static $defaultLanguage = 'en_GB';
    public static $defaultModule = 'news';

	public static $wwwroot = '';
	public static $dirroot = 'c://svipul';

	static function generatePassword($password) {
		return hash('sha256', $password . hash('sha256', Config::$salt));
	}

}
