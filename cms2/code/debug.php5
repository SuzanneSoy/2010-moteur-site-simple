<?php

class Debug {
	public static $types_erreur = array(
		"erreur"     => '<span style="color:red">Erreur</span>',
		"niy"        => '<span style="color:brown">Pas encore implémenté</span>',
		"info"       => '<span style="color:blue">Info</span>',
		"sql"        => 'Requête SQL',
		"erreur_sql" => 'Erreur SQL',
		"permission" => '<span style="color:red">Permission non accordée</span>'
	);
	public static $filtre_erreurs = array(
		"erreur"     => true,
		"niy"        => true,
		"info"       => true,
		"sql"        => false,
		"erreur_sql" => true,
		"permission" => true
	);
	public static $toutes_erreurs = false; // true <=> ignorer le filtre.
	public static $erreurs = array();
	
	public static function __callStatic($nom, $args) {
		if (!array_key_exists($nom, self::$types_erreur)) {
			self::erreur("Type d'erreur inconnu : " . $nom . "\nArguments de Debug::$nom() : " . var_export($args, true));
		} elseif (count($args) != 1) {
			self::erreur("Mauvais nombre d'arguments pour Debug::$nom().\nArguments : " . var_export($args, true));
		} else {
			self::push($nom, $args[0]);
		}
	}
	public static function push($cat, $msg) {
		array_push(self::$erreurs, array($cat, $msg));
	}
	public static function erreur($msg) {
		self::push("erreur", $msg);
		self::die_btrace();
	}
	public static function erreur_sql() {
		self::push("erreur_sql", mysql_error());
		self::die_btrace();
	}
	public static function die_btrace() {
		echo self::afficher(true, true, false);
		echo '<div style="margin:1em 0 0.5em;background-color: #ffbf80;border-top:thin solid red;border-bottom:thin solid red;">Backtrace</div>';
		debug_print_backtrace();
		echo self::afficher(false, false, true);
		die();
	}
	public static function afficher($start = true, $print = true, $end = true) {
		$ret = "";
		if ($start) {
			$ret .= '<pre style="padding-bottom:0.3em;border:thin solid red;">';
			$ret .= '<div style="margin-bottom:0.5em;background-color: pink;border-bottom:thin solid red;">Erreurs</div>';
		}
		if ($print) {
			foreach (self::$erreurs as $e) {
				if (self::$toutes_erreurs === true || self::$filtre_erreurs[$e[0]] === true) {
					$ret .= self::$types_erreur[$e[0]] . " : " . $e[1] . "\n";
				}
			}
		}
		if ($end) {
			$ret .= "</pre>";
		}
		return $ret;
	}
}

function niy($name) {
	Debug::niy($name);
}

?>