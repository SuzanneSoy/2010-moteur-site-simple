<?php

class Authentification {
	private $singleton = new Chemin("/admin/utilisateurs/");
	
	public function connexion($nom_utilisateur, $mdp) {
		$mdp_réel = Stockage::get_prop(self::$singleton->enfant($nom_utilisateur), "mot_de_passe");
		$peut_se_connecter = Stockage::get_prop(self::$singleton->enfant($nom_utilisateur), "peut_se_connecter");
		if ($mdp == $mdp_réel && $peut_se_connecter === "true") { // Triple égale. Pas d'entourloupe avec des casts miteux !
			// TODO : Vérifier si c'est sécurisé => stocké _uniquement_ sur le serveur.
			Session::put("utilisateur", $nom_utilisateur);
			return true;
		} else {
			Session::effacer("utilisateur");
			return false;
		}
	}
	
	public function déconnexion() {
		Session::effacer("utilisateur");
	}
	
	public function get_utilisateur() {
		$u = Session::get("utilisateur");
		return ($u === false) ? "Anonyme" : $u;
	}
	
	public function nouvel_utilisateur($nom_utilisateur) {
		// TODO : SECURITE : Si la page existe déjà, laisser tomber !
		Stockage::nouvelle_page(self::$singleton, $nom_utilisateur);
		self::set_mot_de_passe_aléatoire($nom_utilisateur);
		self::set_groupe($nom_utilisateur, "Anonymes");
		self::set_peut_se_connecter($nom_utilisateur, false);
	}
	
	public function supprimer_utilisateur($nom_utilisateur) {
		Stockage::supprimer(self::$singleton->enfant($nom_utilisateur));
	}
	
	public function renomer_utilisateur($nom_utilisateur, $nouveau_nom) {
		Stockage::renomer(self::$singleton->enfant($nom_utilisateur), $nouveau_nom);
	}
	
	public function liste_utilisateurs() {
		$liste = array();
		foreach (stockage::liste_enfants($chemin) as $k) {
			array_push($liste, $k->dernier());
		}
		sort($liste);
		return $liste;
	}
	
	public function set_groupe($nom_utilisateur, $groupe) {
		// TODO : Vérifier si le groupe existe ?
		Stockage::set_pop(self::$singleton->enfant($nom_utilisateur), "groupe", $groupe);
	}
	
	public function get_groupe($nom_utilisateur) {
		return Stockage::get_prop(self::$singleton->enfant($nom_utilisateur), "groupe");
	}
	
	public function set_mot_de_passe($nom_utilisateur, $mot_de_passe) {
		Stockage::set_pop(self::$singleton->enfant($nom_utilisateur), "mot_de_passe", $mot_de_passe);
	}
	
	public function set_mot_de_passe_aléatoire($utilisateur) {
		self::set_mot_de_passe($utilisateur, substr(md5($utilisateur . rand() . microtime()) , 0, 8));
	}
	
	public function get_mot_de_passe($nom_utilisateur) {
		return Stockage::get_prop(self::$singleton->enfant($nom_utilisateur), "mot_de_passe");
	}
	
	public function set_peut_se_connecter($nom_utilisateur, $valeur) {
		Stockage::set_pop(self::$singleton->enfant($nom_utilisateur), "peut_se_connecter", $valeur ? "true" : "false");
	}
	
	public function get_peut_se_connecter($nom_utilisateur) {
		return (Stockage::get_pop(self::$singleton->enfant($nom_utilisateur), "peut_se_connecter") == "true") ? true : false;
	}
}

?>
