<h1>Test</h1>
<?php

require_once("types/galerie.php");
require_once("types/galerie-periode.php");
require_once("types/galerie-evenement.php");

$p = Page::_new("/modele/galerie");

print_r($p->vue());

?>