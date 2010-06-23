<?php

require_once("config.php");

// Protocole : http://site/actualités/?nouveau=Le%20titre

// SECURITE : Invariants de sécurité :
// Page::chemin ne contient jamais de chaîne '../' ou autres bizarreries des chemins de fichiers.
//   Donc on peut concaténer Page::chemin à un chemin dans le système de fichiers et être sûr d'être dans un sous-dossier.
// TODO : Lors de la construction d'un chemin, tous les composants doivent être nettoyés.

// TODO : créer une classe chemin_page

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
        $this->chemin = nettoyer_chemin($chemin);
    }
    
    // Nettoie un chemin de page pour qu'il respecte l'invariant de sécurité.
    public static function nettoyer_chemin($chemin) {
        return $chemin;
    }
    
    // Renvoie le chemin de la page dans le système de fichiers
    private function chemin_fs() {
        return concaténer_chemin($config_chemin_base, $this->chemin);
    }
    
    public function liste_enfants() {
        $lst = scandir($this->chemin_fs());
        $lst_enfants = Array();
        if ($lst !== false) {
            foreach ($lst as $k => $v) {
                $lst_enfants[] = $this->enfant($v);
            }
        }
        return $lst_enfants;
    }
  
    public function enfant($nom) {
        return new Page(nettoyer_chemin($this->chemin) . '/' . nettoyer_chemin($nom));
    }
  
    public function parent() {
        return new Page(nettoyer_chemin($this->chemin) . '/..'); // TODO
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