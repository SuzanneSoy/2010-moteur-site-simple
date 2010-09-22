<?php

class LiensLien {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, $chemin, '', "redirect");
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin, true); // TODO ! gérer correctement le récursif
			return new Page($chemin, $chemin->parent(), '', "redirect");
		} else {
			if (isset($paramètres["texte"])) {
				Stockage::set_prop($chemin, "texte", $paramètres["texte"]);
			}
			if (isset($paramètres["cible"])) {
				Stockage::set_prop($chemin, "cible", $paramètres["cible"]);
			}
			if (isset($paramètres["description"])) {
				Stockage::set_prop($chemin, "cible", $paramètres["cible"]);
			}
			
			return new Page($chemin, $chemin->parent(), '', "redirect");
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form class="liens lien edition" enctype="multipart/form-data" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<input type="text" name="texte" value="' . Stockage::get_prop($chemin, "texte") . '" />';
				$ret .= '<input type="text" name="cible" value="' . Stockage::get_prop($chemin, "cible") . '" />';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "description");
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= '<a href="' . htmlspecialchars(Stockage::get_prop($chemin, "cible")) . '">' . Stockage::get_prop($chemin, "texte") . '</a>';
				$ret .= affichage_texte_enrichi(Stockage::get_prop($chemin, "description"));
			}
			if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="supprimer"/>';
				$ret .= '<input type="submit" value="Supprimer le lien ' . htmlspecialchars(Stockage::get_prop($chemin, "cible")) . '"/>';
				$ret .= '</form>';
			}
			
			return new Page($chemin, $ret, Stockage::get_prop($chemin, "titre"));
		}
	}
}

Modules::enregister_module("LiensLien", "liens-lien", "vue", "texte cible description");

?>