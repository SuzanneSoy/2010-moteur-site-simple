<?php

class Page {
	public $contenu = "";
	public $titre = "";
	public $sendfile_fichier = "";
	public $sendprop_chemin = "";
	public $sendprop_prop = "";
	public $redirect_destination = "";
	public $type = "page";
	
	public function __construct($a, $b, $type = "page") {
		if ($type == "page") {
			$this->set_page($a, $b);
		} else if ($type == "sendfile") {
			$this->set_sendfile($a);
		} else if ($type == "sendprop") {
			$this->set_sendprop($a, $b);
		} else if ($type == "raw") {
			$this->set_raw($a, $b);
		} else if ($type == "redirect") {
			$this->set_redirect($a, $b);
		}
	}
	
	public function set_page($contenu, $titre) {
		$this->contenu = $contenu;
		$this->titre = $titre;
		$this->type = "page";
	}
	
	public function set_sendfile($fichier) {
		$this->sendfile_fichier = $fichier;
		$this->type = "sendfile";
	}
	
	public function set_sendprop($chemin, $prop) {
		$this->sendprop_chemin = $chemin;
		$this->sendprop_prop = $prop;
		$this->type = "sendprop";
	}

	public function set_raw($données, $mime) {
		$this->raw_données = $données;
		$this->raw_mime = $mime;
		$this->type = "raw";
	}

	public function set_redirect($destination, $params = "") {
		if (!is_string($destination)) $destination = $destination->get_url();
		$this->redirect_destination = $destination . $params;
		$this->type = "redirect";
	}
	
	public function envoyer() {
		// Yeeeeeeeeeeeeeeeeeeeeeeha ! Et on envoie !
		if ($this->type == "page") {
			echo Squelette::enrober($this);
		} else if ($this->type == "sendfile") {
			Système_fichiers::envoyer_fichier_directement($this->sendfile_fichier);
		} else if ($this->type == "sendprop") {
			Stockage::get_prop_sendfile($this->sendprop_chemin, $this->sendprop_prop);
		} else if ($this->type == "raw") {
			header("Content-Type: " . $this->raw_mime);
			echo $this->raw_données;
		} else if ($this->type == "redirect") {
			header("Location: " . $this->redirect_destination);
			/*echo "TODO : Redirection vers <a href=\""
				. $this->redirect_destination . "\">"
				. $this->redirect_destination . "</a>";*/
		}
		// TODO : else erreur
	}
	
	public static function is_page($obj) {
		return get_class($obj) === __CLASS__;
	}
}

?>