<?php

class AdminCouleurs {
	public function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return redirect($chemin);
		} else {
			if (is_set($paramètres["Couleur_A"])) {
				// Stocker couleur A
			}
			if (is_set($paramètres["Couleur_B"])) {
				// Stocker couleur B
			}
			if (is_set($paramètres["Couleur_C"])) {
				// Stocker couleur C
			}
			// ...
			
			if (is_set($paramètres["vue"])) {
				self::vue($chemin, $paramètres["vue"]);
			} else {
				self::vue($chemin);
			}
		}
	}
	
	public function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			// Si l'utilisateur a l'autorisation de modifier les propriétés,
			// on affiche la version modifiable plutôt que la "vue".
			$ret = "";
			$ret .= "<input ... Couleur A />";
			$ret .= "<input ... Couleur B />";
			$ret .= "<input ... Couleur C />";
			// $ret .= ...
			return "Vue normale de la page.";
		} else if ($vue == "css") {
			// TODO : où mettre ce gen_css... ?
			return Site::gen_css(array(
				"Couleur_A" => Stockage::get_prop($chemin, "Coucleur_A"),
				"Couleur_B" => Stockage::get_prop($chemin, "Coucleur_B"),
				"Couleur_C" => Stockage::get_prop($chemin, "Coucleur_C")
			));
		}
	}
}

enregister_module("AdminCouleurs", "admin-couleurs");

?>
