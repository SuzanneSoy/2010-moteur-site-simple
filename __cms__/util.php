<?php

// Fonctions utilitaires.

// Concatène deux chemins.
function concaténer_chemin_fs($p1, $p2) {
    return $p1 . '/' . $p2; // FIXME : . '/' . n'est pas portable !
}

?>