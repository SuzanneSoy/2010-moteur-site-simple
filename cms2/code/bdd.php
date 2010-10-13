<?php

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
		// Création de la table modules pour qu'on puisse select dedans même si elle n'existe pas.
		// TODO : fusionner avec la création de la table modules dans init();
		self::unbuf_query('create table if not exists ' . self::table("modules") . ' ('
						  . 'nom_module    varchar(50) primary key'
						  . ')');
		foreach (self::select('select * from ' . self::table("modules")) as $module) {
			self::unbuf_query('drop table if exists ' . self::table($module["nom_module"]));
		}
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
						  . 'nom_module    varchar(50) primary key'
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
			self::modify("replace into " . self::table("modules") . " values('" . $nom_module . "')");
			self::unbuf_query($table);
		}
		
		self::test();
	}
	
	public static function test() {
		// TODO : dans les modules qui proposent un nom_systeme, faire une fonction init_<nom_systeme>
		// Cette fonction sera appellée lors de l'initialisation de la BDD.
		self::modify("replace into " . self::table("pages") . " values(1, '0', '4', 'true', 'racine', '', 'mGalerieIndex', 'true')");
		self::modify("replace into " . self::table("pages") . " values(2, '1', '3', 'true', '', 'periode-1', 'mGaleriePeriode', 'true')");
		self::modify("replace into " . self::table("pages") . " set uid_page = 3, date_creation = '0', date_modification = '0', publier = 'true', nom_systeme = '', composant_url = 'periode-2', type = 'mGaleriePeriode', dans_nouveautes = 'false'");
		self::modify("replace into " . self::table("liens") . " values(1, 2, 'enfants')");
		self::modify("replace into " . self::table("liens") . " values(1, 3, 'enfants')");
		self::modify("replace into " . self::table("mGalerieIndex") . " values(1, 'Galerie', 'une galerie')");
		self::modify("replace into " . self::table("mGaleriePeriode") . " values(2, 'Periode 1', 'été')");
		self::modify("replace into " . self::table("mGaleriePeriode") . " values(3, 'Periode 2', 'hiver')");
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
		while ($row = mysql_fetch_assoc($qres)) {
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

?>