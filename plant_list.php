<?php
	require('connect.php');

	//Defining recurring names for tables
	$table_plants = '_planten';
	$table_recipes = '_recepten';
	$join_p_names_nl = 'join_p_namen_nl';
	$table_p_names_nl = 'p_namen_nl';
	$join_p_types = 'join_p_type';
	$table_p_types = 'p_type';
	//Defining recurring names for table columns
	$col_p_id = 'plant_id';
	$col_p_general = 'descr';
	$col_p_publish = 'publish';
	$col_p_name_nl = 'naamnl';
	$col_p_name_nl_id = 'naamnl_id';
	$col_p_name_rank = 'order';
	$col_p_type_id = 'type_id';
	$col_p_type = 'type_id';
	$col_p_type_rank = 'order';
	//Defining variables

	$search = "SELECT $col_p_type_id, $col_p_type FROM $table_p_types";
	$result = $dbconn->query($search);
	$nr_results = $result->num_rows;
	for ($i = 0; $i < $nr_results; $i++){
		$row = $result->fetch_assoc();
		$p_type_count[$row[$col_p_type_id]] = 0;
		$p_type_name[$row[$col_p_type_id]] = $row[$col_p_type];
	}
	$p_types_total = $i;

	$search = "SELECT plant_id, maandnr, maanddeel_id FROM join_p_oogsttijd";
	$result = $dbconn->query($search);
	$nr_results = $result->num_rows;
	for ($i = 0; $i < $nr_results; $i++){
		$row = $result->fetch_assoc();
		$oogsten[$row['plant_id']][$row['maandnr']][$row['maanddeel_id']] = 1;
	}

	//Query - search for all plants 
	$search = "SELECT $table_plants.$col_p_id, $table_p_names_nl.$col_p_name_nl, $table_plants.$col_p_general, $table_p_types.$col_p_type
		FROM $table_plants
			JOIN $join_p_names_nl
				ON $join_p_names_nl.$col_p_id = $table_plants.$col_p_id
			JOIN $table_p_names_nl
				ON $table_p_names_nl.$col_p_name_nl_id = $join_p_names_nl.$col_p_name_nl_id
			JOIN $join_p_types
				ON $join_p_types.$col_p_id = $table_plants.$col_p_id
			JOIN $table_p_types
				ON $table_p_types.$col_p_type_id = $join_p_types.$col_p_type_id
		WHERE $join_p_names_nl.$col_p_name_rank = 1
		AND $join_p_types.$col_p_type_rank = 1
		ORDER BY $table_plants.$col_p_id";
	$result = $dbconn->query($search);
	$nr_results = $nr_plants = $nr_ids = $result->num_rows;
	
	for ($i = 0; $i < $nr_results; $i++){
		$row = $result->fetch_assoc();
        $p_sort[$i] = $p_id = $row[$col_p_id];
        $p_name_nl[1][$p_id] = $row[$col_p_name_nl];
        $p_general[$p_id] = $row[$col_p_general];
        $p_type[$p_id] = $row[$col_p_type];
        $p_type_count[$row[$col_p_type]]++; //sets a counter per plant type (vegetables, herbs, etc.)
        $p_sort_by_type[$row[$col_p_type]][$p_type_count[$row[$col_p_type]]] = $p_id;  //$p_sort_by_type[type][type counter] = plant id
        echo "$p_id {$p_name_nl[1][$p_id]} - ";
    	for ($j = 1; $j <= 12; $j++) {
    		for ($k = 1; $k <= 3; $k++) {
    			if (isset($oogsten[$p_id][$j][$k])) echo $j . '-' . str_repeat('I', $k) . ', ';
    		}
    	}
        echo "<br/>";
	}
?>