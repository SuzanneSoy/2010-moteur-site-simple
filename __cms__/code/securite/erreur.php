<?php

class Erreur {
	public $type = "erreur";
	public $message = "erreur";
	public $string = "";
	
	public static function fatale($message, $html = false) {
		echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title>Erreur</title>
	</head>
	<body>
		<h2>Erreur</h2>
		<p>Désolé, une erreur est survenue. Contactez le créateur du site SVP :
		<a href="mailto:' . htmlspecialchars(Config::get('courriel_admin'))
		. '?subject=Erreur%20dans%20le%20programme%202010-moteur-site-simple&body='
		. htmlspecialchars(rawurlencode("Code de l'erreur : " . $message)) . '">'
		. htmlspecialchars(Config::get('courriel_admin'))
		. '</a>. Indiquez l\'erreur ci-dessous dans votre courriel.</p>
		<p><strong>' . ($html ? $message : htmlspecialchars($message)) . '</strong></p>
	</body>
</html>';
		//echo "\n"; debug_print_backtrace();
		exit;
	}
	
	public static function lecture($message) {
		return new self("lecture", $message);
	}
	
	public static function écriture($message) {
		return new self("écriture", $message);
	}
	
	public function __construct($type, $message, $string = null) {
		if (is_null($string)) $string = "[ debug : erreur de " . $type . " ]";
		$this->type = $type;
		$this->message = $message;
		$this->string = $string;
	}
	
	public function __toString() {
		return $this->string;
	}
	
	public static function is_erreur($obj) {
		return is_object($obj) && get_class($obj) === __CLASS__;
	}
}

?>