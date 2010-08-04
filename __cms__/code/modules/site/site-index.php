<?php

class SiteIndex {
	public static function action($chemin, $action, $paramètres) {
		if (isset($paramètres["nom_site"])) {
			Stockage::set_prop($chemin, "nom_site", $paramètres["nom_site"]);
		}
		
		if (isset($paramètres["vue"])) {
			return self::vue($chemin, $paramètres["vue"]);
		} else {
			return self::vue($chemin);
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			$ret .= "<h1>" . Stockage::get_prop($chemin, "nom_site") . "</h1>";
			$ret .= "<ul>";
			$ret .= "<li><a href=\"" . $chemin->enfant("galerie")->get_url() . "\">Galerie</a>";
			$ret .= "</ul>";
			return new Page($ret, Stockage::get_prop($chemin, "nom_site"));
		} else if ($vue == "css") {
			return new Page(get_css(), "text/css", "raw");
		}
		return new Page('',''); // TODO : devrait renvoyer une page d'erreur !
	}
}

Modules::enregister_module("SiteIndex", "site-index", "vue", "titre");

?>