<?php

// Convention de nommage pour les ressources statiques :
// res_h_xxx = html, res_i_xxx = image, res_c_xxx = css, res_j_xxx = javascript

// Convention de nommage pour les modules :
// Doivent commencer par 'm' suivi de CamelCase (et donc ne peuvent pas commencer par "_").

// Convention de nommage pour les attributs :
// Pas de "_" au début (par ex. _publier est interdit).
 
require_once(dirname(__FILE__) . "/util.php5"); // qw
require_once(dirname(__FILE__) . "/document.php5"); // widgets pour la vérification des types.

function inherit($m) {
	return array("inherit" => $m);
}

function is_inherit($i) {
	return (is_array($i) && array_key_exists("inherit", $i));
}

function ressources_statiques($res) {
	// TODO : factoriser d'ici...
	$lim = mPage::$limitation_infos_module;
	$m = mPage::$module_en_cours;
	if ($lim !== true && $lim != "ressources_statiques")
		return;

	if (is_inherit($res)) {
		$i = $res["inherit"];
		mPage::$limitation_infos_module = "ressources_statiques";
		call_user_func(array($i, "info"), $i);
		mPage::$limitation_infos_module = $lim;
	} else {
		// TODO : ... jusqu'ici (mPage::$modules[$m]['ressources_statiques'] peut être factorisé aussi. (pas pour attribut))
		mPage::$modules[$m]['ressources_statiques'] = qw(mPage::$modules[$m]['ressources_statiques'], $res);
	}
}

function ressources_dynamiques($res) {
	// TODO : factoriser d'ici...
	$lim = mPage::$limitation_infos_module;
	$m = mPage::$module_en_cours;
	if ($lim !== true && $lim != "ressources_dynamiques")
		return;

	if (is_inherit($res)) {
		$i = $res["inherit"];
		mPage::$limitation_infos_module = "ressources_dynamiques";
		call_user_func(array($i, "info"), $i);
		mPage::$limitation_infos_module = $lim;
	} else {
		// TODO : ... jusqu'ici (mPage::$modules[$m]['ressources_dynamiques'] peut être factorisé aussi. (pas pour attribut))
		mPage::$modules[$m]['ressources_dynamiques'] = qw(mPage::$modules[$m]['ressources_dynamiques'], $res);
	}
}

function type_liens($groupe, $type = null) {
	// TODO : factoriser d'ici...
	$lim = mPage::$limitation_infos_module;
	$m = mPage::$module_en_cours;
	if ($lim !== true && $lim != "type_liens")
		return;

	if (is_inherit($groupe)) {
		$i = $res["inherit"];
		mPage::$limitation_infos_module = "type_liens";
		call_user_func(array($i, "info"), $i);
		mPage::$limitation_infos_module = $lim;
	} else {
		if ($type === null) {
			Debug("erreur", 'fonction type_liens() : le paramètres $type est obligatoire.');
		}
		// TODO : ... jusqu'ici (mPage::$modules[$m]['types_enfants'] peut être factorisé aussi (pas pour attribut)).
		mPage::$modules[$m]['type_liens'][$groupe] = $type;
	}
}

function attribut($nom, $type = null, $defaut = null) {
	$lim = mPage::$limitation_infos_module;
	$m = mPage::$module_en_cours;
	if ($lim !== true && $lim != "attribut")
		return;
	
	if (is_inherit($nom)) {
		$i = $nom["inherit"];
		mPage::$limitation_infos_module = "attribut";
		call_user_func(array($i, "info"), $i);
		mPage::$limitation_infos_module = $lim;
	} else {
		if ($type === null || $defaut === null) {
			Debug("erreur", 'fonction attribut() : les paramètres $type et $defaut est obligatoire.');
		}
		if (!Document::has_widget("w_" . $type)) {
			Debug("erreur", "L'attribut $nom a le type $type, mais aucun widget w_$type n'existe.");
		}
		mPage::$modules[$m]['attributs'][$nom] = array("global" => false, "type" => $type, "defaut" => $defaut);
	}
}

function attribut_global($nom, $type, $defaut) {
	mPage::$attributs_globaux[$nom] = array('type' => $type, 'defaut' => $defaut);
}

function module($m) {
	mPage::$modules[$m] = array(
		'ressources_statiques' => qw(),
		'ressources_dynamiques' => qw(),
		'type_liens' => array('enfants' => false),
		'attributs' => array()
	);
}

function initModules() {
	foreach (mPage::$modules as $nom_module => $m) {
		mPage::$module_en_cours = $nom_module;
		call_user_func(array($nom_module, "info"), $nom_module);
	}
	mPage::$module_en_cours = null;
	foreach (mPage::$attributs_globaux as $nom_ag => $ag) {
		foreach (mPage::$modules as &$m) {
			if (array_key_exists($nom_ag, $m['attributs'])) {
				$m['attributs'][$nom_ag]['global'] = true;
			}
		}
	}
}

class mPage {
	public static $modules = array();
	public static $attributs_globaux = array();
	public static $module_en_cours = null;
	public static $limitation_infos_module = true;

	public static function info($module) {
		attribut_global("date_creation", "date", "0");
		attribut_global("date_modification", "date", "0");
		attribut_global("publier", "bool", "false");
		attribut_global("nom_systeme", "text_nix", "");
		attribut_global("composant_url", "text_nix", "page");
	}
	
	public static function est_propriete_globale($prop) {
		return array_key_exists($prop, self::$attributs_globaux);
	}
	
	public function nom_module() {
		return get_class($this);
	}
	
	public function module() {
		return self::$modules[$this->nom_module()];
	}
	
	public function type_liens($groupe) {
		return $this->module['type_liens'][$groupe];
	}
	
	public function rendu($res = null, $d = null) {
		// Renvoie un document (classe ElementDocument).
		// L'appel à une fonction statique via $this-> n'est pas propre, mais comment appeller la
		// fonction du sous-type et pas celle de mPage sinon ?
		if ($res === null) {
			$res = $this->module['ressources_dynamiques'][0];
		}
		if ($d === null) {
			$d = new Document();
		}
		return call_user_func(array($this, "res_" . $res), $d);
	}
	
	public function url($ressource = null, $uid_racine = null) {
		// Temporairement (tant qu'on n'a pas la pseudo-réécriture d'url),
		// on renvoie vers l'index du site avec l'uid comme paramètre.
		$url = Config::get("url_base")
			. '?uid_page=' . $this->uid();
		if ($ressource !== null) {
			$url .= '&res=' . urlencode($ressource);
		}
		return $url;
		
		// Renvoie toute l'url (de la ressource principale ou de $ressource).
		if ($uid_racine === null) {
			$uid_racine = self::page_systeme("racine")->uid();
		}
		if ($ressource === null) {
			if ($uid_racine == $this->uid()) {
				return Config::get("url_base");
			} else {
				return $this->parent()->url(null, $uid_racine) . $this->composant_url . '/';
			}
		} else {
			return $this->url(null, $uid_racine) . "?res=" . urlencode($ressource); // TODO : urlencode ?
		}
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

	public function parent() {
		return self::page_uid(
			BDD::select_one(
				"select uid_page_de from " . BDD::table("_liens") . " where uid_page_vers = " . $this->uid()
			)
		);
	}
	
	public function enfants($condition = true, $ordre = "-date_creation", $limit = 0, $offset = 0) {
		// Renvoie un tableau d'instances de sous-classes de mPage.
		// Si $condition === true, il n'y a pas de condition
		//   sinon, par ex: $condition = "apercu = 'true'"
		// ordre = null => ordre = "date_creation desc"
		// limit = null || limit = 0 => pas de limite
		// offset = null => offset = 0
		
		// TODO : nettoyer la condition
		if ($condition !== true)
			$condition = " and ($condition)";
		else
			$condition = "";
		
		$select_order = "";
		$first = true;
		foreach (qw($ordre) as $o) {
			if ($first) {
				$first = false;
				$select_order .= " order by ";
			} else {
				$select_order .= ", ";
			}
			$select_order .= substr($o,1) . " ";
			$select_order .= (substr($o,0,1) == "+") ? "asc" : "desc";
		}
		$select_limit = ($limit == 0) ? "" : " limit $limit";
		$select_offset = ($offset == 0) ? "" : " offset $offset";
		
		// TODO : "natural join"
		$select = "select uid_page_vers from "
			. BDD::table("_liens")
			. " join " . BDD::table("_pages") . " on _uid_page = uid_page_vers"
			. " natural join " . BDD::table($this->type_liens("enfants"))
			. " where groupe = 'enfants' and uid_page_de = " . $this->uid()
			. $condition
			. $select_order
			. $select_limit
			. $select_offset
			. ";";
		
		$res = array();
		foreach (BDD::select($select) as $row) {
			array_push($res, self::page_uid($row["uid_page_vers"]));
		}
		
		return $res;
	}
	
	public static function créer_page($nom_module) {
		$module = self::$modules[$nom_module];
		
		// Insert dans la table _pages.
		$insert = "insert into " . BDD::table("_pages") . " set ";
		$insert .= "_uid_page = null";
		$insert .= ", _type = '" . $nom_module . "'";
		foreach (self::$attributs_globaux as $nom => $attr) {
			if (array_key_exists($nom, $module['attributs'])) {
				$insert .= ", $nom = '" . mysql_real_escape_string($module['attributs'][$nom]['defaut']) . "'";
			} else {
				$insert .= ", $nom = '" . mysql_real_escape_string($attr['defaut']) . "'";
			}
		}
		
		// Récupération du champ auto_increment uid_page.
		$uid_nouvelle_page = BDD::modify($insert);
		
		// Insert dans la table du module
		$insert = "insert into " . BDD::table($nom_module) . " set ";
		$insert .= "_uid_page = " . $uid_nouvelle_page;
		foreach ($module['attributs'] as $nom => $attr) {
			if (!$attr['global']) {
				$insert .= ", $nom = '" . mysql_real_escape_string($attr['defaut']) . "'";
			}
		}
		
		BDD::modify($insert);

		$page = self::page_uid($uid_nouvelle_page);
		// Vu qu'on modifie une propriété, ça set automatiquement la date de dernière modification :
		$page->date_creation = time();
		return $page;
	}
	
	public function créer_enfant($groupe = "enfants") {
		$nouvelle_page = self::créer_page($this->module['type_liens'][$groupe]);
		$this->lier_page($nouvelle_page, $groupe);
		return $nouvelle_page;
	}
	
	public function lier_page($page_vers, $groupe = "enfants") {
		if (!is_numeric($page_vers)) {
			$page_vers = $page_vers->uid();
		}
		
		$insert = "insert into " . BDD::table("_liens") . " set";
		$insert .= " uid_page_de = " . $this->uid();
		$insert .= ", uid_page_vers = " . $page_vers;
		$insert .= ", groupe = '" . $groupe . "'";
		BDD::modify($insert);
	}
	
	public static function page_systeme($nom) {
		return self::page_uid(
			BDD::select_one(
				"select _uid_page from " . BDD::table("_pages") . " where nom_systeme = '" . mysql_real_escape_string($nom) . "';"
			)
		);
	}

	public static function page_uid($uid) {
		$select = "select _type from " . BDD::table("_pages") . " where _uid_page = " . $uid . ";";
		$type = BDD::select_one($select);
		$ret = new $type();
		$ret->uid = $uid;
		return $ret;
	}
	
	public function get_permissions_prop($prop) {
		niy("get_permissions_prop");
	}
	public function get_permissions_enfants($groupe) {
		niy("get_permissions_enfants");
	}
	public function if_perm($action, $nom_propriété) {
		niy("if_perm");
		return true;
		// @param $action = suite de lettre parmi les suivantes :
		//    R = Read prop
		//    W = Write prop
		//    L = Lister les enfants ($nom_propriété désigne alors le groupe)
		//    C = Créer des enfants  ($nom_propriété désigne alors le groupe)
		//    D = Delete la page ($nom_propriété est ignoré)
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
		$select_table = (self::est_propriete_globale($nom)) ? "_pages" : $this->nom_module();
		$select = "select $nom from " . BDD::table($select_table) . " where _uid_page = " . $this->uid() . ";";
		return new BDDCell($this->uid(), $nom, BDD::select_one($select));
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
		$update_table = (self::est_propriete_globale($nom)) ? "_pages" : $this->nom_module();
		$update = "update " . BDD::table($update_table) . " set $nom = '" . mysql_real_escape_string(toString($val)) . "' where _uid_page = " . $this->uid();
		BDD::unbuf_query($update);
		if ($nom != "date_modification") {
			$this->date_modification = time();
		}
	}
	
	public function set_composant_url() {
		// pseudo-réécriture d'URL.
		niy("pseudo-réécriture d'URL dans set_composant_url().");
		return $this->set_prop_direct("composant_url", $val);
	}
}

module("mPage");

?>