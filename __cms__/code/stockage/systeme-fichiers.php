<?php

// NOTE : Cette abstraction a l'air assez innutile à part supprimer...

class Système_fichiers {
	public static function créer_dossier($chemin_fs) {
		mkdir($chemin_fs);
	}
	
	/*public static function créer_fichier($chemin_fs) {
		// touch($chemin_fs)
	}*/
	
	public static function supprimer($chemin_fs, $récursif) {
		if (is_link($chemin_fs) || is_file($chemin_fs)) {
			unlink($chemin_fs);
		} else if ($récursif) {
			$d = dir($chemin_fs);
			while (false !== ($entrée = $d->read())) {
				self::supprimer($chemin_fs . '/' . $entrée, $récursif);
			}
			$d->close();
			rmdir($chemin_fs);
		}
	}
	
	public static function lire($chemin_fs) {
		if (!file_exists($chemin_fs)) return false;
		return file_get_contents($chemin_fs);
	}
	
	public static function écrire($chemin_fs, $données) {
		if (!is_dir(dirname($chemin_fs))) return false;
		return file_put_contents($chemin_fs, $données);
	}
	
	public static function liste_fichiers($chemin_fs) {
		if (!is_dir($chemin_fs)) return false;
		return scandir($chemin_fs);
	}
	
	public static function déplacer($chemin_fs_de, $chemin_fs_vers) {
		rename($chemin_fs_de, $chemin_fs_vers);
	}
	
	public static function déplacer_fichier_téléchargé($chemin_fs_de, $chemin_fs_vers) {
		return move_uploaded_file($chemin_fs_de, $chemin_fs_vers);
	}
	
	public static function envoyer_fichier_directement($chemin_fs) {
		return readfile($chemin_fs);
	}
}

?>