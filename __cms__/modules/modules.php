<?php

class Modules {
	static $liste_modules = array();
	
	public static function enregister_module($classe, $type) {
		self::$liste_modules[$type] = $classe;
	}
	
	public static function get_module($chemin) {
		$type = Stockage::get_prop($chemin, "type");
		if ($type === false) return false;
		return self::$liste_modules[$type];
	}
	
	public static function get_liste_paramètres($chemin) {
		$module = self::get_module($chemin);
		if ($module === false) return false;
		// TODO
		return call_user_func(array($module, "get_liste_paramètres"));;
	}
}

?>