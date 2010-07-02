<?php

require_once("util.php");

$config_url_base = "http://127.0.0.1/2010-moteur-site-simple/";
$config_chemin_base = dirname($_SERVER[SCRIPT_FILENAME]);

$config_chemin_modele = concaténer_chemin_fs($config_chemin_base, "/modele");
?>