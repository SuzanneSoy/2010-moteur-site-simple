<?php

class mAccueil extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre_presentation", "text_line", "L'association");
		Module::attribut("texte_presentation", "text_rich", "");
		Module::attribut("publier", "bool", "true");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre_presentation, $this->texte_presentation); // En-tête standard.
		
		mPage::page_systeme("nouveautes")->rendu($d);
		
		return $d;
	}
}

?>