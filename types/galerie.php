<?php

vue_normale($page) {
    vue(url, false);
}

vue_edition($page) {
    vue($page, $true);
}

vue_admin($page) { // ??

}

vue($page, $edition) {
    $ret = '';
    
    $ret += '<ul>';
    foreach (liste_sous_articles($page) as $k) {
        $ret += '<li><a href="' + url_avec_parametres($k) + '">' + afficher($k, 'miniature') + '</a></li>';
    }
    $ret += '</ul>';
    
    if ($edition) {
        $ret += '<a href="' + url_avec_parametres($page, 'nouveau') + '">Nouvelle période.</a>';
    }
}

// TODO :
// Chaque page est un objet PHP, avec les méthodes suivantes :
// $page->vue(nom_vue, nom_vue_fallback_1, nom_vue_fallback_2, …); // nom_vue = normal, edition, miniature, …
//
// Pour la suite, c'est pas sûr (ptêt garder dans structure ???) :
// $page->liste_enfants();
// $page->enfant(nom);
// $page->parent();
// $page->nouveau();
// $page->supprimer(récursif);
// $page->get_prop();
// $page->set_prop();

?>
