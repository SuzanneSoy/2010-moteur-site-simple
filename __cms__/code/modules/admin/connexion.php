<?php

class AdminConnexion {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "connexion") {
			if (connexion($paramètres["utilisateur"], $paramètres["mdp"])) {
				return self::vue("connexion réussie");
			} else {
				return self::vue("connexion échouée");
			}
		} else if ($action == "déconnexion") {
			déconnexion();
			return self::vue("déconnexion");
		} else {
			if (isset($paramètres["vue"])) {
				self::vue($chemin, $paramètres["vue"]);
			} else {
				self::vue($chemin);
			}
		}
	}
	
	// TODO : Peut-être mettre ces textes dans un autre fichier ?
	// TODO : Config::get("url_base") n'est pas forcément la page d'accueil...
	public static function vue($chemin, $vue = "normal") {
		// Les quatre vues ("connexion réussie", "déconnexion réussie",
		// formulaire de connexion, formulaire + "mauvais mdp")
		if ($vue == "normal") {
			return formulaire_connexion();
		} else if ($vue == "connexion réussie") {
			$ret = "<h2>Connexion réussie</h2>";
			$ret .= "<p>Pour vous déconnecter, utilisez le lien «déconnexion» en haut à droite.</p>";
			$ret .= "<p><a href=\"" . Config::get("url_base") . "\">Retour à la page d'accueil</a>.</p>";
			return $ret;
		}else if ($vue == "connexion échouée") {
			$msg = "<p><strong>Mauvais mot de passe et/ou nom d'utilisateur. Ré-essayez ou retournez à la ";
			$msg .= "<a href=\"" . Config::get("url_base") . "\">page d'accueil</a>";
			$msg .= ".</strong></p>";
			return formulaire_connexion($msg);
		}else if ($vue == "déconnexion") {
			$ret = "<h2>Déconnexion réussie</h2>";
			$ret .= "<p>Vous êtes déconnecté. Vous pouvez à présent retourner à la ";
			$ret .= "<a href=\"" . Config::get("url_base") . "\">page d'accueil</a>";
			$ret .= ".</p>";
			return $ret;
		}
	}
	
	public static function formulaire_connexion($message = "") {
		// TODO
		return "<h2>Connexion</h2>" . $message . "<input type=\"text\" name=\"nom\" value=\"Nom\" />...";
	}
}

Modules::enregister_module("AdminConnexion", "admin-connexion", "vue");

?>