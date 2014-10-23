<?php
	// Get information on sowing dates
	$query = new query;
	$query_result = $query->getResult(array('t.plant_id', 'b.bezigheid', 't.periode_id', 't.loc_binnen', 't.loc_kas', 't.loc_buiten'), array('join_p_teeltschema', 't'), array(array('p_bezigheden', 'b', 't.bezigheid_id = b.bezigheid_id')), 't.loc_buiten = 1 OR t.loc_binnen = 1 OR t.loc_kas = 1', array('t.plant_id', 't.teeltmoment_id', 't.bezigheid_id'));
	$today = getdate();
	$now = ($today['mon'] * 3) - 2;
	if ($today['mday'] > 20) $now += 1;
	if ($today['mday'] > 10) $now += 1;
	$sowplantnow = $sowplantlater = $harvestnow = array();
	foreach ($query_result as $row) {
		$p_id = $row['plant_id'];
        $bez = $row['bezigheid'];
		$selected_plants[] = $p_id;
        $van[$bez] = substr($row['periode_id'], 0, 2);
        $tot[$bez] = substr($row['periode_id'], 2, 2);
        if ($plantinprofile[$p_id]) {
        	if ($bez == 'zaaien') {
	        	if (!in_array($p_id, $sowplantnow) AND $van[$bez] <= $now AND $tot[$bez] >= $now) $sowplantnow[] = $p_id;
	        	if (!in_array($p_id, $sowplantlater) AND $tot[$bez] > $now) $sowplantlater[] = $p_id;
        	}
        	if ($bez == 'oogsten') {
        		if (!in_array($p_id, $harvestnow) AND $van[$bez] <= $now AND $tot[$bez] >= $now) $harvestnow[] = $p_id;
        	}
        }
	}
?>