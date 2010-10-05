<?php

function main() {
	$g = new GalerieIndex();
	Debug::afficher();
	
	$p = $g->rendu();
	echo "<pre>";
	echo htmlspecialchars($p->to_XHTML_5());
	echo "</pre>";
}

?>