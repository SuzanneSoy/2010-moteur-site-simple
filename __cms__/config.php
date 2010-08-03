<?php

// ========== CONFIGURATION =========

// Addresse de courriel de l'administrateur.
Config::set('courriel_admin', "jahvascriptmaniac+github@free.fr");

// URL de la racine du site. Ex: http://www.monsite.com/cms/
// Doit se terminer par '/'.
Config::set('url_base', "http://127.0.0.1/2010-moteur-site-simple/");

// Chemin absolu vers le dossier '__cms__'.
// dirname(__FILE__) peut retourner un chemin relatif (PHP < 4.0.2),
// donc utiliser realpath si on s'en sert.
Config::set('chemin_base', realpath(dirname(__FILE__)));

// Chemin vers le stockage interne des données.
// En général, c'est le chemin ..../__cms__/modele
Config::set('chemin_base_stockage', Path::combine(Config::get("chemin_base"), "donnees"));

// Chemin vers la partie visible du site.
// En général, c'est le chemin vers le dossier contenant __cms__
Config::set('chemin_base_public', Path::combine(Config::get("chemin_base"), "/.."));

// ======== FIN CONFIGURATION =======

?>