<?php

require_once(dirname(__FILE__) . "/config.php");
require_once(dirname(__FILE__) . "/include.php");

class CMS {
    public static function cms() {
		// TODO : appeller Modules::action($chemin, $action, $paramètres);
		
		$chemin = Chemin::depuis_url($_SERVER["request_uri"]); // TODO : vérifier nom variables etc.
		$noms_params = Modules::liste_paramètres();
		$noms_params[] = "action";
		// récupérer $noms_params dans $_GET, $_POST et $_FILE
		$action = $paramètres["action"];
		Modules::action($chemin, $action, $paramètres);
    }
}
?>
