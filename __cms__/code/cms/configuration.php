<?php

  /***************************************
   * La configuration n'est pas ici,     *
   * elle est dans le fichier config.php *
   ***************************************/

class Config {
	static $config = array();
	
	public static function get($nom) {
		if (!isset(self::$config[$nom])) return null;
		return self::$config[$nom];
	}
	
	public static function set($nom, $valeur) {
		self::$config[$nom] = $valeur;
	}
}

require_once(dirname(__FILE__) . "/../chemin/path.php");
require_once(dirname(__FILE__) . "/../../config.php");

if (Config::get('courriel_admin') === null) {
	echo "Vous devez indiquer le courriel de l'administrateur dans le fichier config.php.";
	exit;
}


?>