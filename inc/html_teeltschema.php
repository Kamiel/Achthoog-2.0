<?php
	if (isset($teeltschema)) {
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
				for ($row = 1; $row < 4; $row++) {
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
						foreach ($colors as $key => $color) {
							if (isset($teeltinfo[$color[0]]) AND $row == $color[1]) {
								$cellcolor = $color[2];
								$info = $teeltinfo[$color[0]];
								$colors[$key][3] = true;
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
								if ($i >= $a OR $i <= $b) echo " style=\"background-color: $cellcolor;\"";
							} else {
								if ($a <= $i AND $b >= $i) echo " style=\"background-color: $cellcolor;\"";
							}
						}
						echo '></td>';
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
			if (isset($color[3])) {
				echo "<span class=\"color\" style=\"background-color: " . $color[2] . ";\">&nbsp;</span><span class=\"explanation\">= " . $color[0] . "</span>";
			}
		}

		echo "<div class=\"clear\"></div></section>";
	} else {
		echo "<p>Geen teeltschema beschikbaar.</p>";
	}
?>