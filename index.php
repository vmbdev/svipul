<?php

ini_set('display_errors', 1);
ini_set('short_open_tag', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.hash_function', 'sha512');
ini_set('session.session.hash_bits_per_character', 5);
header('Content-Type: text/html; charset=UTF-8');

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

spl_autoload_register(function($class) {
    // load the class files when the class is instantiated
    // necessary allow models to load other models
    // first check if it's in the root directory, then in lib, dbdrivers
    // and finally check if it's a controller or a model
    if (file_exists('./' . $class . '.php'))
        include_once('./' . $class . '.php');

    else if (file_exists('lib/' . $class . '.php'))
        include_once('lib/' . $class . '.php');

    else if (endsWith($class, 'Driver')) {
        $classname = substr($class, 0, -strlen('Driver'));
        if (file_exists('lib/dbdrivers/' . $classname . '.php'))
            include_once('lib/dbdrivers/' . $classname . '.php');
    }

    else if (endsWith($class, 'Controller')) {
        $classname = substr($class, 0, -strlen('Controller'));
        if (FileSystem::getModuleController($classname))
            include_once(FileSystem::getModuleController($classname));
    }

    else if (FileSystem::getModuleModel($class))
        include_once(FileSystem::getModuleModel($class));

});


// grab lang from url (if present)
// i.e. http://localhost/en_GB/news/whatever
// lang = en_GB
// rest = /news/whatever
$url_lang_exp = '#^/(?P<lang>[a-z]{2}_[A-Z]{2})(?P<rest>/.*)#';
preg_match($url_lang_exp, $_SERVER['REQUEST_URI'], $result);
$url_lang = (!empty($result['lang'])) ? $result['lang'] : null;
$url_rest = (!empty($result['rest'])) ? $result['rest'] : $_SERVER['REQUEST_URI'];

// db init
$db = new MySQLDriver();
$db->connect(Config::$dbhost, Config::$dbname, Config::$dbuser, Config::$dbpasswd);

// session init
$session = new Session($db);
$session->startSession($url_lang);
$lang = $session->getLang();

ResManager::setSession($session);
ResManager::setDatabase($db);

putenv("LANG=$lang");
setlocale(LC_ALL, $lang . '.utf8');
bindtextdomain('messages', Config::$dirroot . "/app/lang/");
bind_textdomain_codeset('messages', 'UTF-8');
textdomain('messages');

$dispatcher = new Dispatcher($db, $url_rest);
$dispatcher->run();
