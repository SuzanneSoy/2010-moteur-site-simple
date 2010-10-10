<?php

// Le widget w_img doit gérer le stockage de l'image dans un dossier, la création de la miniature et le stockage dans la BDD du chemin vers l'image.

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


class BDD {
	private static $handle = null;
	public static function get() {
		if (self::$handle === null) {
			self::$handle = @mysql_connect(
				Config::get('db_hôte'),
				Config::get('db_utilisateur'),
				Config::get('db_mot_de_passe')
			);
			if (!is_resource(self::$handle)) {
				Debug::error("Échec à la connexion à la base de données");
			}
			mysql_select_db(Config::get('db_base'), self::$handle) or Debug::sqlerror();
			self::init();
		}
		return self::$handle;
	}
	
	// ATTENTION : Ré-initialise toute la base de données !!!
	public static function reset() {
		self::unbuf_query('drop table if exists ' . self::table("pages"));
		self::unbuf_query('drop table if exists ' . self::table("enfants"));
		self::unbuf_query('drop table if exists ' . self::table("proprietes"));
		self::init();
	}

	public static function init() {
		self::unbuf_query('create table if not exists ' . self::table("pages") . ' ('
						  . 'uid_page        integer auto_increment primary key'
						  . ')');
		self::unbuf_query('create table if not exists ' . self::table("enfants") . ' ('
						  . 'uid_page        integer,'
						  . 'uid_page_parent integer,'
						  . 'groupe          char(10)'
						  . ')');
		self::unbuf_query('create table if not exists ' . self::table("proprietes") . ' ('
						  . 'uid_prop        integer,'
						  . 'uid_page        integer,'
						  . 'systeme         bool,'
						  . 'nom             char(30),'
						  . 'valeur          char'
						  .')');
	}
	
	public static function unbuf_query($q) {
		debug::info("sql : " . $q . ";");
		mysql_unbuffered_query($q . ";", self::get()) or Debug::sqlerror();
	}
	
	public static function table($nom) {
		return Config::get('db_prefixe') . $nom;
	}
	
	public static function test() {
		$result = mysql_query("SELECT id, name FROM mytable") or Debug::sqlerror();
		echo "<br/><br/>";
		
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			printf("ID : %s  Nom : %s", $row[0], $row[1]);
		}
		
		mysql_free_result($result);
	}
	
	public static function close() {
		mysql_close(self::get());
	}
}



  /*

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

  */

?>