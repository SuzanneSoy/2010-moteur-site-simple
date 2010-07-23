<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else if ($action == "nouvelle_page") {
		// Créer le nouveau message avec comme titre un numéro.
		// return Redirect vers la page actuelle, à l'ancre correspondant à ce message.
	} else if ($action == "supprimer") {
		// Supprimer cette page.
		// return Redirect vers la page parente.
	} else {
		if (is_set($paramètres["titre"])) {
			// renomer la page
		}
		
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
		if (vérifier_permission($chemin, "set_prop", get_utilisateur())) {
			// afficher le <input type="text" /> du titre
		} else {
			$ret .= "<h1>" . get_prop($chemin, "titre") . "</h1>";
		}
		if (vérifier_permission($chemin, "nouvelle_page", get_utilisateur())) {
			// afficher le lien "Nouveau message"
		}
		if (vérifier_permission($chemin, "supprimer", get_utilisateur())) {
			// afficher le lien "Supprimer"
		}
        $ret .= '<ul class="forum sujet">';
        foreach (stockage::liste_enfants($chemin) as $k) {
            $ret .= '<li>' . modules::vue($k) . '</li>';
        }
        $ret .= '</ul>';
		return $ret;
	} else if ($vue == "miniature") {
		return get_prop($chemin, "titre");
	}
}

?>
