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
		$nl = "\n";
        $ret = '';
		$ret .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . $nl;
		$ret .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">' . $nl;
		$ret .= '	<head>' . $nl;
		$ret .= '		<title>' . $page->titre . '</title>' . $nl;
		$ret .= '		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . $nl;
		$ret .= '		<meta http-equiv="Content-Language" content="fr" />' . $nl;
		$ret .= '		<meta name="keywords" lang="fr" content="motcle1,mocle2" />' . $nl;
		$ret .= '		<meta name="description" content="Description de ma page web." />' . $nl;
		$ret .= '		<link href="' . $chemin_css . '" rel="stylesheet" type="text/css" />' . $nl;
		$ret .= '	</head>' . $nl;
		$ret .= '	<body>' . $nl;
		$ret .= '		<h1><a href="' . $racine->get_url() . '">' . Stockage::get_prop($racine, "nom_site") . '</a></h1>' . $nl;
		$ret .= '		<div class="site connexion">' . $nl;
		
		if (Authentification::est_connecté()) {
			$ret .= '<a href="' . $racine->enfant("admin")->enfant("connexion")->get_url("?action=deconnexion") . '">déconnexion</a>' . $nl;
		} else {
			$ret .= '<a href="' . $racine->enfant("admin")->enfant("connexion")->get_url() . '">connexion</a>' . $nl;
		}
		
		$ret .= '		</div>' . $nl;
		$ret .= '		<div class="site navigation">' . $nl;
		$ret .= '			<ul>' . $nl;
		$ret .= '				<li><a href="' . $racine->get_url() . '">Accueil</a></li>' . $nl;
		$ret .= '				<li><a href="' . $racine->enfant("galerie")->get_url() . '">Galerie</a></li>' . $nl;
		$ret .= '				<li><a href="' . $racine->enfant("forum")->get_url() . '">Forum</a></li>' . $nl;
		if (Permissions::vérifier_permission($racine->enfant("admin"), "set_prop", Authentification::get_utilisateur())) {
			$ret .= '<li><a href="' . $racine->enfant("admin")->get_url() . '">Administration</a></li>' . $nl;
		}
		$ret .= '			</ul>' . $nl;
		$ret .= '		</div>' . $nl;
		$ret .= '		<div class="site contenu">' . $nl;
		return $ret;
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
