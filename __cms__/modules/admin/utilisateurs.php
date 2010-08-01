<?php

class AdminUtilisateurs {
	public function action($chemin, $action, $paramètres) {
		$singleton = new Chemin("/admin/utilisateurs/");
		if ($action == "anuler") {
			return redirect($chemin);
		
		// TODO ...
		// Solution A (propre) :
		//   Il faut que le bouton "nouvel utilisateur" crée une nouvelle page et redirect vers la page utilisateur_S_ (la page en cours)
		//   Puis sur la page utilisateur_S_ on modifie les champs de l'utilisateur X, on clique sur appliquer,
		//   ça POST vers la page de X, qui fait les modifs et redirect vers utiliseur_S_.
		// Solution B :
		//   [nom] [mdp] [peut_se_connecter] [[nouvel utilisateur]] -> crée nouvelle page "nom", définit le mot de passe et peut_se_connecter.
		//      Puis redirect vers utilisateur_S_
		//   [nom] [mdp] [peut_se_connecter] [[Appliquer]] -> renome la page, définit le mdp et peut_se_connecter.
		
		// Solution B :
		} else {
			if (is_set($paramètres["nouveau_nom"]) && ($action == "nouvelle_page")) {
				// TODO : SECURITE : Si l'utilisateur existe déjà, laisser tomber et ne pas faire les set_* qui suivent !
				Authentification::nouvel_utilisateur($paramètres["nouveau_nom"]);
				$paramètres["nom"] = $paramètres["nouveau_nom"];
				// TODO : message de confirmation quelque part ?
			}
			
			if (is_set($paramètres["nom"]) && is_set($paramètres["nouveau_nom"]) && ($action != "nouvelle_page")) {
				Authentification::renomer_utilisateur($paramètres["nom"], $paramètres["nouveau_nom"]);
				$paramètres["nom"] = $paramètres["nouveau_nom"];
			}
			
			if (is_set($paramètres["nom"]) && is_set($paramètres["mot_de_passe"])) {
				Authentification::set_mot_de_passe($paramètres["nom"], $paramètres["mot_de_passe"]);
			}
			
			if (is_set($paramètres["nom"]) && is_set($paramètres["groupe"])) {
				Authentification::set_groupe($paramètres["nom"], $paramètres["groupe"]);
			}
			
			if (is_set($paramètres["nom"]) && is_set($paramètres["peut_se_connecter"])) {
				Authentification::set_peut_se_connecter($paramètres["nom"], ($paramètres["peut_se_connecter"] == "true"));
			}
			
			if (is_set($paramètres["nom"]) && ($action == "supprimer")) {
				Authentification::supprimer_utilisateur($paramètres["nom"]);
				// TODO : message de confirmation quelque part ?
			}
			
			if (is_set($paramètres["vue"])) {
				self::vue($chemin, $paramètres["vue"]);
			} else {
				self::vue($chemin);
			}
		}
	}
	
	public function vue($chemin, $vue = "normal") {
		$singleton = new Chemin("/admin/utilisateurs/");
		if ($vue == "normal") {
	        $ret = '';
			$ret .= "<h1>Utilisateurs</h1>";
			if (vérifier_permission($chemin, "nouvelle_page", get_utilisateur())) {
				// afficher le lien "Nouvel utilisateur"
			}
	        $ret .= '<table class="utilisateurs index"><thead><th>Nom</th><th>Prénom</th><th>Groupe</th><th>Mot de passe</th><th>Peut se connecter</th><th colspan="2"></th></thead><tbody>';
	        $listegroupes = ""; // Construire la liste des groupes sous forme de menu drop-down.
	        foreach (Authentification::liste_utilisateurs() as $u) {
	            $ret .= '<tr>'
					. '<form action="' . $chemin->get_url() . '">'
					. '<td>' . $u . '</td>' // TODO : Nom
					. '<td>' . $u . '</td>' // TODO : Prénom
					. '<td>' . Authentification::get_groupe($u) . '</td>'
					. '<td>' . Authentification::get_mot_de_passe($u) . '<input type="submit" value="Générer un nouveau mot de passe"/></td>'
					. '<td>' . Authentification::get_peut_se_connecter($u) . '</td>'
					. '<td><input type="submit" value="appliquer"/></td>'
					. '<td><input type="submit" value="supprimer"/></td>' // TODO
					. '</form>'
					. '</tr>';
	            // Le champ mot de passe doit être un lien / bouton "nouveau
	            // mot de passe automatique" qui redirige vers
	            // $chemin->enfant("$utilisateur") ?action=gen_mdp .
	        }
	        $ret .= '</tbody></table>';
			return $ret;
		}
	}
}

?>
