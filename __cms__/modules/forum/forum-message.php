<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else if ($action == "supprimer") {
		// Supprimer cette page.
		// return Redirect vers la page parente.
	} else {
		if (is_set($paramètres["message"])) {
			// set_prop($chemin, "message", $paramètres["message"]);
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
			$ret .= formulaire_édition_texte_enrichi(get_prop($chemin, "message"), $nom_champ);
		} else {
			$ret .= affichage_texte_enrichi(get_prop($chemin, "message"));
		}
		if (vérifier_permission($chemin, "supprimer", get_utilisateur())) {
			// peut-être afficher le bouton "Supprimer" ??? ou est-ce trop d'options ?
		}
		// Peut-être afficher le bouton "citer" ? ou est-ce trop d'options ?
		return $ret;
	}
}

?>
