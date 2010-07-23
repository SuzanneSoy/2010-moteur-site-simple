<?php

// Chaque fonction appelle vérifier_permission($chemin, $action, $utilisateur).

// Chaque fonction ajoute un chemin de base (pour le stockage) avant
// $chemin, puis appelle une fonction de systeme-fichiers.php

function créer_enfant($chemin, $nom) {
}

function set_prop($chemin, $prop, $valeur) {
}

function set_prop_fichier($chemin, $prop, $fichier) {
	// Stocke le contenu de $fichier dans $prop, et supprime $fichier.
}

function get_prop($chemin, $prop) {
}

function get_prop_sendfile($chemin, $prop) {
	// Envoie tout le conctenu de $prop sur le réseau.
	// Équivalent à appeller sendfile sur le fichier qui contient $prop.
}

function supprimer($chemin, $récursif) {
}

function liste_enfants($chemin) {
}

/*function parent($chemin) {
}*/

function renomer($chemin, $nouveau_nom) {
	// Vérifie si l'ancien nom et le nouveau nom sont différents.
	// Renome le dossier.
}

?>
