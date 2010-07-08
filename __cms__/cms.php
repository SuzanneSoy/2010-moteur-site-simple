<?php

 // Tous les chemins pour les include sont relatifs à __cms__ .
chdir(dirname(__FILE__));

require_once("controleur/page.php");

class CMS {
    public static function affiche($chemin, $params) {
        $action = $params["action"];
        
        $p = Page::_new($chemin);
        
        if ($action == "nouvel_enfant") {
            $p->nouvel_enfant($params["titre"]);
        } else if ($action == "supprimer") {
            $p->supprimer($params["recursif"]);
        } else if ($action == "modifier") {
            // TODO : houlà...
        } else {
            $p->affiche();
        }
    }
}
?>