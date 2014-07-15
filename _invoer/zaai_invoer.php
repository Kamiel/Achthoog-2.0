<!DOCTYPE html>
<?php
	@ $dbconn = new mysqli('localhost', 'root', 'root', 'achthoog');
	//@ $dbconn = new mysqli('mysql5.achthoog.nl', 'achthoog_user', 'muskuskaasjeskruid', 'achthoog');
    if (mysqli_connect_errno()){
        echo 'Fout: kan niet verbinden met de database. Probeert u het later nog eens.';
        exit;
    }       
    $dbconn->set_charset('utf8');

	//Defining recurring names for tables
	$table_plants = '_planten';
	$table_recipes = '_recepten';
	$join_p_names_nl = 'join_p_namen_nl';
	$table_p_names_nl = 'p_namen_nl';
	$join_p_types = 'join_p_type';
	$table_p_types = 'p_type';
	$join_p_oogsttijd = 'join_p_oogsttijd';
	//Defining recurring names for table columns
	$col_p_id = 'plant_id';
	$col_p_general = 'descr';
	$col_p_publish = 'publish';
	$col_p_name_nl = 'naamnl';
	$col_p_name_nl_id = 'naamnl_id';
	$col_p_name_rank = 'order';
	$col_p_type_id = 'type_id';
	$col_p_type_name = 'type';
	$col_p_type_rank = 'order';
	$col_p_maandnr = 'maandnr';
	$col_p_maanddeel_id = 'maanddeel_id';
	//Defining variables

	//Query - search for all plants 
	$search = "SELECT $table_plants.$col_p_id, $table_p_names_nl.$col_p_name_nl
		FROM $table_plants
			JOIN $join_p_names_nl
				ON $join_p_names_nl.$col_p_id = $table_plants.$col_p_id
			JOIN $table_p_names_nl
				ON $table_p_names_nl.$col_p_name_nl_id = $join_p_names_nl.$col_p_name_nl_id
		WHERE $join_p_names_nl.$col_p_name_rank = 1
		AND $table_plants.$col_p_publish = 1
		ORDER BY $table_plants.$col_p_id";
	$result = $dbconn->query($search);
	$nr_results = $nr_plants = $nr_ids = $result->num_rows;

	for ($i = 0; $i < $nr_results; $i++){
		$row = $result->fetch_assoc();
        $p_id[$i] = $row[$col_p_id];
        $p_name_nl[1][$p_id[$i]] = $row[$col_p_name_nl];
	}

	$search = "SELECT plant_id FROM join_p_oogsttijd";
	$result = $dbconn->query($search);
	$nr_results = $result->num_rows;
	echo $nr_results;
	for ($i = 0; $i < $nr_results; $i++){
		$row = $result->fetch_assoc();
        $ingevuld[$row['plant_id']] = 1;
	}
	@ $dbconn = null;
?>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
		<title>Achthoog – zaaiinvoer</title>
		<style media="screen" type="text/css">
			.helemaand {
				float: left;
				width: 110px;
				font-size: 12px
			}
		</style>
	</head>
	<body>
		<h1>OOGSTTIJD</h1>
		<form action="zaai_invoer_insert.php" method="get">
			<select name="plant">
				<?php
					for($i = 0; $i <= $nr_plants; $i++) {
						if (!isset($ingevuld[$p_id[$i]])) {
							echo '
				<option value="' . $p_id[$i] . '" selected>' . $p_name_nl[1][$p_id[$i]] . '</option>';
						}
					}
				?>
			</select><br>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart1" value="1-1">Begin januari<br>
				<input type="checkbox" class="maand" name="monthpart2" value="1-2">Midden januari<br>
				<input type="checkbox" class="maand" name="monthpart3" value="1-3">Eind januari<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart4" value="2-1">Begin februari<br>
				<input type="checkbox" class="maand" name="monthpart5" value="2-2">Midden februari<br>
				<input type="checkbox" class="maand" name="monthpart6" value="2-3">Eind februari<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart7" value="3-1">Begin maart<br>
				<input type="checkbox" class="maand" name="monthpart8" value="3-2">Midden maart<br>
				<input type="checkbox" class="maand" name="monthpart9" value="3-3">Eind maart<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart10" value="4-1">Begin april<br>
				<input type="checkbox" class="maand" name="monthpart11" value="4-2">Midden april<br>
				<input type="checkbox" class="maand" name="monthpart12" value="4-3">Eind april<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart13" value="5-1">Begin mei<br>
				<input type="checkbox" class="maand" name="monthpart14" value="5-2">Midden mei<br>
				<input type="checkbox" class="maand" name="monthpart15" value="5-3">Eind mei<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart16" value="6-1">Begin juni<br>
				<input type="checkbox" class="maand" name="monthpart17" value="6-2">Midden juni<br>
				<input type="checkbox" class="maand" name="monthpart18" value="6-3">Eind juni<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart19" value="7-1">Begin juli<br>
				<input type="checkbox" class="maand" name="monthpart20" value="7-2">Midden juli<br>
				<input type="checkbox" class="maand" name="monthpart21" value="7-3">Eind juli<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart22" value="8-1">Begin augustus<br>
				<input type="checkbox" class="maand" name="monthpart23" value="8-2">Midden augustus<br>
				<input type="checkbox" class="maand" name="monthpart24" value="8-3">Eind augustus<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart25" value="9-1">Begin september<br>
				<input type="checkbox" class="maand" name="monthpart26" value="9-2">Midden september<br>
				<input type="checkbox" class="maand" name="monthpart27" value="9-3">Eind september<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart28" value="10-1">Begin oktober<br>
				<input type="checkbox" class="maand" name="monthpart29" value="10-2">Midden oktober<br>
				<input type="checkbox" class="maand" name="monthpart30" value="10-3">Eind oktober<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart31" value="11-1">Begin november<br>
				<input type="checkbox" class="maand" name="monthpart32" value="11-2">Midden november<br>
				<input type="checkbox" class="maand" name="monthpart33" value="11-3">Eind november<br><br>
			</div>
			<div class="helemaand">
				<input type="checkbox" class="maand" name="monthpart34" value="12-1">Begin december<br>
				<input type="checkbox" class="maand" name="monthpart35" value="12-2">Midden december<br>
				<input type="checkbox" class="maand" name="monthpart36" value="12-3">Eind december<br>
			</div>
			<input type="submit"/>
			<div style="clear: both"></div><br><br>
		</form>

	</body>
</html>