<h1>Test</h1>
<?php

require_once("types/galerie.php");

$p = new Galerie("/modele");

print_r($p->vue());

?>