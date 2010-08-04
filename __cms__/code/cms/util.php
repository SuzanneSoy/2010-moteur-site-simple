<?php

function qw($arg, $sep = " ") {
	if (is_array($arg)) return $arg;
	$ret = array();
	foreach(explode($sep, $arg) as $v) {
		if ($v !== "") array_push($ret, $v);
	}
	return $ret;
}

?>