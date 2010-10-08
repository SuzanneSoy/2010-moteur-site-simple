<?php

class SiteIndex {
	public static function action($chemin, $action, $paramètres) {
		if (isset($paramètres["nom_site"])) {
			Stockage::set_prop($chemin, "nom_site", $paramètres["nom_site"]);
		}
		
		if (isset($paramètres["prochain_evenement"])) {
			Stockage::set_prop($chemin, "prochain_evenement", $paramètres["prochain_evenement"]);
		}
		
		if (isset($paramètres["description"])) {
			Stockage::set_prop($chemin, "description", $paramètres["description"]);
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
			
			// $ret .= '<div class="prochain-evenement">';
			// $ret .= '<h2>Prochain évènement</h2>';
			// if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
			// 	$ret .= '<form method="post" action="' . $chemin->get_url() . '">';
			// 	$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "prochain_evenement"), "prochain_evenement");
			// 	$ret .= '<p><input type="submit" value="appliquer" /></p>';
			// 	$ret .= '</form>';
			// } else {
			// 	$ret .= Stockage::get_prop($chemin, "prochain_evenement");
			// }
			// $ret .= '</div>';
			
			// $ret .= '<div class="logo-site">';
			// $ret .= '<img src="' . $chemin->get_url("logo.png") . '">';
			// $ret .= '</div>';
			
			$ret .= '<div class="description-site">';
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form class="nom_site infos" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<h2><input type="text" name="nom_site" value="' . Stockage::get_prop($chemin, "nom_site") . '" /></h2>';
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= "<h2>" . Stockage::get_prop($chemin, "nom_site") . "</h2>";
			}
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form method="post" action="' . $chemin->get_url() . '">';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "description");
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= Stockage::get_prop($chemin, "description");
			}
			$ret .= '</div>';
			return new Page($chemin, $ret, Stockage::get_prop($chemin, "nom_site"));
		} else if ($vue == "css") {
			return new Page($chemin, get_css(), "text/css", "raw");
		}
		return new Page($chemin, '',''); // TODO : devrait renvoyer une page d'erreur !
	}
}

Modules::enregister_module("SiteIndex", "site-index", "vue", "nom_site prochain_evenement description");

?>