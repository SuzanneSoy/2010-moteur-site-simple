<?php

require_once(dirname(__FILE__) . "/config.php");
require_once(dirname(__FILE__) . "/include.php");

class CMS {
    public static function cms($chemin_str) {
		// TODO : appeller Modules::action($chemin, $action, $paramètres);
		
		$chemin = new Chemin($chemin_str);
		$noms_params = Modules::liste_paramètres();
		$noms_params[] = "action";
		// récupérer $noms_params dans $_GET, $_POST et $_FILE
		$action = $paramètres["action"];
		Modules::action($chemin, $action, $paramètres);
    }
}
?>
