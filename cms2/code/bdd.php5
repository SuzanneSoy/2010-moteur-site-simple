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
				Debug::erreur("Échec à la connexion à la base de données");
			}
			// TODO : begin transaction à la 1ere écriture.
			self::begin_transaction();
			self::init();
		}
		return self::$handle;
	}
	
	// ATTENTION : Ré-initialise toute la base de données !!!
	public static function reset() {
		self::unbuf_query('drop table if exists ' . self::table("_pages"));
		self::unbuf_query('drop table if exists ' . self::table("_liens"));
		// Création de la table _modules pour qu'on puisse select dedans même si elle n'existe pas.
		// TODO : fusionner avec la création de la table _modules dans init();
		self::unbuf_query('create table if not exists ' . self::table("_modules") . ' ('
						  . 'nom_module    varchar(50) primary key'
						  . ')');
		foreach (self::select('select * from ' . self::table("_modules")) as $module) {
			self::unbuf_query('drop table if exists ' . self::table($module["nom_module"]));
		}
		self::unbuf_query('drop table if exists ' . self::table("_modules"));
		self::init();
	}
	
	public static function init() {
		self::unbuf_query("create database if not exists " . Config::get('db_base'));
		mysql_select_db(Config::get('db_base'), self::$handle) or Debug::erreur_sql();
		
		if (count(self::select("show tables like '" . self::table("_pages") . "'")) == 1) {
			Debug::info("La base de données est déjà initialisée, on continue...");
			return;
		}
		
		self::unbuf_query('create table if not exists ' . self::table("_liens") . ' ('
						  . 'uid_page_de   integer,'
						  . 'uid_page_vers integer,'
						  . 'groupe        varchar(50)'
						  . ')');
		
		self::unbuf_query('create table if not exists ' . self::table("_modules") . ' ('
						  . 'nom_module    varchar(50) primary key'
						  . ')');
		
		foreach (mPage::$modules as $nom_module => $m) {
			$table = "create table if not exists " . self::table($nom_module) . " (_uid_page integer";
			foreach ($m['attributs'] as $nom => $attr) {
				if (!$attr['global']) {
					$table .= ", $nom varchar(50)";
				}
			}
			$table .= ")";
			self::modify("replace into " . self::table("_modules") . " values('" . $nom_module . "')");
			self::unbuf_query($table);
		}
		
		$table = "create table if not exists " . self::table("_pages") . " ("
			. "_uid_page integer auto_increment primary key"
			. ", _type varchar(50)";
		foreach (mPage::$attributs_globaux as $nom => $attr) {
			$table .= ", $nom varchar(50)";
		}
		$table .= ")";
		self::unbuf_query($table);
		
		self::test();
	}
	
	public static function test() {
		// TODO : dans les modules qui proposent un nom_systeme, faire une fonction init_<nom_systeme>
		// Cette fonction sera appellée lors de l'initialisation de la BDD et leur permettra de la remplir.
		$r = mPage::créer_page("mGalerieIndex");
		$r->nom_systeme = 'racine';
		$r->composant_url = '';
		$r->titre = 'Galerie';
		$r->description = 'Une galerie.';
		
		$e1 = $r->créer_enfant();
		$e1->composant_url = 'periode-1';
		$e1->titre = 'Période 1';
		$e1->description = 'Été.';
		
		$e2 = $r->créer_enfant();
		$e2->composant_url = 'periode-2';
		$e2->titre = 'Période 2';
		$e2->description = 'Hiver.';
		$e2->dans_nouveautes = 'false';
	}
	
	public static function begin_transaction() {
		self::unbuf_query('begin');
	}
	
	public static function commit() {
		self::unbuf_query('commit');
	}
	
	public static function unbuf_query($q) {
		debug::sql($q . ";");
		mysql_unbuffered_query($q . ";", self::get()) or Debug::erreur_sql();
	}
	
	public static function select($q) {
		debug::sql($q);
		$qres = mysql_query($q, BDD::get()) or Debug::erreur_sql();
		$ret = array();
		while ($row = mysql_fetch_array($qres)) {
			$ret[] = $row;
		}
		return $ret;
	}
	
	// Select avec une seule colonne et un seul rang.
	public static function select_one($q, $strict = true) {
		$res = self::select($q);
		if ($strict && count($res) != 1) {
			Debug::erreur("Un rang de la base de données a été demmandé, mais, soit aucun rang correspondant aux critères n'a été trouvé, soit plusieurs ont été trouvés.");
			return null;
		}
		if (count($res) == 0) {
			Debug::erreur("Un rang de la base de données a été demmandé, mais, aucun rang correspondant aux critères n'a été trouvé.");
		}
		return $res[0][0];
	}
	
	public static function modify($q) {
		debug::sql($q . ";");
		mysql_unbuffered_query($q . ";", self::get()) or Debug::erreur_sql();
		// http://stackoverflow.com/questions/621369/sql-insert-and-catch-the-id-auto-increment-value
		return mysql_insert_id(self::get());
	}
	
	public static function table($nom) {
		return Config::get('db_prefixe') . $nom;
	}
	
	public static function close() {
		if (is_resource(self::$handle)) {
			self::commit();
			mysql_close(self::get()) or Debug::erreur_sql();
			self::$handle = null;
		}
	}
}

class BDDCell {
	private $uid_page;
	private $propriete;
	private $valeur;
	public function __construct($uid_page, $propriete, $valeur) {
		$this->uid_page = $uid_page;
		$this->propriete = $propriete;
		$this->valeur = $valeur;
	}
	public function uid_page() {
		return $this->uid_page;
	}
	public function propriete() {
		return $this->propriete;
	}
	public function valeur() {
		return $this->valeur;
	}
	public function __toString() {
		return "".$this->valeur;
	}
}

?>