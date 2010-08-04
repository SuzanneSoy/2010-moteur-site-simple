<?php

class Authentification {
	private static function singleton() {
		return new Chemin("/admin/utilisateurs/");
	}
	
	public static function connexion($nom_utilisateur, $mdp) {
		$ch_utilisateur = self::singleton()->enfant($nom_utilisateur);
		$mdp_réel = self::get_mot_de_passe($nom_utilisateur, true); // true => forcer permissions.
		$peut_se_connecter = self::get_peut_se_connecter($nom_utilisateur, true);  // true => forcer permissions.
		if ($mdp == $mdp_réel && $peut_se_connecter) {
			// TODO : Vérifier si c'est sécurisé => stocké _uniquement_ sur le serveur.
			Session::put("utilisateur", $nom_utilisateur);
			return true;
		} else {
			Session::effacer("utilisateur");
			return false;
		}
	}
	
	public static function est_connecté() {
		return self::get_utilisateur() != "Anonyme";
	}
	
	public static function déconnexion() {
		Session::effacer("utilisateur");
	}
	
	public static function get_utilisateur() {
		$u = Session::get("utilisateur");
		return (Erreur::is_erreur($u)) ? "Anonyme" : $u;
	}
	
	public static function nouvel_utilisateur($nom_utilisateur) {
		// TODO : SECURITE : Si la page existe déjà, laisser tomber !
		Stockage::nouvelle_page(self::singleton(), $nom_utilisateur, "admin-utilisateur");
		self::set_mot_de_passe_aléatoire($nom_utilisateur);
		self::set_groupe($nom_utilisateur, "Anonymes");
		self::set_peut_se_connecter($nom_utilisateur, false);
	}
	
	public static function supprimer_utilisateur($nom_utilisateur) {
		Stockage::supprimer(self::singleton()->enfant($nom_utilisateur));
	}
	
	public static function renomer_utilisateur($nom_utilisateur, $nouveau_nom) {
		Stockage::renomer(self::singleton()->enfant($nom_utilisateur), $nouveau_nom);
	}
	
	public static function liste_utilisateurs() {
		$liste = array();
		foreach (stockage::liste_enfants($chemin) as $k) {
			array_push($liste, $k->dernier());
		}
		sort($liste);
		return $liste;
	}
	
	public static function set_groupe($nom_utilisateur, $groupe) {
		// TODO : Vérifier si le groupe existe ?
		Stockage::set_pop(self::singleton()->enfant($nom_utilisateur), "groupe", $groupe);
	}
	
	public static function get_groupe($nom_utilisateur, $forcer_permissions = false) {
		return Stockage::get_prop(self::singleton()->enfant($nom_utilisateur), "groupe", $forcer_permissions);
	}
	
	public static function set_mot_de_passe($nom_utilisateur, $mot_de_passe) {
		Stockage::set_pop(self::singleton()->enfant($nom_utilisateur), "mot_de_passe", $mot_de_passe);
	}
	
	public static function set_mot_de_passe_aléatoire($utilisateur) {
		self::set_mot_de_passe($utilisateur, substr(md5($utilisateur . rand() . microtime()) , 0, 8));
	}
	
	public static function get_mot_de_passe($nom_utilisateur, $forcer_permissions = false) {
		return Stockage::get_prop(self::singleton()->enfant($nom_utilisateur), "mot_de_passe", $forcer_permissions);
	}
	
	public static function set_peut_se_connecter($nom_utilisateur, $valeur) {
		Stockage::set_pop(self::singleton()->enfant($nom_utilisateur), "peut_se_connecter", $valeur ? "oui" : "non");
	}
	
	public static function get_peut_se_connecter($nom_utilisateur, $forcer_permissions = false) {
		$peut_se_connecter = Stockage::get_prop(self::singleton()->enfant($nom_utilisateur), "peut_se_connecter", $forcer_permissions);
		return ($peut_se_connecter == "oui") ? true : false;
	}
}

?>