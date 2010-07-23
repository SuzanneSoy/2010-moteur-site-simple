<?php

function action($chemin, $action, $param�tres) {
	if ($action == "anuler") {
		return redirect($chemin);
	} else if ($action == "nouvelle_page") {
		// Cr�er la nouvelle page avec les valeurs par d�faut.
		// return Redirect vers cette nouvelle page.
	} else {
		if (is_set($param�tres["description"])) {
			set_prop($chemin, "description", $param�tres["description"]);
		}
/*		if (is_set($param�tres[""])) {
		}*/
		
		if (is_set($param�tres["vue"])) {
			self::vue($chemin, $param�tres["vue"]);
		} else {
			self::vue($chemin);
		}
	}
}

function vue($chemin, $vue = "normal") {
	if ($vue == "normal") {
        $ret = '';
		$ret .= "<h1>Forum</h1>";
		if (v�rifier_permission($chemin, "nouvelle_page", get_utilisateur())) {
			// afficher le lien "Nouveau sujet"
		}
        $ret .= '<ul class="forum index">';
        foreach (stockage::liste_enfants($chemin) as $k) {
            $ret .= '<li><a href="' . chemin::vers_url($k) . '">' . modules::vue($k, 'miniature') . '</a></li>'; // TODO : escape l'url !
        }
        $ret .= '</ul>';
		return $ret;
	}
}

?>
