<?php

function main() {
	$bdd = BDD::get();
	BDD::reset();
	
	$g = new AdminListeUtilisateurs();
	
	$p = $g->rendu();
	echo "<pre>";
	echo htmlspecialchars($p->to_XHTML_5());
	echo "</pre>";
	
	BDD::close();
	Debug::afficher();
}

?>