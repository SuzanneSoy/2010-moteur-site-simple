<?php

class AdminIndex {
	public static function action($chemin, $action, $paramètres) {
		if (isset($paramètres["vue"])) {
			return self::vue($chemin, $paramètres["vue"]);
		} else {
			return self::vue($chemin);
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			$ret .= '<h2>Administration</h2>';
			$ret .= '<ul>';
			foreach (Stockage::liste_enfants($chemin) as $k) {
				if (Stockage::get_prop($k, "inclure_administration") == "oui") {
					$ret .= '<li>';
					$ret .= '<a href="' . $k->get_url() . '">'; // TODO : escape l'url !
					$ret .= Stockage::get_prop($k, "titre");
					$ret .= '</a>';
					$ret .= '</li>';
				}
			}
			$ret .= '</ul>';
			return new Page($chemin, $ret, Stockage::get_prop($chemin, "nom_site"));
		}
		return new Page($chemin, '',''); // TODO : devrait renvoyer une page d'erreur !
	}
}

Modules::enregister_module("AdminIndex", "admin-index", "vue");

?>