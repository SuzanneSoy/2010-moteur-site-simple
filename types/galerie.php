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
}

Page::ajouterType("Galerie", "Galerie");

?>
