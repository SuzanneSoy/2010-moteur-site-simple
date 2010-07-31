<?php

class ForumIndex {
	public function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return redirect($chemin);
		} else if ($action == "nouvelle_page") {
			// TODO : faut-il demander à avoir directement le nom du nouveau sujet ?
			// TODO : quel est le propriétaire du nouveau sujet ?
			$np = Stockage::nouvelle_page($chemin, "Nouveau sujet");
			Stockage::set_prop($np, "proprietaire", get_utilisateur());
			return redirect($np);
		} else {
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
			$ret .= "<h1>Forum</h1>";
			if (vérifier_permission($chemin, "nouvelle_page", get_utilisateur())) {
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

?>
