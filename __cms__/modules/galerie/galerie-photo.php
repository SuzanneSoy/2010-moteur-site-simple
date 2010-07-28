<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else if ($action == "nouvelle_page") {
		// Créer la nouvelle page avec les valeurs par défaut.
		// return Redirect vers cette nouvelle page.
	} else if ($action == "supprimer") {
		// Supprimer cette page.
		// return Redirect vers la page parente.
	} else {
		if (is_set($paramètres["fichier_image"])) {
			// redimensionner l'image avec gd, stocker la miniature dans
			// prop_image_mini (set_prop_fichier()).
			// Pb : Où est-ce qu'on met temporairement la miniature
			// avant de la déplacer ???
			// stocker le fichier reçu dans prop_image (set_prop_fichier_reçu()).
		}
		if (is_set($paramètres["titre"])) {
			// renomer la page
		}
		if (is_set($paramètres["description"])) {
			// set_prop($chemin, "description", $paramètres["description"]);
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
		// Si l'utilisateur a l'autorisation de modifier les propriétés,
		// on affiche la version modifiable plutôt que la "vue".
		return "Vue normale de la page.";
	} else if ($vue == "miniature") {
		return "Vue miniature.";
	} else if ($vue == "image") {
		// stockage::get_prop_sendfile("image");
	} else if ($vue == "image_mini") {
		// stockage::get_prop_sendfile("image_mini");
	}
}

?>
