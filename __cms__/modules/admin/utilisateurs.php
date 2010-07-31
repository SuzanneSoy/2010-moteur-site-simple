<?php

function action($chemin, $action, $paramètres) {
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
			// TODO : SECURITE : Si la page existe déjà, laisser tomber !
			Stockage::nouvelle_page($singleton, $paramètres["nouveau_nom"]);
			$paramètres["nom"] = $paramètres["nouveau_nom"];
			// TODO : message de confirmation quelque part ?
		}
		
		if (is_set($paramètres["nouveau_nom"]) && is_set($paramètres["nom"])) {
			// renomer la page $paramètres["nom"]
			$paramètres["nom"] = $paramètres["nouveau_nom"];
		}
		
		if (is_set($paramètres["mot_de_passe"]) && is_set($paramètres["nom"])) {
			Stockage::set_pop($singleton->enfant($paramètres["nom"]), "mot_de_passe", $paramètres["mot_de_passe"]);
		}
		
		if (is_set($paramètres["peut_se_connecter"]) && is_set($paramètres["nom"])) {
			Stockage::set_pop($singleton->enfant($paramètres["nom"]), "peut_se_connecter", $paramètres["peut_se_connecter"]);
		}
		
		if (is_set($paramètres["vue"])) {
			self::vue($chemin, $paramètres["vue"]);
		} else {
			self::vue($chemin);
		}
	}
}

function vue($chemin, $vue = "normal") {
	$singleton = new Chemin("/admin/utilisateurs/");
	if ($vue == "normal") {
        $ret = '';
		$ret .= "<h1>Utilisateurs</h1>";
		if (vérifier_permission($chemin, "nouvelle_page", get_utilisateur())) {
			// afficher le lien "Nouvel utilisateur"
		}
        $ret .= '<table class="utilisateurs index"><thead><th>Nom</th><th>Prénom</th><th>Groupe</th><th>Mot de passe</th></thead><tbody>';
        $listegroupes = // Construire la liste des groupes sous forme de menu drop-down.
        foreach (stockage::liste_enfants($chemin) as $k) { // TODO : trier par ordre alphabétique du nom ?
            $ret .= '<tr>' . modules::vue($k) . '</tr>'; // ??? TODO
            // Le champ mot de passe doit être un lien / bouton "nouveau
            // mot de passe automatique" qui redirige vers
            // $chemin->enfant("$utilisateur") ?action=gen_mdp .
        }
        $ret .= '</tbody></table>';
		return $ret;
	}
}

?>
