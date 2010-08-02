<?php

class GaleriePhoto {
	public function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return redirect($chemin);
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin);
			return redirect($chemin->parent());
		} else {
			if (is_set($paramètres["fichier_image"])) {
				// redimensionner l'image avec gd, stocker la miniature dans
				// prop_image_mini (set_prop_fichier()).
				// Pb : Où est-ce qu'on met temporairement la miniature
				// avant de la déplacer ???
				// stocker le fichier reçu dans prop_image (set_prop_fichier_reçu()).
			}
	/*		if (is_set($paramètres["titre"])) {
				Stockage::renomer($chemin, $paramètres["titre"]);
				$chemin = $chemin->renomer($paramètres["titre"]);
				// TODO : peut-être redirect($chemin) ?
			}*/
			if (is_set($paramètres["description"])) {
				Stockage::set_prop($chemin, "description", $paramètres["description"]);
			}
			
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
			if (vérifier_permission($chemin, "set_prop", get_utilisateur())) {
				$ret .= '<input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" />';
				$ret .= '<img src="' . $chemin->get_url("?vue=image") . '"></img>';
				$ret .= '<input type="filename" .../>';
				$ret .= formulaire_édition_texte_enrichi(get_prop($chemin, "description"), "message");
			} else {
				$ret .= '<h1>' . Stockage::get_prop($chemin, "titre") . '</h1>';
				$ret .= '<img src="' . $chemin->get_url("?vue=image") . '"></img>';
				$ret .= affichage_texte_enrichi(get_prop($chemin, "message"));
			}
			return $ret;
		} else if ($vue == "miniature") {
			return '<img src="' . $chemin->get_url("?vue=image_mini") . '"></img>';
		} else if ($vue == "image") {
			Stockage::get_prop_sendfile("image");
		} else if ($vue == "image_mini") {
			Stockage::get_prop_sendfile("image_mini");
		}
	}
}

enregister_module("GaleriePhoto", "galerie-photo");

?>