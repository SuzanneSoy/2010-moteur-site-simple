<?php

require_once("util.php");
require_once("config.php");
require_once("controleur/chemin_page.php");

// Protocole : http://site/actualités/?nouveau=Le%20titre

// Structure des répertoires
// article/_prop_article
//        /_prop_type
//        /_prop_photo
//        /_prop_date
//        /_prop_lieu
//        /article_1 // Sous article
//        /article_2 // Sous article

class Page {
    public function __construct($chemin) {
        $this->chemin = new CheminPage($chemin);
    }
    
    // Renvoie le chemin de la page dans le système de fichiers
    private function chemin_fs() {
        global $config_chemin_base;
        return concaténer_chemin_fs($config_chemin_base, $this->chemin->get());
    }
    
    public function liste_enfants() {
        $scandir = scandir($this->chemin_fs());
        if ($scandir === false) { error_log("Impossible d'accéder à la liste des pages enfant de " . $this->chemin->get()); }
        
        $enfants = Array();
        foreach ($scandir as $k => $v) {
            $enfants[] = $this->enfant($v);
        }
        return $enfants;
    }
  
    public function enfant($nom) {
        return new Page($this->chemin->enfant($nom));
    }
  
    public function parent() {
        return new Page($this->chemin->parent());
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
        return $config_url_base . $this->chemin->get();
    }

    public function vue() {
        return "Aucune vue pour «" . $this->chemin->get() . "» .";
    }
}

?>