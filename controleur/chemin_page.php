<?php

// Note : L'implémentation de CheminPage pourrait utiliser une pile au lieu des chaînes de caractère :
// ["Chemin", "Vers", "Page"] == "/Chemin/Vers/Page"

class CheminPage {
    public function __construct($chemin) {
        $this->chemin = CheminPage::nettoyer_chemin($chemin);
    }
    
    public function get() {
        return $this->chemin;
    }
    
    public function enfant($nom) {
        return $this->chemin . '/' . CheminPage::nettoyer_chemin($nom);
    }
    
    public function parent() {
        $slash = strrpos($this->chemin, '/');
        if ($slash !== false) {
            return substr($this->chemin, 0, $slash);
        } else {
            return '/';
        }
    }
    
    public static function nettoyer_chemin($chemin) {
        // SECURITE : $chemin_nettoyé
        //   * Ne contient pas '\0'
        //   * Ne contient pas '../'
        //   * Ne contient pas de double occurence de '/'
        //   * Ni d'autres bizarreries des chemins de fichiers.
        //   * Ne contient pas _prop_
        //   * Ne se termine pas par '/'
        //   * Commence par '/'
        
        // TODO
        return $chemin;
    }
    
}