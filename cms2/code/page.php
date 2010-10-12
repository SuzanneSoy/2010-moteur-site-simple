<?php

require_once(dirname(__FILE__) . "/util.php"); // qw
require_once(dirname(__FILE__) . "/document.php"); // widgets pour la vérification des types.

function inherit($m) {
	return array("inherit" => $m);
}

function is_inherit($i) {
	return (is_array($i) && array_key_exists("inherit", $i));
}

function ressources_statiques($res) {
	// TODO : factoriser d'ici...
	$lim = Page::$limitation_infos_module;
	$m = Page::$module_en_cours;
	if ($lim !== true && $lim != "ressources_statiques")
		return;

	if (is_inherit($res)) {
		$i = $res["inherit"];
		Page::$limitation_infos_module = "ressources_statiques";
		call_user_func(array($i, "info"));
		Page::$limitation_infos_module = $lim;
	} else {
		// TODO : ... jusqu'ici (Page::$modules[$m]['ressources_statiques'] peut être factorisé aussi. (pas pour attribut))
		Page::$modules[$m]['ressources_statiques'] = qw(Page::$modules[$m]['ressources_statiques'], $res);
	}
}

function ressources_dynamiques($res) {
	// TODO : factoriser d'ici...
	$lim = Page::$limitation_infos_module;
	$m = Page::$module_en_cours;
	if ($lim !== true && $lim != "ressources_dynamiques")
		return;

	if (is_inherit($res)) {
		$i = $res["inherit"];
		Page::$limitation_infos_module = "ressources_dynamiques";
		call_user_func(array($i, "info"));
		Page::$limitation_infos_module = $lim;
	} else {
		// TODO : ... jusqu'ici (Page::$modules[$m]['ressources_dynamiques'] peut être factorisé aussi. (pas pour attribut))
		Page::$modules[$m]['ressources_dynamiques'] = qw(Page::$modules[$m]['ressources_dynamiques'], $res);
	}
}

function types_enfants($types) {
	// TODO : factoriser d'ici...
	$lim = Page::$limitation_infos_module;
	$m = Page::$module_en_cours;
	if ($lim !== true && $lim != "types_enfants")
		return;

	if (is_inherit($types)) {
		$i = $res["inherit"];
		Page::$limitation_infos_module = "types_enfants";
		call_user_func(array($i, "info"));
		Page::$limitation_infos_module = $lim;
	} else {
		// TODO : ... jusqu'ici (Page::$modules[$m]['types_enfants'] peut être factorisé aussi (pas pour attribut)).
		Page::$modules[$m]['types_enfants'] = qw(Page::$modules[$m]['types_enfants'], $types);
	}
}

function groupes_enfants($groupes) {
	// TODO : factoriser d'ici...
	$lim = Page::$limitation_infos_module;
	$m = Page::$module_en_cours;
	if ($lim !== true && $lim != "attribut")
		return;
	
	if (is_inherit($groupes)) {
		$i = $groupes["inherit"];
		Page::$limitation_infos_module = "groupes_enfants";
		call_user_func(array($i, "info"));
		Page::$limitation_infos_module = $lim;
	} else {
		// TODO : ... jusqu'ici (Page::$modules[$m]['types_enfants'] peut être factorisé aussi (pas pour attribut)).
		Page::$modules[$m]['groupes_enfants'] = qw(Page::$modules[$m]['groupes_enfants'], $groupes);
	}
}

function attribut($nom, $type = null, $defaut = null) {
	$lim = Page::$limitation_infos_module;
	$m = Page::$module_en_cours;
	if ($lim !== true && $lim != "attribut")
		return;
	
	if (is_inherit($nom)) {
		$i = $nom["inherit"];
		Page::$limitation_infos_module = "attribut";
		call_user_func(array($i, "info"));
		Page::$limitation_infos_module = $lim;
	} else {
		if ($type === null || $defaut === null) {
			Debug::error('fonction attribut() : les paramètres $type et $defaut doivent être définis');
		}
		if (!Document::has_widget("w_" . $type)) {
			Debug::error("L'attribut $nom a le type $type, mais aucun widget w_$type n'existe.");
		}
		Page::$modules[$m]['attributs'][$nom] = array("global" => false, "type" => $type, "defaut" => $defaut);
	}
}

function attributs_globaux($attributs) {
	Page::$attributs_globaux = qw(Page::$attributs_globaux, $attributs);
}

function module($m) {
	Page::$modules[$m] = array(
		'ressources_statiques' => qw(),
		'ressources_dynamiques' => qw(),
		'types_enfants' => qw(),
		'groupes_enfants' => qw(),
		'attributs' => array()
	);
}

function initModules() {
	foreach (Page::$modules as $nom_module => $m) {
		Page::$module_en_cours = $nom_module;
		call_user_func(array($nom_module, "info"));
	}
	Page::$module_en_cours = null;
	foreach (Page::$attributs_globaux as $ag) {
		foreach (Page::$modules as &$m) {
			if (array_key_exists($ag, $m['attributs'])) {
				$m['attributs'][$ag]['global'] = true;
			}
		}
	}
}

class Page {
	public static $modules = array();
	public static $attributs_globaux = array();
	public static $module_en_cours = null;
	public static $limitation_infos_module = true;

	public static function info() {
		// Convention de nommage pour les ressources statiques :
		// res_h_xxx = html, res_i_xxx = image, res_c_xxx = css, res_j_xxx = javascript
		attributs_globaux("date_creation date_modification publier nom_systeme composant_url type");
		attribut("date_creation", "date", "0");
		attribut("date_modification", "date", "0");
		attribut("publier", "bool", "false");
		attribut("nom_systeme", "text_no_space", "");
		attribut("composant_url", "text_no_space", "page");
		attribut("type", "text_no_space", "mSiteIndex");
	}
	
	private $parent = null;
	public function parent() {
		return $this->parent;
	}
	
	public function module() {
		return self::$modules[get_class($this)];
	}
	
	public function rendu($res = null, $d = null) {
		// Renvoie un document (classe ElementDocument).
		// L'appel à une fonction statique via $this-> n'est pas propre, mais comment appeller la
		// fonction du sous-type et pas celle de Page sinon ?
		if ($res === null) {
			$res = $this->module['ressources_dynamiques'][0];
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
		//   sinon, par ex: $condition = "@apercu = true"
		// ordre = null => ordre = "date_creation desc"
		// limit = null || limit = 0 => pas de limite
		// offset = null => offset = 0
		
		// Deux possibilités :
		// 1) On select tous les rangs qui correspondent à une des $condition,
		//    puis on group by having count(uid_page) = <nombre de condition>.
		// 2) On met chaque "type" (galerie-index, ...) dans une table séparée,
		//    et on a une table des types. On select dans cette table des types
		//    les tables qui ont les champs sur lesquels portent les conditions,
		//    puis on construit une requête comme suit :
		//    select * from (select uid_page, prop_cond_1, prop_cond_2 from table_1)
		//            union (select uid_page, prop_cond_1, prop_cond_2 from table_2)
		//            union (...                                            table_3)
		//            ... where prop_cond_1 = val_cond_1 and prop_cond_2 = val_cond_2;
		
		// Tous les enfants
		niy("enfants__");
		$select = "select uid_page from " . BDD::table("enfants") . " where uid_page_parent = " . $this->uid();
		
		if ($condition !== true) {
			// Toutes les propriétés des enfants
			$select = "select$distinct uid_page from " . BDD::table("proprietes") . " where uid_page in (" . $select . ")";
			// Liste des conditions :
			$select .= "and (";
			$firstcond = true;
			foreach ($conditions as $c) {
				if (!$firstcond) {
					$select .= " or ";
				}
				$select .= "(nom = '" . mysql_real_escape_string($c["cle"]) . "' and valeur = '" . mysql_real_escape_string($c["valeur"]) . "')";
				$firstcond = false;
			}
			$select .= ") group by uid_page having count(uid_page) = " . count($conditions);
		}
		
		echo "Page::enfants : result of select : ";
		var_dump(BDD::select($select . ";"));
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
		if ($nom == "module") { return $this->module(); } // Raccourci.
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

module("Page"); // TODO ! attention : risque de conflit avec la table pages dans la bdd. Page ne devrait pas y apparaître de toute façon.

?>