<?php
	$query_result = $query->getResult(
		array('ri.recept_id', 'rn.receptnaam'),
		array('_recepten', 'r'),
		array(
			array('join_r_ingredienten', 'ri', 'r.recept_id = ri.recept_id'),
			array('r_ingr_lijst', 'il', 'ri.ingr_id = il.ingr_id'),
			array('join_r_ingr_namen', 'rin', 'rin.ingr_naam_id = il.ingr_naam_id'),
			array('r_receptnamen', 'rn', 'rn.receptnaam_id = r.receptnaam_id')
		),
		'rin.plant_id = ' . $pid,
		NULL);

	if (isset($query_result)) {
		foreach ($query_result as $row) {
	    	$receptnamen[$row['recept_id']] = $row['receptnaam'];
	    }
	}

	$query_result = $query->getResult(array('ingr_eenh_id', 'ingr_eenh', 'ingr_eenh_meerv'), array('r_ingr_eenheden'), NULL, NULL, NULL);
	if (isset($query_result)) {foreach ($query_result as $row) {$eenheden[$row['ingr_eenh_id']] = array($row['ingr_eenh'], $row['ingr_eenh_meerv']);}}
	$query_result = $query->getResult(array('ingr_prefix_id', 'ingr_prefix'), array('r_ingr_prefix'), NULL, NULL, NULL);
	if (isset($query_result)) {foreach ($query_result as $row) {$prefix[$row['ingr_prefix_id']] = $row['ingr_prefix'];}}
	$query_result = $query->getResult(array('ingr_suffix_id', 'ingr_suffix'), array('r_ingr_suffix'), NULL, NULL, NULL);
	if (isset($query_result)) {foreach ($query_result as $row) {$suffix[$row['ingr_suffix_id']] = $row['ingr_suffix'];}}
	$query_result = $query->getResult(array('ingr_naam_id', 'plant_id'), array('join_r_ingr_namen'), NULL, NULL, NULL);
	if (isset($query_result)) {foreach ($query_result as $row) {$ingr_planten[$row['ingr_naam_id']][] = $row['plant_id'];}}
	$query_result = $query->getResult(array('serv_eenh_id', 'serv_eenh'), array('r_serveereenheden'), NULL, NULL, NULL);
	if (isset($query_result)) {foreach ($query_result as $row) {$serveereenheden[$row['serv_eenh_id']] = $row['serv_eenh'];}}

	$query_result = $query->getResult(
		array('r.serv_aantal_min', 'r.serv_aantal_max', 'r.serv_eenh_id', 'r.voorbereiding', 'r.bereiding', 'r.serveertips', 'rin.ingr_naam_id', 'rin.ingr_naam', 'rin.ingr_naam_meerv', 'il.ingr_aantal_min', 'il.ingr_aantal_max', 'il.ingr_eenh_id', 'il.ingr_prefix_id', 'il.ingr_suffix_id'),
		array('_recepten', 'r'),
		array(
			array('join_r_ingredienten', 'ri', 'r.recept_id = ri.recept_id'),
			array('r_ingr_lijst', 'il', 'ri.ingr_id = il.ingr_id'),
			array('r_ingr_namen', 'rin', 'rin.ingr_naam_id = il.ingr_naam_id')
		),
		'r.recept_id = ' . $rid,
		NULL);

	if (isset($query_result)) {
		foreach ($query_result as $row) {
	    	$ingredienten[$row['ingr_naam_id']] = array($row['ingr_aantal_min'], $row['ingr_aantal_max'], $row['ingr_naam'], $row['ingr_naam_meerv']);
	    	if (isset($row['ingr_eenh_id'])) $ingredienten[$row['ingr_naam_id']][4] = array($eenheden[$row['ingr_eenh_id']][0], $eenheden[$row['ingr_eenh_id']][1]);
	    	if (isset($row['ingr_prefix_id'])) $ingredienten[$row['ingr_naam_id']][5] = $prefix[$row['ingr_prefix_id']];
	    	if (isset($row['ingr_suffix_id'])) $ingredienten[$row['ingr_naam_id']][6] = $suffix[$row['ingr_suffix_id']];
	    	if (isset($ingr_planten[$row['ingr_naam_id']])) $ingredienten[$row['ingr_naam_id']][7] = $ingr_planten[$row['ingr_naam_id']];
	    	$voorbereiding = $row['voorbereiding'];
	    	$bereiding = $row['bereiding'];
	    	$serveertips = $row['serveertips'];
	    	if (isset($row['serv_aantal_min'])) $serveert[0] = $row['serv_aantal_min'];
	    	if (isset($row['serv_aantal_max'])) $serveert[1] = $row['serv_aantal_max'];
	    	if (isset($row['serv_eenh_id'])) $serveert[2] = $serveereenheden[$row['serv_eenh_id']];

	    }
	}
?>