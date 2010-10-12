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
		if (!is_resource(self::$handle)) {
			self::$handle = @mysql_connect(
				Config::get('db_hôte'),
				Config::get('db_utilisateur'),
				Config::get('db_mot_de_passe')
			);
			if (!is_resource(self::$handle)) {
				Debug::error("Échec à la connexion à la base de données");
			}
			self::begin_transaction();
			self::init();
		}
		return self::$handle;
	}
	
	// ATTENTION : Ré-initialise toute la base de données !!!
	public static function reset() {
		self::unbuf_query('drop table if exists ' . self::table("pages"));
		self::unbuf_query('drop table if exists ' . self::table("liens"));
		// TODO : drop les tables des classes (les noms sont dans self::table("modules")).
		self::unbuf_query('drop table if exists ' . self::table("modules"));
		self::init();
	}
	
	public static function init() {
		self::unbuf_query("create database if not exists " . Config::get('db_base'));
		mysql_select_db(Config::get('db_base'), self::$handle) or Debug::sqlerror();
		
		if (count(self::select("show tables like '" . self::table("pages") . "'"))) {
			Debug::info("La base de données est déjà initialisée, on continue...");
			return;
		}
		
		self::unbuf_query('create table if not exists ' . self::table("liens") . ' ('
						  . 'uid_page_de   integer,'
						  . 'uid_page_vers integer,'
						  . 'groupe        varchar(50)'
						  . ')');
		
		self::unbuf_query('create table if not exists ' . self::table("modules") . ' ('
						  . 'modules       varchar(50) primary key'
						  . ')');
		
		$table = "create table if not exists " . self::table("pages") . " (uid_page integer auto_increment primary key";
		foreach (Page::$attributs_globaux as $nom) {
			$table .= ", $nom varchar(50)";
		}
		$table .= ")";
		self::unbuf_query($table);
		
		foreach (Page::$modules as $nom_module => $m) {
			$table = "create table if not exists " . self::table($nom_module) . " (uid_page integer";
			foreach ($m['attributs'] as $nom => &$attr) {
				if (!$attr['global']) {
					$table .= ", $nom varchar(50)";
				}
			}
			$table .= ")";
			self::unbuf_query($table);
			self::modify("replace into " . self::table("modules") . " values('" . $nom_module . "')");
		}
		
		self::test();
	}
	
	public static function test() {
		// Insertion de la racine :
		self::modify("replace into " . self::table("pages") . " values(1, '0', '0', 'true', 'racine', '', 'mGalerieIndex')");
		self::modify("replace into " . self::table("pages") . " values(2, '0', '0', 'true', '', '', 'mGaleriePeriode')");
		self::modify("replace into " . self::table("pages") . " values(3, '0', '0', 'true', '', '', 'mGaleriePeriode')");
		self::modify("replace into " . self::table("liens") . " values(1, 2, 'enfant')");
		self::modify("replace into " . self::table("liens") . " values(1, 3, 'enfant')");
	}
	
	public static function begin_transaction() {
		self::unbuf_query('begin');
	}
	
	public static function commit() {
		self::unbuf_query('commit');
	}
	
	public static function unbuf_query($q) {
		debug::info("sql : " . $q . ";");
		mysql_unbuffered_query($q . ";", self::get()) or Debug::sqlerror();
	}
	
	public static function select($q) {
		debug::info("sql : " . $q);
		$qres = mysql_query($q, BDD::get()) or Debug::sqlerror();
		$ret = array();
		while ($row = mysql_fetch_array($qres)) {
			$ret[] = $row;
		}
		return $ret;
	}
	
	public static function modify($q) {
		debug::info("sql : $q;");
		mysql_unbuffered_query($q . ";", self::get()) or Debug::sqlerror();
	}
	
	public static function table($nom) {
		return Config::get('db_prefixe') . $nom;
	}
	
	public static function close() {
		if (is_resource(self::$handle)) {
			self::commit();
			mysql_close(self::get()) or Debug::sqlerror();
			self::$handle = null;
		}
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