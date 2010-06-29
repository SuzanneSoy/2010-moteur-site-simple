<h1>Test</h1>
<?php

require_once("types/galerie.php");
require_once("types/galerie-periode.php");

$p = Page::_new("/modele/galerie");

print_r($p->vue());

?>