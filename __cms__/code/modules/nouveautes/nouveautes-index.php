<?php

// TODO : accents pour nouveauté.
class NouveautesIndex {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else {
			if (isset($paramètres["titre"])) {
				Stockage::set_prop($chemin, "titre", $paramètres["titre"]);
			}
			
			if (isset($paramètres["vue"])) {
				return self::vue($chemin, $paramètres["vue"]);
			} else {
				return self::vue($chemin);
			}
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form class="articles infos" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<h2><input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" /></h2>';
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= '<h2>' . Stockage::get_prop($chemin, "titre") . '</h2>';
			}
			
			$ret .= '<div class="nouveautes list index">';
			$ret .= '<ul>';

			/*foreach (Stockage::liste_enfants(new Chemin("/forum")) as $k) {
				$date = Stockage::get_prop($k, "date_modif");
				if (Erreur::is_erreur($date)) $date = "0";
				$date = (int)$date;
				
				var_dump($date);
				}*/

			// TODO : faire dans l'ordre décroissant les 5 dernières nouveautés.
			foreach (Stockage::liste_enfants($chemin) as $n) {
				$k = new Chemin(Stockage::get_prop($n, "chemin"));
				$mini = Modules::vue($k, 'miniature');
				$ret .= '<li>';
				// TODO : mettre une ancre "#message<numéro>"
				$ret .= '<a href="' . $k->get_url() . '">'; // TODO : escape l'url !
				$ret .= '<span class="titre">';
				$ret .= $mini->titre;
				$ret .= '</span>';
				$ret .= '</a>';
				$ret .= '<p class="contenu">';
				$ret .= $mini->contenu;
				$ret .= '</p>';
				$ret .= '</li>';
			}
			
			$ret .= '</ul>';
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		}
	}
}

// TODO : maintenir la liste à jour lors des renomages, suppressions, ...
function enregistrer_nouveaute($chemin) {
	$singleton = new Chemin("/nouveautes");
	Stockage::set_prop($chemin, "date_modif", "".time());
	// SECURITE : On ne doit PAS pouvoir modifier dernier_numero arbitrairement
	// CONCURENCE : Faire un lock quelque part...
	$numéro_nouveauté = 1 + Stockage::get_prop($singleton, "dernier_numero");
	Stockage::set_prop($singleton, "dernier_numero", $numéro_nouveauté);
	
	$nouv = Stockage::nouvelle_page($singleton, "" . $numéro_nouveauté, "nouveaute-element-liste");
	Stockage::set_prop($nouv, "chemin", $chemin->get());
}

Modules::enregister_module("NouveautesIndex", "nouveautes-index", "vue", "titre");

?>
