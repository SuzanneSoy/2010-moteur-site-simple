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
	
	public static function add_type($singleton, $type, $typesEnfants = "", $attributs_oblig = "", $attributs_opt = "") {
		if ($singleton !== true && $singleton !== false) {
			$attributs_opt = $attributs_oblig;
			$attributs_oblig = $typesEnfants;
			$typesEnfants = $type;
			$type = $singleton;
			$singleton = false;
		}
		self::$types[$type] = array(
			"singleton" => $singleton,
			"enfants" => qw($typesEnfants),
			"attributs_oblig" => qw($attributs_oblig),
			"attributs_opt" => qw($attributs_opt)
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

	public function to_XHTML_5() {
		return applyXSLT($this->to_XML(), dirname(__FILE__) . "/xslt/xhtml5.xsl");
	}

	public function to_XML($indent = "") {
		if ($this->type == "litteral") {
			return $this->attr['valeur'];
		}
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
				$ret .= $v->to_XML($indent . "  ");
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

		$max = 0;
		foreach (self::$types[$type]["attributs_oblig"] as $i => $nom) {
			if (!isset($args[$i])) {
				Debug("erreur", "Argument manquant : $nom pour " . $elem->type);
			}
			$elem->attr($nom, $args[$i]);
			$max = $i;
		}
		foreach (self::$types[$type]["attributs_opt"] as $i => $nom) {
			if (isset($args[$i])) {
				$elem->attr($nom, $args[$i]);
			}
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
		} elseif (self::has_widget($fn)) {
			return $this->créer_widget($fn, $args);
		} else {
			Debug("erreur", "Impossible d'ajouter un élément $fn à " . $this->type);
			return null;
		}
	}
}

class Document extends ElementDocument {
	protected $singletons = array();
	public function __construct() {
		parent::__construct("document", $this);
		$this->erreurs();
		$this->header();
		$this->nav();
		$this->article();
		$this->footer();
	}
}

$inline_elems = "span text a strong em img";
ElementDocument::add_type("document", "erreurs header footer nav article script style");
ElementDocument::add_type(true, "header", "title");
ElementDocument::add_type(true, "erreurs", "litteral");
ElementDocument::add_type("title", "text");
ElementDocument::add_type("litteral", "", "valeur");
ElementDocument::add_type(true, "footer", "");
ElementDocument::add_type(true, "nav", "ul");
ElementDocument::add_type(true, "article", "ul hX table p form span"); // span ?
ElementDocument::add_type("hX", $inline_elems);
ElementDocument::add_type("script", "", "src");
ElementDocument::add_type("style", "", "src");
ElementDocument::add_type("ul", "li");
ElementDocument::add_type("table", "thead tbody tfoot");
ElementDocument::add_type("tbody", "tr");
ElementDocument::add_type("tr", "td th");
ElementDocument::add_type("td", $inline_elems, "", "colspan rowspan");
ElementDocument::add_type("th", $inline_elems);
ElementDocument::add_type("li", $inline_elems);
ElementDocument::add_type("form", "input_text_line input_text_multi input_text_rich input_file");
ElementDocument::add_type("a", $inline_elems, "href");
ElementDocument::add_type("span", $inline_elems, "", "class");
ElementDocument::add_type("img", "", "alt src");
ElementDocument::add_type("p", $inline_elems);
ElementDocument::add_type("text", "", "text");



ElementDocument::add_widget("titre", create_function('$d, $cell', '
		// renvoie un <h2> ou un <input> selon les droits
		$d->header()->title()->text(toString($cell));
		// TODO : modification si on a les droits.
		$d->article()->hX()->text(toString($cell));
	'));


ElementDocument::add_widget("en_tete", create_function('$d, $cell_titre, $cell_description', '
		$d->w_titre($cell_titre);
		$d->w_description($cell_description);
	'));


ElementDocument::add_widget("description", create_function('$d, $cell', '
		// TODO : modification si on a les droits.
		return $d->article()->p()->text(toString($cell));
	'));


ElementDocument::add_widget("field", create_function('$d, $cell', '
		return call_user_func(array($d, "w_" . $cell->type()), $cell);
	'));


ElementDocument::add_widget("text_line", create_function('$d, $cell', '
		// TODO : modification si on a les droits.
		return $d->text(toString($cell));
	'));


ElementDocument::add_widget("text_nix", create_function('$d, $cell', '
		// Texte naze (sans espaces etc.) à la *nix.
		// TODO : modification si on a les droits.
		// TODO : vérifier que ça match [a-zA-Z][-a-zA-Z0-9_]*
		return $d->text(toString($cell));
	'));


ElementDocument::add_widget("text_rich", create_function('$d, $cell', '
		// TODO : modification si on a les droits.
		// TODO : rendu du texte riche.
		return $d->p()->text(toString($cell));
	'));


ElementDocument::add_widget("bool", create_function('$d, $cell', '
		// checkbox
		return $d->text("w_bool(" . toString($cell) . ")");
	'));


ElementDocument::add_widget("bouton", create_function('$d, $texte, $page_callback, $ressource_callback, $action_callback', '
		// afficher un input[type=button]
		// lors du clic, appeller $action_callback sur $page_callback/?res=$ressource_callback ?
		return $d->text("Not Implemented Yet : w_bouton($texte, $page_callback, $ressource_callback, $action_callback)");
	'));


// Le widget w_img_file doit gérer le stockage de l'image dans un dossier,
// la création de la miniature et le stockage dans la BDD du chemin vers l'image.
ElementDocument::add_widget("img_file", create_function('$d, $cell_description, $cell_img', '
		// TODO : modification si on a les droits.
		// input[file] et <img>
		$img = $d->img(toString($cell_description), toString($cell_img));
		$d->w_description($cell_description);
		return $img;
	'));


ElementDocument::add_widget("date", create_function('$d, $select', '
		// affichage localisé.
		return $d->text("Not Implemented Yet : date($select)");
	'));


ElementDocument::add_widget("liste", create_function('$d, $list_cells, $function_formattage_elements', '
		$ul = $d->ul();
		foreach ($list_cells as $cell) {
			$li = $ul->li();
			$function_formattage_elements($cell, $li);
		}
		return $ul;
	'));

ElementDocument::add_widget("tableau", create_function('$d, $select, $function_formattage_elements', '
		$t = $d->table();
		$tr = $t->tbody()->tr();
		$tr->td()->text("Not Implemented Yet");
		return $t;
	'));

?>