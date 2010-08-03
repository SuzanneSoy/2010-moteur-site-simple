<?php

class Session {
	public static function démarrer() {
		return (session_id() !== "") || session_start();
	}
	
	public static function put($k, $v) {
		self::démarrer();
		$_SESSION[$k] = $v;
	}
	
	public static function get($k) {
		self::démarrer();
		return isset($_SESSION[$k]) ? $_SESSION[$k] : false;
	}
	
	public static function effacer($k) {
		self::démarrer();
		unset($_SESSION[$k]);
	}
}

?>
