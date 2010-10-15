<?php

error_reporting(E_ALL | E_STRICT);
// display_errors ne s'appliquera pas au fichier courant,
// alors gardons-le aussi court que possible !
ini_set("display_errors", 1);

require_once(dirname(__FILE__) . "/configuration.php");
require_once(dirname(__FILE__) . "/include.php");

main();

?>