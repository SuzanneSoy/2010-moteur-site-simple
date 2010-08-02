<?php

require_once(dirname(__FILE__) . "/path.php");

class Chemin {
	// Si $chemin est un tableau, chaque segment doit vérifier les invariants de nettoyer_segment.
    public function __construct($chemin) {
		if (! is_array($chemin)) {
			$this->segments = self::nettoyer_chemin($chemin);
		} else {
			$this->segments = $chemin;
		}
    }
    
	public function correspond($motif) {
		// motif : liste de segments, pouvant être un chaîne ou un jocker
		// correspondant à "n'importe quelle chaîne pour ce segment". Le
		// dernier segment peut être le joker "n'importe quelle suite de
		// segments (le motif doit donc correspondre à un préfixe du chemin
		// dans ce cas-là).
		// chemin : liste de segments.
		
		// SÉCURITÉ : les segments de chemin et motif sont déjà nettoyés.
		
		if ((count($motif) != count($this->segments)) && (end($motif) != CHEMIN_JOKER_MULTI_SEGMENTS)) {
			return false;
		}
		for ($i = 0; $i < count($motif); $i++) {
			if ($motif[$i] == CHEMIN_JOKER_MULTI_SEGMENTS) {
				continue;
			}
			if ($motif[$i] == CHEMIN_JOKER_SEGMENT) {
				continue;
			}
			if ($motif[$i] == $this->segments[$i]) {
				continue;
			}
			return false;
		}
		return true;
	}
	
	// Invariant de sécurité : la chaîne renvoyée ne commence ni ne
	// termine par '/'.
    public function get() {
        return '/'.join($this->segments, '/');
    }
    
    public function get_url() {
		// $config_url_base DOIT se terminer par '/', tel que spécifié
		// dans config.php.
		return $config_url_base . $this->get();
	}
	
	public function get_fs_stockage() {
		return Path::combine(Config::get('chemin_base_stockage'), $this->get());
	}
	
	public function get_fs_public() {
		return Path::combine(Config::get('chemin_base_public'), $this->get());
	}
    
    public function enfant($nom) {
		$s = $this->segments;
		$x = self::nettoyer_segment($nom);
		if ($x != '') {
			array_push($s, $x);
		}
		return new self($s);
	}
	
	public function parent() {
		return new self(array_slice($this->segments, 0, -1));
	}
	
	public function renomer($nom) {
		return $this->parent()->enfant($nom);
	}
	
    public function dernier() {
        return end($this->segments);
    }

    
    public static function nettoyer_chemin($chemin) {
        // SÉCURITÉ : $chemin nettoyé
        //   * Ne contient pas '\0'
        //   * Ne contient pas '../'
        //   * Ne contient pas de double occurence de '/'
        //   * Ne se termine pas par '/'
        //   * Ne commence pas par '/'
        //   * Est découpé en segments
        //   * Chaque segment est nettoyé avec nettoyer_segment().
        
        $chemin = preg_replace("/\\0/", '', $chemin); // TODO : vérifier si c'est bien ça ! (supprime _toutes_ les occurences ???)
        $chemin = Path::normalize($chemin);
        $chemin = preg_replace("/^\\/*/", '', $chemin);
        $chemin = preg_replace("/\\/*$/", '', $chemin);
        
        $segments = explode('/', $chemin);
        $segments = array_map(array("self", "nettoyer_segment"), $segments);
        
        return $segments;
    }
    
    public static function nettoyer_segment($segment) {
		// SÉCURITÉ : $segment nettoyé :
		//   * /!\ Peut être vide /!\
		//   * Ne doit pas contenir '/' non plus, remplacer par '-'.
		//   * Ne doit pas contenir '*' non plus, remplacer par '-'.
        //   * Ne contient pas "__prop__", remplacer par "___prop___".
		
        $segment = preg_replace("/\\0/", '', $segment); // TODO : vérifier si c'est bien ça ! (supprime _toutes_ les occurences ???)
        $segment = preg_replace("/\\//", '', $segment); // TODO : vérifier si c'est bien ça ! (supprime _toutes_ les occurences ???)
        $segment = preg_replace("/\\*/", '', $segment); // TODO : vérifier si c'est bien ça ! (supprime _toutes_ les occurences ???)
        $segment = preg_replace("/__prop__/", '___prop___', $segment); // TODO : vérifier si c'est bien ça ! (supprime _toutes_ les occurences ???)
		return $segment;
	}
}

?>