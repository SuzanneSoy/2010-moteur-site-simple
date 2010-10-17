<?php

class mInterpretes extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "Partitions");
		Module::attribut("description", "text_line", "");
		Module::attribut("publier", "bool", "true");
		Module::type_liens("enfants", "mPartitionsPartition");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, $this->description); // En-tête standard.
		
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$e->rendu("h_page", $d);
			'));
		
		return $d;
	}
}

class mInterpretesInterprete extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "Nom de l'interprète");
		Module::attribut("commentaire", "text_rich", "");
		Module::attribut("publier", "bool", "true");
		Module::type_liens("enregistrements", "mEnregistrementsEnregistrement");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, $this->commentaire);
		
		// TODO : enfants catégorie enregistrements
		$d->hx("Enregistrements faits par cet interprète :");
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$li->a($e->url())->text($e->titre);
			'));

		return $d;
	}
}

Module::add_module("mInterpretes");
Module::add_module("mInterpretesInterprete");

?>