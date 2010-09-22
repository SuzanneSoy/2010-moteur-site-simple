<?php

class AdminConnexion {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "connexion") {
			if (Authentification::connexion(strtolower($paramètres["utilisateurnom"] . "___" . $paramètres["utilisateurprenom"]), $paramètres["mdp"])) {
				return self::vue($chemin, "connexion réussie");
			} else {
				return self::vue($chemin, "connexion échouée");
			}
		} else if ($action == "deconnexion") {
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
			return new Page($chemin, $ret, "Connexion");
		} else if ($vue == "connexion réussie") {
			$ret = "<h2>Connexion réussie</h2>";
			$ret .= "<p>Pour vous déconnecter, utilisez le lien «déconnexion» en haut à droite.</p>";
			$ret .= "<p><a href=\"" . Config::get("url_base") . "\">Retour à la page d'accueil</a>.</p>";
			return new Page($chemin, $ret, "Connexion réussie");
		}else if ($vue == "connexion échouée") {
			$msg = "<p><strong>Mauvais mot de passe et/ou nom d'utilisateur. Ré-essayez ou retournez à la ";
			$msg .= "<a href=\"" . Config::get("url_base") . "\">page d'accueil</a>";
			$msg .= ".</strong></p>";
			
			$ret = self::formulaire_connexion($chemin, "Connexion échouée", $msg);
			return new Page($chemin, $ret, "Connexion échouée");
		}else if ($vue == "déconnexion") {
			$ret = "<h2>Déconnexion réussie</h2>";
			$ret .= "<p>Vous êtes déconnecté. Vous pouvez à présent retourner à la ";
			$ret .= "<a href=\"" . Config::get("url_base") . "\">page d'accueil</a>";
			$ret .= ".</p>";
			return new Page($chemin, $ret, "Déconnexion réussie");
		}
	}
	
	public static function formulaire_connexion($chemin, $titre = "Connexion", $message = "") {
		// TODO
		$ret = "<h2>" . $titre . "</h2>";
		$ret .= $message;
		$ret .= '<form method="post" action="' . $chemin->get_url() . '">';
		$ret .= '<p><label for="utilisateurnom">Nom : </label><input type="text" id="utilisateurnom" name="utilisateurnom" value="" /></p>';
		$ret .= '<p><label for="utilisateurprenom">Prénom : </label><input type="text" id="utilisateurprenom" name="utilisateurprenom" value="" /></p>';
		$ret .= '<p><label for="mdp">Mot de passe : </label><input type="password" id="mdp" name="mdp" value="" /></p>';
		$ret .= '<p>';
		$ret .= '<input type="hidden" name="action" value="connexion" />';
		$ret .= '<input type="submit" value="Connexion" />';
		$ret .= '</p>';
		$ret .= '</form>';
		return $ret;
	}
}

Modules::enregister_module("AdminConnexion", "admin-connexion", "vue", "utilisateurnom utilisateurprenom mdp");

?>