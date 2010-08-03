<?php

class Modules {
	static $liste_modules = array();
	
	public static function enregister_module($classe, $type, $get_post = array(), $post = array(), $file = array()) {
		self::$liste_modules[$type] = array(
			"classe" => $classe,
			"get_post" => qw($get_post),
			"post" => qw($post),
			"file" => qw($file)
		);
                self::$liste_modules[$type]["get_post"][] = "action"; // Toujours présent.
	}
	
	public static function get_module($chemin) {
		$type = Stockage::get_prop($chemin, "type");
		if ($type === false || !isset(self::$liste_modules[$type])) {
			Erreur::fatale("Type inconnu (" . $type . ") pour la page " . $chemin->get() . ".");
			// return false;
		}
		return self::$liste_modules[$type];
	}
	
	public static function action($chemin, $action, $paramètres) {
		$module = self::get_module($chemin);
		if ($module === false) return self::page(false, "Erreur");
		return call_user_func(array($module["classe"], "action"), $chemin, $action, $paramètres);
	}
	
	public static function vue($chemin, $vue = "normal") {
		$module = self::get_module($chemin);
		if ($module === false) return self::page(false, "Erreur");
		return call_user_func(array($module["classe"], "vue"), $chemin, $vue);
	}

	public static function page($contenu, $titre) {
		return (object)array("contenu" => $contenu, "titre" => $titre);
	}
}

?>