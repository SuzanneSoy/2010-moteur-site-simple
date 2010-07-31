<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else if ($action == "supprimer") {
		Stockage::supprimer($chemin);
		return redirect($chemin->parent());
	} else {
		if (is_set($paramètres["message"])) {
			Stockage::set_prop($chemin, "message", $paramètres["message"]);
		}
		
		// TODO ... Quelles sont les interactions entre l'utilisateur et le message, dans quel ordre, ...
		if (is_set($paramètres["vue"])) {
			Modules::vue($chemin->parent(), $paramètres["vue"]);
		} else {
			Modules::vue($chemin->parent());
		}
	}
}

function vue($chemin, $vue = "normal") {
	if ($vue == "normal") {
        $ret = '';
		if (vérifier_permission($chemin, "set_prop", get_utilisateur())) {
			$ret .= formulaire_édition_texte_enrichi(get_prop($chemin, "message"), "message");
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
