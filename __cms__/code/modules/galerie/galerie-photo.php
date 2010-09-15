<?php

class GaleriePhoto {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin, true); // TODO ! gérer correctement le récursif
			return new Page($chemin->parent(), '', "redirect");
		} else {
			if (isset($paramètres["fichier_image"]) && ($paramètres["fichier_image"]["tmp_name"] != "")) {
				$fichier_image = $paramètres["fichier_image"]["tmp_name"];
				$fichier_image_mini = self::creer_miniature($fichier_image, 64, 64);
				Stockage::set_prop_fichier($chemin, "image_mini", $fichier_image_mini);
				Stockage::set_prop_fichier_reçu($chemin, "image", $fichier_image);
			}
			
			if (isset($paramètres["description"])) {
				Stockage::set_prop($chemin, "description", $paramètres["description"]);
			}
			
			// titre après les autres paramètres car il peut générer un redirect.
			if (isset($paramètres["titre"]) && Stockage::prop_diff($chemin, "titre", $paramètres["titre"])) {
				Stockage::set_prop($chemin, "titre", $paramètres["titre"]);
				Stockage::renomer($chemin, $paramètres["titre"]);
				$chemin = $chemin->renomer($paramètres["titre"]);
				// TODO : transmettre le paramètre "vue"
				return new Page($chemin, '', "redirect");
			}
			
			if (isset($paramètres["vue"])) {
				return self::vue($chemin, $paramètres["vue"]);
			} else {
				return self::vue($chemin);
			}
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form class="galerie infos" enctype="multipart/form-data" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<h2><input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" /></h2>';
				$ret .= '<img alt="' . Stockage::get_prop($chemin, "titre") . '" src="' . $chemin->get_url("?vue=image") . '"/>';
				$ret .= '<p>';
				$ret .= '<input type="file" name="fichier_image" id="fichier_image">';
				$ret .= '</p>';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "description");
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= '<h2>' . Stockage::get_prop($chemin, "titre") . '</h2>';
				$ret .= '<img alt="' . Stockage::get_prop($chemin, "titre") . '" src="' . $chemin->get_url("?vue=image") . '"/>';
				$ret .= affichage_texte_enrichi(Stockage::get_prop($chemin, "description"));
			}
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} else if ($vue == "miniature" || $vue == "mini") {
			$ret = '<img alt="' . Stockage::get_prop($chemin, "titre") . '" src="' . $chemin->get_url("?vue=image_mini") . '"/>';
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} else if ($vue == "image") {
			return new Page($chemin, "image", "sendprop");
		} else if ($vue == "image_mini") {
			return new Page($chemin, "image_mini", "sendprop");
		}
		return new Page('',''); // TODO : devrait renvoyer une page d'erreur !
	}
	
	public static function creer_miniature($chemin_fs, $largeur_max, $hauteur_max) {
		$chemin_fs_dest = tempnam(dirname($chemin_fs), "img");
		if ($chemin_fs_dest === false) return false; // TODO : return Erreur::...(...);
		
		/* TODO : utiliser imagealphablending si nécessaire... http://www.php.net/manual/fr/function.imagecreatefrompng.php#85754 */
		$image = imagecreatefromjpeg($chemin_fs); // ... formpng()
		$largeur = imageSX($image);
		$hauteur = imageSY($image);
		if ($largeur < $largeur_max && $hauteur < $hauteur_max) {
			$largeur_miniature = $largeur;
			$hauteur_miniature = $hauteur;
		} else if ($largeur / $hauteur < $largeur_max / $hauteur_max) { // limité par la hauteur.
			$largeur_miniature = $largeur_max;
			$hauteur_miniature = $hauteur * $largeur_miniature/$largeur;
		} else { // limité par la largeur
			$hauteur_miniature = $hauteur_max;
			$largeur_miniature = $largeur * $hauteur_miniature/$hauteur;
		}
		$miniature = ImageCreateTrueColor($largeur_miniature, $hauteur_miniature); // miniatures de tailles différentes
		var_dump($largeur_miniature, $hauteur_miniature, $largeur, $hauteur);
		imagecopyresampled(
			$miniature,         // image destination
			$image,             // image source
			0,                  // x destination
			0,                  // y destination
			0,                  // x source
			0,                  // y source
			$largeur_miniature, // largeur destination
			$hauteur_miniature, // hauteur destination
			$largeur,           // largeur source
			$hauteur            // hauteur source
		);
		imagedestroy($image); // On libère la mémoire le plus tôt possible.
		imagejpeg($miniature, $chemin_fs_dest);
		imagedestroy($miniature);
		return $chemin_fs_dest;
	}
}

Modules::enregister_module("GaleriePhoto", "galerie-photo", "vue", "description", "fichier_image");

?>