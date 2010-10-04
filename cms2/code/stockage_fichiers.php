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
	public static function récupérer_fichier($uid) {
		// sendfile ?
	}
	public static function url_fichier($uid) {
	}
	public static function récupérer_fichier_statique() {
		// sendfile ?
	}
	public static function url_fichier_statique($chemin) {
		// chemin relatif à __cms__. Utile pour récupérer les icônes définies par les modules etc.
	}
}

?>