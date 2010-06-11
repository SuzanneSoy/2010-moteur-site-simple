<?php

créer_article(article_parent) {
    // Crée le dossier de cet article
    // Crée un une propriété "article" pour le contenu de cet article à partir du modèle du dossier parent
    // Crée un une propriété "modèle" pour les nouveaux sous-articles
}

supprimer_article(article, récursif) {
}

liste_sous_articles(article) {
}

get_prop(article, nom_propriété) {
}

set_prop(article, nom_propriété, valeur) {
}

// TODO :
// Pouvoir ajouter des propriétés aux articles :
// sous_article/prop_article
//             /prop_modèle     // ???
//             /prop_photo
//             /prop_date
//             /prop_lieu
//             /sous_article_1
//             /sous_article_2

// Utilisation : http://site/actualités/?créer_article=une%20actualité

?>