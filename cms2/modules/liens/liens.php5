<?php

class mLiens extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "Liens");
		Module::attribut("description", "text_line", "Liens utiles.");
		Module::attribut("publier", "bool", "true");
		Module::type_liens("enfants", "mLiensLien");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, $this->description); // En-tête standard.
		
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$li->a($e->cible)->text($e->texte);
			'));
		
		return $d;
	}
}

class mLiensLien extends mPage {
	public static function info($module) {
		Module::attribut("texte", "text_line", "Texte du lien");
		Module::attribut("cible", "text_rich", "http://www.example.com/");
	}
}

Module::add_module("mLiens");
Module::add_module("mLiensLien");

?>