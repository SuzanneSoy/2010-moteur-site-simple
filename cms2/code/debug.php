<?php

class Debug {
	public static $errors = array();
	public static function niy($msg) {
		array_push(self::$errors, "Not implemented yet : $msg");
	}
	public static function afficher() {
		echo "<pre>";
		echo "Erreurs:\n";
		foreach (self::$errors as $e) {
			echo $e . "\n";
		}
		echo "</pre>";
	}
}

function niy($name) {
	Debug::niy($name);
}

?>