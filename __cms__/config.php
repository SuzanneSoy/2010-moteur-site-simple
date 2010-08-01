<?php

// Fonction Path::normalize();
require_once(dirname(__FILE__) . "/chemin/path.php");

// ========== CONFIGURATION =========

// URL de la racine du site. Ex: http://www.monsite.com/cms/
// Doit se terminer par '/'.
$config_url_base = "http://127.0.0.1/2010-moteur-site-simple/";

// Chemin absolu vers le dossier '__cms__'.
// dirname(__FILE__) peut retourner un chemin relatif (PHP < 4.0.2),
// donc utiliser realpath si on s'en sert.
$config_chemin_base = realpath(dirname(__FILE__));

// Chemin vers le stockage interne des données.
// En général, c'est le chemin ..../__cms__/modele
$config_chemin_base_stockage = Path::combine($config_chemin_base, "modele");

// Chemin vers la partie visible du site.
// En général, c'est le chemin vers le dossier contenant __cms__
$config_chemin_base_public = Path::combine($config_chemin_base, "/..");

// ======== FIN CONFIGURATION =======

?>