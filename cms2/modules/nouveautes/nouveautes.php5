<?php

class mNouveautes extends mPage {
	public static function info($module) {
		Module::ressources_statiques("i_icône_nouvelle_page c_style");
		Module::ressources_dynamiques("h_page");
		Module::type_liens("sources", "*");
		Module::attribut("titre", "text_line", "Nouveautés");
		Module::attribut("description", "text_rich", "");
		Module::attribut_global("dans_nouveautes", "bool", "true");
	}
	
	public function res_i_icône_nouvelle_page() {
		return StockageFichiers::envoyer_fichier_statique(Path::combine(dirname(__FILE__), "nouvelle_source.png"));
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete(); // En-tête standard.
		
		$l = $d->article()->w_liste($this->enfants(true, "-date_creation", 10), create_function('$e, $li', '
				$a = $li->a($e->uid());
				// TODO : h_miniature_nouveautes s\'il existe sinon h_miniature sinon juste un lien.
				// Comme ça le h_miniature_nouveautes d\'une période de la galerie, c\'est 3 ou 4 images alors que normalement c\'en est juste une seule.
				$e->rendu("h_miniature", $a);
			'));
		$nouveau = $l->li();
		$nouveau->span("miniature")->img("", $this->url("i_icône_nouvelle_page"));
		$nouveau->span("action")->text("Ajouter un article aux nouveautés.");
		return $d;
	}
}

Module::add_module("mNouveautes");

?>