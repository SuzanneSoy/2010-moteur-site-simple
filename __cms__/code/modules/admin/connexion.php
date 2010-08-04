<?php

class AdminConnexion {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "connexion") {
			if (Authentification::connexion($paramètres["utilisateur"], $paramètres["mdp"])) {
				return self::vue($chemin, "connexion réussie");
			} else {
				return self::vue($chemin, "connexion échouée");
			}
		} else if ($action == "déconnexion") {
			Authentification::déconnexion();
			return self::vue($chemin, "déconnexion");
		} else {
			if (isset($paramètres["vue"])) {
				return self::vue($chemin, $paramètres["vue"]);
			} else {
				return self::vue($chemin);
			}
		}
	}
	
	// TODO : Peut-être mettre ces textes dans un autre fichier ?
	// TODO : Config::get("url_base") n'est pas forcément la page d'accueil...
	public static function vue($chemin, $vue = "normal") {
		// Les quatre vues ("connexion réussie", "déconnexion réussie",
		// formulaire de connexion, formulaire + "mauvais mdp")
		if ($vue == "normal") {
			$ret = self::formulaire_connexion($chemin);
			return new Page($ret, "Connexion");
		} else if ($vue == "connexion réussie") {
			$ret = "<h2>Connexion réussie</h2>";
			$ret .= "<p>Pour vous déconnecter, utilisez le lien «déconnexion» en haut à droite.</p>";
			$ret .= "<p><a href=\"" . Config::get("url_base") . "\">Retour à la page d'accueil</a>.</p>";
			return new Page($ret, "Connexion réussie");
		}else if ($vue == "connexion échouée") {
			$msg = "<p><strong>Mauvais mot de passe et/ou nom d'utilisateur. Ré-essayez ou retournez à la ";
			$msg .= "<a href=\"" . Config::get("url_base") . "\">page d'accueil</a>";
			$msg .= ".</strong></p>";
			
			$ret = self::formulaire_connexion($chemin, "Connexion échouée", $msg);
			return new Page($ret, "Connexion échouée");
		}else if ($vue == "déconnexion") {
			$ret = "<h2>Déconnexion réussie</h2>";
			$ret .= "<p>Vous êtes déconnecté. Vous pouvez à présent retourner à la ";
			$ret .= "<a href=\"" . Config::get("url_base") . "\">page d'accueil</a>";
			$ret .= ".</p>";
			return new Page($ret, "Déconnexion réussie");
		}
	}
	
	public static function formulaire_connexion($chemin, $titre = "Connexion", $message = "") {
		// TODO
		$ret = "<h2>" . $titre . "</h2>";
		$ret .= $message;
		$ret .= '<form method="post" action="' . $chemin->get_url() . '">';
		$ret .= '<label for="utilisateur">Nom : </label><input type="text" name="utilisateur" value="" />';
		$ret .= '<br />';
		$ret .= '<label for="mdp">Mot de passe : </label><input type="password" name="mdp" value="" />';
		$ret .= '<br />';
		$ret .= '<input type="hidden" name="action" value="connexion" />';
		$ret .= '<input type="submit" value="Connexion" />';
		$ret .= '</form>';
		return $ret;
	}
}

Modules::enregister_module("AdminConnexion", "admin-connexion", "vue", "utilisateur mdp");

?>