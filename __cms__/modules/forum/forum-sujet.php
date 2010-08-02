<?php

class ForumSujet {
	public function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return redirect($chemin);
		} else if ($action == "nouvelle_page") {
			// SECURITE : On ne doit PAS pouvoir modifier dernier_numero arbitrairement
			$numéro_message = 1 + Stockage::get_prop($chemin, "dernier_numero");
			Stockage::set_prop($chemin, "dernier_numero", $numéro_message);
			$np = Stockage::nouvelle_page($chemin, "" . $numéro_message, "forum-message");
			Stockage::set_prop($np, "proprietaire", get_utilisateur());
	
			return redirect($chemin, "#message" . $numéro_message);
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin);
			return redirect($chemin->parent());
		} else {
			if (is_set($paramètres["titre"])) {
				Stockage::renomer($chemin, $paramètres["titre"]);
				$chemin = $chemin->renomer($paramètres["titre"]);
				// TODO : peut-être redirect($chemin) ?
			}
			
			if (is_set($paramètres["vue"])) {
				self::vue($chemin, $paramètres["vue"]);
			} else {
				self::vue($chemin);
			}
		}
	}
	
	public function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
	        $ret = '';
			if (vérifier_permission($chemin, "set_prop", get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="text" name="titre" class="forum sujet titre edition" value="' . Stockage::get_prop($chemin, "titre") . '"/>';
				$ret .= '<input type="submit" value="renomer" />';
				$ret .= '</form>';
			} else {
				$ret .= '<h1 class="forum sujet titre affichage">' . get_prop($chemin, "titre") . '</h1>';
			}
			if (vérifier_permission($chemin, "supprimer", get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="supprimer"/>';
				$ret .= '<input type="submit" value="Supprimer"/>';
				$ret .= '</form>';
			}
	        $ret .= '<ul class="forum sujet">';
	        foreach (stockage::liste_enfants($chemin) as $k) {
	            $ret .= '<li>' . Modules::vue($k) . '</li>';
	        }
	        $ret .= '</ul>';
			if (vérifier_permission($chemin, "nouvelle_page", get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouvelle page"/>';
				$ret .= '</form>';
			}
			return $ret;
		} else if ($vue == "miniature") {
			return get_prop($chemin, "titre");
		}
	}
}

enregister_module("ForumSujet", "forum-sujet");

?>