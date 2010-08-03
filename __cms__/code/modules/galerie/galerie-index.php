<?php

class GalerieIndex {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return redirect($chemin);
		} else if ($action == "nouvelle_page") {
			$np = Stockage::nouvelle_page($chemin, "Nouvelle période", "galerie-periode");
			Stockage::set_prop($np, "proprietaire", Authentification::get_utilisateur());
			return redirect($np);
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin);
			return redirect($chemin->parent());
		} else {
			if (isset($paramètres["titre"])) {
				Stockage::renomer($chemin, $paramètres["titre"]);
				$chemin = $chemin->renomer($paramètres["titre"]);
				// TODO : peut-être redirect($chemin) ?
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
				$ret .= '<input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" />';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "message");
			} else {
				$ret .= '<h1>' . Stockage::get_prop($chemin, "titre") . '</h1>';
				$ret .= '<p class="galerie index description affichage">' . Stockage::get_prop($chemin, "description") . '</p>';
			}
			if (Permissions::vérifier_permission($chemin, "nouvelle_page", Authentification::get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="nouvelle_page"/>';
				$ret .= '<input type="submit" value="Nouvelle page"/>';
				$ret .= '</form>';
			}
			if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="supprimer"/>';
				$ret .= '<input type="submit" value="Supprimer"/>';
				$ret .= '</form>';
			}
	        $ret .= '<ul class="galerie index">';
	        foreach (Stockage::liste_enfants($chemin) as $k) {
	            $ret .= '<li><a href="' . $k->get_url() . '">' . Modules::vue($k, 'miniature') . '</a></li>'; // TODO : escape l'url !
	        }
	        $ret .= '</ul>';
			return $ret;
		} else if ($vue == "miniature") {
			$enfants = Stockage::liste_enfants($chemin);
			if (isset($enfants[0])) return Modules::vue($enfants[0], 'miniature');
			else return "Aucune<br/>photo";
		}
	}
}

Modules::enregister_module("GalerieIndex", "galerie-index", "vue", "titre description");

?>