<?php

// Regle :
//  - motif de chemin (liste de segments pouvant être une chaîne, *, ou ** pour le dernier)
//  - motif d'action (get_prop, set_prop, creer_page, supprimer, *)
//  - motif d'utilisateur ($utilisateur ou * ou "propriétaire")
//  - autorisation : true ou false.

class Permissions {
	private static function singleton() {
		return new Chemin("/admin/permissions/");
	}
	
	// Vérifie si $utilisateur a la permission d'effectuer $action sur $chemin.
	public static function vérifier_permission($chemin, $action, $utilisateur = null) {
		if ($utilisateur === null) {
			$utilisateur = Authentification::get_utilisateur();
		}
		$groupe = Authentification::get_groupe($utilisateur, true); // true => forcer permissions.
		
		// Parcourt la liste les règles de sécurité (get_regles()), et
		// sélectionne la première pour laquelle $chemin correspond au motif
		// de la règle, $action correspond au motif de la règle, et
		// $utilisateur a comme groupe le groupe de la règle (get_groupe()).
		// Si le champ "autorisation" de la règle est true, on renvoie true,
		// sinon on renvoie false
		
		foreach (self::get_regles() as $r) {
			if (    self::chemin_correspond($chemin, $r["chemin"])
			    &&  self::action_correspond($action, $r["action"])
			    &&  self::groupe_correspond($groupe, $r["groupe"])) {
				return $r["autorisation"];
			}
		}
		
		return false;
	}
	
	public static function chemin_correspond($chemin, $motif) {
		return $chemin->correspond($motif);
	}
	
	public static function action_correspond($action, $motif) {
		if ($motif == '*') return true;
		if ($action == $motif) return true;
		return false;
	}
	
	public static function groupe_correspond($groupe, $motif) {
		if ($motif == '*') return true;
		if ($groupe == $motif) return true;
		return false;
	}
	
	// TODO : d'abord set_nouvelles_regles, puis appliquer_nouvelles_regles.
	public static function set_regles($regles) {
		// $regles est un tableau de quadruplets
		// (chemin, action, groupe, autorisation).
		
		$str_regles = '';
		foreach ($regles as $k => $r) {
			$str_regles .= self::escape_element_regle($r["chemin"]->get());
			$str_regles .= '|' . self::escape_element_regle($r["action"]);
			$str_regles .= '|' . self::escape_element_regle($r["groupe"]);
			$str_regles .= '|' . ($r["autorisation"] ? "oui" : "non");
			$str_regles .= '|' . self::escape_element_regle($r["commentaire"]);
			$str_regles .= "\n"; // TODO vérifier que la séquence d'échappement est bien comprise.
		}
		
		return Stockage::set_prop(self::singleton(), "regles", $str_regles);
	}
	
	public static function get_regles() {
		// Renvoie un tableau de quintuplets
		// (chemin, action, groupe, autorisation, commentaire).
		// ou false si erreur.
		
		$str_regles = Stockage::get_prop(self::singleton(), "regles", true); // true => forcer permissions.
		if (!$str_regles) Erreur::fatale("Impossible de lire les règles de sécurité.");
		
		$str_regles = preg_replace('/\r\n|\r/', "\n", $str_regles);
		$regles = array();
		// TODO : ignorer les lignes vides !
		foreach (explode("\n", $str_regles) as $k => $v) {
			$r = explode('|',$v);
			if (count($r) != 5) {
				return false;
			}
			$regles[] = array(
				"chemin" => new Chemin(self::unescape_element_regle($r[0])),
				"action" => self::unescape_element_regle($r[1]),
				"groupe" => self::unescape_element_regle($r[2]),
				"autorisation" => ($r[3] == "oui"),
				"commentaire" => self::unescape_element_regle($r[4])
			);
		}
		return $regles;
	}
	
	public static function escape_element_regle() {
		$str = preg_replace('/-/', '--', $str);
		$str = preg_replace('/|/', '-p', $str);
		return $str;
	}
	
	public static function unescape_element_regle($str) {
		$str = preg_replace('/-p/', '|', $str);
		$str = preg_replace('/--/', '-', $str);
		return $str;
	}
}

?>