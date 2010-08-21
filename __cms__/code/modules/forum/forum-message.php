<?php

class ForumMessage {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin, true); // TODO ! gérer correctement le récursif
			return new Page($chemin->parent(), '', "redirect");
		} else {
			if (isset($paramètres["message"])) {
				Stockage::set_prop($chemin, "message", $paramètres["message"]);
			}
			
			return new Page($chemin->parent(), '', "redirect");
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form class="forum message edition" enctype="multipart/form-data" method="post" action="' . $chemin->get_url() . '">';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "message"), "message");
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= affichage_texte_enrichi(Stockage::get_prop($chemin, "message"));
			}
			if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
				// peut-être afficher le bouton "Supprimer" ??? ou est-ce trop d'options ?
			}
			
			// Peut-être afficher le bouton "citer" ? ou est-ce trop d'options ?
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		}
	}
}

Modules::enregister_module("ForumMessage", "forum-message", "vue", "message");

?>