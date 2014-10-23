<?php
	if (isset($teeltschema)) {
		print_r($teeltschema);
		echo '
<table class="teeltschema">
	<tr class="tableheader">';
		$teeltmomentcheck = false;
		foreach($teeltschema as $teeltmoment => $temp) {
			if (count($temp) > 1) {
				$teeltmomentcheck = true;
			} else {
				foreach ($temp as $jaar => $teeltinfo) {
					foreach ($teeltinfo as $key => $go) {
						if ($go[0] != NULL) {
							$teeltmomentcheck = true;
						}
					}
				}
			}
		}
		if ($teeltmomentcheck) echo '
		<th class="teeltmomentheader"></th>';
		for ($i = 0; $i < 12; $i++) {
			echo '<th class="bottom" colspan="3">' . $maanden[$i] . '</th>';
		}
		echo '
	</tr>';	
		foreach($teeltschema as $teeltmoment => $temp) {
			$stap = 0;
			foreach ($temp as $jaar => $teeltinfo) {
				$stap++;
				for ($row = 1; $row <= 3; $row++) {
					echo "
	<tr>";
					if ($row == 1) {
						if (count($temp) == $stap) {$lijn = 'bottom';} else {$lijn = 'middle';}
						foreach ($teeltinfo as $key => $go) {$moment = $go[0];}
						if ($teeltmomentcheck) {
							echo "
		<th class=\"teeltmoment $lijn\" rowspan=\"3\">";
							if ($jaar == 1) echo $moment . "</th>";
							if ($jaar > 1) echo "<span class=\"jaar\">jaar $jaar</span></th>";
						}
					}
					for ($i = 0; $i < 36; $i++) {
						$cellcolor = '';
						$icon = $test = '';
						foreach ($colors as $key => $color) {
							if (isset($teeltinfo[$color[0]]) AND $row == $color[1]) {
								$x = $teeltinfo[$color[0]];
								if ($x[3] == true AND $x[4] == true AND $x[5] == true) {
								} elseif ($x[3] == true AND $x[4] == true) { $icon = '<img src="images/icons/loc_binnen-glas.svg">';
								} elseif ($x[4] == true AND $x[5] == true) {
								} elseif ($x[3] == true AND $x[5] == true) {
								} elseif ($x[3] == true) { $icon = '<img src="images/icons/loc_binnen.svg">';
								} elseif ($x[4] == true) { $icon = '<img src="images/icons/loc_glas.svg">';
								} elseif ($x[5] == true) { $icon = '<img src="images/icons/loc_buiten.svg">';
								} else { echo 'test';}

								if (is_array($color[2])) {
									for ($j = 0; $j < 3; $j++) {
										if ($teeltinfo[$color[0]][$j+3] == true) {
											$cellcolor = $color[2][$j];
											$colors[$key][4+$j] = true;
										}
									}
								} else {
									$cellcolor = $color[2];
									$colors[$key][3] = true;
								}
								$info = $teeltinfo[$color[0]];
							}
						}
						echo '
			<td';		
						if ($row == 3) {
							echo " class=\"$lijn";
							if ($i == 0) echo " left";
							if (($i % 3) == 2) echo " right";
						} else {
							echo " class=\"";
							if ($i == 0) echo "left";
							if (($i % 3) == 2) echo ' right';

						}
						echo "\"";
						if ($cellcolor) {
							$a = intval($info[1]) - 1;
							$b = intval($info[2]) - 1;
							if ($a > $b) {
								if ($i >= $a OR $i <= $b) echo " style=\"background-color: $cellcolor;\">$icon";
							} else {
								if ($a <= $i AND $b >= $i) echo " style=\"background-color: $cellcolor;\">$icon";
							}
						}
						echo "</td>";
					}
					echo "
		</tr>";
				}
			}
		}
		echo "
	</table>
	<section class=\"legenda\">";

		foreach ($colors as $key => $color) {
			for ($j = 3; $j <= 6; $j++) {
				if (isset($color[$j])) {
					if (isset($color[4])) $labeladd = 'binnen';
					if (isset($color[5])) $labeladd = 'kas';
					if (isset($color[6])) $labeladd = 'buiten';
					if (isset($color[4], $color[5])) $labeladd = 'binnen en in de kas';
					if (isset($color[4]) AND isset($color[5]) AND isset($color[6])) $labeladd = 'overal';
					$legenda = ($j == 3 ? $color[2] : $color[2][$j-4]);
					$label = $color[0] . ($j > 3 ? ' (' . $labeladd . ')' : '');
					echo "<span class=\"color\" style=\"background-color: " . $legenda . ";\">&nbsp;</span><span class=\"explanation\">= " . $label . "</span>";
				}
			}
		}

		echo "<div class=\"clear\"></div></section>";
	} else {
		echo "<p>Geen teeltschema beschikbaar.</p>";
	}
?>