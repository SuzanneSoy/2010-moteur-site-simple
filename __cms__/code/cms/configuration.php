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

?>