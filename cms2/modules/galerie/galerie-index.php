<?php

class GalerieIndex extends Page {
	protected static $ressources_statiques = array("i_icône_nouvelle_période image/jpeg", "c_style text/css");
	protected static $ressources_dynamiques = array("h_page Document", "h_miniature Document", "h_mini_miniature Document");
	protected static $attributs = array(
		"titre" => "Galerie",
		"description" => ""
	);
	protected static $enfants = "GalerieÉvènement";
	
	public function res_i_icône_nouvelle_période() {
		niy("res_i_icône_nouvelle_période");
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
		$nouveau->span("miniature")->img("", $this->url("i_icône_nouvelle_période"));
		$nouveau->span("titre")->texte("Nouvelle période");
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

Page::ajouter_type("GalerieIndex");

?>