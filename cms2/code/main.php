<?php

function verifications() {
	// url_base doit toujours se terminer par '/'.
	Config::set('url_base', preg_replace("|/*$|", "/", Config::get('url_base'), 1));
}
verifications();

function main() {
	echo "<pre>";
	initModules();
	//var_dump(Page::$modules);
	
	$bdd = BDD::get();
	BDD::reset();

	$r = Page::page_uid(1);
	
	$p = $r->rendu();
	echo "<pre>";
	echo htmlspecialchars($p->to_XHTML_5());
	echo "</pre>";
	
	BDD::close();
	Debug::afficher();
	echo "</pre>";
}

?>