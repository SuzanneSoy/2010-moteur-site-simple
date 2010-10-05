<?php

require_once(dirname(__FILE__) . "/configuration.php");
require_once(dirname(__FILE__) . "/include.php");

function main() {
	$g = new GalerieIndex();
	$g->res_h_page();

	Debug::afficher();
}

//trigger_error("ABCDE", E_USER_NOTICE);
main();

?>