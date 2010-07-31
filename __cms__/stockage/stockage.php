<?php

// Chaque fonction appelle vérifier_permission($chemin, $action, $utilisateur).

// Chaque fonction ajoute un chemin de base (pour le stockage) avant
// $chemin, puis appelle une fonction de systeme-fichiers.php

function nouvelle_page($chemin, $nom) {
	if (vérifier_permission($chemin, "nouvelle_page")) {
		$enfant = $chemin->enfant($nom);
		SystemeFichiers::créer_dossier($config_chemin_base_stockage . '/' . $enfant->get());
		// Imitation de l'url rewriting
		SystemeFichiers::écrire($config_chemin_base_public . '/' . $enfant->get(), "<?php require_once(" . $config_chemin_base_php . "/cms.php);"); // TODO : séparer dans une autre fonction (rewriting.php ?)
		return $enfant;
	} else {
		return false;
	}
}

function set_prop($chemin, $prop, $valeur) {
	if (vérifier_permission($chemin, "set_prop")) {
		return SystemeFichiers::écrire($config_chemin_base_stockage . '/' . $chemin . '/' . $prop, $valeur)
	} else {
		return false;
	}
}

// Stocke le contenu de $fichier dans $prop, et supprime $fichier.
function set_prop_fichier($chemin, $prop, $fichier) {
	if (vérifier_permission($chemin, "set_prop")) {
		// TODO : Utiliser move_uploaded_file lorsque nécessaire !!!
		return SystemeFichiers::déplacer($fichier, $config_chemin_base_stockage . '/' . $chemin . '/' . $prop)
	} else {
		return false;
	}
}

// Comme pour set_prop_fichier, mais pour un fichier reçu (uploadé).
function set_prop_fichier_reçu($chemin, $prop, $fichier) {
	// move_uploaded_file directement ? (on court-circuite SystemeFichiers)
	// ou bien is_uploaded_file, puis si oui on appelle set_prop_fichier ?
	// ou mieux, on ajoute un "move_uploaded_file" à SystèmeFichiers ?
}

function get_prop($chemin, $prop, $forcer_permissions = false) {
	// $forcer_permissions permet à vérifier_permission() et ses
	// dépendances get_regles() et get_groupe() de faire des get_prop
	// même si l'utilisateur courant n'en a pas le droit.
	if ($forcer_permissions || vérifier_permission($chemin, "get_prop")) {
		return SystemeFichiers::lire($config_chemin_base_stockage . '/' . $chemin . '/' . $prop);
	} else {
		return "[Accès interdit]";
	}
}

function get_prop_sendfile($chemin, $prop) {
	// Envoie tout le conctenu de $prop sur le réseau.
	// Équivalent à appeller sendfile sur le fichier qui contient $prop.
	if (vérifier_permission($chemin, "get_prop")) {
		// (SystemeFichiers:: ???) sendfile($config_chemin_base_stockage . '/' . $chemin . '/' . $prop);
	} else {
		return false;
	}
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
