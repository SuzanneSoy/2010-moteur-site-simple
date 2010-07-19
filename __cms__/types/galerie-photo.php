<?php

require_once("controleur/page.php");

class GaleriePhoto extends Page {
    public function vue($nom_vue = "normal") {
        if ($nom_vue == "miniature") {
            return '<img src="' . $this->url($this->get_prop("image_mini")) . '"/>';
        }
        
		$ret = '<img src="' . $this->get_prop("image") . '"/>';
		
        if ($nom_vue == "edition") {
            $ret .= '<input type="file" value="' . . '" /><input type="button" value="Changer la photo">';
            $ret .= '<a href="' . $this->url('supprimer') . '">Supprmier cet évènement.</a>';
        }

        return $ret;
    }
}

Page::ajouterType("GaleriePhoto", "GaleriePhoto");

?>
