<?php

require_once("controleur/page.php");

class GalerieEvenement extends Page {
    public function vue($nom_vue = "normal") {
        $ret = '';
        
        if ($nom_vue == "miniature") {
            $enfants = $this->liste_enfants();
            if ($enfants[0]) return $enfants[0]->vue('miniature');
            else return "Aucune<br/>photo";
        }
        
        $ret .= '<ul class="galerie evenement">';
        foreach ($this->liste_enfants() as $k) {
            $ret .= '<li><a href="' . $k->url() . '">' . $k->vue('miniature') . '</a></li>';
        }
        $ret .= '</ul>';
        
        if ($nom_vue == "edition") {
            $ret .= '<a href="' . $this->url('nouveau') . '">Nouvelle photo.</a>';
            $ret .= '<a href="' . $this->url('supprimer') . '">Supprmier cet évènement.</a>';
        }
        
        return $ret;
    }
}

Page::ajouterType("GalerieEvenement", "GalerieEvenement");

?>
