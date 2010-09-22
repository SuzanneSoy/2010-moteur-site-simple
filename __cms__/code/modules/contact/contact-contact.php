<?php

class ContactContact {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, $chemin, '', "redirect");
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin, true); // TODO ! gérer correctement le récursif
			return new Page($chemin, $chemin->parent(), '', "redirect");
		} else {
			if (isset($paramètres["nom"])) {
				Stockage::set_prop($chemin, "nom", $paramètres["nom"]);
			}
			if (isset($paramètres["prenom"])) {
				Stockage::set_prop($chemin, "prenom", $paramètres["prenom"]);
			}
			if (isset($paramètres["description"])) {
				Stockage::set_prop($chemin, "description", $paramètres["description"]);
			}
			
			return new Page($chemin, $chemin->parent(), '', "redirect");
		}
	}
	
	public static function vue($chemin, $vue = "normal") {
		if ($vue == "normal") {
			$ret = '';
			
			if (Permissions::vérifier_permission($chemin, "set_prop", Authentification::get_utilisateur())) {
				$ret .= '<form class="contacts contact edition" enctype="multipart/form-data" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<input type="text" name="prenom" value="' . Stockage::get_prop($chemin, "prenom") . '" />';
				$ret .= '<input type="text" name="nom" value="' . Stockage::get_prop($chemin, "nom") . '" />';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "description"), "description");
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= Stockage::get_prop($chemin, "prenom");
				$ret .= " ";
				$ret .= Stockage::get_prop($chemin, "nom");
				$ret .= affichage_texte_enrichi(Stockage::get_prop($chemin, "description"));
			}
			if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
				$ret .= '<form action="' . $chemin->get_url() . '">';
				$ret .= '<input type="hidden" name="action" value="supprimer"/>';
				$ret .= '<input type="submit" value="Supprimer le contact ' . htmlspecialchars(Stockage::get_prop($chemin, "prenom") . " " . Stockage::get_prop($chemin, "nom")) . '"/>';
				$ret .= '</form>';
			}
			
			return new Page($chemin, $ret, Stockage::get_prop($chemin, "titre"));
		}
	}
}

Modules::enregister_module("ContactContact", "contact-contact", "vue", "nom prenom description");

?>