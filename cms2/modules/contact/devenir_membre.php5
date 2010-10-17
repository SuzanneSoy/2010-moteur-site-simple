<?php

class mDevenirMembre extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "Biographie");
		Module::type_liens("enfants", "mPhoto");
		Module::attribut("texte_dm", "text_rich", "");
		Module::attribut("publier", "bool", "true");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, $this->texte_dm); // En-tête standard.
		
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$a = $li->a($e->url());
				$e->rendu("h_miniature", $a);
			'));
		
		return $d;
	}
}

Module::add_module("mDevenirMembre");

?>