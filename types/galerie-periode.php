<?php

require_once("controleur/page.php");

class GaleriePeriode extends Page {
    public function vue($nom_vue = "normal") {
        $ret = '';
        
        if ($nom_vue == "miniature") {
            $enfants = $this->liste_enfants();
            return "miniature". $enfants[0]->vue('miniature');
        }
        
        $ret .= '<ul>';
        foreach ($this->liste_enfants() as $k) {
            $ret .= '<li><a href="' . $k->url() . '">' . '['.$k->chemin->get().'] ' . $k->vue('miniature') . '</a></li>';
        }
        $ret .= '</ul>';
        
        if ($nom_vue == "edition") {
            $ret .= '<a href="' . $this->url('nouveau') . '">Nouvel évènement.</a>';
        }
        
        return $ret;
    }
  
    // TODO :
    // Chaque page est un objet PHP, avec les méthodes suivantes :
    // $page->vue(nom_vue, nom_vue_fallback_1, nom_vue_fallback_2, …); // nom_vue = normal, edition, miniature, …
    //
    // + Méthodes définies dans modele/page.php
}

Page::ajouterType("GaleriePeriode", "GaleriePeriode");

?>
