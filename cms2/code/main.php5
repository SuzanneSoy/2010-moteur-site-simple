<?php

function verifications() {
	// url_base doit toujours se terminer par '/'.
	Config::set('url_base', preg_replace("|/*$|", "/", Config::get('url_base'), 1));
}
verifications();

function main() {
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
	BDD::close();

	$rendu->erreurs()->litteral(_Debug::afficher());
	$rendu = $rendu->to_XHTML_5();

	echo $rendu;
	// echo "<pre>" . htmlspecialchars($rendu) . "</pre>";
}

?>