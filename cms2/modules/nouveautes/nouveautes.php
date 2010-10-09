<?php

abstract class Nouveautes extends Page {
	protected static $texte_titre = "Nouveautés";
	protected static $texte_nouvelle_page = "Ajouter un article aux nouveautés";
	protected static $icône_nouvelle_page = "nouvelle_source.png";
	
	public static function ressources_statiques() {
		return qw("i_icône_nouvelle_page c_style");
	}
	public static function ressources_dynamiques() {
		return qw("h_page");
	}
	public static function types_enfants() {
		return qw("NouveautesSource");
	}
	public static function attributs() {
		return array(
			"titre" => self::$texte_titre,
			"description" => "",
		);
	}
	
	public function res_i_icône_nouvelle_page() {
		return StockageFichiers::envoyer_fichier_statique(Path::combine(dirname(__FILE__), self::$icône_nouvelle_page));
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete(); // En-tête standard.
		
		$l = $d->article()->w_liste($this->enfants(true, "date_creation desc", 10), function($e, $li) {
				$a = $li->a($e->uid());
				$e->rendu("h_miniature", $a);
			});
		$nouveau = $l->li();
		$nouveau->span("miniature")->img("", $this->url("i_icône_nouvelle_page"));
		$nouveau->span("titre")->text(self::$texte_nouvelle_page);
		return $d;
	}
}

Page::ajouter_type("Nouveautes");


?>