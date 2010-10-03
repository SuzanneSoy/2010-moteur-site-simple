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
		$d->heading->standard();
		$l = $d->article(1)->append->liste(select(/*todo*/), function($e) {/*todo*/});
		// todo $l->append->...
	}
	
	public function res_h_miniature() {
		return $this->res_h_miniature_image();
		// todo : ajouter le titre etc.;
	}
	
	public function res_h_miniature_image() {
		// Prendre le 1er par ordre décroissant sur la date, ou bien :
		// TODO : prendre l'élément ayant la propriété "aperçu" à true (s'il y en a un, sinon date).
		return $this->select("./*", "date desc", 1)->mini_miniature;
	}
}

Page::ajouter_type("GalerieIndex");

?>