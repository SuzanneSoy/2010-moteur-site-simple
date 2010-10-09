<?php

// Chaque type d'élément est une sous-classe de ElementDocument, et impléménte uniquement les méthodes de création qui respectent les règles d'imbrication des éléments.
// Pour les éléments dont les enfants possibles dépendent du parent (par ex. <a>), on restreindra les enfants et (parents) possibles à quelque chose de sensé.
// Plutôt que d'avoir plein de sous-classes, ElementDocument a une méthode __call(), qui vérifie ce qu'on peut appeller en fonction du type de l'élément.

// Propriété "url" pour le document ? Probablement innutile, pusiqu'on bosse principalement avec des uid (le href d'un <a> est un uid).

class ElementDocument {
	public $espaceCss = null;
	private static $types = array();
	private static $widgets = array();
	private $type = null;
	private $enfants = array();
	private $attr = array();
	protected $document = null;
	
	public static function add_type($singleton, $type, $typesEnfants = "", $attributs = "") {
		if ($singleton !== true && $singleton !== false) {
			$attributs = $typesEnfants;
			$typesEnfants = $type;
			$type = $singleton;
			$singleton = false;
		}
		self::$types[$type] = array(
			"singleton" => $singleton,
			"enfants" => qw($typesEnfants),
			"attributs" => qw($attributs)
		);
	}
	
	public static function add_widget($nom, $callback) {
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

	public function __construct($type = "document", &$doc = null) {
		$this->type = $type;
		$this->document = $doc;
	}

	public static function has_widget($w) {
		return array_key_exists($w, self::$widgets);
	}
	
	public function type_autorisé($t) {
		return array_key_exists($t, self::$types) && in_array($t, self::$types[$this->type]["enfants"]);
	}
	
	public function singleton_élément($type, $args) {
		if (!array_key_exists($type, $this->document->singletons)) {
			$this->document->singletons[$type] = $this->créer_élément($type, $args);
		}
		return $this->document->singletons[$type];
	}
	
	public function créer_élément($type, $args) {
		$elem = new self($type, $this->document);
		
		foreach (self::$types[$type]["attributs"] as $i => $nom) {
			if (!isset($args[$i])) {
				Debug::error("Argument manquant : $nom pour " . $elem->type);
			}
			$elem->attr($nom, $args[$i]);
		}
		
		$this->enfants[] = $elem;
		return $elem;
	}

	public function créer_widget($nom, $args) {
			$f = self::$widgets[$nom];
			array_unshift($args, $this);
			return call_user_func_array($f, $args);
	}
	
	public function __call($fn, $args) {
		if (self::type_autorisé($fn)) {
			if (self::$types[$fn]["singleton"])
				return $this->singleton_élément($fn, $args);
			else
				return $this->créer_élément($fn, $args);
		} else if (self::has_widget($fn)) {
			return $this->créer_widget($fn, $args);
		} else {
			Debug::error("Impossible d'ajouter un élément $fn à " . $this->type);
			return null;
		}
	}
}

class Document extends ElementDocument {
	protected $singletons = array();
	public function __construct() {
		parent::__construct("document", $this);
		$this->header();
		$this->nav();
		$this->article();
		$this->footer();
	}
}

// TODO: Comment s'assurer que le header, footer, nav soit unique ?
$inline_elems = "span text a strong em img";
ElementDocument::add_type("document", "header footer nav article script style");
ElementDocument::add_type(true, "header", "title");
ElementDocument::add_type("title", "text");
ElementDocument::add_type(true, "footer", "");
ElementDocument::add_type(true, "nav", "ul");
ElementDocument::add_type(true, "article", "ul table p form span"); // span ?
ElementDocument::add_type("script", "", "src");
ElementDocument::add_type("style", "", "src");
ElementDocument::add_type("ul", "li");
ElementDocument::add_type("table", "thead tbody tfoot");
ElementDocument::add_type("tbody", "tr");
ElementDocument::add_type("tr", "td th");
ElementDocument::add_type("td", $inline_elems);
ElementDocument::add_type("th", $inline_elems);
ElementDocument::add_type("li", $inline_elems);
ElementDocument::add_type("form", "input_text_line input_text_multi input_text_rich input_file");
ElementDocument::add_type("a", $inline_elems, "href");
ElementDocument::add_type("span", $inline_elems, "class");
ElementDocument::add_type("img", "", "alt src");
ElementDocument::add_type("p", $inline_elems);
ElementDocument::add_type("text", "", "text");



ElementDocument::add_widget("titre", function($d, $select){
		// renvoie un <h2> ou un <input> selon les droits
		return $d->header()->title()->text("Not Implemented Yet : w_titre($select)");
	});


ElementDocument::add_widget("en_tete", function($d, $select_titre, $select_description){
		//$d->w_titre($this->select("titre"));
		//$d->w_description($this->select("description"));
		$d->w_titre("NIY en_tete");
		$d->w_description("NIY en_tete");
	});


ElementDocument::add_widget("description", function($d, $select){
		return $d->article()->p()->text("NIY Descrption($select)");
	});


ElementDocument::add_widget("field", function($d, $select){
		$f = $d->span("field");
		$f->text("NIY : " . $select);
		return $f;
	});






//ElementDocument::add_widget("richText", function($select){}); // similaire
// Peut-être que _field peut détecter automatiquement s'il faut traiter un champ de la BDD
// (par ex. pour le richText) en fonction d'une info "type" dans la classe correspondant à la page de ce champ ?
ElementDocument::add_widget("liste", function($d, $select, $function_formattage_elements) {
		$l = $d->ul();
		$l->li()->text("Not Implemented Yet");
		return $l;
	});

ElementDocument::add_widget("tableau", function($d, $select, $function_formattage_elements) {
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