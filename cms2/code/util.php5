<?php

function qw($arg = null, $arg2 = null, $sep = " ") {
	if ($arg === null && $arg2 === null)
		return array();
	
	$ret = array();
	if (is_array($arg))	{
		if ($arg2 === null) {
			return $arg;
		} else {
			$ret = $arg;
			$arg = $arg2;
		}
	}
	foreach(explode($sep, $arg) as $v) {
		if ($v !== "") array_push($ret, $v);
	}
	return $ret;
}

function str_contains($str, $small) {
	return strpos($str, $small) !== false;
}

function applyXSLT($xml, $xslt_file) {
	$dom = new DOMDocument();
	$dom->preserveWhiteSpace = false;
	$dom->loadXML($xml);
	
	$xsl = new DOMDocument();
	$xsl->load($xslt_file); // LIBXML_NOCDATA ?
	
	$xslt = new XSLTProcessor();
	$xslt->importStylesheet($xsl);
	
	return $xslt->transformToXML($dom);
}

function toString($obj) {
	if (is_object($obj) && method_exists($obj, "toString")) {
		return $obj->toString();
	} else {
		return "".$obj;
	}
}

function correspondance_accents(&$arr_ascii, &$arr_accents, $ascii, $accents) {
	$_accents = explode(".", $accents);
	foreach ($_accents as $k=>$v) {
		array_push($arr_accents, $v);
		array_push($arr_ascii, $ascii);
	}
}

// Transforme en une chaîne qui match [a-zA-Z][-a-zA-Z0-9_]*
function str_to_nix($input) {
	$ascii = array();
	$accents = array();
	correspondance_accents($ascii, $accents, "a", "à.á.â.ä.ã.ǎ.å");
	correspondance_accents($ascii, $accents, "e", "è.é.ê.ë.ě.ẽ");
	correspondance_accents($ascii, $accents, "i", "ì.í.î.ï.ĩ.ǐ");
	correspondance_accents($ascii, $accents, "o", "ò.ó.ô.ö.õ.ǒ.ø");
	correspondance_accents($ascii, $accents, "u", "ù.ú.û.ü.ũ.ǔ.ů");
	correspondance_accents($ascii, $accents, "y", "ỳ.ý.ŷ.ÿ.ỹ.ẙ");
	correspondance_accents($ascii, $accents, "c", "ç");
	correspondance_accents($ascii, $accents, "A", "À.Á.Â.Ä.Ã.Ǎ.Å");
	correspondance_accents($ascii, $accents, "E", "È.É.Ê.Ë.Ě.Ẽ");
	correspondance_accents($ascii, $accents, "I", "Ì.Í.Î.Ï.Ĩ.Ǐ");
	correspondance_accents($ascii, $accents, "O", "Ò.Ó.Ô.Ö.Õ.ǒ.Ø");
	correspondance_accents($ascii, $accents, "U", "Ù.Ú.Û.Ü.Ũ.Ů.ǔ");
	correspondance_accents($ascii, $accents, "Y", "Ŷ.Ý.Ŷ.Ÿ.Ỹ");
	correspondance_accents($ascii, $accents, "C", "Ç");
	correspondance_accents($ascii, $accents, "ae", "æ");
	correspondance_accents($ascii, $accents, "oe", "œ");
	correspondance_accents($ascii, $accents, "AE", "Æ");
	correspondance_accents($ascii, $accents, "OE", "Œ");
	correspondance_accents($ascii, $accents, "-", " ");
	$input = str_replace($accents, $ascii, $input);
	$first = preg_replace("/[^a-zA-Z]/", "a", substr($input, 0, 1));
	$rest = preg_replace("/[^-a-zA-Z0-9_]/", "-", substr($input, 1));
	return $first . $rest;
}

/**** Début PATH ****/

// http://www.liranuna.com/php-path-resolution-class-relative-paths-made-easy/
// Licence : WTFPL
/**
 * @class Path
 *
 * @brief Utility class that handles file and directory pathes
 *
 * This class handles basic important operations done to file system paths.
 * It safely renders relative pathes and removes all ambiguity from a relative path.
 *
 * @author Liran Nuna
 */
final class Path
{
	/**
	 * Returns the parent path of this path.
	 * "/path/to/directory" will return "/path/to"
	 *
	 * @arg $path	The path to retrieve the parent path from
	 */
	public static function dirname($path) {
		return dirname(self::normalize($path));
	}
 
	/**
	 * Returns the last item on the path.
	 * "/path/to/directory" will return "directory"
	 *
	 * @arg $path	The path to retrieve the base from
	 */
	public static function basename($path) {
		return basename(self::normalize($path));
	}
 
	/**
	 * Normalizes the path for safe usage
	 * This function does several operations to the given path:
	 *   * Removes unnecessary slashes (///path//to/////directory////)
	 *   * Removes current directory references (/path/././to/./directory/./././)
	 *   * Renders relative pathes (/path/from/../to/somewhere/in/../../directory)
	 *
	 * @arg $path	The path to normalize
	 */
	public static function normalize($path) {
		return array_reduce(explode('/', $path), create_function('$a, $b', '
			if($a === 0)
				$a = "/";
 
			if($b === "" || $b === ".")
				return $a;
 
			if($b === "..")
				return dirname($a);
 
			return preg_replace("/\/+/", "/", "$a/$b");
		'), 0);
	}
	
	// Ajout par js jahvascriptmaniac+github@gmail.com
	public static function realpath($path) {
		return self::normalize(realpath($path));
	}
 
	/**
	 * Combines a list of pathes to one safe path
	 *
	 * @arg $root	The path or array with values to combine into a single path
	 * @arg ...		Relative pathes to root or arrays
	 *
	 * @note		This function works with multi-dimentional arrays recursively.
	 */
	public static function combine($root, $rel1) {
		$arguments = func_get_args();
		return self::normalize(array_reduce($arguments, create_function('$a,$b', '
			if(is_array($a))
				$a = array_reduce($a, "Path::combine");
			if(is_array($b))
				$b = array_reduce($b, "Path::combine");
 
			return "$a/$b";
		')));
	}

	// Ajout par js jahvascriptmaniac+github@gmail.com
	// Depuis le dossier $a, construire un chemin relatif vers $b.
	public static function relative($a, $b) {
		$a = explode('/', self::normalize($a));
		$b = explode('/', self::normalize($b));

		// Zapper la partie commune
		for ($i = 0; $i < count($a) && $i < count($b); $i++) {
			if (! ($a[$i] == $b[$i])) break;
		}
		
		$rel = ".";
		for ($j = $i; $j < count($a); $j++) {
			$rel .= "/..";
		}
		for ($j = $i; $j < count($b); $j++) {
			$rel .= '/' . $b[$j];
		}
		
		return $rel;
	}
	
	/**
	 * Empty, private constructor, to prevent instantiation
	 */
	private function __construct() {
		// Prevents instantiation
	}
}

/**** Fin PATH ****/

?>