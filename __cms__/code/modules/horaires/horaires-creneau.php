<?php

class HorairesCreneau {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, $chemin, '', "redirect");
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin, true); // TODO ! gérer correctement le récursif
			return new Page($chemin, $chemin->parent(), '', "redirect");
		} else {
			if (isset($paramètres["equipe"])) {
				Stockage::set_prop($chemin, "equipe", $paramètres["equipe"]);
			}
			if (isset($paramètres["jour"])) {
				Stockage::set_prop($chemin, "jour", $paramètres["jour"]);
			}
			if (isset($paramètres["debut"])) {
				Stockage::set_prop($chemin, "debut", $paramètres["debut"]);
			}
			if (isset($paramètres["fin"])) {
				Stockage::set_prop($chemin, "fin", $paramètres["fin"]);
			}
			
			return new Page($chemin, $chemin->parent(), '', "redirect");
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form class="creneau edition" enctype="multipart/form-data" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<input type="text" name="equipe" value="' . Stockage::get_prop($chemin, "equipe") . '" />';
				$ret .= " le ";
				$ret .= '<input type="text" name="jour" value="' . Stockage::get_prop($chemin, "jour") . '" />';
				$ret .= " de ";
				$ret .= '<input type="text" name="debut" value="' . Stockage::get_prop($chemin, "debut") . '" />';
				$ret .= " à ";
				$ret .= '<input type="text" name="fin" value="' . Stockage::get_prop($chemin, "fin") . '" />';
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= Stockage::get_prop($chemin, "equipe");
				$ret .= " le ";
				$ret .= Stockage::get_prop($chemin, "jour");
				$ret .= " de ";
				$ret .= Stockage::get_prop($chemin, "debut");
				$ret .= " à ";
				$ret .= Stockage::get_prop($chemin, "fin");
			}
			if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="supprimer"/>';
				$ret .= '<input type="submit" value="Supprimer le créneau"/>';
				$ret .= '</form>';
			}
			
			return new Page($chemin, $ret, Stockage::get_prop($chemin, "titre"));
		}
	}
}

Modules::enregister_module("HorairesCreneau", "horaires-creneau", "vue", "equipe jour debut fin");

?>