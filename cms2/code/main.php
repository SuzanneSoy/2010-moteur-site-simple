<?php

function verifications() {
	// url_base doit toujours se terminer par '/'.
	Config::set('url_base', preg_replace("|/*$|", "/", Config::get('url_base'), 1));
}
verifications();

function main() {
	echo "<pre>";
	initModules();
	
	// Attention ! ne pas garder BDD::reset() en production !
	BDD::reset();
	
	$res = array_key_exists('res', $_GET) ? $_GET['res'] : null;
	if (array_key_exists('uid_page', $_GET)) {
		$page = mPage::page_uid($_GET['uid_page']);
	} else {
		$page = mPage::page_systeme('racine');
	}
	
	$rendu = $page->rendu($res);
	$rendu = htmlspecialchars($rendu->to_XHTML_5());
	
	BDD::close();
	
	echo Debug::afficher();
	echo "<pre>";
	echo $rendu;
	echo "</pre>";
}

?>