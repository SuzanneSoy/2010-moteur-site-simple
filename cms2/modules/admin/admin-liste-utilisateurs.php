<?php

class AdminListeUtilisateurs extends Page {
	public static function ressources_statiques() {
		return qw("i_icône_nouvelle_page c_style");
	}
	public static function ressources_dynamiques() {
		return qw("h_page");
	}
	public static function types_enfants() {
		return qw("AdminUtilisateur");
	}
	public static function attributs() {
		return qw();
	}
	
	public function res_i_icône_nouvelle_page() {
		return StockageFichiers::envoyer_fichier_statique(Path::combine(dirname(__FILE__), "nouvel_utilisateur.png"));
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_page() {
		$d = new Document();
		$d->w_titre("Utilisateurs");
		
		$l = $d->article()->w_liste($this->enfants(true, "nom asc prenom asc"), function($e, $li) {
				$li->append(
					$e->rendu("h_admin")
				);
			});
		$nouveau = $l->li();
		$nouveau->text("Nouvel utilisateur");
		return $d;
	}
}

class AdminUtilisateur extends Page {
	public static function ressources_statiques() {
		return qw("c_style");
	}
	public static function ressources_dynamiques() {
		// TODO : h_page = affichage "en grand" de l'utilisateur (~= page perso, par ex. destination d'un lien de la page contacts).
		return qw("h_admin");
	}
	public static function types_enfants() {
		return qw("AdminUtilisateur");
	}
	public static function attributs() {
		return qw("nom prenom equipe mot_de_passe");
	}
	
	public function res_c_style() {
		niy("res_c_style");
	}
	
	public function res_h_admin() {
		// Vue de l'utilisateur pour inclusion dans admin/utilisateurs.
		$d = new Document();
		$d->w_titre("" . $this->nom . $this->prenom);

		$d->w_field($this->nom);
		$d->w_field($this->prenom);
		$d->w_field($this->equipe);
		$d->w_field($this->mot_de_passe);
		
		return $d;
	}
}

Page::ajouter_type("AdminListeUtilisateurs");

?>