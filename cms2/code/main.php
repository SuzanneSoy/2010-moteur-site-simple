<?php

function main() {
	echo "<pre>";
	initModules();
	//var_dump(Page::$modules);
	
	$bdd = BDD::get();
	BDD::reset();

	$r = Page::page_uid(1);
	var_dump($r);
	
	$p = $r->rendu();
	echo "<pre>";
	echo htmlspecialchars($p->to_XHTML_5());
	echo "</pre>";
	
	BDD::close();
	Debug::afficher();
	echo "</pre>";
}

?>