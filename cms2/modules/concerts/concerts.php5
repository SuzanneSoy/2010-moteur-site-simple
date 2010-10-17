<?php

class mConcerts extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "Concerts");
		Module::attribut("description", "text_line", "");
		Module::attribut("publier", "bool", "true");
		Module::type_liens("enfants", "mConcertsAnnee");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_en_tete($this->titre, $this->description); // En-tête standard.
		
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				return $li->a($e->url())->text($e->titre);
			'));
		
		return $d;
	}
}

class mConcertsAnnee extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("titre", "text_line", "2010");
		Module::attribut("publier", "bool", "true");
		Module::type_liens("enfants", "mConcertsConcert");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_titre($this->titre); // En-tête standard.
		
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$e->rendu("h_page", $d);
			'));
		
		return $d;
	}
}

class mConcertsConcert extends mPage {
	public static function info($module) {
		Module::ressources_statiques("c_style");
		Module::ressources_dynamiques("h_page");
		Module::attribut("date", "text_line", "15 Décembre 2010");
		Module::attribut("lieu", "text_line", "");
		Module::attribut("commentaire", "text_rich", "");
		Module::attribut("publier", "bool", "true");
		Module::type_liens("photos", "mPhoto");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->hx()->text("Date : ");
		$d->w_field($this->date);
		
		$d->text("Lieu : ");
		$d->w_field($this->lieu);
		
		$d->w_field($this->commentaire);
		
		// TODO : enfants catégorie photos
		$d->hx("Photos du concert :");
		$d->article()->w_liste($this->enfants(true, "-date_creation"), create_function('$e, $li', '
				$e->rendu("h_miniature", $d);
			'));

		// TODO : articles de presse.
		
		return $d;
	}
}

Module::add_module("mConcerts");
Module::add_module("mConcertsAnnee");
Module::add_module("mConcertsConcert");

?>