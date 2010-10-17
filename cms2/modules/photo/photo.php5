<?php

class mPhoto {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page i_grande i_image i_miniature");
		Module::attribut("titre", "text_line", $cvars['texte_titre']);
		Module::attribut("description", "text_rich", "");
		Module::attribut("publier", "bool", "true");
		Module::attribut("image", "img_file", "");
	}
	
	public function res_c_style() {
		niy("GaleriePhoto::res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, toString($this->description)); // En-tête standard.
		$d->w_img_file_desc($this->image, $this->description);
		return $d;
	}
	
	public function res_h_mini_miniature($d) {
		$d->img($this->description, $this->i_image);
		return $d;
	}

	// ===============================
	
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
		//var_dump($largeur_miniature, $hauteur_miniature, $largeur, $hauteur);
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

Module::add_module("mPhoto");

?>