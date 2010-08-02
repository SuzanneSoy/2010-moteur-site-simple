<?php

// NOTE : Cette abstraction a l'air assez innutile à part supprimer...

class SystèmeFichiers {
	public function créer_dossier($chemin_fs) {
		mkdir($chemin_fs);
	}
	
	/*public function créer_fichier($chemin_fs) {
		// touch($chemin_fs)
	}*/
	
	public function supprimer($chemin_fs, $récursif) {
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
	
	public function lire($chemin_fs) {
		file_get_contents($chermin_fs);
	}
	
	public function écrire($chemin_fs, $données) {
		file_put_contents($chemin_fs, $données);
	}
	
	public function liste_fichiers($chemin_fs) {
		return scandir($chemin_fs);
	}
	
	public function déplacer($chemin_fs_de, $chemin_fs_vers) {
		rename($chemin_fs_de, $chemin_fs_vers);
	}
	
	public function déplacer_fichier_téléchargé($chemin_fs_de, $chemin_fs_vers) {
		return move_uploaded_file($chemin_fs_de, $chemin_fs_vers);
	}
	
	public function envoyer_fichier_directement($chemin_fs) {
		return readfile($chemin_fs);
	}
}

?>