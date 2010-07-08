<?php

// Note : L'implémentation de CheminPage utilise une pile au lieu de chaînes de caractère :
// ["Chemin", "Vers", "Page"] == "/Chemin/Vers/Page"

require_once("path.php");

class CheminPage {
    public function __construct($chemin) {
        $this->chemin = explode('/', CheminPage::nettoyer_chemin($chemin));
    }
    
    public function get() {
        return '/'.join($this->chemin, '/');
    }
    
    public function enfant($nom) {
        return '/'.join($this->chemin, '/') . '/' . CheminPage::nettoyer_chemin($nom);
    }
    
    public function parent() {
        return '/'.array_slice($this->chemin, 0, count($this->chemin) - 1);
    }
    
    public function dernier() {
        return $this->chemin[count($this->chemin) - 1];
    }
    
    public static function nettoyer_chemin($chemin) {
        // SECURITE : $chemin_nettoyé
        //   * Ne contient pas '\0'
        //   * Ne contient pas '../'
        //   * Ne contient pas de double occurence de '/'
        //   * Ne contient pas _prop_
        //   * Ne se termine pas par '/'
        //   * Ne commence pas par '/'
        //   * Ni d'autres bizarreries des chemins de fichiers.
        
        $chemin = preg_replace("/\\0/", '', $chemin); // TODO : vérifier si c'est bien ça !
        $chemin = Path::normalize($chemin);
        $chemin = preg_replace("/^\/*/", '', $chemin);
        $chemin = preg_replace("/\/*$/", '', $chemin);
        
        // TODO
        return $chemin;
    }
    
}