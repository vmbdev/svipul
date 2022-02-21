<?php

class ResManager {
	protected static $session, $db;

	public static function getSession() {
		return self::$session;
	}

	public static function setSession($session) {
		if (!empty(self::$session)) {
			if ($session != self::$session)
				return false;
			else
				return true;
		}

		self::$session = $session;
		return true;
	}

	public static function getDatabase() {
		return self::$db;
	}

	public static function setDatabase($db) {
		if (!empty(self::$db)) {
			if ($db != self::$db)
				return false;
			else
				return true;
		}

		self::$db = $db;
		return true;
	}
}
