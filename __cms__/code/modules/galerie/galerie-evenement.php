<?php

class GalerieÉvènement {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "nouvelle_page") {
			$np = Stockage::nouvelle_page($chemin, "Nouvelle photo", "galerie-photo");
			Stockage::set_prop($np, "proprietaire", Authentification::get_utilisateur());
			Stockage::set_prop($np, "titre", "Nouvelle photo");
			Stockage::set_prop($np, "description", "");
			enregistrer_nouveaute($np);
			return new Page($np, '', "redirect");
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin, true); // TODO ! gérer correctement le récursif
			return new Page($chemin->parent(), '', "redirect");
		} else {
			if (isset($paramètres["description"])) {
				Stockage::set_prop($chemin, "description", $paramètres["description"]);
			}
			
			// titre après les autres paramètres car il peut générer un redirect.
			if (isset($paramètres["titre"]) && Stockage::prop_diff($chemin, "titre", $paramètres["titre"])) {
				Stockage::set_prop($chemin, "titre", $paramètres["titre"]);
				Stockage::renomer($chemin, $paramètres["titre"]);
				$chemin = $chemin->renomer($paramètres["titre"]);
				// TODO : transmettre le paramètre "vue"
				return new Page($chemin, '', "redirect");
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
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= '<h2 class="galerie evenement titre affichage">' . Stockage::get_prop($chemin, "titre") . '</h2>';
				$ret .= '<p class="galerie evenement description affichage">' . Stockage::get_prop($chemin, "description") . '</p>';
			}
			
			$ret .= '<div class="galerie photos evenement">';
			$ret .= '<ul>';
			foreach (Stockage::liste_enfants($chemin) as $k) {
				$mini = Modules::vue($k, 'miniature');
				$ret .= '<li>';
				$ret .= '<a href="' . $k->get_url() . '">'; // TODO : escape l'url !
				$ret .= '<span class="miniature">';
 				$ret .= $mini->contenu; // TODO : escape l'url !
				$ret .= '</span>';
				$ret .= '<span class="titre">';
				$ret .= $mini->titre;
				$ret .= '</span>';
				$ret .= '</a>';
				$ret .= '</li>';
			}
			
			if (Permissions::vérifier_permission($chemin, "nouvelle_page", Authentification::get_utilisateur())) {
				$ret .= '<li>';
				$ret .= '<div class="miniature">';
				$ret .= '<img alt="nouvelle photo" src="' . $chemin->get_url("?vue=image_nouvelle_photo") . '" />';
				$ret .= '</div>';
				$ret .= '<div class="titre">';
				
				$ret .= '<form class="galerie nouvelle_page" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<p>';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouvelle photo"/>';
				$ret .= '</p>';
				$ret .= '</form>';
				
				$ret .= '</div>';
				$ret .= '</li>';
			}
			
			$ret .= '</ul>';
			$ret .= '<div class="clearboth"></div>';
			$ret .= '</div>';
			
			if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="supprimer"/>';
				$ret .= '<input type="submit" value="Supprimer l\'évènement"/>';
				$ret .= '</form>';
			}
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} else if ($vue == "miniature") {
			$ret = "Aucune<br/>photo";
			
			$enfants = Stockage::liste_enfants($chemin);
			if (isset($enfants[0])) $ret = Modules::vue($enfants[0], 'miniature')->contenu;
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} else if ($vue == "image_nouvelle_photo") {
			// Houlàlà ça sent le hack pas beau !
			return new Page(Path::combine(Config::get("chemin_base"), "/code/site/nouvelle_photo.jpg"), null, "sendfile");
		}
	}
}

Modules::enregister_module("GalerieÉvènement", "galerie-evenement", "vue", "titre description");

?>