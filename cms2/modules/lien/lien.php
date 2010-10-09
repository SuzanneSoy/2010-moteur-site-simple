<?php

class Lien extends Page {
	public static function ressources_statiques() {
		return qw();
	}
	public static function ressources_dynamiques() {
		return qw();
	}
	public static function types_enfants() {
		return qw();
	}
	public static function attributs() {
		return array(
			attribut("lien", "uid", "0")
		);
	}
}

Page::ajouter_type("Lien");

?>