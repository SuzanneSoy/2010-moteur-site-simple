<?php

class ÉquipesÉquipe {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "nouvelle_page") {
			// SECURITE : On ne doit PAS pouvoir modifier dernier_numero arbitrairement
			// CONCURENCE : Faire un lock quelque part...
			$numéro_joueur = 1 + Stockage::get_prop($chemin, "dernier_numero");
			Stockage::set_prop($chemin, "dernier_numero", $numéro_joueur);
			$np = Stockage::nouvelle_page($chemin, "Joueur" . $numéro_joueur, "equipes-joueur");
			Stockage::set_prop($np, "proprietaire", Authentification::get_utilisateur());
			Stockage::set_prop($np, "nom", "Dupondt");
			Stockage::set_prop($np, "prenom", "Jean");
			Stockage::set_prop($np, "description", "");
			enregistrer_nouveaute($np);
			
			return new Page($chemin, '', "redirect");
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
				$ret .= '<form class="équipes équipe infos" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<h2><input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" /></h2>';
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= '<h2 class="équipes équipe titre affichage">' . Stockage::get_prop($chemin, "titre") . '</h2>';
			}
			if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="supprimer"/>';
				$ret .= '<input type="submit" value="Supprimer l\'équipe"/>';
				$ret .= '</form>';
			}
	        $ret .= '<ul class="équipes équipe">';
			
	        foreach (stockage::liste_enfants($chemin) as $k) {
	            $ret .= '<li>' . Modules::vue($k)->contenu . '</li>';
	        }
			
			if (Permissions::vérifier_permission($chemin, "nouvelle_page", Authentification::get_utilisateur())) {
				$ret .= '<li>';
				$ret .= '<form class="équipes équipe nouvelle_page" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<p>';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouveau joueur"/>';
				$ret .= '</p>';
				$ret .= '</form>';
				$ret .= '</li>';
			}
			
	        $ret .= '</ul>';
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} else if ($vue == "miniature") {
			return new Page("Équipe.", Stockage::get_prop($chemin, "titre"));
		}
	}
}

Modules::enregister_module("ÉquipesÉquipe", "equipes-equipe", "vue", "titre");

?>