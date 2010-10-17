<?php

class mEnregistrements extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "Partitions");
		Module::attribut("description", "text_line", "");
		Module::attribut("publier", "bool", "true");
		Module::type_liens("enfants", "mEnregistrementsEnregistrement");
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

class mEnregistrementsEnregistrement extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "Nom du morceau");
		Module::attribut("commentaire", "text_rich", "");
		Module::attribut("publier", "bool", "true");
		Module::attribut("enregistrement", "file_audio", "");
		Module::attribut("complet", "bool", "true");
		Module::type_liens("partitions", "mPartitionsPartition");
		Module::type_liens("interpretes", "mInterpretesInterprete");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, $this->commentaire);
		
		$d->w_field($this->enregistrement);
		$d->w_field($this->complet);
		
		// TODO : enfants catégorie partitions
		$d->hx("Partitions de ce morceau :");
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$li->a($e->url())->text($e->titre);
			'));

		// TODO : enfants catégorie interpretes
		$d->hx("Interprètes :");
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$li->a($e->url())->text($e->titre);
			'));
		
		return $d;
	}
}

Module::add_module("mEnregistrements");
Module::add_module("mEnregistrementsEnregistrement");

?>