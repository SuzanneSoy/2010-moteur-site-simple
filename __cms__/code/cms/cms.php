<?php

error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1); // Ne s'appliquera pas au fichier courant !

require_once(dirname(__FILE__) . "/configuration.php");
require_once(dirname(__FILE__) . "/include_tous.php");

// It's not a bug, its a feature
if (get_magic_quotes_runtime()) set_magic_quotes_runtime(false);

class CMS {
    public static function page($chemin_str) {
		// TODO : appeller Modules::action($chemin, $action, $paramètres);
		
		$chemin = new Chemin($chemin_str);
		$module = Modules::get_module($chemin);
                
		$paramètres = array("action" => "vue");
		
		foreach ($module["get_post"] as $param) {
			if (isset($_GET[$param])) $paramètres[$param] = self::param_get($param);
			if (isset($_POST[$param])) $paramètres[$param] = self::param_post($param);
		}
		foreach ($module["post"] as $param) {
			if (isset($_POST[$param])) $paramètres[$param] = self::param_post($param);
		}
		foreach ($module["file"] as $param) {
			if (isset($_FILES[$param])) $paramètres[$param] = $_FILES[$param];
		}
        
		$action = $paramètres["action"];
		$ret = Modules::action($chemin, $action, $paramètres);
		
		if (!Page::is_page($ret)) {
			Erreur::fatale("Le module de " . $chemin->get() . " n'a pas renvoyé une page mais à la place : " . var_export($ret, true));
		} else {
			$ret->envoyer();
		}
    }
	
	// Not even beneath my contempt...
	public static function param_get($param) {
		return get_magic_quotes_gpc() ? stripslashes($_GET[$param]) : $_GET[$param];
	}
	
	public static function param_post($param) {
		return get_magic_quotes_gpc() ? stripslashes($_POST[$param]) : $_POST[$param];
	}
}

?>