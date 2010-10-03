<?php

class StockageFichiers {
	public static function stocker_fichier($fichier, $uid) {
		// Stocker $fichier avec le nom $uid dans Config::get('chemin_base_stockage')
		niy("stocker fichier");
	}
	public static function stocker_upload($fichier, $id) {
		// Stocker $fichier avec le nom $uid dans Config::get('chemin_base_stockage')
		// Utiliser move_uploaded_file().
	}
}

?>