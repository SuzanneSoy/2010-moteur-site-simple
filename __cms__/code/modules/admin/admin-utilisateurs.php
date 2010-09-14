<?php

class AdminUtilisateurs {
	public static function action($chemin, $action, $paramètres) {
		$singleton = new Chemin("/admin/utilisateurs/");
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		
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
			if (isset($paramètres["nouveau_nom"]) && ($action == "nouvelle_page")) {
				// TODO : SECURITE : Si l'utilisateur existe déjà, laisser tomber et ne pas faire les set_* qui suivent !
				Authentification::nouvel_utilisateur($paramètres["nouveau_nom"]);
				$paramètres["nom"] = $paramètres["nouveau_nom"];
				// TODO : message de confirmation quelque part ?
			}
			
			if (isset($paramètres["nom"]) && isset($paramètres["nouveau_nom"]) && ($action != "nouvelle_page")) {
				Authentification::renomer_utilisateur($paramètres["nom"], $paramètres["nouveau_nom"]);
				$paramètres["nom"] = $paramètres["nouveau_nom"];
			}
			
			if (isset($paramètres["nom"]) && isset($paramètres["mot_de_passe"])) {
				Authentification::set_mot_de_passe($paramètres["nom"], $paramètres["mot_de_passe"]);
			}
			
			if (isset($paramètres["nom"]) && isset($paramètres["groupe"])) {
				Authentification::set_groupe($paramètres["nom"], $paramètres["groupe"]);
			}
			
			if (isset($paramètres["nom"]) && isset($paramètres["peut_se_connecter"])) {
				Authentification::set_peut_se_connecter($paramètres["nom"], ($paramètres["peut_se_connecter"] == "true"));
			}
			
			if (isset($paramètres["nom"]) && ($action == "supprimer")) {
				Authentification::supprimer_utilisateur($paramètres["nom"]);
				// TODO : message de confirmation quelque part ?
			}
			
			if (isset($paramètres["vue"])) {
				return self::vue($chemin, $paramètres["vue"]);
			} else {
				return self::vue($chemin);
			}
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		$singleton = new Chemin("/admin/utilisateurs/");
		if ($vue == "normal") {
	        $ret = '';
			$ret .= "<h2>Utilisateurs</h2>";
			if (Permissions::vérifier_permission($chemin, "nouvelle_page", Authentification::get_utilisateur())) {
				// afficher le lien "Nouvel utilisateur"
			}
			$ret .= '<p><strong>Attention :</strong> On ne peut pas encore ajouter des utilisateurs au site...</p>';
	        $ret .= '<table class="admin utilisateurs liste"><thead><th>Nom</th><th>Prénom</th><th>Groupe</th><th colspan="2">Mot de passe</th><th>Peut se connecter</th><th colspan="2"></th></thead><tbody>';
	        $listegroupes = ""; // Construire la liste des groupes sous forme de menu drop-down.
	        foreach (Authentification::liste_utilisateurs() as $u) {
	            $ret .= '<tr>'
					. '<form action="' . $chemin->get_url() . '">'
					. '<td>' . $u . '</td>' // TODO : Nom
					. '<td>' . $u . '</td>' // TODO : Prénom
					. '<td>' . Authentification::get_groupe($u) . '</td>'
					. '<td>' . Authentification::get_mot_de_passe($u) . '</td>'
					. '<td>' . '<input type="submit" value="Générer un nouveau mot de passe"/>' . '</td>'
					. '<td>' . (Authentification::get_peut_se_connecter($u) ? "oui" : "non") . '</td>'
					. '<td><input type="submit" value="appliquer"/></td>'
					. '<td><input type="submit" value="supprimer"/></td>' // TODO
					. '</form>'
					. '</tr>';
	            // Le champ mot de passe doit être un lien / bouton "nouveau
	            // mot de passe automatique" qui redirige vers
	            // $chemin->enfant("$utilisateur") ?action=gen_mdp .
	        }
	        $ret .= '</tbody></table>';
			return new Page($ret, "Utilisateurs");
		}
	}
}

Modules::enregister_module("AdminUtilisateurs", "admin-utilisateurs", "vue", "nouveau_nom nom mot_de_passe groupe peut_se_connecter");

?>