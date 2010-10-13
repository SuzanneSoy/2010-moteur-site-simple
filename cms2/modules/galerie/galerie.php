<?php

abstract class mGalerieBase extends Page {
	protected static $texte_titre = "Galerie";
	protected static $texte_nouvelle_page = "Nouvel élément";
	protected static $icone_nouvelle_page = "nouvelle_periode.png";
	protected static $type_enfants = "mGaleriePeriode";
	
	public static function info() {
		ressources_statiques("i_icone_nouvelle_page c_style");
		ressources_dynamiques("h_page h_miniature h_mini_miniature");
		type_liens("enfants", self::$type_enfants);
		type_liens("liens", "*");
		attribut("titre", "text_line", self::$texte_titre);
		attribut("description", "text_rich", "");
		attribut("dans_nouveautes", "bool", "true");
	}
	
	public function res_i_icone_nouvelle_page() {
		return StockageFichiers::envoyer_fichier_statique(Path::combine(dirname(__FILE__), self::$icone_nouvelle_page));
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, $this->description); // En-tête standard.
		$l = $d->article()->w_liste($this->enfants(true, "-date_creation"), function($e, $li) {
				$a = $li->a($e->uid());
				$e->rendu("h_miniature", $a);
			});
		$nouveau = $l->li();
		// TODO : nouveau devrait être un lien, bouton, ...
		var_dump($this->url("i_icone_nouvelle_page"));
		$nouveau->span("miniature")->img("", $this->url("i_icone_nouvelle_page"));
		$nouveau->span("titre")->text(self::$texte_nouvelle_page);
		
		if ($this->if_perm("W", "dans_nouveautes")) {
			$d->p()->w_form($this->dans_nouveautes);
		}

		// TODO : lister les liens et pouvoir en ajouter (personne, lieu etc.).
		
		return $d;
	}
	
	public function res_h_miniature($d) {
		$d->span("miniature")->append($this->res_h_mini_miniature());
		$d->span("titre")->_field($this->titre);
		return $d;
	}
	
	public function res_h_mini_miniature($d) {
		$a = $this->enfants("apercu = 'true'", "-date_creation", 1); // TODO : l'aperçu devrait être défini par le parent => ajouter un attribut "virtuel".
		if (count($a) != 1)
			$a = $this->enfants(true, "-date_creation", 1);
		if (count($a) != 1)
			return; // TODO : aucune photo
		return $a[0]->rendu("h_mini_miniature", $d);;
	}
	
	public function set_dans_nouveautes($val) {
		$this->page_systeme("nouveautes")->lier_page("$this");
		return $this->set_prop_direct("dans_nouveautes", $val);
	}
}

class mGalerieIndex extends mGalerieBase {
	protected static $texte_titre = "Galerie";
	protected static $texte_nouvelle_page = "Nouvelle période";
	protected static $icone_nouvelle_page = "nouvelle_periode.png";
	protected static $type_enfants = "mGaleriePeriode";
}

class mGaleriePeriode extends mGalerieBase {
	protected static $texte_titre = "Période";
	protected static $texte_nouvelle_page = "Nouvel événement";
	protected static $icone_nouvelle_page = "nouvel_evenement.png";
	protected static $type_enfants = "mGalerieEvenement";
}

class mGalerieEvenement extends mGalerieBase {
	protected static $texte_titre = "Événement";
	protected static $texte_nouvelle_page = "Nouvelle photo";
	protected static $icone_nouvelle_page = "nouvelle_photo.png";
	protected static $type_enfants = "mGaleriePhoto";
}

class mGaleriePhoto extends mGalerieBase {
	protected static $texte_titre = "Photo";
	
	public static function info() {
		ressources_statiques("c_style");
		ressources_dynamiques(inherit(get_parent_class()), "i_grande i_image i_miniature");
		attribut(inherit(get_parent_class()));
		attribut("image", "img_file", "");
	}
	
	public function set_titre($titre) {
		// set url quand on set titre !
		// TODO : valeur initiale pour l'url !
		niy("GaleriePhoto::set_titre($titre)");
	}

	public function set_image($fichier_image) {
		// Faire la miniature et l'image de taille "normale".
		niy("GaleriePhoto::set_image");
	}
	
	public function res_c_style() {
		niy("GaleriePhoto::res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, "".$this->description); // En-tête standard.
		$d->w_img_file($this->description, $this->i_image);
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

module("mGalerieIndex");
module("mGaleriePeriode");
module("mGalerieEvenement");
module("mGaleriePhoto");

?>