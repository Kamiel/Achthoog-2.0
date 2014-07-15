<?php
	require('inc/db_connect.php');

	$search = "SELECT p.plant_id, n.naamnl
		FROM _planten AS p
			JOIN join_p_namen_nl AS pn
				ON pn.plant_id = p.plant_id
			JOIN p_namen_nl AS n
				ON n.naamnl_id = pn.naamnl_id
		WHERE pn.order = 1
		ORDER BY n.naamnl";
	$result = $dbconn->query($search);
	$nr_results = $result->num_rows;
	for ($i = 0; $i < $nr_results; $i++){
		$row = $result->fetch_assoc();
		$p_id = $row['plant_id'];
		$namen_nl[1][$p_id] = $row['naamnl'];
	}


	$search = "SELECT t.plant_id, b.bezigheid, t.teeltmoment_id, t.teeltmoment_naam, t.jaar, t.loc_binnen, t.loc_kas, t.loc_buiten, t.periode_id
		FROM join_p_teeltschema AS t
			JOIN p_bezigheden AS b
				ON t.bezigheid_id = b.bezigheid_id
			WHERE t.loc_buiten = 1 OR t.loc_binnen = 1 OR t.loc_kas = 1
			ORDER BY t.plant_id, t.teeltmoment_id, t.bezigheid_id";
	$result = $dbconn->query($search);	
	while ($row = $result->fetch_assoc()) {
        $p_id = $row['plant_id'];
        $mom = $row['teeltmoment_id'];
        $bez = $row['bezigheid'];
        $periode[$p_id][$mom][$bez] = array(substr($row['periode_id'], 0, 2), substr($row['periode_id'], 2, 2));
		$selected_plants[] = $p_id;
        $moment[$p_id] = $mom;
        $bezigheid[$mom] = $bez;
        $van[$bez] = substr($row['periode_id'], 0, 2);
        $tot[$bez] = substr($row['periode_id'], 2, 2);
        $teeltmoment[$row['teeltmoment_id']] = $row['teeltmoment_naam'];
	}


?>
<html>
<head>
	<meta charset="utf-8" />
		<title>Achthoog – T E S T</title>
		<link href="http://fonts.googleapis.com/css?family=Merriweather+Sans:300,300italic,400,400italic,700" rel="stylesheet" type="text/css">
		<!--<link rel="stylesheet" type="text/css" href="css/achthoog.css" />-->
</head>
<body>
	<?php
		foreach(array_keys($periode) as $index => $key) {
		    echo '<h1>' . $namen_nl[1][$key] . '</h1><br>';
		    $deeper1 = $periode[$key];
		    echo '<table>';
		    echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan="3"; text-align="center">jan</td><td colspan="3"; text-align="center">feb</td><td colspan="3"; text-align="center">mrt</td><td colspan="3"; text-align="center">apr</td><td colspan="3"; text-align="center">mei</td><td colspan="3"; text-align="center">jun</td><td colspan="3"; text-align="center">jul</td><td colspan="3"; text-align="center">aug</td><td colspan="3"; text-align="center">sep</td><td colspan="3"; text-align="center">okt</td><td colspan="3"; text-align="center">nov</td><td colspan="3"; text-align="center">dec</td>';
		    	foreach(array_keys($deeper1) as $index => $key) {
		    		$x = $key;
		    		$deeper2 = $deeper1[$key];
		    		$norepeat = 0;
		    		foreach(array_keys($deeper2) as $index => $key) {
		    			echo '<tr><td style="padding-right: 10 px;">';
		    			if ($norepeat == 0) {echo $teeltmoment[$x];} else {echo '&nbsp;';};
		    			echo "</td>";
			    		echo '<td style="padding-right:10px;">' . $key . "</td>";
			    		$color = '#333';
			    		if ($key == 'zaaien') $color = 'green';
			    		if ($key == 'poten') $color = 'green';
			    		if ($key == 'uitplanten') $color = 'blue';
			    		if ($key == 'aanplanten') $color = 'blue';
			    		if ($key == 'oogsten') $color = 'red';
			    		$deeper3 = $deeper2[$key];
			    		for ($i = 1; $i <= 36; $i++) {
			    			echo '<td style="width:20px; background-color:';
			    			if ($deeper3[0] <= $i AND $deeper3[1] >= $i) {
			    				echo $color . ';">';
			    			} else {
			    				echo '#eee;">';
			    			}
			    			echo '</td>';
			    		}
				    	$norepeat = 1;
			    	}
			    	echo "</tr>";
		    	}
		    echo "</table>";
			}
	?>
</body>
