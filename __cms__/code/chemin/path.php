<?php
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

?>