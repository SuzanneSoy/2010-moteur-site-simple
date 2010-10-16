<?php

class Inherit {
	public function __construct($module) {
		$this->inherit = $module;
	}
}

class Module {
	public static $types = array();
	public static $modules = array();
	public static $attributs_globaux = array();
	public static $module_en_cours = null;
	public static $limitation_infos_module = true;
	
	public static function is_inherit($i) {
		return is_object($i) && get_class($i) == "Inherit";
	}

	public static function ressources_statiques($res) {
		// TODO : factoriser d'ici...
		$lim = self::$limitation_infos_module;
		$m = self::$module_en_cours;
		if ($lim !== true && $lim != "ressources_statiques")
			return;

		if (self::is_inherit($res)) {
			$i = $res->inherit;
			self::$limitation_infos_module = "ressources_statiques";
			call_user_func(array($i, "info"), $i);
			self::$limitation_infos_module = $lim;
		} else {
			// TODO : ... jusqu'ici (self::$modules[$m]['ressources_statiques'] peut être factorisé aussi. (pas pour attribut))
			self::$modules[$m]['ressources_statiques'] = qw(self::$modules[$m]['ressources_statiques'], $res);
		}
	}

	public static function ressources_dynamiques($res) {
		// TODO : factoriser d'ici...
		$lim = self::$limitation_infos_module;
		$m = self::$module_en_cours;
		if ($lim !== true && $lim != "ressources_dynamiques")
			return;

		if (self::is_inherit($res)) {
			$i = $res->inherit;
			self::$limitation_infos_module = "ressources_dynamiques";
			call_user_func(array($i, "info"), $i);
			self::$limitation_infos_module = $lim;
		} else {
			// TODO : ... jusqu'ici (self::$modules[$m]['ressources_dynamiques'] peut être factorisé aussi. (pas pour attribut))
			self::$modules[$m]['ressources_dynamiques'] = qw(self::$modules[$m]['ressources_dynamiques'], $res);
		}
	}

	public static function type_liens($groupe, $type = null) {
		// TODO : factoriser d'ici...
		$lim = self::$limitation_infos_module;
		$m = self::$module_en_cours;
		if ($lim !== true && $lim != "type_liens")
			return;

		if (self::is_inherit($groupe)) {
			$i = $res->inherit;
			self::$limitation_infos_module = "type_liens";
			call_user_func(array($i, "info"), $i);
			self::$limitation_infos_module = $lim;
		} else {
			if ($type === null) {
				Debug("erreur", 'fonction type_liens() : le paramètres $type est obligatoire.');
			}
			// TODO : ... jusqu'ici (self::$modules[$m]['types_enfants'] peut être factorisé aussi (pas pour attribut)).
			self::$modules[$m]['type_liens'][$groupe] = $type;
		}
	}

	public static function attribut($nom, $type = null, $defaut = null) {
		$lim = self::$limitation_infos_module;
		$m = self::$module_en_cours;
		if ($lim !== true && $lim != "attribut")
			return;
	
		if (self::is_inherit($nom)) {
			$i = $nom->inherit;
			self::$limitation_infos_module = "attribut";
			call_user_func(array($i, "info"), $i);
			self::$limitation_infos_module = $lim;
		} else {
			if ($type === null || $defaut === null) {
				Debug("erreur", 'fonction attribut() : les paramètres $type et $defaut est obligatoire.');
			}
			if (!array_key_exists($type, self::$types)) {
				Debug("erreur", "L'attribut $nom a le type $type, mais ce type n'existe pas.");
			}
			self::$modules[$m]['attributs'][$nom] = array("global" => false, "type" => $type, "defaut" => $defaut);
		}
	}

	public static function attribut_global($nom, $type, $defaut) {
		self::$attributs_globaux[$nom] = array('type' => $type, 'defaut' => $defaut);
	}

	public static function add_module($m) {
		self::$modules[$m] = array(
			'ressources_statiques' => qw(),
			'ressources_dynamiques' => qw(),
			'type_liens' => array('enfants' => false),
			'attributs' => array()
		);
	}

	public static function initModules() {
		foreach (self::$modules as $nom_module => $m) {
			self::$module_en_cours = $nom_module;
			call_user_func(array($nom_module, "info"), $nom_module);
		}
		self::$module_en_cours = null;
		foreach (self::$attributs_globaux as $nom_ag => $ag) {
			foreach (self::$modules as &$m) {
				if (array_key_exists($nom_ag, $m['attributs'])) {
					$m['attributs'][$nom_ag]['global'] = true;
				}
			}
		}
	}
	
	public static function add_type($nom) {
		ElementDocument::add_widget("r_" . $nom);
		ElementDocument::add_widget("w_" . $nom);
		// fn_serialize_$nom
		self::$types[$nom] = array();
	}
}

?>