<h1>Test</h1>
<?php

require_once("types/galerie.php");
require_once("types/galerie-periode.php");
require_once("types/galerie-evenement.php");
require_once("types/galerie-photo.php");

$p = Page::_new("/galerie");

print_r($p->vue());

?>