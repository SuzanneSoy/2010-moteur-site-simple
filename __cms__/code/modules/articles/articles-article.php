<?php

class ArticlesArticle {
	public static function action($chemin, $action, $paramètres) {
		if ($action == "anuler") {
			return new Page($chemin, '', "redirect");
		} else if ($action == "supprimer") {
			Stockage::supprimer($chemin, true); // TODO ! gérer correctement le récursif
			return new Page($chemin->parent(), '', "redirect");
		} else {
			if (isset($paramètres["contenu"])) {
				Stockage::set_prop($chemin, "contenu", $paramètres["contenu"]);
			}
			
			// titre après les autres paramètres car il peut générer un redirect.
			if (isset($paramètres["titre"]) && Stockage::prop_diff($chemin, "titre", $paramètres["titre"])) {
				Stockage::set_prop($chemin, "titre", $paramètres["titre"]);
				Stockage::renomer($chemin, $paramètres["titre"]);
				$chemin = $chemin->renomer($paramètres["titre"]);
				// TODO : transmettre le paramètre "vue"
				return new Page($chemin, '', "redirect");
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
				$ret .= '<form class="articles article edition" enctype="multipart/form-data" method="post" action="' . $chemin->get_url() . '">';
				$ret .= '<h2><input type="text" name="titre" value="' . Stockage::get_prop($chemin, "titre") . '" /></h2>';
				$ret .= formulaire_édition_texte_enrichi(Stockage::get_prop($chemin, "contenu"), "contenu");
				$ret .= '<p><input type="submit" value="appliquer" /></p>';
				$ret .= '</form>';
			} else {
				$ret .= '<h2>' . Stockage::get_prop($chemin, "titre") . '</h2>';
				$ret .= affichage_texte_enrichi(Stockage::get_prop($chemin, "contenu"));
			}
			if (Permissions::vérifier_permission($chemin, "supprimer", Authentification::get_utilisateur())) {
				// TODO : afficher le bouton "Supprimer".
			}
			
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		} elseif ($vue == "miniature") {
			$ret = miniature_texte_enrichi(Stockage::get_prop($chemin, "contenu"));
			return new Page($ret, Stockage::get_prop($chemin, "titre"));
		}
	}
}

Modules::enregister_module("ArticlesArticle", "articles-article", "vue", "titre contenu");

?>