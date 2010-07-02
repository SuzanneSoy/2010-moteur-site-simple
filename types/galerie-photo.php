<?php

require_once("controleur/page.php");

class GaleriePhoto extends Page {
    public function vue($nom_vue = "normal") {
        if ($nom_vue == "miniature") {
            return '<img src="' . $this->url($this->get_prop("image_mini")) . '"/>';
        }
        
        if ($nom_vue == "edition") {
            return '<a href="' . $this->url('nouveau') . '">Changer la photo</a>';
        }

        return '<img src="' . $this->get_prop("image") . '"/>';
    }
}

Page::ajouterType("GaleriePhoto", "GaleriePhoto");

?>
