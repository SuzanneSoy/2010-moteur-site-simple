<?php

function vérifier_permission($chemin, $action, $utilisateur = null) {
	if ($utilisateur === null) {
		$utilisateur = Authentification::get_utilisateur();
	}
	// Vérifie si $utilisateur a la permission d'effectuer $action sur $chemin.
	// Parcourt la liste les règles de sécurité (get_regles()), et
	// sélectionne la première pour laquelle $chemin correspond au motif
	// de la règle, $action correspond au motif de la règle, et
	// $utilisateur a comme groupe le groupe de la règle (get_groupe()).
	// Si le champ "autorisation" de la règle est true, on renvoie true,
	// sinon on renvoie false
}

function set_regles($regles) {
	// $regles est un tableau de quadruplets
	// (chemin, action, groupe, autorisation).
	
	// Enregistre $regles pour qu'il puisse être lu par get_regles.
}

function get_regles() {
	// Renvoie les regles.
}

?>
