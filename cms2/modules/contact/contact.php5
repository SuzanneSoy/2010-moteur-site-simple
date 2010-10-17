<?php

class mContacts extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "Contact");
		Module::attribut("publier", "bool", "true");
		Module::type_liens("enfants", "mContactsContact");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_titre($this->titre);
		
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$li->a($e->cible)->text($e->texte);
			'));
		
		return $d;
	}
}

class mContactsContact extends mPage {
	public static function info($module) {
		Module::attribut("texte", "text_line", "Texte du lien");
		Module::attribut("cible", "text_rich", "http://www.example.com/");
	}
}

Module::add_module("mContacts");
Module::add_module("mContactsContact");

?>