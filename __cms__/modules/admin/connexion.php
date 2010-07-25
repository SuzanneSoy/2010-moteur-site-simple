<?php

function action($chemin, $action, $paramètres) {
	if ($action == "connexion") {
		if (connexion($paramètres["utilisateur"], $paramètres["mdp"])) {
			// Afficher la page "Connexion réussie" et un lien vers la page d'accueil.
		} else {
			// Afficher "Mauvais mot de passe ou nom d'utilisateur" puis le formulaire de connexion.
		}
	} else if ($action == "déconnexion") {
		déconnexion();
		// Afficher "déconnexion réussie" et un lien vers la page d'accueil.
	} else {
		// Afficher le formulaire de connexion.
		if (is_set($paramètres["vue"])) {
			self::vue($chemin, $paramètres["vue"]);
		} else {
			self::vue($chemin);
		}
	}
}

function vue($chemin, $vue = "normal") {
	// Les quatre vues ("connexion réussie", "déconnexion réussie",
	// formulaire de connexion, formulaire + "mauvais mdp")
}

?>
