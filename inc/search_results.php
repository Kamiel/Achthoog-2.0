<?php
	require('db_connect.php');

	$category = 'geen categorie gedefiniëerd';
	if ($_GET['cat'] == 'plant_name_nl') {$category = 'plantnaam (Nederlands)';}
	if ($_GET['cat'] == 'plant_name_lat') {$category = 'plantnaam (Latijn)';}
	if ($_GET['cat'] == 'recipe_name') {$category = 'receptnaam';}
	if ($_GET['cat'] == 'ingredient') {$category = 'ingrediënt';}
	
	$search_term = trim($_GET['s']);
	if ($search_term == '*') $search_term = '';

	$query = new query;
	$query_result = $query->getResult(array("p.plant_id", "n.naamnl", "p.descr", "t.type_id"), array("_planten", "p"), array(array("join_p_namen_nl", "pn", "pn.plant_id = p.plant_id"), array("p_namen_nl", "n", "n.naamnl_id = pn.naamnl_id"), array("join_p_type", "pt", "pt.plant_id = p.plant_id"), array("p_type", "t", "t.type_id = pt.type_id")), "pn.order = 1 AND pt.order = 1 AND p.publish = 1 AND n.naamnl LIKE '%$search_term%'", array("n.naamnl_id"));
	$x = 0;
	if ($query_result) {
		foreach ($query_result as $row) {
			if ($plantinprofile[$row['plant_id']]) {
		    	$p_sort[$x] = $p_id = $row['plant_id'];
		        $p_name_nl[1][$p_id] = $row['naamnl'];
			        if (substr($row['naamnl'], 0, 2) == 'ij') {
		        	$p_name_nl_cap[1][$p_id] = 'IJ' . substr($row['naamnl'], 2);
		        } else {
		        	$p_name_nl_cap[1][$p_id] = ucfirst($row['naamnl']);
		        }
		        $p_descr[$p_id] = $row['descr'];
		        $p_type[$p_id] = $row['type_id'];
		        $p_type_count[$row['type_id']]++; //sets a counter per plant type (vegetables, herbs, etc.)
		        $p_sort_by_type[$row['type_id']][$p_type_count[$row['type_id']]] = $p_id;  //$p_sort_by_type[type][type counter] = plant id
		        $x++;
		    }
		}
	}
	$nr_results = $x;


	require('db_close.php');
?>