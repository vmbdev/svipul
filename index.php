<?php

function dope($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

ini_set('display_errors', 1);
ini_set('short_open_tag', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.hash_function', 'sha512');
ini_set('session.session.hash_bits_per_character', 5);
header('Content-Type: text/html; charset=UTF-8');

require_once 'config.php';
require_once 'lib/filesystem.php';
require_once 'lib/database.php';
require_once 'lib/dbdrivers/mysql.php';
require_once 'lib/router.php';
require_once 'lib/module.php';
require_once 'lib/model.php';
require_once 'lib/session.php';
require_once 'lib/maincontroller.php';

// db init
$db = new MySQLDatabase(Config::$dbhost, Config::$dbname, Config::$dbuser, Config::$dbpasswd);

// grab lang from url (if present)
// i.e. http://localhost/en_GB/news/whatever
// lang = en_GB
// rest = /news/whatever
$url_lang_exp = '#^/(?P<lang>[a-z]{2}_[A-Z]{2})(?P<rest>/.*)#';
preg_match($url_lang_exp, $_SERVER['REQUEST_URI'], $result);
$url_lang = (!empty($result['lang']))?$result['lang']:null;
$url_rest = (!empty($result['rest']))?$result['rest']:$_SERVER['REQUEST_URI'];

// session init
new Session($db);
Session::startSession($url_lang);

$lang = Session::getSessionLang();

putenv("LANG=$lang");
setlocale(LC_ALL, $lang . '.utf8');
bindtextdomain('messages', Config::$dirroot . "/app/lang/");
bind_textdomain_codeset('messages', 'UTF-8');
textdomain('messages');

$maincontroller = new MainController($db, $url_rest);
$maincontroller->run();
