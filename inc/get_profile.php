<?php
	if (isset($_GET['pr'])) {
		foreach ($spaces as $key=>$space) {
			for ($i = 0; $i < 3; $i++) {
				$profile[$space][$i] = substr(str_pad(decbin($_GET['pr']), 24, "0", STR_PAD_LEFT), 3 * $key + $i, 1); // vul de binaire versie van de decimale profielcode vooraan aan met nullen tot de string 24 lang is en pak vervolgens de waarde die bij de array hoort
			}
		}
	} else {
		echo 'Geen profiel opgegeven';
	}

?>