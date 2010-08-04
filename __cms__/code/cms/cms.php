<?php

error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . "/configuration.php");
require_once(dirname(__FILE__) . "/include_tous.php");

class CMS {
    public static function page($chemin_str) {
		// TODO : appeller Modules::action($chemin, $action, $paramètres);
		
		$chemin = new Chemin($chemin_str);
		$module = Modules::get_module($chemin);
                
		$paramètres = array("action" => "vue");
		
		foreach ($module["get_post"] as $param) {
			if (isset($_GET[$param])) $paramètres[$param] = $_GET[$param];
			if (isset($_POST[$param])) $paramètres[$param] = $_POST[$param];
		}
		foreach ($module["post"] as $param) {
			if (isset($_POST[$param])) $paramètres[$param] = $_POST[$param];
		}
		foreach ($module["file"] as $param) {
			if (isset($_FILE[$param])) $paramètres[$param] = $_FILE[$param];
		}
        
		$action = $paramètres["action"];
		$ret = Modules::action($chemin, $action, $paramètres);
		
		$ret->envoyer();
    }
}

?>