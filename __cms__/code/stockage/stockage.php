<?php

// Chaque fonction appelle Permissions::vérifier_permission($chemin, $action, $utilisateur).

// Chaque fonction ajoute un chemin de base (pour le stockage) avant
// $chemin, puis appelle une fonction de systeme-fichiers.php

class Stockage {
	public static function nouvelle_page($chemin, $nom, $type) {
		if (Permissions::vérifier_permission($chemin, "nouvelle_page")) {
			$enfant = $chemin->enfant($nom);
			Système_fichiers::créer_dossier($enfant->get_fs_stockage());
			self::set_prop($enfant, "type", $type);
			$e = self::activer_réécriture($enfant);
			if (Erreur::is_erreur($e)) return Erreur::fatale("Erreur lors de la mise en place de la redirection pour " . $enfant->get() . ".", $e);
			return $enfant;
		} else {
			return false;
		}
	}
	
	// Imitation de l'url rewriting lorsque ce n'est pas disponible sur
	// le serveur.
	public static function activer_réécriture($chemin_vers) {
		// TODO : SECURITE : échapper les " dans le require_once et l'appel à cms.
		$a = $chemin_vers->get_fs_public();
		$b = Path::combine(Config::get("chemin_base"), "cms.php");
		
		$php_str = "<?php\n\n";
		$php_str .= "require_once(dirname(__FILE__) . \"/" . Path::relative($a, $b) . "\");\n\n";
		$php_str .= "CMS::page(\"" . $chemin_vers->get() . "\");\n\n";
		$php_str .= "?>";
		return Système_fichiers::écrire(Path::combine($a, "/index.php"), $php_str);
	}
	
	public static function désactiver_réécriture($chemin_vers) {
		return Système_fichiers::supprimer($chemin_vers->get_fs_public());
	}
	
	private static function fichier_prop($chemin, $prop) {
		return Path::combine($chemin->get_fs_stockage(), '__prop__' . $prop);
	}
	
	public static function set_prop($chemin, $prop, $valeur) {
		if (Permissions::vérifier_permission($chemin, "set_prop")) {
			return Système_fichiers::écrire(self::fichier_prop($chemin, $prop), $valeur);
		} else {
			return false;
		}
	}
	
	// Stocke le contenu de $fichier dans $prop, et supprime $fichier.
	public static function set_prop_fichier($chemin, $prop, $fichier) {
		if (Permissions::vérifier_permission($chemin, "set_prop")) {
			return Système_fichiers::déplacer($fichier, self::fichier_prop($chemin, $prop));
		} else {
			return false;
		}
	}
	
	// Comme pour set_prop_fichier, mais pour un fichier reçu (uploadé).
	public static function set_prop_fichier_reçu($chemin, $prop, $fichier) {
		if (Permissions::vérifier_permission($chemin, "set_prop")) {
			return Système_fichiers::déplacer_fichier_téléchargé($fichier, self::fichier_prop($chemin, $prop));
		} else {
			return false;
		}
	}
	
	public static function get_prop($chemin, $prop, $forcer_permissions = false) {
		// $forcer_permissions permet à Permissions::vérifier_permission() et ses
		// dépendances get_regles() et get_groupe() de faire des get_prop
		// même si l'utilisateur courant n'en a pas le droit.
		if ($forcer_permissions || Permissions::vérifier_permission($chemin, "get_prop")) {
			return Système_fichiers::lire(self::fichier_prop($chemin, $prop));
		} else {
			return Erreur::lecture("Permission non accordée pour la lecture de chemin: " . $chemin->get() . " propriété: " . $prop);
		}
	}
	
	public static function get_prop_sendfile($chemin, $prop) {
		// Envoie tout le conctenu de $prop sur le réseau.
		// Équivalent à appeller sendfile sur le fichier qui contient $prop.
		if (Permissions::vérifier_permission($chemin, "get_prop")) {
			return Système_fichiers::envoyer_fichier_directement(self::fichier_prop($chemin, $prop));
		} else {
			return Erreur::lecture("Permission non accordée pour la lecture de chemin: " . $chemin->get() . " propriété: " . $prop);
		}
	}
	
	public static function prop_diff($chemin, $prop, $valeur) {
		// Renvoie true si la valeur actuelle de $prop est différente de $valeur.
		return self::get_prop($chemin, $prop) != $valeur;
	}
	
	// TODO : la suppression non récursive d'une page implique de supprimer
	// ses propriétés, or pour ça, il faudrait que la suppression soit
	// récursive sur un niveau seulement, ce qui n'est pas possible avec ce
	// code.
	public static function supprimer($chemin, $récursif) {
		if (Permissions::vérifier_permission($chemin, "supprimer")) {
			// TODO : désactiver_réécriture($chemin) récursivement
			return Système_fichiers::supprimer($chemin->get_fs_stockage(), $récursif);
		} else {
			return false;
		}
	}
	
	public static function liste_enfants($chemin) {
		// TODO : SECURITE : vérifier la permission. Mais pour quelle action ?
		// get_prop ? ou une nouvelle (?) : liste_enfants ?
		$enfants = Array();
		foreach (Système_fichiers::liste_fichiers($chemin->get_fs_stockage()) as $k => $v) {
			if (strpos($v, "__prop__") !== 0 && is_dir($chemin->enfant($v)->get_fs_stockage()) && $v != "." && $v != "..") {
				$enfants[] = $chemin->enfant($v);
			}
		}
		return $enfants;
	}
	
	public static function renomer($chemin, $nouveau_nom) {
		if ($chemin->dernier() == $nouveau_nom) {
			return true;
		}
		
		if (Permissions::vérifier_permission($chemin->parent(), "nouvelle_page") && Permissions::vérifier_permission($chemin, "supprimer")) {
			self::désactiver_réécriture($chemin); // TODO : récursivement !
			self::activer_réécriture($chemin->renomer($nouveau_nom)); // TODO : récursivement !
			return Système_fichiers::déplacer($chemin->get_fs_stockage(), $chemin->renomer($nouveau_nom)->get_fs_stockage());
		} else {
			return false;
		}
	}
}

?>