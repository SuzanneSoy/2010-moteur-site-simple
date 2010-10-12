<?php

function main() {
	echo "<pre>";
	initModules();
	//var_dump(Page::$modules);
	
	$bdd = BDD::get();
	BDD::reset();
	echo "</pre>";
	
	$g = new mAdminListeUtilisateurs();
	
	$p = $g->rendu();
	echo "<pre>";
	echo htmlspecialchars($p->to_XHTML_5());
	echo "</pre>";
	
	BDD::close();
	Debug::afficher();
}

?>