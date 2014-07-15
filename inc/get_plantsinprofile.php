<?php
	require('db_connect.php');
	if (isset($_GET['show'])) {
		$showfromprofile = $_GET['show'];
	} else {
		$showfromprofile ='';
		foreach ($spaces as $key => $space) {
			(array_filter($profile[$space])) ? ($showfromprofile .= '1') : ($showfromprofile .= '0');
		}
	}

	$query = new query;
	$query_result = $query->getResult(array("p.plant_id", "p.publish", "p.vensterbank", "p.plantenbak", "p.geveltuin", "p.tuin", "p.balkon", "p.dakterras", "p.kas", "p.serre", "z.zon_id"), array("_planten", "p"), array(array("join_p_zonvoorkeur", "pz", "pz.plant_id = p.plant_id"), array("p_zonvoorkeur", "z", "z.zon_id = pz.zon_id"), array('join_p_namen_nl', 'pnnl', 'pnnl.plant_id = p.plant_id'), array('p_namen_nl', 'nnl', 'nnl.naamnl_id = pnnl.naamnl_id')), "p.publish = 1 AND pnnl.order = 1", array("nnl.naamnl"));

	if ($query_result) {
		foreach ($query_result as $row) {
			if (!isset($plantinprofile[$row['plant_id']])) $plantinprofile[$row['plant_id']] = 0;
			foreach ($spaces as $key => $space) {
				if (array_filter($profile[$space]) AND substr($showfromprofile, $key, 1) == '1' AND $row[$space] AND $profile[$space][$row['zon_id']-1] == 1) {$plantinprofile[$row['plant_id']] = 1;}
			}
		}
	}
	require('db_close.php');
?>