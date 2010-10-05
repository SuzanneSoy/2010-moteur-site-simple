<?php

abstract class GalerieBase extends Page {
	protected static $texte_titre = "Galerie";
	protected static $texte_nouvelle_page = "Nouvel élément";
	protected static $icône_nouvelle_page = "nouvelle_periode.png";
	
	
	public static function ressources_statiques() {
		return qw("i_icône_nouvelle_page c_style"); }
	public static function ressources_dynamiques() {
		return qw("h_page h_miniature h_mini_miniature");
	}
	public static function types_enfants() {
		return qw("GalerieÉvènement");
	}
	public static function attributs() {
		return array(
			"titre" => self::$texte_titre,
			"description" => ""
		);
	}
	
	public function res_i_icône_nouvelle_page() {
		return StockageFichiers::envoyer_fichier_statique(Path::combine(dirname(__FILE__), self::$icône_nouvelle_page));
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page() {
		$d = new Document();
		$d->w_en_tete(); // En-tête standard.
		$l = $d->article()->w_liste($this->enfants(true, "date desc"), function($e, $li) {
				$li->a($e->uid())->append(
					$e->rendu("h_miniature")
				);
			});
		$nouveau = $l->li();
		$nouveau->span("miniature")->img("", $this->url("i_icône_nouvelle_page"));
		$nouveau->span("titre")->text(self::$texte_nouvelle_page);
		return $d;
	}
	
	public function res_h_miniature() {
		$e = new ElementDocument();
		$e->span("miniature")->append($this->res_h_mini_miniature());
		$e->span("titre")->_field($this->titre);
		return $e;
	}
	
	public function res_h_mini_miniature() {
		$a = $this->enfants("@apercu = true", "date desc", 1);
		if ($a->size() != 1)
			$a = $this->enfants(true, "date desc", 1);
		return $a->get(0)->rendu("h_mini_miniature");
	}
}

class GalerieIndex extends GalerieBase {
	protected static $texte_titre = "Galerie";
	protected static $texte_nouvelle_page = "Nouvelle période";
	protected static $icône_nouvelle_page = "nouvelle_periode.png";
}

class GaleriePériode extends GalerieBase {
	protected static $texte_titre = "Période";
	protected static $texte_nouvelle_page = "Nouvel événement";
	protected static $icône_nouvelle_page = "nouvel_evenement.png";
}

class GalerieÉvénement extends GalerieBase {
	protected static $texte_titre = "Événement";
	protected static $texte_nouvelle_page = "Nouvelle photo";
	protected static $icône_nouvelle_page = "nouvelle_photo.png";
}

class GaleriePhoto {
	protected static $texte_titre = "Photo";
	
	public static function ressources_statiques() {
		return qw("c_style");
	}
	public static function ressources_dynamiques() {
		return qw(parent::ressources_dynamiques(), "i_grande i_image i_miniature");
	}
	public static function types_enfants() {
		return null;
	}
	public static function attributs() {
		$a = parent::attributs();
		$a["image"] = null; // TODO !! TODO !! TODO
		return $a;
	}

	public function set_titre($titre) {
		// TODO : set url quand on set titre !
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
	
	public function res_h_page() {
		$d = new Document();
		$d->w_en_tete(); // En-tête standard.
		// TODO : la description devrait être soit dans w_en_tete, soit dans w_img !
		$d->w_img($this->description, $this->i_image);
		return $d;
	}
	
	public function res_h_mini_miniature() {
		$d = new Document();
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

Page::ajouter_type("GalerieIndex");
Page::ajouter_type("GaleriePériode");
Page::ajouter_type("GalerieÉvénement");
Page::ajouter_type("GaleriePhoto");

?>