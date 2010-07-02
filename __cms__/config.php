<?php

require_once("util.php");

$config_url_base = "http://" . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];
$config_chemin_base = dirname(__FILE__);

$config_chemin_modele = concaténer_chemin_fs($config_chemin_base, "/modele");
?>