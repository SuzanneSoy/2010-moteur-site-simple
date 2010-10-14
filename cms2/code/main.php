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
		$page = Page::page_uid($_GET['uid_page']);
	} else {
		$page = Page::page_systeme('racine');
	}
	$rendu = $page->rendu($res);
	
	echo "<pre>";
	echo htmlspecialchars($rendu->to_XHTML_5());
	echo "</pre>";
	
	BDD::close();
	Debug::afficher();
	echo "</pre>";
}

?>