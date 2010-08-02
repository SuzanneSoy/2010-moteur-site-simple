<?php

function formulaire_édition_texte_enrichi($données, $nom_champ) {
	// Renvoie un formulaire d'édition de texte, avec comme valeur
	// $données, et comme nom de champ POST $nom_champ.
	
	// Peut être juste un textarea, un éditeur wysiwym, ...
	// Le format des données est libre, et n'est utilisé que par les
	// fonctions de ce fichier.
	
	// TODO : comment stocker / utiliser des images etc. dans le texte
	// enrichi ?
	
	// Pour l'instant, juste du texte brut. TODO : éditeur wysiwym
	return '<textarea name="' . $nom_champ . '">' . $données . '</textarea>'; // TODO : escape html chars & co.
}

function affichage_texte_enrichi($données) {
	// Renvoie $données formattées en HTML.
	
	// Les $données sont au même format que celles produites par le
	// formulaire de la fonction ci-dessus.
	
	return "<p>" . $données . "</p>"; // escape html chars & co.
}

?>