<?php

class mAdminListeUtilisateurs extends Page {
	public static function info() {
		ressources_statiques("i_icône_nouvelle_page c_style");
		ressources_dynamiques("h_page h_liste_mots_de_passe");
		type_liens("enfants", "mAdminUtilisateur");
	}
	
	public function res_i_icône_nouvelle_page() {
		return StockageFichiers::envoyer_fichier_statique(Path::combine(dirname(__FILE__), "nouvel_utilisateur.png"));
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page($d) {
		$d->w_titre("Utilisateurs");
		
		$l = $d->article()->w_tableau($this->enfants(true, "+nom +prenom"), function($e, $tr) {
				$e->rendu("h_admin", $tr);
			});
		$nouveau = $l->tbody(0)->tr()->td(6);
		$nouveau->text("Nouvel utilisateur");
		return $d;
	}
	
	public function res_h_liste_mots_de_passe($d) {
		$d->w_titre("Liste de mots de passe.");
		
		$l = $d->article()->w_liste($this->enfants("u_groupe = 'utilisateurs'", "+nom +prenom"), function($e, $li) {
				$e->rendu("h_admin_mdp", $li);
			});
		return $d;
	}
}

class mAdminUtilisateur extends Page {
	public static function info() {
		ressources_statiques("c_style");
		// TODO : h_page = affichage "en grand" de l'utilisateur (~= page perso, par ex. destination d'un lien de la page contacts).
		ressources_dynamiques("h_admin");
		// TODO : le couple (nom,prenom) doit être unique.
		attribut("nom", "text_line", "Dupondt");
		attribut("prenom", "text_line", "Jean");
		attribut("equipe", "uid", "null");
		attribut("mot_de_passe", "password", "");
		// TODO : permissions différentes pour les propriétés peut_se_connecter et groupe_permissions.
		// L'utilisateur ne doit pas pouvoir les modifier.
		attribut("groupe_permissions", "groupe_permissions", "utilisateurs");
		attribut("peut_se_connecter", "bool", "false");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_admin($d) {
		// Vue de l'utilisateur pour inclusion dans admin/utilisateurs.
		assert('$d->type() == "tr"');
		$a = $d->article();
		//$d->w_titre("" . $this->nom . $this->prenom);

		$a->w_field($this->nom);
		$a->w_field($this->prenom);
		$a->w_field($this->equipe);
		$a->w_field($this->mot_de_passe);
		$a->w_field($this->groupe); // TODO : menu de séléction
		$a->w_field($this->peut_se_connecter); // TODO : checkbox
		
		return $a;
	}
	
	public function res_h_admin_mdp($d) {
		// Vue de l'utilisateur pour inclusion dans admin/utilisateurs/liste des mots de passe.

		$a = $d->article();
		$a->w_field($this->nom);
		$a->w_field($this->prenom);
		$a->w_field($this->mot_de_passe);
		
		return $a;
	}
}

module("mAdminListeUtilisateurs");

?>