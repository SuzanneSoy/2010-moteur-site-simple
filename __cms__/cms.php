<?php

error_reporting(E_ALL); //  || E_STRICT

require_once(dirname(__FILE__) . "/config.php");
if (Config::get('courriel_admin') === null) {
	echo "Vous devez indiquer le courriel de l'administrateur dans le fichier config.php.";
	exit;
}

require_once(dirname(__FILE__) . "/include.php");

class CMS {
    public static function page($chemin_str) {
		// TODO : appeller Modules::action($chemin, $action, $paramètres);
		
		$chemin = new Chemin($chemin_str);
		$noms_params = Modules::get_liste_paramètres($chemin);
		$noms_params[] = "action";
		// récupérer $noms_params dans $_GET, $_POST et $_FILE
		$action = $paramètres["action"];
		Modules::action($chemin, $action, $paramètres);
    }
}

?>