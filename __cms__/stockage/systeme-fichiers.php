<?php

function créer_dossier($chemin) {
	// mkdir($chemin)
}

/*function créer_fichier($chemin) {
	// touch($chemin)
}*/

function supprimer($chemin, $récursif) {
	// Si non récursif, supprime ssi c'est un fichier.
	// Sinon, si c'est un fichier ou un lien, supprime,
	//        si c'est un dossier, appelle récursivement puis rmdir.
}

function lire($chemin) {
	// file_get_contents()
}

function écrire($chemin, $données) {
	// file_put_contents();
}

function liste_enfants($chemin) {
	// Renvoie la liste des sous-fichiers et sous-dossiers.
}

function déplacer($chemin_de, $chemin_vers) {
	// mv
}

?>
