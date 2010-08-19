<?php

// NOTE : Cette abstraction a l'air assez innutile à part supprimer...

class Système_fichiers {
	public static function créer_dossier($chemin_fs) {
		$d = dirname($chemin_fs);
                $r = true;
		if (!is_dir($d)) $r = self::créer_dossier($d);
		if (!is_dir($chemin_fs)) return $r && mkdir($chemin_fs);
	}
	
	/*public static function créer_fichier($chemin_fs) {
		// touch($chemin_fs)
	}*/
	
	public static function supprimer($chemin_fs, $récursif) {
            var_dump($chemin_fs);
		if (is_link($chemin_fs) || is_file($chemin_fs)) {
			unlink($chemin_fs);
		} else if ($récursif && is_dir($chemin_fs)) {
			$d = dir($chemin_fs);
			while (false !== ($entrée = $d->read())) {
				if ($entrée == '.') continue; // TODO : gérer correctement les boucles dans le système de fichiers (liens durs, ...)
				if ($entrée == '..') continue;
				self::supprimer($chemin_fs . '/' . $entrée, $récursif);
			}
			$d->close();
			rmdir($chemin_fs);
		} else if (is_dir($chemin_fs)) {
                    rmdir($chemin_fs);
                }
	}
	
	public static function lire($chemin_fs) {
		if (!file_exists($chemin_fs)) return  Erreur::lecture("Ne peut lire le fichier " . $chemin_fs);
		return file_get_contents($chemin_fs);
	}
	
	public static function écrire($chemin_fs, $données) {
		$d = dirname($chemin_fs);
		if (!is_dir($d) && !self::créer_dossier($d)) {
                    return Erreur::écriture("Impossible d'écrire dans $chemin_fs : le dossier $d n'existe pas");
                }
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
		// TODO : utiliser http://www.php.net/manual/en/function.readfile.php#86244 pour les téléchargements partiels
		// TODO : utiliser http://www.php.net/manual/en/function.readfile.php#52722 pour les types mimes
		// TODO : ou bien http://www.php.net/manual/en/function.header.php#48538 pour les types mimes
		//        (ou mieux, l'utiliser au cas où on ne trouve pas dans le fichier d'apache).
		// TODO : stocker le type mime dans $chemin_fs . '__mime' et utiliser celui-là si possible, sinon les méthodes ci-dessus.
		// Licence des bouts de code du manuel PHP : CC-Attribution http://php.net/manual/en/about.notes.php
		header("Content-Type: image/jpg");
		return readfile($chemin_fs);
	}
}

?>