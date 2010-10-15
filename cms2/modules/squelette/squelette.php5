<?php

class mSquelette extends mPage {
	// Trouver un moyen pour que mSquelette soit appellé après avoir généré la page, pour qu'il puisse l'emballer.
	
	public static function info($module) {
		ressources_dynamiques("c_css_principal text/css");
	}
	
	public function res_c_css_principal() {
		// mettre bout à bout tous les CSS ?
		niy("res_c_css_principal");
	}
}

module("mSquelette");

?>