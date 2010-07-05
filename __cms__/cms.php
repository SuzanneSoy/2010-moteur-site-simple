<?php

 // Tous les chemins pour les include sont relatifs à __cms__ .
chdir(dirname(__FILE__));

require_once("util.php");
require_once("path.php");
require_once("controleur/page.php");

require_once("types/galerie.php");
require_once("types/galerie-periode.php");
require_once("types/galerie-evenement.php");
require_once("types/galerie-photo.php");

class CMS {
    public static function affiche($chemin) {
        $p = Page::_new($chemin);
        
        echo CMS::en_tete($p->chemin->get()) // TODO
            . $p->vue()
            . CMS::pied();
    }
    
    public static function en_tete($titre) {
        return
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title>' . $titre . '</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="fr" />
		<link href="../style.css" rel="stylesheet" type="text/css" /><!-- TODO : chemin incorrect -->
	</head>
	<body>
		<h1>' . $titre . '</h1>';
//		<meta name="keywords" lang="fr" content="motcle1,mocle2" />
//		<meta name="description" content="Description de ma page web." />
    }
    
    public static function pied() {
        return
'	</body>
</html>';
    }
}
?>