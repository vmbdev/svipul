<?php

class Session {
	private static $db;
	private static $lang = null;
	private static $isLogged = false;
	private static $sessionExists = false;
	private static $user;

	function __construct($db) {
		self::$db = $db;
	}

	function startSession($lang=null) {
		session_start();

		if ($lang && array_key_exists($lang, Config::$languages))
			self::$lang = $lang;

		self::createSessionData();
	}

	function createSessionData() {
		if (isset($_GET['lang']) && array_key_exists($_GET['lang'], Config::$languages))
			self::$lang = $_GET['lang'];

		$q = 'SELECT lang FROM sessions WHERE id = ? LIMIT 1';
		$session = self::$db->query($q, array(session_id()));

		if (empty($session)) {
			if (!self::$lang)
				self::$lang = Config::$defaultLanguage;

			$q = 'INSERT INTO sessions (id, lang) VALUES (?, ?)';
			self::$db->query($q, array(session_id(), self::$lang));
		}

		else {
// TODO: plug the users
/*
            if ($session['user']) {
				$q = 'SELECT firstname, surname, email, type, birthday FROM users WHERE id = ? LIMIT 1';
				$usr = self::$db->query($q, array($session['user']));

				self::$user = new User($session['user'], $usr['email'], $usr['firstname'], $usr['surname'], $usr['type'], $usr['birthday']);
				self::$isLogged = true;
			}
*/
			if (!self::$lang)
				self::$lang = $session['lang'];

			$sql = 'UPDATE sessions SET last_use = NOW(), lang = ? WHERE id = ? LIMIT 1';
			self::$db->query($sql, array(self::$lang, session_id()));
		}

		self::$sessionExists = true;
	}

	function sessionExists() {
		return self::$sessionExists;
	}

	function getSessionLang() {
		$sql = 'SELECT lang FROM sessions WHERE id = ? LIMIT 1';
		$session = self::$db->query($sql, array(session_id()));

		return $session['lang'];
	}

	function getLang() {
		return self::$lang;
	}

	function isLogged() {
		return self::$isLogged;
	}

	function loginUser($id) {
		self::$user = $id;
		$sql = 'UPDATE sessions SET user = ? WHERE id = ? LIMIT 1';
		self::$db->query($sql, array($id, session_id()));
	}

	function closeSession() {
		if (self::sessionExists()) {
			$q = 'DELETE FROM sessions WHERE id = ?';
			self::$db->query($q, array(session_id()));
		}
	}
}
