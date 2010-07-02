<?php

require_once("util.php");
require_once("config.php");
require_once("controleur/chemin_page.php");

// Protocole : http://site/actualités/?nouveau=Le%20titre

// Structure des répertoires
// article/__prop__article
//        /__prop__type
//        /__prop__photo
//        /__prop__date
//        /__prop__lieu
//        /article_1 // Sous article
//        /article_2 // Sous article

class Page {
    private static $types = Array();
    
    public static function ajouterType($nom, $classe) {
        self::$types[$nom] = $classe;
    }
    
    
    /* ****** Début du hack ****** */
    // Lorsqu'on instancie un objet Page, il faudrait en fait instancier un objet Galerie ou Forum ou ...
    // selon le type de la page. Il faut donc lors de l'instanciation modifier la classe de $this.
    //
    // C'est malheureusement impossible. Avec classkit_method_copy, il serait possible de recopier les
    // méthodes de Galerie ou Forum ou ... par-dessus les méthodes de $this. Mais classkit_method_copy est
    // expérimentale.
    //
    // Une autre approche consisterait à modifier toutes les méthodes de Page pour qu'elles appellent
    // d'elles-même leur alter ego dans Galerie ou Forum. Mais ces méthodes (dans Galerie) ne pourraient
    // pas se servir de la méthode parente (dans Page), donc pas d'héritage complet.
    //
    // La solution qui a été retenue consiste à appeller la fonction statique "Page::_new()" au lieu de
    // "new Page()". Page::_new() détecte le type de la page et instancie la bonne classe.
    // Cependant, pour accéder à __prop__type, il faudrait pouvoir utiliser Page->get_prop(), qui est non
    // statique alors que Page::_new() est statique. On instancie donc un objet Page, on utilise
    // Page->get_prop(), puis on instancie la bonne sous-classe de Page (Galerie, Forum, ...).
    
    public static function _new($chemin) {
        $page = new Page($chemin);
        $type = $page->get_prop("type");
        if (array_key_exists($type, self::$types)) {
            return new self::$types[$type]($chemin);
        } else {
            return new self($chemin);
        }
    }
    /* ******  Fin du hack  ****** */
    
    
    public function __construct($chemin) {
        $this->chemin = new CheminPage($chemin);
    }
    
    // Renvoie le chemin de la page dans le système de fichiers
    private function chemin_fs() {
        global $config_chemin_modele;
        return concaténer_chemin_fs($config_chemin_modele, $this->chemin->get());
    }
    
    public function liste_enfants() {
        $scandir = scandir($this->chemin_fs());
        if ($scandir === false) { error_log("Impossible d'accéder à la liste des pages enfant de " . $this->chemin->get()); }
        
        $enfants = Array();
        foreach ($scandir as $k => $v) {
            if (strpos($v, "__prop__") !== 0 && is_dir(concaténer_chemin_fs($this->chemin_fs(), $v)) && $v != "." && $v != "..") {
                $enfants[] = $this->enfant($v);
            }
        }
        return $enfants;
    }
  
    public function enfant($nom) {
        return self::_new($this->chemin->enfant($nom));
    }
  
    public function parent() {
        return self::_new($this->chemin->parent());
    }
  
    public function nouveau($nom) {
        // Si nom est null, utiliser "Article" + numéro
        // Créer un sous-dossier "nom"
        // L'initialiser avec le modèle donné dans __prop__modele_enfants
        // Construire un objet Page (code commun avec Page::enfant(nom)).
    }
  
    public function supprimer($récursif) {
        // Si récursif || il n'y a pas de sous-dossiers
        //  alors supprimer récursivement le dossier courant
        //  sinon renvoyer FAUX
    }
    
    private function chemin_fs_prop($nom_propriété) {
        return concaténer_chemin_fs($this->chemin_fs(), "__prop__" . $nom_propriété);
    }
    
    public function get_prop($nom_propriété) {
        // lire le contenu du fichier prop_nom_propriété
        // renvoie toujours une chaîne (vide si pas de propriété ou erreur).
        $fichier = $this->chemin_fs_prop($nom_propriété);
        if (file_exists($fichier)) {
            $a = file_get_contents($fichier);
            return ($a ? $a : '');
        } else {
            return "";
        }
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