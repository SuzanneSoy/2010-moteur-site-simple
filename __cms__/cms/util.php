<?php

function qw($arg) {
	if (is_array($arg)) return $arg;
	$ret = array();
	foreach(explode(" ", $arg) as $v) {
		if ($v !== "") array_push($ret, $v);
	}
	return $ret;
}

?>