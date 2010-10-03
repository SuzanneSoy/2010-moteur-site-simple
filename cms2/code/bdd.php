<?php

// TODO : Comment pseudo-insérer des images dans la BDD ? Est-ce la classe de la page qui décide de les déplacer dans /data et de mettre juste le nom dans la BDD ?
//        (Et c'est à ce moment-là que la miniature est faite). Ou bien, un "trigger" (get et set) pour faire la même chose ?

// TODO : Pouvoir stocker des collections de propriétés avec le même nom... Par ex. une photo peut avoir une collection de personnes.

/*
 Base de données :
 table page (uid autoincrement primary key, nomSysteme, composantUrl, parent)
   Racine : (?,'racine','',NULL)
 table propriétés(uid auto pk, fk uid_page, bool systeme, nom, valeur)
   (?,true,dateCréation,?)
   (?,true,dateModification,?)
   (?,false,publier,?)
   (?,true,nomSysteme,?)
   (?,true,composantUrl,?)
*/

// Lors d'une requête, ne renvoyer que les éléments que l'utilisateur a
// le droit de voir. Filtrage après la requête (attention au LIMIT et OFFSET !) ?
// ou y a-t-il moyen d'exprimer ça directement dans la requête ?

class DB extends Selectable {
	private static $handle = null;
	public function __construct() {
		if (self::$handle === null) {
			niy("Connexion à la BDD");
		} else {
			return self::$handle;
		}
	}
}

class Selectable {
	// TODO : comment faire un select sur la table page ?
	// TODO : comment créer une nouvelle page ?
	function select($qw_champs, $qw_ordre = "", $limit = 0, $offset = 0) {
		// Retourne un selectable, qui a une méthode run() (la méthode run renvoie la liste d'éléments. Chaque élément a une méthode set()).
		// Une limit de 0 signifie qu'on prend tous les éléments à partir de $offset.
	}

	function set($valeur, $qw_champs) {
		// Appelle set(valeur) sur chaque élément de $this->select(champs, ordre, 0, 0)->run()
	}

	function set_with_uid($valeur, int $uid) {
		// Appelle set(valeur) sur la ligne de la base de données avec cet uid.
		// Les <form> des pages ont des champ <input type="hidden" name"_uid_"> et un bouton submit.
		// Lorsqu'on active ce submit, les lignes correspondant aux _uid_ dans la base de données reçoivent la bonne valeur.
	}

	function sql() {
	}

	function add_setter($condition, $fonction_setter) {
		// Si on fait un set qui remplit la condition, la fonction_setter est appellée au lieu de modifier directement la bdd.
		// Condition peut être :
		// true                       // toujours appellé (pour les permissions, les dates de modification etc.).
		// type de page parente       // lorsqu'on récupère la liste des enfants d'une page de ce type.
		// type de page, nom attribut // lorsqu'on récupère cet attribut d'une page de ce type.
	}
	
	function add_getter($condition, $fonction_getter) {
		// Comme add_setter().
	}
}


?>