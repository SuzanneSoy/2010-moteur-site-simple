<?php

// Fonctions utilitaires.

// Concatène deux chemins.
function concaténer_chemin_fs($p1, $p2) {
    return $p1 . '/' . $p2; // FIXME : . '/' . n'est pas portable !
}

function split_uri($uri) {
    $uri = urldecode($uri);
    if (strpos($uri, 'https://') === 0) {
        $split["protocole"] = 'https://';
        $uri = substr($uri, 8);
        $split["hote"] = substr($uri, 0, strpos($uri,'/'));
        $uri = substr($uri, strpos($uri,'/'));
    } else if (strpos($uri, 'http://') === 0) {
        $split["protocole"] = 'http://';
        $uri = substr($uri, 7);
        $split["hote"] = substr($uri, 0, strpos($uri,'/'));
        $uri = substr($uri, strpos($uri,'/'));
    } else {
        $split["protocole"] = '';
        $split["hote"] = '';
    }

    $question_pos = strpos($uri, '?');
    if ($question_pos === FALSE) {
        $chemin = $uri;
        $parametres = '';
    } else {
        $chemin = substr($uri, 0, $question_pos);
        $parametres = substr($uri, $question_pos);
    }
    
    $split["chemin"] = explode('/', $chemin);
    $split["parametres"] = explode('&', $parametres);

    return $split;
}
