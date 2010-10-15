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
	
	public static function envoyer_fichier($uid) {
		// sendfile
		niy("récupérer_fichier($chemin);");
	}
	
	public static function envoyer_fichier_statique($chemin) {
		// TODO : utiliser http://www.php.net/manual/en/function.readfile.php#86244 pour les téléchargements partiels
		// TODO : utiliser http://www.php.net/manual/en/function.readfile.php#52722 pour les types mimes
		// TODO : ou bien http://www.php.net/manual/en/function.header.php#48538 pour les types mimes
		//        (ou mieux, l'utiliser au cas où on ne trouve pas dans le fichier d'apache).
		// TODO : stocker le type mime dans $chemin_fs . '__mime' et utiliser celui-là si possible, sinon les méthodes ci-dessus.
		// Licence des bouts de code du manuel PHP : CC-Attribution http://php.net/manual/en/about.notes.php
		header("Content-Type: image/jpg");
		return readfile($chemin);
	}
}

?>