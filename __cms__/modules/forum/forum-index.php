<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else if ($action == "nouvelle_page") {
		// Créer la nouvelle page avec les valeurs par défaut.
		// return Redirect vers cette nouvelle page.
	} else {
		if (is_set($paramètres["description"])) {
			set_prop($chemin, "description", $paramètres["description"]);
		}
/*		if (is_set($paramètres[""])) {
		}*/
		
		if (is_set($paramètres["vue"])) {
			self::vue($chemin, $paramètres["vue"]);
		} else {
			self::vue($chemin);
		}
	}
}

function vue($chemin, $vue = "normal") {
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

?>
