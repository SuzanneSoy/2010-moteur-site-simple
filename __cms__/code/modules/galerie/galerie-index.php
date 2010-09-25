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
<%texte @description %>
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
  (texte @description)
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
  (texte @description)
  (<ul>
   (foreach/rendu ((p ./periodes))
			(<li>
			 (<a> (url p)
				  (<span> :class miniature
						  (get "contenu" rendu))
				  (<span> :class titre
						  (get "titre" rendu)))))))

La fonction rendu pren en paramètre une "page" renvoie un fragment html.
Tous les fragments html ont 3 parties :
 - le titre
 - le head (scripts, css etc.)
 - le body
 - Peut-être séparer le <h2>..</h2> du reste du body ?
 - et l'url ?

Dans la base de données, chaque "page" a :
 - un identifiant unique
 - des propriétés accessibles via @nom-propriété.
 - des groupes de pages enfant (?) :
    Pour la page galerie :
      ./periodes/2009-2010
      ./periodes/2010-2011
    Pour accéder au 3e évènement de la 2e période, on ferait :
      ./periodes/2010-2011/evenements/1er avril
 - et l'url (canonique) ?

Un module peut déclarer des ressources statiques (par ex. un fragment de less/css).
Les ressources doivent pouvoir être accédées via une certaine url.