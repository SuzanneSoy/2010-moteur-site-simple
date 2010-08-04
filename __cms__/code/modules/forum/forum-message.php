<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return new Page($chemin, '', "redirect");
	} else if ($action == "supprimer") {
		Stockage::supprimer($chemin);
		return new Page($chemin->parent(), '', "redirect");
	} else {
		if (isset($paramètres["message"])) {
			Stockage::set_prop($chemin, "message", $paramètres["message"]);
		}
		
		// TODO ... Quelles sont les interactions entre l'utilisateur et le message, dans quel ordre, ...
		if (isset($paramètres["vue"])) {
			Modules::vue($chemin->parent(), $paramètres["vue"]);
		} else {
			Modules::vue($chemin->parent());
		}
	}
}

function vue($chemin, $vue = "normal") {
	if ($vue == "normal") {
        $ret = '';
		if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
			$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "message"), "message");
		} else {
			$ret .= affichage_texte_enrichi(Stockage::get_prop($chemin, "message"));
		}
		if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
			// peut-être afficher le bouton "Supprimer" ??? ou est-ce trop d'options ?
		}
		// Peut-être afficher le bouton "citer" ? ou est-ce trop d'options ?
		return $ret;
	}
}

Modules::enregister_module("ForumMessage", "forum-message", "vue", "message");

?>