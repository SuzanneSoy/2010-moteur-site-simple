<?php

class GalerieIndex {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "nouvelle_page") {
			$np = Stockage::nouvelle_page($chemin, "Nouvelle période", "galerie-periode");
			Stockage::set_prop($np, "proprietaire", Authentification::get_utilisateur());
			return new Page($np, '', "redirect");
		} else {
			if (isset($paramètres["titre"])) {
				Stockage::set_prop($chemin, "titre", $paramètres["titre"]);
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
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form class="galerie infos" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<h2><input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" /></h2>';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "description");
				$ret .= '<br />';
				$ret .= '<input type="submit" value="appliquer" />';
				$ret .= '</form>';
			} else {
				$ret .= '<h2>' . Stockage::get_prop($chemin, "titre") . '</h2>';
				$ret .= '<p class="galerie index description affichage">' . Stockage::get_prop($chemin, "description") . '</p>';
			}
			
			$ret .= '<div class="galerie photos index">';
			$ret .= '<ul>';
			foreach (Stockage::liste_enfants($chemin) as $k) {
				$mini = Modules::vue($k, 'miniature');
				$ret .= '<li>';
				$ret .= '<a href="' . $k->get_url() . '">'; // TODO : escape l'url !
				$ret .= '<div class="miniature">';
 				$ret .= $mini->contenu; // TODO : escape l'url !
				$ret .= '</div>';
				$ret .= '<div class="titre">';
				$ret .= $mini->titre;
				$ret .= '</div>';
				$ret .= '</a>';
				$ret .= '</li>';
			}
			
			if (Permissions::vérifier_permission($chemin, "nouvelle_page", Authentification::get_utilisateur())) {
				$ret .= '<li>';
				$ret .= '<div class="miniature">';
				$ret .= '<img src="' . $chemin->get_url("?vue=image_nouvelle_periode") . '" />';
				$ret .= '</div>';
				$ret .= '<div class="titre">';
				
				$ret .= '<form class="galerie nouvelle_page" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouvelle période"/>';
				$ret .= '</form>';
				
				$ret .= '</div>';
				$ret .= '</li>';
			}
			
			$ret .= '</ul>';
			$ret .= '<div class="clearboth"></div>';
			$ret .= '</div>';
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} else if ($vue == "image_nouvelle_periode") {
			// Houlàlà ça sent le hack pas beau !
			return new Page(Path::combine(Config::get("chemin_base"), "/code/site/nouvelle_image.jpg"), null, "sendfile");
		}
	}
}

Modules::enregister_module("GalerieIndex", "galerie-index", "vue", "titre description");

?>