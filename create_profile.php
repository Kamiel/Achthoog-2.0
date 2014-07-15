<?php
	include 'inc/set_spaces.php'; // Set array $spaces

	$profileshort = "";
	foreach ($spaces as $space) {
		$profile[$space] = array(0,0,0);
		if (isset($_POST[$space])){
			for ($i = 1; $i <= 3; $i++) {
				(isset($_POST[$space . 'zon' . $i])) ? ($x = 1) : ($x = 0);
				$profile[$space][$i - 1] = $x;
				$profileshort .= $x;
			}
		} else {
			$profileshort .= '000';
		}
	}
	header("Location: planten.php?pr=" . bindec($profileshort));
?>