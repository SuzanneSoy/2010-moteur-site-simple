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

	public function type() {
		return $this->type;
	}
	
		/*	public function inclure($elem) {
		// Tente de fusionner $elem avec $this
		// Très mauvaise fonction car l'inclusion peut planter bien après la définition des deux parties.
		niy("inclure");
		}*/

	public function attr($nom, $valeur) {
		$this->attr[$nom] = $valeur;
	}

	public function to_XHTML_5($indent = "") {
		$ret = "";
		$ret .= "$indent<" . $this->type;
		foreach ($this->attr as $k => $v) {
			$ret .= " " . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '"';
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

	public static function has_widget($w) {
		return array_key_exists($w, self::$widgets);
	}
	
	public static function has_type_élément($t) {
		return array_key_exists($t, self::$enfantsÉléments);
	}
	
	public function type_élément_autorisé($t) {
		return self::has_type_élément($t)
			&& in_array($t, self::$enfantsÉléments[$this->type]);
	}
	
	public function __call($fn, $args) {
		if (self::type_élément_autorisé($fn)) {
			$elem = new self($fn);
			
			foreach (self::$attributsÉléments[$fn] as $i => $nom) {
				if (!isset($args[$i])) {
					Debug::error("Argument manquant : $nom pour " . $elem->type);
				}
				$elem->attr($nom, $args[$i]);
			}
			
			$this->enfants[] = $elem;
			return $elem;
		} else if (self::has_widget($fn)) {
			$f = self::$widgets[$fn];
			$a = $args;
			array_unshift($a, $this);
			return call_user_func_array($f, $a);
		} else {
			Debug::error("Impossible d'ajouter un élément $fn à " . $this->type);
			return null;
		}
	}
}

class Document extends ElementDocument {
}

// TODO: Comment s'assurer que le header, footer, nav soit unique ?
$inline_elems = "span text a strong em img";
ElementDocument::ajouter_type_élément("document", "header footer nav article script style");
ElementDocument::ajouter_type_élément("header", "title");
ElementDocument::ajouter_type_élément("title", "text");
ElementDocument::ajouter_type_élément("footer", "");
ElementDocument::ajouter_type_élément("nav", "ul");
ElementDocument::ajouter_type_élément("article", "ul table p form span"); // span ?
ElementDocument::ajouter_type_élément("script", "", "src");
ElementDocument::ajouter_type_élément("style", "", "src");
ElementDocument::ajouter_type_élément("ul", "li");
ElementDocument::ajouter_type_élément("table", "thead tbody tfoot");
ElementDocument::ajouter_type_élément("tbody", "tr");
ElementDocument::ajouter_type_élément("tr", "td th");
ElementDocument::ajouter_type_élément("td", $inline_elems);
ElementDocument::ajouter_type_élément("th", $inline_elems);
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


ElementDocument::ajouter_widget("en_tete", function($d, $select_titre, $select_description){
		//$d->w_titre($this->select("titre"));
		//$d->w_description($this->select("description"));
		$d->w_titre("NIY en_tete");
		$d->w_description("NIY en_tete");
	});


ElementDocument::ajouter_widget("description", function($d, $select){
		return $d->article()->p()->text("NIY Descrption($select)");
	});


ElementDocument::ajouter_widget("field", function($d, $select){
		$f = $d->span("field");
		$f->text("NIY : " . $select);
		return $f;
	});






//ElementDocument::ajouter_widget("richText", function($select){}); // similaire
// Peut-être que _field peut détecter automatiquement s'il faut traiter un champ de la BDD
// (par ex. pour le richText) en fonction d'une info "type" dans la classe correspondant à la page de ce champ ?
ElementDocument::ajouter_widget("liste", function($d, $select, $function_formattage_elements) {
		$l = $d->ul();
		$l->li()->text("Not Implemented Yet");
		return $l;
	});

ElementDocument::ajouter_widget("tableau", function($d, $select, $function_formattage_elements) {
		$t = $d->table();
		$tr = $t->tbody()->tr();
		$tr->td()->text("Not Implemented Yet");
		return $t;
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