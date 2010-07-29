<?php

require_once(dirname(__FILE__) . "/path.php");

class Chemin {
	// Si $chemin est un tableau, il doit vérifier les invariants de nettoyer_chemin.
    public function __construct($chemin) {
		if (! is_array($chemin)) {
			$this->segments = explode('/', self::nettoyer_chemin($chemin));
		} else {
			$this->segments = $chemin;
		}
    }
    
	public function correspond(motif, chemin) {
		// motif : liste de segments, pouvant être un chaîne ou un jocker
		// correspondant à "n'importe quelle chaîne pour ce segment". Le
		// dernier segment peut être le joker "n'importe quelle suite de
		// segments (le motif doit donc correspondre à un préfixe du chemin
		// dans ce cas-là).
		// chemin : liste de segments.
		
		// les segments de chemin et motif sont déjà nettoyés.
	}
	
	// Invariant de sécurité : la chaîne renvoyée ne commence ni ne
	// termine par '/'.
    public function get() {
        return '/'.join($this->segments, '/');
    }
    
    public function enfant($nom) {
		$s = $this->segments;
		array_push($s, self::nettoyer_segment($nom));
		return new self($s);
	}
	
	public function parent() {
		return new self(array_slice($this->segments, 0, -1));
	}
	
    public function dernier() {
        return end($this->segments);
    }

    
    public static function nettoyer_chemin($chemin) {
        // SECURITE : $chemin nettoyé
        //   * Ne contient pas '\0'
        //   * Ne contient pas '../'
        //   * Ne contient pas de double occurence de '/'
        //   * Ne contient pas __prop__
        //   * Ne se termine pas par '/'
        //   * Ne commence pas par '/'
        //   * Ni d'autres bizarreries des chemins de fichiers.
        
        $chemin = preg_replace("/\\0/", '', $chemin); // TODO : vérifier si c'est bien ça ! (supprime _toutes_ les occurences ???)
        $chemin = Path::normalize($chemin);
        $chemin = preg_replace("/__prop__/", '___prop___', $chemin); // TODO : vérifier si c'est bien ça ! (supprime _toutes_ les occurences ???)
        $chemin = preg_replace("/^\/*/", '', $chemin);
        $chemin = preg_replace("/\/*$/", '', $chemin);
        
        // TODO
        return $chemin;
    }
    
    public static function nettoyer_segment($segment) {
		// TODO. Ne doit pas être vide, remplacer par _vide_ sinon.
		// Ne doit pas contenir '*' non plus. Remplacer par '-'
	}
}

?>
