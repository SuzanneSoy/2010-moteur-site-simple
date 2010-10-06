<?php

function main() {
	$g = new AdminListeUtilisateurs();
	
	$p = $g->rendu();
	echo "<pre>";
	echo htmlspecialchars($p->to_XHTML_5());
	echo "</pre>";
	
	Debug::afficher();
}

?>