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
				Stockage::renomer($chemin, $paramètres["titre"]);
				$chemin = $chemin->renomer($paramètres["titre"]);
				// TODO : peut-être new Page($chemin, '', "redirect") ?
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
		$ret = '';
		if ($vue == "normal") {
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<h2><input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" /></h2>';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "message");
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
				$ret .= '</div>';
				$ret .= '<div class="titre">';
				
				$ret .= '<form class="galerie nouvelle_page" action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouvelle page"/>';
				$ret .= '</form>';
				
				$ret .= '</div>';
				$ret .= '</li>';
			}
			
			$ret .= '</ul>';
			$ret .= '<div class="clearboth"></div>';
			$ret .= '</div>';
		}
		return new Page($ret, Stockage::get_prop($chemin, "titre"));
	}
}

Modules::enregister_module("GalerieIndex", "galerie-index", "vue", "titre description");

?>