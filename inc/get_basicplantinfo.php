<?php
	// Get most common Dutch name, desription, plant type and amount of plants per type
	$sort_options = array('naamnl-asc', 'naamnl-desc');
	$sort_option_labels = array('Sorteer (A–Z)', 'Sorteer (Z–A)');

	if (!isset($_GET['sort'])) $_GET['sort'] = 'naamnl-asc';
	if (!in_array($_GET['sort'], $sort_options)) die('Fout: deze sorteermogelijkheid wordt niet ondersteund');
	$temp = explode('-', $_GET['sort']);
	$sortby = $temp[0] . ' ' . $temp[1];
	$query = new query;
	$query_result = $query->getResult(array('p.plant_id', 'n.naamnl', 'p.descr', 't.type_id'), array('_planten', 'p'), array(array('join_p_namen_nl', 'pn', 'pn.plant_id = p.plant_id'), array('p_namen_nl', 'n', 'n.naamnl_id = pn.naamnl_id'), array('join_p_type', 'pt', 'pt.plant_id = p.plant_id'), array('p_type', 't', 't.type_id = pt.type_id')), 'pn.order = 1 AND pt.order = 1 AND p.publish = 1', $sortby);
	$x = 0;
	foreach ($query_result as $row) {
		if ($plantinprofile[$row['plant_id']]) {
			$p_sort[$x] = $p_id = $row['plant_id'];
	        $p_name_nl[1][$p_id] = $row['naamnl'];
	        $p_descr[$p_id] = $row['descr'];
	        $p_type[$p_id] = $row['type_id'];
	        $p_type_count[$row['type_id']]++; //sets a counter per plant type (vegetables, herbs, etc.)
	        $p_sort_by_type[$row['type_id']][$p_type_count[$row['type_id']]] = $p_id;  //$p_sort_by_type[type][type counter] = plant id
	        $x++;
	    }
	}
?>