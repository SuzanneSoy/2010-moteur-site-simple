<?php

function get_css() {
	return ".site.connexion {
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
}

.site.navigation li:hover {
	background-color: yellow; // Flashy <3
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

.galerie.photos ul {
	padding: 0;
	margin: 0;
}

.galerie.photos li {
    list-style-type: none;
    float: left;
    margin: 1em;
	padding: 0;
    padding: 0.1em;
	width: 7em;
	text-align: center;
}

.galerie.photos li a {
	color: black;
}

.galerie.photos .miniature {
	border: thin solid gray;
}

.galerie.photos img {
	border: none;
}

.galerie.photos .titre {
	border: thin solid gray;
	border-top: none;
	padding: 0.2em 0.4em;
}

.galerie.photos a:hover .titre {
	background-color: #ff6;
}

.clearboth {
	clear: both;
}

/****** Formulaires ******/

textarea {
	width: 50%;
	margin: 1em 0;
	// font-size: large; // Activer pour plus d'accessibilité.
}

h2 input {
	font-size: x-large;
	font-weight: bold;
}";
}