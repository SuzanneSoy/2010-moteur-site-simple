<h2><?php echo this->select("@titre"); ?></h2>
<p><?php echo this->select("@description"); ?></p>
<ul>
	<?php foreach (this->select("periodes") as $k => $periode) { ?>
	<li>
		<a href="<?php echo $periode->url(); ?>">
			<?php $rendu = $periode->rendu(); ?>
			<span class="miniature">
				<?php echo $rendu->get("contenu"); ?>
			</span>
			<span class="titre">
				<?php echo $rendu->get("titre"); ?>
			</span>
	</li>
	<?php } ?>
</ul>


<%titre @titre %>
<%texte-riche @description %>
<%list ./periodes %>
	<li>
		<a href="<?php echo $periode->url(); ?>">
			<?php $rendu = $periode->rendu(); ?>
			<span class="miniature">
				<?php echo $rendu->get("contenu"); ?>
			</span>
			<span class="titre">
				<?php echo $rendu->get("titre"); ?>
			</span>
	</li>
<%/list>

(document @titre
  (titre @titre)
  (texte-riche @description)
  (list ./periodes
		(lambda (p)
		  (let ((rendu (rendu p)))
			(<a> (url p)
				 (<span> :class miniature
						 (get "contenu" rendu))
				 (<span> :class titre
						 (get "titre" rendu)))))))

(document @titre
  (titre @titre)
  (texte-riche @description)
  (<ul>
   (foreach/rendu ((p ./periodes))
			(<li>
			 (<a> (url p)
				  (<span> :class miniature
						  (get "contenu" rendu))
				  (<span> :class titre
						  (get "titre" rendu)))))))

La fonction rendu prend en paramètre une "page" renvoie un fragment html.
Tous les fragments html ont 3 parties :
 - le titre
 - le head (scripts, css etc.)
 - le body
 - Peut-être séparer le <h2>...</h2> du reste du body ?
 - Peut-être séparer le header, footer, article, nav, ...
 - et l'url ?

Dans la base de données, chaque "page" a :
 - un identifiant unique. Chaque élément de la base de données a un identifiant unique construit à partir de
   son autoincrement, et du nom de la table.
 - un systemName (optionnel) : pour les "pages" Permissions, Utilisateurs, etc. Permet d'accéder en chemin absolu sans connaître l'uid / le nom.
 - des propriétés accessibles via @nom-propriété.
 + des propriétés "système" (dates, permissions, composantUrl) avec des droits potentiellement différents.
 - des groupes de pages enfant (?) :
    Pour la page galerie :
      ./periodes/2009-2010
      ./periodes/2010-2011
    Pour accéder au 3e évènement de la 2e période, on ferait :
      ./periodes/2010-2011/evenements/1er avril
 - et l'url (canonique) ?

Langage de requêtes :
 - Récupérer un attribut
 - Récupérer une page enfant d'une certaine catégorie (?)
 - Trier selon une propriété des éléments renvoyés (plus tard, on pourra trier en fonction d'autres critères).
 - Limiter le nombre de résultats (LIMIT et OFFSET).
 - Ne renvoie que les éléments que l'utilisateur a la permission de voir.

Un module peut déclarer des ressources statiques (par ex. un fragment de less/css) et dynamiques (p. ex. image).
Les ressources statiques ont une url qui ne dépend pas du chemin de la page en cours, les dynamiques si
(en général).
Les ressources doivent pouvoir être accédées via une certaine url.
 => "Espaces de nommage" : dès la réception de l'url, on s'empresse de trouver son espace de nommage,
    et on appelle la fonction correspondante. Par ex. : dans /css, on appelle directement la fonction
    qui renverra le fichier CSS. Ou bien, système de "liens" : /css est un lien vers /admin/style:css
    (ressource css de /admin/style). La solution "fonction" est plus générale. Par ex. /css/blabla peut
    appeller la fonction en question avec "blabla" en paramètre (pour récupérer la bonne feuille de style).
    ATTENTION : éviter les conflits de nommage, vu que les espaces de nommage se recouvrent partiellement.
    TODO : méthode "shorturl" qui renvoie l'url d'une ressource via son raccourci s'il en existe un, sinon
           via le chemin normal.
	TODO : Comment les modules indiquent-ils qu'ils ont une feuille css à mettre dans l'espace de nommage
	       "CSS" ?
	NOTE : ça sent le truc bien compliqué avec plein de bugs ça... :( .

Notes en vrac :
===============

On doit pouvoir insérer des éléments supplémentaires dans une liste (entre autres le bouton "nouvelle page").
Donc la "macro" (liste) ne fonctionnera pas. À moins que insérer soit la seule chose à ajouter. Ou si on fait
un mécanisme qui permet d'ajouter d'autre choses à la liste (prepend / append, ou paramètres supplémentaires).

Il faut une macro (action), qui génère en html un bouton.

Dans les actions de chaque module, il y a :
 - vue (toujours)
 - set_prop (toujours?)
 - supprimer (souvent? toujours?)
 - créer_enfant (souvent)

Problème des class css.
Solution : chaque classe PHP de création d'élément (Document->ul(), Document->div(), ...) a un méthode
->espaceCSS($class). Lorsqu'on l'appelle, tous les éléments enfants qui ne sont pas déjà dans un espace CSS
reçoivent la classe $class. (ceci est effectué lors de la conversion Document->(x)html5().)

Pour les aperçus dans la galerie : prendre le 1er enfant (ou l'enfant marqué 'aperçu').

Pour les titres :
"@titre url" signifie :
Lorsqu'on modifie le titre, on modifie aussi l'emplacement de la page de pseudo-réécriture d'url.
De plus, lorsqu'on demande l'adresse de cette page, elle est calculée en fonction du titre.
Il faut trouver comment générer automatiquement une url / titre / je-ne-sais-quoi dans le cas où
le titre n'est pas renseigné (par ex. pour les 250 photos de la galerie, pour que les utilisateurs
n'aient pas à indiquer le titre à chaque fois.

Toutes les pages ont une date de création et de modification automatiquement.

TODO : pouvoir accéder l'attribut @image directement depuis l'extérieur.

Abstractions :
 - Valeur avec binding (+ callbacks ?).
 - Action (créer page, supprimer, ... (générer mot de passe)).
 - Getter et setter optionnels sur les valeurs.
   Ex: getter de titre : Renvoie "Photo [date]" si le titre est indéfini, sinon le titre.
 - Vue vs ressource (chaque ressource a une ou plusieurs(?) vue(s?), une page "photo" est une ressource (celle par défaut), l'image elle-même est une autre ressource).
   Ou bien pas de vue, que des ressources (la "vue miniature" est une autre ressource).

Valeurs par défaut pour les @attributs, définies dans le module lui-même.

Pour la galerie : 3 vues : normal, miniature (image + caption), mini-miniature (juste l'image).
Pour les photos : 4 ressources : la page (ressource par défaut), l'image en grand, l'image en 800x600 (par ex.), l'image mini (64x64).
  La page affiche la photo 800x600, et si on clique dessus, c'est la version en grand qui est affichée.
