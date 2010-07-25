<?php

function action($chemin, $action, $paramètres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else if ($action == "nouvelle_page") {
		// Créer le nouvel utilisateur avec comme nom "Nouvel utilisateur".
		// return Redirect vers la page actuelle, à l'ancre correspondant
		// à cet utilisateur (#Nouvel_utilisateur).
	} else {
		if (is_set($paramètres["vue"])) {
			self::vue($chemin, $paramètres["vue"]);
		} else {
			self::vue($chemin);
		}
	}
}

function vue($chemin, $vue = "normal") {
	if ($vue == "normal") {
        $ret = '';
		$ret .= "<h1>Utilisateurs</h1>";
		if (vérifier_permission($chemin, "nouvelle_page", get_utilisateur())) {
			// afficher le lien "Nouvel utilisateur"
		}
        $ret .= '<table class="utilisateurs index"><thead><th>Nom</th><th>Prénom</th><th>Groupe</th><th>Mot de passe</th></thead><tbody>';
        $listegroupes = // Construire la liste des groupes sous forme de menu drop-down.
        foreach (stockage::liste_enfants($chemin) as $k) {
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
