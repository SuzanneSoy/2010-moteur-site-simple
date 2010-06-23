<?php

require_once("config.php");

// Protocole : http://site/actualités/?nouveau=Le%20titre

// TODO : Constructeur.

class Page {
    // article/prop_article
    //        /prop_type
    //        /prop_photo
    //        /prop_date
    //        /prop_lieu
    //        /article_1 // Sous article
    //        /article_2 // Sous article
    
    public function __construct($chemin) {
        // SECURITE : chemin doit être un sous-dossier de .../modele/
        $this->chemin = $chemin;
    }
    
    public function liste_enfants() {
        $lst = scandir($this->chemin);
        $lst_enfants = Array();
        if ($lst !== false) {
            foreach ($lst as $k => $v) {
                // Construire un objet Page pour chacun (code commun avec Page::enfant(nom)).
                $lst_enfants[] = new Page($this->chemin . '/' . $v); // TODO : . '/' . n'est pas portable !
            }
        }
        return $lst_enfants;
    }
  
    public function enfant($nom) {
        // Récupéere le sous-dossier "nom"
        // Construire un objet Page (code commun avec Page::liste_enfants()).
    }
  
    public function parent() {
        // Récupère le dossier parent
        // Construire un objet Page (code commun avec Page::enfant(nom)).
    }
  
    public function nouveau($nom) {
        // Si nom est null, utiliser "Article" + numéro
        // Créer un sous-dossier "nom"
        // L'initialiser avec le modèle donné dans prop_modele_enfants
        // Construire un objet Page (code commun avec Page::enfant(nom)).
    }
  
    public function supprimer($récursif) {
        // Si récursif || il n'y a pas de sous-dossiers
        //  alors supprimer récursivement le dossier courant
        //  sinon renvoyer FAUX
    }
 
    public function get_prop($nom_propriété) {
        // lire le contenu du fichier prop_nom_propriété
    }
 
    public function set_prop($nom_propriété, $valeur) {
        // écrire le contenu du fichier prop_nom_propriété
    }
 
    public function url() {
        // calculer l'url de cette page en fonction de son chemin et de l'url de base
        global $config_url_base;
        return $config_url_base . $this->chemin;
    }

    public function vue() {
        return "Aucune vue pour «" . $this->chemin . "» .";
    }
}

?>