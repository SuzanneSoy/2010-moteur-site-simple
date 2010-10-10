<?php

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
	public static function attributs() {
		return array(
			attribut("date_creation", "date", "0"),
			attribut("date_modification", "date", "0"),
			attribut("publier", "bool", "false"),
			attribut("nom_systeme", "text_no_space", ""),
			attribut("composant_url", "text_no_space", "page"),
			attribut("groupe", "text_no_space", "main") // Groupe des enfants.
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

	private $uid = 0;
	public function uid() {
		// Renvoie l'uid de la page dans la base de données.
		return $this->uid;
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
		var_dump(BDD::select("uid_page", "enfants", "where uid_page_parent = " . $this->uid()));
		niy("enfants__");
	}

	public function ajouter_enfant($type, $groupe = "main") {
		// ajouter l'enfant
		// renvoyer une instance de la sous-classe de Page correspondant à $type.
		niy("ajouter_enfant");
	}

	public function lier_page($page_source, $groupe = "main") {
		$l = ajouter_enfant("Lien", "$groupe");
		$l->lien = $page_source;
		niy("lier_page");
	}

	public static function page_systeme($nom) {
		// select from pages where nomSysteme = $nom limit 1
		niy("page_systeme");
	}

	public function if_perm($action, $nom_propriété) {
		// @param $action = suite de lettre parmi les suivantes :
		//    R = Read prop
		//    W = Write prop
		//    L = Lister les enfants ($nom_propriété désigne alors le groupe)
		//    C = Créer des enfants  ($nom_propriété désigne alors le groupe)
		//    D = Delete des enfants ($nom_propriété désigne alors le groupe)
		// @return true si on a l'autorisation pour TOUTES les actions demandées, false sinon.
		
		// Squelette du code :
		$action = strtolower($action);
		$permissions_prop = strtolower($this->get_permissions_prop($nom_propriété));
		$permissions_enfants = strtolower($this->get_permissions_enfants($nom_propriété));
		if (str_contains($action, "r") && !str_contains($permissions_prop,    "r")) { return false; }
		if (str_contains($action, "w") && !str_contains($permissions_prop,    "w")) { return false; }
		if (str_contains($action, "l") && !str_contains($permissions_enfants, "l")) { return false; }
		if (str_contains($action, "c") && !str_contains($permissions_enfants, "c")) { return false; }
		if (str_contains($action, "d") && !str_contains($permissions_enfants, "d")) { return false; }
		return true;
		niy("if_perm");
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
	
	public function set_composant_url() {
		// pseudo-réécriture d'URL.
		niy("pseudo-réécriture d'URL dans set_composant_url().");
		return $this->set_prop_direct("composant_url", $val);
	}
}

function attribut($nom, $type, $defaut) {
	if (!Document::has_widget($type)) {
		Debug::error("L'attribut $nom a le type $type, mais aucun widget w_$type n'existe.");
	}
	return array($nom, $type, $defaut);
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