<?php

function main() {
	echo "<pre>";
	initModules();
	var_dump(Page::$modules);
	echo "</pre>";
	$bdd = BDD::get();
	BDD::reset();
	
	$g = new mAdminListeUtilisateurs();
	
	$p = $g->rendu();
	echo "<pre>";
	echo htmlspecialchars($p->to_XHTML_5());
	echo "</pre>";
	
	BDD::close();
	Debug::afficher();
}

?>