<?php

// Chaque fonction appelle vérifier_permission($chemin, $action, $utilisateur).

// Chaque fonction ajoute un chemin de base (pour le stockage) avant
// $chemin, puis appelle une fonction de systeme-fichiers.php

function créer_enfant($chemin, $nom) {
}

function set_prop($chemin, $prop, $valeur) {
}

function get_prop($chemin, $prop) {
}

function supprimer($chemin, $récursif) {
}

function liste_enfants($chemin) {
}

/*function parent($chemin) {
}*/

/*function stocker_fichier($chemin_fs_orig, $chemin, $prop) {
}*/

function renomer($chemin, $nouveau_nom) {
	// Vérifie si l'ancien nom et le nouveau nom sont différents.
	// Renome le dossier.
}

?>
