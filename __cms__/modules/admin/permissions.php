<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else {
		if (is_set($paramètres["regles"])) {
			// Stocker les regles dans regles, peut-être faire une
			// sauvegarde des règles actuelles ?
		}
		
		if (is_set($paramètres["vue"])) {
			self::vue($chemin, $paramètres["vue"]);
		} else {
			self::vue($chemin);
		}
	}
}

function vue($chemin, $vue = "normal") {
	if ($vue == "normal") {
		// Si l'utilisateur a l'autorisation de modifier les propriétés,
		// on affiche la version modifiable plutôt que la "vue".
		$ret = "";
		$ret .= "<h1>Règles de sécurité</h1>";
		$ret .= "<p>La première règle correspondant à une action de l'utilisateur est appliquée. Bla-bla blabla sur le fonctionnement.</p>";
		$ret .= "<textarea ... Règles />";
		return "Vue normale de la page.";
	}
}

?>
