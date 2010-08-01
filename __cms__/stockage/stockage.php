<?php

// Chaque fonction appelle vérifier_permission($chemin, $action, $utilisateur).

// Chaque fonction ajoute un chemin de base (pour le stockage) avant
// $chemin, puis appelle une fonction de systeme-fichiers.php

class Stockage {
	public function nouvelle_page($chemin, $nom) {
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
	
	public function set_prop($chemin, $prop, $valeur) {
		if (vérifier_permission($chemin, "set_prop")) {
			return SystemeFichiers::écrire($config_chemin_base_stockage . '/' . $chemin . '/' . $prop, $valeur)
		} else {
			return false;
		}
	}
	
	// Stocke le contenu de $fichier dans $prop, et supprime $fichier.
	public function set_prop_fichier($chemin, $prop, $fichier) {
		if (vérifier_permission($chemin, "set_prop")) {
			return SystemeFichiers::déplacer($fichier, $config_chemin_base_stockage . '/' . $chemin . '/' . $prop)
		} else {
			return false;
		}
	}
	
	// Comme pour set_prop_fichier, mais pour un fichier reçu (uploadé).
	public function set_prop_fichier_reçu($chemin, $prop, $fichier) {
		if (vérifier_permission($chemin, "set_prop")) {
			return SystemeFichiers::déplacer_fichier_téléchargé($fichier, $config_chemin_base_stockage . '/' . $chemin . '/' . $prop)
		} else {
			return false;
		}
	}
	
	public function get_prop($chemin, $prop, $forcer_permissions = false) {
		// $forcer_permissions permet à vérifier_permission() et ses
		// dépendances get_regles() et get_groupe() de faire des get_prop
		// même si l'utilisateur courant n'en a pas le droit.
		if ($forcer_permissions || vérifier_permission($chemin, "get_prop")) {
			return SystemeFichiers::lire($config_chemin_base_stockage . '/' . $chemin . '/' . $prop);
		} else {
			return "[Accès interdit]";
		}
	}
	
	public function get_prop_sendfile($chemin, $prop) {
		// Envoie tout le conctenu de $prop sur le réseau.
		// Équivalent à appeller sendfile sur le fichier qui contient $prop.
		if (vérifier_permission($chemin, "get_prop")) {
			return SystemeFichiers::envoyer_fichier_directement($config_chemin_base_stockage . '/' . $chemin . '/' . $prop);
		} else {
			return false;
		}
	}
	
	// TODO : la suppression non récursive d'une page implique de supprimer
	// ses propriétés, or pour ça, il faudrait que la suppression soit
	// récursive sur un niveau seulement, ce qui n'est pas possible avec ce
	// code.
	public function supprimer($chemin, $récursif) {
		if (vérifier_permission($chemin, "supprimer")) {
			return SystèmeFichier::supprimer($config_chemin_base_stockage . '/' . $chemin, $récursif);
		} else {
			return false;
		}
	}
	
	public function liste_enfants($chemin) {
		// TODO : SECURITE : vérifier la permission. Mais pour quelle action ?
		// get_prop ? ou une nouvelle (?) : liste_enfants ?
        $enfants = Array();
        foreach (SystemeFichiers::liste_fichiers() as $k => $v) {
            if (strpos($v, "__prop__") !== 0 && is_dir($config_chemin_base_stockage . '/' . $chemin->enfant($v))) && $v != "." && $v != "..") {
                $enfants[] = $chemin->enfant($v);
            }
        }
        return $enfants;
	}
	
	public function renomer($chemin, $nouveau_nom) {
		if ($chemin->dernier() == $nouveau_nom) {
			return true;
		}
		
		if (vérifier_permission($chemin->parent(), "nouvelle_page") && vérifier_permission($chemin, "supprimer")) {
			return SystemeFichiers::déplacer($config_chemin_base_stockage . '/' . $chemin, $config_chemin_base_stockage . '/' . $chemin->renomer($nouveau_nom));
		} else {
			return false;
		}
	}
}

?>
