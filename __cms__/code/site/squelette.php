<?php

class Squelette {
    public static function enrober($page) {
        return ''
            . Squelette::en_tete($page)
            . $page->contenu
            . Squelette::pied($page);
    }
    
    public static function en_tete($page) {
		// TODO : chemin css relatif.
		$racine = new Chemin('/');
		$chemin_css = $racine->get_url('?vue=css');
        return
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title>' . $page->titre . '</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="fr" />
		<link href="' . $chemin_css . '" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<h1>' . Stockage::get_prop($racine, "nom_site") . '</h1>
		<div class="site connexion">
			<a href="' . $racine->enfant("admin")->enfant("connexion")->get_url() . '">connexion</a>
		</div>
		<div class="site navigation">
			<ul>
				<li><a href="' . $racine->enfant("galerie")->get_url() . '">Galerie</a></li>
			</ul>
		</div>
		<div class="site contenu">
';
//		<meta name="keywords" lang="fr" content="motcle1,mocle2" />
//		<meta name="description" content="Description de ma page web." />
    }
    
    public static function pied() {
        return
'
		</div>
	</body>
</html>';
    }
}

?>
