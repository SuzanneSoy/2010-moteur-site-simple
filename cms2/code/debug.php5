<?php

// TODO : sécurité : permettre d'avoir des modèles pour les erreurs, et remplir des champs dedans, en échappant les méchants caractères etc.
// TODO : sécurité : ne pas faire de backtrace en production !

class _Debug {
	public static $types_erreur = array(
		"erreur"      => '<span style="font-weight:bold;color:red;">Erreur</span>',
		"warn"        => '<span style="font-weight:bold;color:#ef6f00;">Attention</span>',
		"info"        => '<span style="color:blue;">Info</span>',
		"utilisateur" => '<span style="font-weight:bold;color:red;">Erreur</span>',
		"niy"         => '<span style="color:brown;">Pas encore implémenté</span>',
		"sql"         => 'Requête SQL',
		"erreur_sql"  => '<span style="font-weight:bold;color:red;">Erreur SQL</span>',
		"permission"  => '<span style="font-weight:bold;color:red;">Permission non accordée</span>'
	);
	public static $filtre_erreurs = array(
		"erreur"      => true,
		"warn"        => true,
		"info"        => true,
		"niy"         => true,
		"sql"         => false,
		"erreur_sql"  => true,
		"utilisateur" => true,
		"permission"  => true
	);
	public static $filtre_erreurs_en_production = array(
		"erreur"      => false,
		"warn"        => false,
		"info"        => false,
		"niy"         => false,
		"sql"         => false,
		"erreur_sql"  => false,
		"utilisateur" => true, // erreur générée par des données de l'utilisateur.
		"permission"  => true  // permission non accordée.
	);
	
	public static $toutes_erreurs = false; // true <=> ignorer le filtre.
	public static $erreurs = array();
	
	public function __call($nom, $args) {
		if (!array_key_exists($nom, self::$types_erreur)) {
			self::erreur("Type d'erreur inconnu : " . $nom . "\nArguments de Debug->$nom : " . var_export($args, true));
		} elseif (count($args) != 1) {
			self::erreur("Mauvais nombre d'arguments pour Debug->$nom.\nArguments : " . var_export($args, true));
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
				if (self::$toutes_erreurs === true
					|| (array_key_exists($e[0], self::$filtre_erreurs)
						&& self::$filtre_erreurs[$e[0]] === true)) {
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

function Debug($fn) {
	$args = func_get_args();
	array_shift($args);
	$d = new _Debug();
	call_user_func_array(array($d, $fn), $args);
}

function niy($name) {
	Debug("niy", $name);
}

?>