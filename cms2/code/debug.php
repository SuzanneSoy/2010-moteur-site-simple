<?php

class Debug {
	public static $errors = array();
	public static function niy($msg) {
		array_push(self::$errors, "Not implemented yet : $msg");
	}
	public static function info($msg) {
		array_push(self::$errors, "Info : $msg");
	}
	public static function error($msg) {
		array_push(self::$errors, "Error : $msg");
		self::afficher(true, true, false);
		echo "\n";
		debug_print_backtrace();
		self::afficher(false, false, true);
		die();
	}
	public static function afficher($start = true, $print = true, $end = true) {
		if ($start) {
			echo "<pre>";
			echo '<span style="color:red">Erreurs:</span>' . "\n";
		}
		if ($print) {
			foreach (self::$errors as $e) {
				echo $e . "\n";
			}
		}
		if ($end) {
			echo '<span style="color:red">Fin erreurs.</span>' . "\n";
			echo "</pre>";
		}
	}
}

function niy($name) {
	Debug::niy($name);
}

?>