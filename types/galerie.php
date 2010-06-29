<?php

require_once("controleur/page.php");

class Galerie extends Page {
    public function vue($nom_vue = "normal") {
        $ret = '';
    
        $ret .= '<ul>';
        foreach ($this->liste_enfants() as $k) {
            $ret .= '<li><a href="' . $k->url() . '">' . '['.$k->chemin->get().'] ' . $k->vue('miniature') . '</a></li>';
        }
        $ret .= '</ul>';
    
        if ($nom_vue == "edition") {
            $ret .= '<a href="' . $this->url('nouveau') . '">Nouvelle période.</a>';
        }
    
        return $ret;
    }
  
    // TODO :
    // Chaque page est un objet PHP, avec les méthodes suivantes :
    // $page->vue(nom_vue, nom_vue_fallback_1, nom_vue_fallback_2, …); // nom_vue = normal, edition, miniature, …
    //
    // + Méthodes définies dans modele/page.php
}

Page::ajouterType("Galerie", "Galerie");

?>
