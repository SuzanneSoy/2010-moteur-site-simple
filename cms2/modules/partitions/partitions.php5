<?php

class mPartitions extends mPage {
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

class mPartitionsPartition extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "Nom du morceau");
		Module::attribut("commentaire", "text_rich", "");
		Module::attribut("publier", "bool", "true");
		Module::attribut("partition", "file_pdf", "");
		Module::type_liens("enregistrements", "mEnregistrementsEnregistrement");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, $this->commentaire);
		
		$d->w_field($this->partition);
		
		// TODO : enfants catégorie enregistrements
		$d->hx("Enregistrements de ce morceau :");
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$li->a($e->url())->text($e->titre);
			'));

		return $d;
	}
}

Module::add_module("mPartitions");
Module::add_module("mPartitionsPartition");

?>