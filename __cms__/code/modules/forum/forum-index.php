<?php

class ForumIndex {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "nouvelle_page") {
			// TODO : faut-il demander à avoir directement le nom du nouveau sujet ?
			// TODO : quel est le propriétaire du nouveau sujet ?
			$np = Stockage::nouvelle_page($chemin, "Nouveau sujet", "forum-sujet");
			Stockage::set_prop($np, "proprietaire", Authentification::get_utilisateur());
			return new Page($np, '', "redirect");
		} else {
			if (isset($paramètres["vue"])) {
				self::vue($chemin, $paramètres["vue"]);
			} else {
				self::vue($chemin);
			}
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
	        $ret = '';
			$ret .= "<h1>Forum</h1>";
			if (Permissions::vérifier_permission($chemin, "nouvelle_page", Authentification::get_utilisateur())) {
				// afficher le lien "Nouveau sujet"
			}
	        $ret .= '<ul class="forum index">';
	        foreach (stockage::liste_enfants($chemin) as $k) {
	            $ret .= '<li><a href="' . chemin::vers_url($k) . '">' . modules::vue($k, 'miniature') . '</a></li>'; // TODO : escape l'url !
	        }
	        $ret .= '</ul>';
			return $ret;
		}
	}
}

Modules::enregister_module("ForumIndex", "forum-index", "vue");

?>