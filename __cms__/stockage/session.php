<?php

class Session {
	public function démarrer() {
		return session_id !== "" || session_start();
	}
	
	public function put($k, $v) {
		self::démarrer();
		$_SESSION[$k] = $v;
	}
	
	public function get($k) {
		self::démarrer();
		return is_set($_SESSION[$k]) ? $_SESSION[$k] : false;
	}
	
	public function effacer($k) {
		self::démarrer();
		unset($_SESSION[$k]);
	}
}

?>