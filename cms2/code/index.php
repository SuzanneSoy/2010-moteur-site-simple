<?php

error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1); // Ne s'appliquera pas au fichier courant !

require_once(dirname(__FILE__) . "/debug.php");

$old_error_handler = set_error_handler(array("Debug", "error_handler"), E_ALL | E_STRICT);
$old_error_handler = set_error_handler(array("Debug", "error_handler"), E_ALL | E_STRICT);
register_shutdown_function(array("Debug", "error_handler"));

if ($old_error_handler === null) {
	echo "<pre>Erreur lors de la mise en place des mécanismes de détection et d'affichage d'erreurs.</pre>";
	die();
}

require_once(dirname(__FILE__) . "/main.php");

?>