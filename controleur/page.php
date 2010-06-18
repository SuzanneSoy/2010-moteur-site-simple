<?php

// Protocole : http://site/actualités/?nouveau=Le%20titre

class Page {
  // sous_article/prop_article
  //             /prop_modèle     // ???
  //             /prop_photo
  //             /prop_date
  //             /prop_lieu
  //             /sous_article_1
  //             /sous_article_2
  
  public function liste_enfants() {
  }
  
  public function enfant(nom) {
  }
  
  public function parent() {
  }
  
  public function nouveau(nom) {
    // Si nom est null, utiliser "Article" + numéro
  }
  
  public function supprimer(récursif) {
  }
 
  public function get_prop(nom_propriété) {
  }
 
  public function set_prop(nom_propriété, valeur) {
  }
 
  public function url() {
  }
 
  public function nouvel_enfant() {
    // Crée le dossier de cet article
    // Crée un une propriété "article" pour le contenu de cet article à partir du modèle du dossier parent
    // Crée un une propriété "modèle" pour les nouveaux sous-articles (?)
  }
  
  
}

?>