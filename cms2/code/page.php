<?php

// TODO : gestion du renomage (pseudo-réécriture d'URL).

class Page {
	// Convention de nommage :
	// res_h_xxx = html, res_i_xxx = image, res_c_xxx = css, res_j_xxx = javascript
	protected static $ressources_statiques = array();
	protected static $ressources_dynamiques = array();
	protected static $attributs = array(
		"date_creation" => 0,
		"date_modification" => 0,
		"publier" => false,
		"nom_systeme" => "",
		"composant_url" => "page",
	);
	protected static $enfants = true; // Type des enfants. True pour tout autoriser.

	public static function ajouter_type($type) {
		niy("Page::ajouter_type($type);");
		// Insérer la ressource "res_c_style" dans le CSS principal
	}
	
	private $parent = null;
	public function parent() {
		return $this->parent;
	}
	
	public function rendu() {
		// Renvoie un document (classe ElementDocument).
		niy("rendu");
	}
	
	public function url() {
		// Renvoie toute l'url
		niy("url");
	}
	
	public function composant_url() {
		// renvoie juste la fin de l'url (le composant de l'url qui désigne cette page).
		niy("composant_url");
	}

	private $uid = -1;
	public function uid() {
		// Renvoie l'uid de la page dans la base de données.
		niy("uid");
	}
	
	public function select($requête) {
		// Renvoie un objet de la classe CollectionPages.
		niy("select");
	}
	
	public function __get($nom) {
		// s'il y a un getter (trigger), on l'appelle, sinon on appelle get_prop_direct();
		// le getter fait ce qu'il veut, puis appelle set_prop_direct();
		niy("get $name");
	}

	private function get_prop_direct($nom) {
		// Récupère l'attribut "$nom" depuis la BDD.
		niy("get direct $name");
	}
	
	public function __set($nom, $val) {
		// s'il y a un setter (trigger), on l'appelle, sinon on appelle set_prop_direct();
		// le setter fait ce qu'il veut, puis appelle set_prop_direct();
		niy("set $name = $val");
	}
	
	public function set_prop_direct($nom, $val) {
		// Modifie l'attribut "$nom" dans la BDD.
		niy("set direct $name = $val");
	}
}

class CollectionPages {
	function __construct() {
		niy("CollectionPages");
	}
	
	function __call($fn, $args) {
		// appelle la fonction sur tous les objets, et renvoie les résultats
		// dans un tableau. si les résultats sont des CollectionPages, ils
		// sont concaténés dans la même collection.
		niy("CollectionPages");
	}
	
	function __get($name) {
		// renvoie un tableau contenant le champ pour chaque objet.
		niy("CollectionPages");
	}
	
	function __set($name, $val) {
		// affecte la valeur sur chaque objet.
		niy("CollectionPages");
	}
}

?>