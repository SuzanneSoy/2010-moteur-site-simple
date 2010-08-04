<?php

class GaleriePhoto {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin);
			return new Page($chemin->parent(), '', "redirect");
		} else {
			if (isset($paramètres["fichier_image"])) {
				// redimensionner l'image avec gd, stocker la miniature dans
				// prop_image_mini (set_prop_fichier()).
				// Pb : Où est-ce qu'on met temporairement la miniature
				// avant de la déplacer ???
				// stocker le fichier reçu dans prop_image (set_prop_fichier_reçu()).
			}
	/*		if (isset($paramètres["titre"])) {
				Stockage::renomer($chemin, $paramètres["titre"]);
				$chemin = $chemin->renomer($paramètres["titre"]);
				// TODO : peut-être new Page($chemin, '', "redirect") ?
			}*/
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
				$ret .= '<img src="' . $chemin->get_url("?vue=image") . '"></img>';
				$ret .= '<input type="filename" .../>';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "message");
			} else {
				$ret .= '<h1>' . Stockage::get_prop($chemin, "titre") . '</h1>';
				$ret .= '<img src="' . $chemin->get_url("?vue=image") . '"></img>';
				$ret .= affichage_texte_enrichi(Stockage::get_prop($chemin, "message"));
			}
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} else if ($vue == "miniature") {
			$ret = '<img src="' . $chemin->get_url("?vue=image_mini") . '"></img>';
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} else if ($vue == "image") {
			return new Page($chemin, "image", "sendfile");
		} else if ($vue == "image_mini") {
			return new Page($chemin, "image_mini", "sendfile");
		}
	}
}

Modules::enregister_module("GaleriePhoto", "galerie-photo", "vue", "description", "fichier_image");

?>