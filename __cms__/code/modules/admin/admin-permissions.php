<?php

class AdminPermissions {
	public static function action($chemin, $action, $paramètres) {
		$singleton = new Chemin("/admin/permissions/");
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else {
			if (isset($paramètres["regles"])) {
				Stockage::set_prop($singleton, "regles", $paramètres["regles"]);
			}
			
			if (isset($paramètres["vue"])) {
				self::vue($chemin, $paramètres["vue"]);
			} else {
				self::vue($chemin);
			}
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		$singleton = new Chemin("/admin/permissions/");
		if ($vue == "normal") {
			$ret = "";
			$ret .= "<h2>Règles de sécurité</h2>";
			$ret .= "<p>La première règle correspondant à une action de l'utilisateur est appliquée. Bla-bla blabla sur le fonctionnement.</p>";
			if (Permissions::vérifier_permission($singleton, "set_prop", Authentification::get_utilisateur())) {
				$ret .= "<textarea ...>" . Stockage::get_prop($singleton, "regles") . "</textarea>"; // TODO : html escape chars etc.
			} else {
				$ret .= "<pre><code>" . Stockage::get_prop($singleton, "regles") . "</code></pre>"; // TODO : html escape chars etc.
			}
			return new Page($ret, "Permissions");
		}
	}
}

Modules::enregister_module("AdminPermissions", "admin-permissions", "vue", "regles");

?>