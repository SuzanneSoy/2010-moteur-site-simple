<?php

class Gallerie extends Page {
  public vue($nom_vue) {
    $ret = '';
    
    $ret += '<ul>';
    foreach ($this->liste_sous_articles() as $k) {
      $ret += '<li><a href="' + url_avec_parametres($k) + '">' + afficher($k, 'miniature') + '</a></li>';
    }
    $ret += '</ul>';
    
    if ($edition) {
      $ret += '<a href="' + $this->url('nouveau') + '">Nouvelle période.</a>';
    }
  }
  
// TODO :
// Chaque page est un objet PHP, avec les méthodes suivantes :
// $page->vue(nom_vue, nom_vue_fallback_1, nom_vue_fallback_2, …); // nom_vue = normal, edition, miniature, …
//
// + Méthodes définies dans modele/page.php

?>
