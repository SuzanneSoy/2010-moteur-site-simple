<?php

class AdminConnexion {
	public function action($chemin, $action, $paramètres) {
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
			if (is_set($paramètres["vue"])) {
				self::vue($chemin, $paramètres["vue"]);
			} else {
				self::vue($chemin);
			}
		}
	}
	
	// TODO : Peut-être mettre ces textes dans un autre fichier ?
	// TODO : $config_url_base n'est pas forcément la page d'accueil...
	public function vue($chemin, $vue = "normal") {
		// Les quatre vues ("connexion réussie", "déconnexion réussie",
		// formulaire de connexion, formulaire + "mauvais mdp")
		if ($vue == "normal") {
			return formulaire_connexion();
		} else if ($vue == "connexion réussie") {
			return "<h1>Connexion réussie</h1><p>Pour vous déconnecter, utilisez le lien «déconnexion» en haut à droite.</p><p><a href=\"" . $config_url_base . "\">Retour à la page d'accueil</a>.</p>";
		}else if ($vue == "connexion échouée") {
			return formulaire_connexion("<p><strong>Mauvais mot de passe et/ou nom d'utilisateur. Ré-essayez ou retournez à la <a href=\"" . $config_url_base . "\">page d'accueil</a>.</strong></p>");
		}else if ($vue == "déconnexion") {
		  return '<h1>Déconnexion réussie</h1><p>Vous êtes déconnecté. Vous pouvez à présent retourner à la <a href="' . $config_url_base . "\">page d'accueil</a>.</p>";
		}
	}
	
	public function formulaire_connexion($message = "") {
		// TODO
		return "<h1>Connexion</h1>" . $message . "<input type=\"text\" name=\"nom\" value=\"Nom\" />...";
	}
}

enregister_module("AdminConnexion", "admin-connexion");

?>