<?php

  // TODO : gestion du renomage (pseudo-réécriture d'URL).
  // TODO : méthode if_perm("R" ou "W", nom_propriété)
  // TODO : méthode if_perm("List" ou "Create" ou "Delete", enfant)

class Page {
	public static $types = array();
	
	// Convention de nommage :
	// res_h_xxx = html, res_i_xxx = image, res_c_xxx = css, res_j_xxx = javascript
	public static function ressources_statiques() {
		return array();
	}
	public static function ressources_dynamiques() {
		return array();
	}
	public static function types_enfants() {
		// true => n'importe quel type est accepté
		// null ou false => aucun type.
		return true;
	}
	// TODO !! TODO !! TODO
	// Comment spécifier que telle valeur référence telle autre (si on le spécifie, sinon c'est juste le widget qui fait la translation) ?
	public static function attributs() {
		return array(
			"date_creation" => 0,
			"date_modification" => 0,
			"publier" => false,
			"nom_systeme" => "",
			"composant_url" => "page",
			"groupe" => "main"
		);
	}

	public static function ajouter_type($type) {
		array_push(self::$types, $type);
	}
	
	private $parent = null;
	public function parent() {
		return $this->parent;
	}
	
	public function rendu($res = null, $d = null) {
		// Renvoie un document (classe ElementDocument).
		// L'appel à une fonction statique via $this-> n'est pas propre, mais comment appeller la
		// fonction du sous-type et pas celle de Page sinon ?
		if ($res === null) {
			$ressources = $this->ressources_dynamiques();
			$res = $ressources[0];
		}
		if ($d === null) {
			$d = new Document();
		}
		return call_user_func(array($this, "res_" . $res), $d);
	}
	
	public function url($ressource = null) {
		// Renvoie toute l'url (de la ressource principale ou de $ressource).
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
	
	/*	public function select($requête) {
	 // Renvoie un objet de la classe CollectionPages.
	 niy("select");
	 }*/

	public function enfants($condition = true, $ordre = "date_creation desc", $limit = 0, $offset = 0) {
		// Renvoie un objet de la classe CollectionPages.
		// Si $condition === true, il n'y a pas de condition
		//   ex: $condition = "@apercu = true"
		// ordre = null => ordre = "date_creation desc"
		// limit = null || limit = 0 => pas de limite
		// offset = null => offset = 0
		niy("enfants");
	}

	public function ajouter_enfant() {
		// ajouter l'enfant
		// renvoyer un pointeur sur cet enfant
		niy("ajouter_enfant");
	}

	public function lier_page($page_source, $groupe = "main") {
		// ajouter un enfant contenant "@lien = page_source" et "@groupe = groupe"
		niy("lier_page");
	}
	
	public function __get($nom) {
		// s'il y a un getter (trigger), on l'appelle, sinon on appelle get_prop_direct();
		// le getter fait ce qu'il veut, puis appelle set_prop_direct();
		if (is_callable(array($this,"get_".$nom))) {
			return call_user_func(array($this,"get_".$nom));
		} else {
			return $this->get_prop_direct($nom);
		}
	}

	private function get_prop_direct($nom) {
		// Récupère l'attribut "$nom" depuis la BDD.
		niy("get direct $nom");
	}
	
	public function __set($nom, $val) {
		// s'il y a un setter (trigger), on l'appelle, sinon on appelle set_prop_direct();
		// le setter fait ce qu'il veut, puis appelle set_prop_direct();
		if (is_callable(array($this,"get_".$nom))) {
			return call_user_func(array($this,"set_".$nom), $val);
		} else {
			return $this->set_prop_direct($nom, $val);
		}
	}
	
	public function set_prop_direct($nom, $val) {
		// Modifie l'attribut "$nom" dans la BDD.
		niy("set direct $nom = $val");
	}
  }

class CollectionPages {
	public function size() {
		niy("CollectionPages::size()");
	}
	
	public function get($i) {
		niy("CollectionPages::get($i)");
	}
	
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