<?php

function hash_p($p) {
	$i = 0;
	$h = $p . "2mSU5XkpM5av3Jy9xzqAa3AuaoMo25wR";
	while ($i < 256) {
		$h = hash('sha256', $h . $i);
		$i += 1;
	}
	return $h;
}

function r_is_bad($arg, $r_min, $r_max) {
	return strlen($arg) < $r_min || strlen($arg) > $r_max;
}
