<?php

// Chaque type d'élément est une sous-classe de ElementDocument, et impléménte uniquement les méthodes de création qui respectent les règles d'imbrication des éléments.
// Pour les éléments dont les enfants possibles dépendent du parent (par ex. <a>), on restreindra les enfants et (parents) possibles à quelque chose de sensé.
// Plutôt que d'avoir plein de sous-classes, ElementDocument a une méthode __call(), qui vérifie ce qu'on peut appeller en fonction du type de l'élément.

// Propriété "url" pour le document ? Probablement innutile, pusiqu'on bosse principalement avec des uid (le href d'un <a> est un uid).

class ElementDocument {
	public $espaceCss = null;
	private static $enfantsÉléments = array();
	private static $attributsÉléments = array();
	private static $widgets = array();
	private $type = null;
	private $enfants = array();
	private $attr = array();
	
	public static function ajouter_type_élément($type, $typesEnfants, $attributs = "") {
		self::$enfantsÉléments[$type] = qw($typesEnfants);
		self::$attributsÉléments[$type] = qw($attributs);
	}

	public static function ajouter_widget($nom, $callback) {
		self::$widgets["w_" . $nom] = $callback;
	}

	public function inclure($elem) {
		// Tente de fusionner $elem avec $this
		niy("inclure");
	}

	public function attr($nom, $valeur) {
		$this->attr[$nom] = $valeur;
	}

	public function to_XHTML_5($indent = "") {
		$ret = "";
		$ret .= "$indent<" . $this->type;
		foreach ($this->attr as $k => $v) {
			$ret .= " " . $k . '="' . htmlspecialchars($v) . '"'; // TODO : htmlspecialchars ne suffit pas !
		}
		if (count($this->enfants) == 0) {
			$ret .= "/>\n";
		} else {
			$ret .= ">\n";
			foreach ($this->enfants as $k => $v) {
				$ret .= $v->to_XHTML_5($indent . "  ");
			}
			$ret .= "$indent</" . $this->type . ">\n";
		}
		return $ret;
	}

	public function to_HTML_5() {
		niy("to_HTML_5");
	}

	public function to_HTML_4_01() {
		niy("to_HTML_4_01");
	}

	public function to_XHTML_1_1() {
		niy("to_XHTML_1_1");
	}

	public function __construct($type = "document") {
		$this->type = $type;
	}

	public function __call($fn, $args) {
		// TODO (peut-être ?): si on ne peut pas ajouter directement un élément, chercher un chemin qui permette de l'ajouter (p.ex. un strong directement à la racine d'un document, on ajoutera un p).
		if (array_key_exists($this->type, self::$enfantsÉléments)
			&& in_array($fn, self::$enfantsÉléments[$this->type])) {
			$elem = new self($fn);
			
			foreach (self::$attributsÉléments[$fn] as $i => $nom) {
				$elem->attr($nom, $args[$i]);
			}
			
			$this->enfants[] = $elem;
			return $elem;
		} else if (array_key_exists($fn, self::$widgets)) {
			$f = self::$widgets[$fn];
			$a = $args;
			array_unshift($a, $this);
			return call_user_func_array($f, $a);
		} else {
			Debug::error("Impossible d'ajouter un élément $fn");
			return null;
		}
	}
}

class Document extends ElementDocument {
}

/*****
 TODO
 Comment s'assurer que le header, footer, nav soit unique ?
******/
$inline_elems = "span text a strong em img";
ElementDocument::ajouter_type_élément("document", "header footer nav article script style");
ElementDocument::ajouter_type_élément("header", "title");
ElementDocument::ajouter_type_élément("title", "text");
ElementDocument::ajouter_type_élément("footer", "");
ElementDocument::ajouter_type_élément("nav", "ul");
ElementDocument::ajouter_type_élément("article", "ul p form");
ElementDocument::ajouter_type_élément("script", "", "src");
ElementDocument::ajouter_type_élément("style", "", "src");
ElementDocument::ajouter_type_élément("ul", "li");
ElementDocument::ajouter_type_élément("li", $inline_elems);
ElementDocument::ajouter_type_élément("form", "input_text_line input_text_multi input_text_rich input_file");
ElementDocument::ajouter_type_élément("a", $inline_elems, "href");
ElementDocument::ajouter_type_élément("span", $inline_elems, "class");
ElementDocument::ajouter_type_élément("img", "", "alt src");
ElementDocument::ajouter_type_élément("p", $inline_elems);
ElementDocument::ajouter_type_élément("text", "", "text");



ElementDocument::ajouter_widget("titre", function($d, $select){
		// renvoie un <h2> ou un <input> selon les droits
		return $d->header()->title()->text("Not Implemented Yet : w_titre($select)");
	});


ElementDocument::ajouter_widget("en_tete", function($d){
		//$d->w_titre($this->select("titre"));
		//$d->w_description($this->select("description"));
		$d->w_titre("NIY en_tete");
		$d->w_description("NIY en_tete");
	});


ElementDocument::ajouter_widget("description", function($d, $select){
		return $d->article()->p()->text("NIY Descrption($select)");
	});








//ElementDocument::ajouter_widget("richText", function($select){}); // similaire
//ElementDocument::ajouter_widget("field", function($select){}); // ...
// Peut-être que _field peut détecter automatiquement s'il faut traiter un champ de la BDD
// (par ex. pour le richText) en fonction d'une info "type" dans la classe correspondant à la page de ce champ ?
ElementDocument::ajouter_widget("liste", function($d, $select, $function_formattage_elements) {
		$l = $d->ul();
		$l->li()->text("Not Implemented Yet");
		return $l;
	});

/* Widgets :
	function liste($elts, $format) {
		$d = new Document();
		$ul = $d->append->ul();
		foreach ($elts as $k => $e) {
			$ul->append($format($e));
		}
		return $d;
	}
	
	function bouton($texte, $page_callback, $action_callback) {
		// afficher un input
		// lors du clic, appeller $action_callback sur $page_callback ?
	}
*/

?>