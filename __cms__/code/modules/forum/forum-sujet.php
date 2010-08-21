<?php

class ForumSujet {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "nouvelle_page") {
			// SECURITE : On ne doit PAS pouvoir modifier dernier_numero arbitrairement
			$numéro_message = 1 + Stockage::get_prop($chemin, "dernier_numero");
			Stockage::set_prop($chemin, "dernier_numero", $numéro_message);
			$np = Stockage::nouvelle_page($chemin, "" . $numéro_message, "forum-message");
			Stockage::set_prop($np, "proprietaire", Authentification::get_utilisateur());
			Stockage::set_prop($np, "message", "");
			
			return new Page($chemin, "#message" . $numéro_message, "redirect");
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin, true); // TODO ! gérer correctement le récursif
			return new Page($chemin->parent(), '', "redirect");
		} else {
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
				$ret .= '<form class="forum sujet infos" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<h2><input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" /></h2>';
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= '<h2 class="forum sujet titre affichage">' . Stockage::get_prop($chemin, "titre") . '</h2>';
			}
			if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="supprimer"/>';
				$ret .= '<input type="submit" value="Supprimer le sujet"/>';
				$ret .= '</form>';
			}
	        $ret .= '<ul class="forum sujet">';
			
	        foreach (stockage::liste_enfants($chemin) as $k) {
	            $ret .= '<li>' . Modules::vue($k)->contenu . '</li>';
	        }
			
			if (Permissions::vérifier_permission($chemin, "nouvelle_page", Authentification::get_utilisateur())) {
				$ret .= '<li>';
				$ret .= '<form class="forum sujet nouvelle_page" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<p>';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouveau message"/>';
				$ret .= '</p>';
				$ret .= '</form>';
				$ret .= '</li>';
			}
			
	        $ret .= '</ul>';
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} else if ($vue == "miniature") {
			return new Page("Sujet.", Stockage::get_prop($chemin, "titre"));
		}
	}
}

Modules::enregister_module("ForumSujet", "forum-sujet", "vue", "titre");

?>