<?php

  /****************************************
   * La configuration n'est pas ici,      *
   * elle est dans le fichier config.php5 *
   ****************************************/

class Config {
	private static $config = array();
	
	public static function get($nom) {
		if (!isset(self::$config[$nom])) {
			// Utilisation de die() plutôt que de Debug("erreur") car Debug n'est peut-être pas encore chargé.
			echo "Variable de configuration manquante : $nom";
			die();
		}
		return self::$config[$nom];
	}
	
	public static function set($nom, $valeur) {
		self::$config[$nom] = $valeur;
	}
}

require_once(dirname(__FILE__) . "/util.php5"); // class Path.
require_once(dirname(__FILE__) . "/../config.php5");

if (Config::get('courriel_admin') === null) {
	echo "Vous devez indiquer le courriel de l'administrateur dans le fichier config.php5 .";
	// Utilisation de die() plutôt que de Debug("erreur") car Debug n'est peut-être pas encore chargé.
	die();
}


?>