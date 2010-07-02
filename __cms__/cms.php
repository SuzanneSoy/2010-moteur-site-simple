<?php

require_once("controleur/page.php");

require_once("types/galerie.php");
require_once("types/galerie-periode.php");
require_once("types/galerie-evenement.php");
require_once("types/galerie-photo.php");

class CMS {
    public static function affiche($url) {
        $p = Page::_new("/galerie");
        
        echo "<h1>Test</h1>";
        echo $p->vue();
    }
}
?>