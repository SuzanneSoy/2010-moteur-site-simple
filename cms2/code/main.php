<?php

function main() {
	$bdd = BDD::get();
	BDD::reset();
	BDD::close();
	Debug::afficher();
	exit;
	
	$g = new AdminListeUtilisateurs();
	
	$p = $g->rendu();
	echo "<pre>";
	echo htmlspecialchars($p->to_XHTML_5());
	echo "</pre>";
	
	Debug::afficher();
}

?>