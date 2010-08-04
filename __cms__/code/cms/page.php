<?php

class Page {
	public $contenu = "";
	public $titre = "";
	public $sendfile_chemin = "";
	public $sendfile_prop = "";
	public $redirect_destination = "";
	public $type = "page";
	
	public function __construct($a, $b, $type = "page") {
		if ($type == "page") {
			$this->set_page($a, $b);
		} else if ($type == "sendfile") {
			$this->set_sendfile($a, $b);
		} else if ($type == "redirect") {
			$this->set_redirect($a, $b);
		}
	}
	
	public function set_page($contenu, $titre) {
		$this->contenu = $contenu;
		$this->titre = $titre;
		$this->type = "page";
	}
	
	public function set_sendfile($chemin, $prop) {
		$this->sendfile_chemin = $chemin;
		$this->sendfile_prop = $prop;
		$this->type = "sendfile";
	}

	public function set_redirect($destination, $params = "") {
		if (!is_string($destination)) $destination = $destination->get_url();
		$this->redirect_destination = $destination . $params;
		$this->type = "redirect";
	}
	
	public function envoyer() {
		// Yeeeeeeeeeeeeeeeeeeeeeeha ! Et on envoie !
		if ($this->type == "page") {
			Squelette::enrober($this);
		} else if ($this->type == "sendfile") {
			Stockage::get_prop_sendfile($this->sendfile_chemin, $this->sendfile_prop);
		} else if ($this->type == "redirect") {
			echo "TODO : Redirection vers <a href=\""
				. $this->redirect_destination . "\">"
				. $this->redirect_destination . "</a>";
		}
	}
}

?>