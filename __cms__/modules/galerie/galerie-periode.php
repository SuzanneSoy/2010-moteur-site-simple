<?php

class GaleriePériode {
	public function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return redirect($chemin);
		} else if ($action == "nouvelle_page") {
			$np = Stockage::nouvelle_page($chemin, "Nouvel évènement", "galerie-periode");
			Stockage::set_prop($np, "proprietaire", get_utilisateur());
			return redirect($np);
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin);
			return redirect($chemin->parent());
		} else {
			if (is_set($paramètres["titre"])) {
				Stockage::renomer($chemin, $paramètres["titre"]);
				$chemin = $chemin->renomer($paramètres["titre"]);
				// TODO : peut-être redirect($chemin) ?
			}
			if (is_set($paramètres["description"])) {
				Stockage::set_prop($chemin, "description", $paramètres["description"]);
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
				$ret .= '<input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" />';
				$ret .= formulaire_édition_texte_enrichi(get_prop($chemin, "description"), "message");
			} else {
				$ret .= '<h1>' . get_prop($chemin, "titre") . '</h1>';
				$ret .= '<p class="galerie periode description affichage">' . get_prop($chemin, "description") . '</p>';
			}
			if (vérifier_permission($chemin, "nouvelle_page", get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouvelle page"/>';
				$ret .= '</form>';
			}
			if (vérifier_permission($chemin, "supprimer", get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="supprimer"/>';
				$ret .= '<input type="submit" value="Supprimer"/>';
				$ret .= '</form>';
			}
	        $ret .= '<ul class="galerie période">';
	        foreach (Stockage::liste_enfants($chemin) as $k) {
	            $ret .= '<li><a href="' . $k->get_url() . '">' . Modules::vue($k, 'miniature') . '</a></li>'; // TODO : escape l'url !
	        }
	        $ret .= '</ul>';
			return $ret;
		} else if ($vue == "miniature") {
			$enfants = Stockage::liste_enfants($chemin);
			if (is_set($enfants[0])) return Modules::vue($enfants[0], 'miniature');
			else return "Aucune<br/>photo";
		}
	}
}

enregister_module("GaleriePériode", "galerie-periode");

?>
