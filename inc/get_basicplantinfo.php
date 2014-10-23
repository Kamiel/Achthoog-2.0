<?php
	// Get most common Dutch name, desription, plant type and amount of plants per type
	$sort_options = array('naamnl-asc', 'naamnl-desc', 'type_id-asc');
	$sort_option_labels = array('Sorteer (A–Z)', 'Sorteer (Z–A)', 'Sorteer op type');

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

	$query = new query;
	$query_result = $query->getResult(array('p.plant_id', 'z.zon_id'), array('_planten', 'p'), array(array('join_p_zonvoorkeur', 'z', 'z.plant_id = p.plant_id')), 'p.publish = 1', 'plant_id asc');
	$x = 0;
	foreach ($query_result as $row) {
		if ($plantinprofile[$row['plant_id']]) {
			$p_id = $row['plant_id'];
		    if ($row['zon_id'] == 1) {$z = '< 3';}
	        if ($row['zon_id'] == 2) {$z = '3–5';}
	        if ($row['zon_id'] == 3) {$z = '> 5';}
	        if (!isset($p_zon[$p_id])) {
	        	$p_zon[$p_id] = $z;
	        } else {
	        	if ($p_zon[$p_id] == '< 3') {$p_zon[$p_id] = '< 5';}
	        	if ($p_zon[$p_id] == '3–5') {$p_zon[$p_id] = '> 3';}
	        	if ($p_zon[$p_id] == '< 5') {$p_zon[$p_id] = '> 0';}
	        }
	    }
	}
?>