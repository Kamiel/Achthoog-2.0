<?php
	// Get all plant types
	$query = new query;
	$query_result = $query->getResult(array('type_id', 'type'), array('p_type', NULL), NULL, NULL, NULL);
	foreach ($query_result as $row) {
		$p_type_count[$row['type_id']] = 0;
		$p_type_name[$row['type_id']] = $row['type'];
	}
	$p_types_total = count($query_result);
?>