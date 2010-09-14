<?php

class AdminApparence {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else {
			if (isset($paramètres["Couleur_A"])) {
				Stockage::set_prop($chemin, "Couleur_A", $paramètres["Couleur_A"]);
			}
			if (isset($paramètres["Couleur_B"])) {
				Stockage::set_prop($chemin, "Couleur_B", $paramètres["Couleur_B"]);
			}
			if (isset($paramètres["Couleur_C"])) {
				Stockage::set_prop($chemin, "Couleur_C", $paramètres["Couleur_C"]);
			}
			if (isset($paramètres["Couleur_D"])) {
				Stockage::set_prop($chemin, "Couleur_D", $paramètres["Couleur_D"]);
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
			// Si l'utilisateur a l'autorisation de modifier les propriétés,
			// on affiche la version modifiable plutôt que la "vue".
			$ret = '';
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<p><strong>Attention :</strong> Les couleurs du site ne peuvent pas encore être modifiées...</p>';
				$ret .= '<form method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<ul>';
				$ret .= '<li><label for="Couleur_A">Couleur A : </label><input type="text" id="Couleur_A" name="Couleur_A" value="#000" /></li>';
				$ret .= '<li><label for="Couleur_B">Couleur B : </label><input type="text" id="Couleur_B" name="Couleur_B" value="#eee" /></li>';
				$ret .= '<li><label for="Couleur_C">Couleur C : </label><input type="text" id="Couleur_C" name="Couleur_C" value="#ff6" /></li>';
				$ret .= '<li><label for="Couleur_D">Couleur D : </label><input type="text" id="Couleur_D" name="Couleur_D" value="#fff" /></li>';
				$ret .= '</ul>';
				$ret .= '<p>';
				$ret .= '<input type="submit" value="Appliquer" />';
				$ret .= '</p>';
			} else {
				$ret .= '<ul>';
				$ret .= '<li>Couleur A : ' . Stockage::get_prop($chemin, "Coucleur_A") . '</li>';
				$ret .= '<li>Couleur B : #eee</li>';
				$ret .= '<li>Couleur C : #ff6</li>';
				$ret .= '<li>Couleur D : #fff</li>';
				$ret .= '</ul>';
			}
			return new Page($ret, "Apparence");
		} else if ($vue == "css") {
			// TODO : où mettre ce gen_css... ?
			return Site::gen_css(array(
				"Couleur_A" => Stockage::get_prop($chemin, "Coucleur_A"),
				"Couleur_B" => Stockage::get_prop($chemin, "Coucleur_B"),
				"Couleur_C" => Stockage::get_prop($chemin, "Coucleur_C"),
				"Couleur_D" => Stockage::get_prop($chemin, "Coucleur_D")
			));
		}
	}
}

Modules::enregister_module("AdminApparence", "admin-apparence", "vue", "Couleur_A Couleur_B Couleur_C Couleur_D");

?>