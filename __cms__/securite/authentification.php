<?php

function connexion($utilisateur, $mdp) {
	// vérifie si $utilisateur a pour mot de passe $mdp.
	// Si oui, on place une variable de session, et on renvoie true.
	//    (Note : session_start doit avoir été exécuté avant.)
	//    La variable de session contient $utilisateur (vérifier si c'est sécurisé...)
	// Si non, on renvoie false.
}

function déconnexion() {
	// Efface la variable de session positionnée par connexion().
}

function get_utilisateur() {
	// Renvoie $utilisateur s'il est connecté, false sinon.
}

function nouvel_utilisateur($utilisateur) {
	// Crée un nouvel utilisateur nommé $utilisateur.
	// Lui affecte un mot de passe aléatoire.
	// Positionne son groupe à "anonyme".
}

function set_groupe($utilisateur, $groupe) {
	// Positionne le groupe de $utilisateur à $groupe.
}

function get_groupe($utilisateur) {
	// Renvoie le groupe de $utilisateur.
}

function supprimer_utilisateur($utilisateur) {
	// Supprime l'utilisateur créé par nouvel_utilisateur
}

function get_mot_de_passe($utilisateur) {
	// Renvoie le mot de passe de $utilisateur
}

?>
