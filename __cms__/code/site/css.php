<?php

// #ba7500
// #c0a700

function get_css() {
	return "h1 a {
	color: #7f7f33;
}

.site.connexion {
	position: absolute;
	top: 0;
	right: 0;
	padding: 0.1em 0.2em;
}

.site.navigation {
	float: left;
	padding: 0em 0em;
	margin-right: 1em;
	border: thick solid black;
	background-color: #eee;
}

.site.navigation ul {
	padding: 0;
	margin: 0;
	text-align: center;
}

.site.navigation li:hover {
	background-color: #ff0; /* Flashy <3 */
}

.site.navigation li {
	padding: 0.5em 1.5em;
	border-bottom: thin solid black;
	list-style-type: none;
}

.site.navigation li:last-child {
	border:none;
}

.site.contenu {
	width: 63em;
	float: left;
}

.galerie.infos {
	border-bottom: thick solid gray;
	padding-bottom: 1em;
}

.galerie.photos ul {
	padding: 0;
	margin: 0;
}

.galerie.photos li {
    list-style-type: none;
    float: left;
    margin: 1em;
	padding: 0;
	width: 9em;
	text-align: center;
}

.galerie.photos li a {
	display:block;
	color: black;
}

.galerie.photos .miniature {
	display:block;
	border: thin solid gray;
	height: 70px;
}

.galerie.photos img {
	border: none;
}

.galerie.photos .titre {
	display:block;
	padding: 0.2em 0.4em;
	height: 5em;
}

.galerie.photos li:hover .miniature {
	border-color: #ff6;
}

.galerie.photos li:hover .titre {
	color: #7f7f33;
}

.admin.permissions.regles {
	width: 100%;
}

.clearboth {
	clear: both;
}

/****** Formulaires ******/

textarea {
	width: 50%;
	margin: 1em 0;
	/* font-size: large; */ /* Activer pour plus d'accessibilité. */
}

h2 input {
	font-size: x-large;
	font-weight: bold;
}";
}