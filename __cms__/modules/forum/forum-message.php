<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else if ($action == "supprimer") {
		// Supprimer cette page.
		// return Redirect vers la page parente.
	} else {
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
			// afficher le textarea du message
		} else {
			$ret .= "<p>" . get_prop($chemin, "message") . "</p>";
		}
		if (vérifier_permission($chemin, "supprimer", get_utilisateur())) {
			// peut-être afficher le lien "Supprimer" ???
		}
		// Peut-être afficher le bouton "citer" ?
		return $ret;
	}
}

?>
