<?php

abstract class GalerieBase extends Page {
	protected static $ressources_statiques = array("i_icône_nouvelle_page image/jpeg", "c_style text/css");
	protected static $ressources_dynamiques = array("h_page Document", "h_miniature Document", "h_mini_miniature Document");
	protected static $attributs = array(
		"titre" => "Galerie",
		"description" => ""
	);
	protected static $enfants = "GalerieÉvènement";

	protected static $texte_nouvelle_page = "Nouvel élément";
	protected static $fichier_nouvelle_page = "nouvelle_periode.png";
	
	public function res_i_icône_nouvelle_page() {
		return StockageFichiers::envoyer_fichier_statique(Path::combine(dirname(__FILE__), self::$fichier_nouvelle_page));
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page() {
		$d = new Document();
		$d->w_en_tete(); // En-tête standard.
		$l = $d->article()->w_liste($this->select("./*", "date desc"), function($e, $li) {
			$li->a($e->uid())->append(
				$e->rendu("h_miniature")
			);
		});
		$nouveau = $l->li();
		$nouveau->span("miniature")->img("", $this->url("i_icône_nouvelle_page"));
		$nouveau->span("titre")->texte(self::$texte_nouvelle_page);
		return $d;
	}
	
	public function res_h_miniature() {
		$e = new ElementDocument();
		$e->span("miniature")->append($this->res_h_miniature_image());
		$e->span("titre")->_field($this->titre);
		return $e;
	}
	
	public function res_h_miniature_image() {
		// Prendre le 1er par ordre décroissant sur la date, ou bien :
		// TODO : prendre l'élément ayant la propriété "aperçu" à true (s'il y en a un, sinon date).
		return $this->select("./*", "date desc", 1)->rendu("h_miniature_image");
	}
}

class GalerieIndex extends GalerieBase {
	protected static $texte_nouvelle_page = "Nouvelle période";
	protected static $fichier_nouvelle_page = "nouvelle_periode.png";
}

class GaleriePériode extends GalerieBase {
	protected static $texte_nouvelle_page = "Nouvel événement";
	protected static $fichier_nouvelle_page = "nouvel_evenement.png";
}

class GalerieÉvénement extends GalerieBase {
	protected static $texte_nouvelle_page = "Nouvelle photo";
	protected static $fichier_nouvelle_page = "nouvelle_photo.png";
}

Page::ajouter_type("GalerieIndex");

?>