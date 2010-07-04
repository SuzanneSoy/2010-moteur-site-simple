<?php

 // Tous les chemins pour les include sont relatifs à __cms__ .
chdir(dirname(__FILE__));

require_once("controleur/page.php");

require_once("types/galerie.php");
require_once("types/galerie-periode.php");
require_once("types/galerie-evenement.php");
require_once("types/galerie-photo.php");

class CMS {
    public static function uri_vers_chemin($uri) {
        global $config_url_base;
        // TODO : Pas propre !
        $base = "/" . preg_replace("/^https?:\/\/[^\/]*\//", "", $config_url_base, 1);
        if (strpos($uri, $base) == 0) {
            return '/' . substr($uri, strlen($base));
        } else {
            return $uri;
        }
    }
    
    public static function affiche($uri) {
        $p = Page::_new(CMS::uri_vers_chemin($uri));
        
        echo "<h1>Test</h1>";
        echo $p->vue();
    }
}
?>