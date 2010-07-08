<?php

require_once("util.php");
require_once("path.php");
require_once("controleur/page.php");

class Squelette {
    public static function enrober($page, $contenu) {
        return ''
            . Squelette::en_tete($page)
            . $contenu
            . Squelette::pied($page);
    }
    
    public static function en_tete($page) {
        return
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title>' . $page->chemin->dernier() . '</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="fr" />
		<link href="../style.css" rel="stylesheet" type="text/css" /><!-- TODO : chemin incorrect -->
	</head>
	<body>
		<h1>' . $page->chemin->dernier() . '</h1>';
//		<meta name="keywords" lang="fr" content="motcle1,mocle2" />
//		<meta name="description" content="Description de ma page web." />
    }
    
    public static function pied() {
        return
'	</body>
</html>';
    }
}