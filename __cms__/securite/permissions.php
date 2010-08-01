<?php

// Regle :
//  - motif de chemin (liste de segments pouvant être une chaîne, *, ou ** pour le dernier)
//  - motif d'action (get_prop, set_prop, creer_page, supprimer, *)
//  - motif d'utilisateur ($utilisateur ou * ou "propriétaire")
//  - autorisation : true ou false.

class Permissions {
	private function singleton() {
		return new Chemin("/admin/utilisateurs/");
	}
	
	// Vérifie si $utilisateur a la permission d'effectuer $action sur $chemin.
	public function vérifier_permission($chemin, $action, $utilisateur = null) {
		if ($utilisateur === null) {
			$utilisateur = Authentification::get_utilisateur();
		}
		$groupe = Authentification::get_groupe($utilisateur);
		
		// Parcourt la liste les règles de sécurité (get_regles()), et
		// sélectionne la première pour laquelle $chemin correspond au motif
		// de la règle, $action correspond au motif de la règle, et
		// $utilisateur a comme groupe le groupe de la règle (get_groupe()).
		// Si le champ "autorisation" de la règle est true, on renvoie true,
		// sinon on renvoie false
		
		foreach (self::get_regles() as $r) {
			if ($chemin->correspond($r["chemin"])
			&&  self::action_correspond($action, $r["action"])
			&&  $groupe == $r["groupe"]) {
				return $r["autorisation"];
			}
		}
		
		return false;
	}
	
	// TODO : d'abord set_nouvelles_regles, puis appliquer_nouvelles_regles.
	public function set_regles($regles) {
		// $regles est un tableau de quadruplets
		// (chemin, action, groupe, autorisation).
		
		$str_regles = '';
		foreach ($regles as $k => $r) {
			$str_regles .= self::escape_element_regle($r["chemin"]->get());
			$str_regles .= '|' . self::escape_element_regle($r["action"]);
			$str_regles .= '|' . self::escape_element_regle($r["groupe"]);
			$str_regles .= '|' . ($r["autorisation"] ? "oui" : "non");
			$str_regles .= "\n"; // TODO vérifier que la séquence d'échappement est bien comprise.
		}
		
		return Stockage::get_prop(self::singleton(), "regles", $str_regles);
	}
	
	public function get_regles() {
		// Renvoie un tableau de quadruplets
		// (chemin, action, groupe, autorisation).
		// ou false si erreur.
		$str_regles = Stockage::get_prop(self::singleton(), "regles");
		// TODO erreur si la propriété n'existe pas.
		$str_regles = preg_replace('/\r\n|\r/', "\n", $str_regles);
		$regles = array();
		// TODO : ignorer les lignes vides !
		foreach (explode("\n", $str_regles) as $k => $v) {
			$r = explode('|',$v);
			if (count($r) != 4) {
				return false;
			}
			$regles[] = array(
				"chemin" => self::unescape_element_regle(new Chemin($r[0])),
				"action" => self::unescape_element_regle($r[1]),
				"groupe" => self::unescape_element_regle($r[2]),
				"autorisation" => ($r[3] == "oui")
			);
		}
		return $regles;
	}
	
	public function escape_element_regle() {
		$str = preg_replace('/-/', '--', $str);
		$str = preg_replace('/|/', '-p', $str);
		return $str;
	}
	
	public function unescape_element_regle($str) {
		$str = preg_replace('/-p/', '|', $str);
		$str = preg_replace('/--/', '-', $str);
		return $str;
	}
}

?>
