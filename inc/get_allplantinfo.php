<?php
	$query = new query;
	$query_result = $query->getResult(
		array('p.plant_id', 'p.zaaiinfo', 'p.vermeerderen', 'p.teelt', 'p.oogst', 'nlat.naamlat', 'nnl.naamnl', 'pnnl.order', 'p.descr', 't.type_id'),
		array('_planten', 'p'),
		array(
			array('join_p_namen_nl', 'pnnl', 'pnnl.plant_id = p.plant_id'),
			array('p_namen_nl', 'nnl', 'nnl.naamnl_id = pnnl.naamnl_id'),
			array('join_p_namen_lat', 'pnlat', 'pnlat.plant_id = p.plant_id'),
			array('p_namen_lat', 'nlat', 'nlat.naamlat_id = pnlat.naamlat_id'),
			array('join_p_type', 'pt', 'pt.plant_id = p.plant_id'),
			array('p_type', 't', 't.type_id = pt.type_id')
		),
		'p.publish = 1',
		NULL);

	foreach ($query_result as $row) {
		if ($plantinprofile[$row['plant_id']]) {
			$p_sort[$x] = $p_id = $row['plant_id'];
	        $p_name_lat[$p_id][] = $row['naamlat'];
	        $zaaiinfo[$p_id] = $row['zaaiinfo'];
	        $vermeerderinfo[$p_id] = $row['vermeerderen'];
	        $teeltinfo[$p_id] = $row['teelt'];
	        $oogstinfo[$p_id] = $row['oogst'];
	        $p_descr[$p_id] = $row['descr'];
	        $p_type[$p_id] = $row['type_id'];
	        $p_type_count[$row['type_id']]++; //sets a counter per plant type (vegetables, herbs, etc.)
	        $p_sort_by_type[$row['type_id']][$p_type_count[$row['type_id']]] = $p_id;  //$p_sort_by_type[type][type counter] = plant id
	        $p_descr[$p_id] = $row['descr'];
	        $x++;
	    }
	}

	$locations = array('binnen', 'buiten', 'kas');
	$checkspace['binnen'] = $checkspace['buiten'] = $checkspace['kas'] = false;
	if (array_filter($profile['vensterbank']) OR array_filter($profile['serre'])) $checkspace['binnen'] = true;
	if (array_filter($profile['plantenbak']) OR array_filter($profile['geveltuin']) OR array_filter($profile['tuin']) OR array_filter($profile['balkon']) OR array_filter($profile['dakterras'])) $checkspace['buiten'] = true;
	if (array_filter($profile['kas'])) $checkspace['kas'] = true;

	$query = new query;
	$query_result = $query->getResult(
		array('t.teeltmoment_id', 't.teeltmoment_naam', 't.loc_binnen', 't.loc_kas', 't.loc_buiten'),
		array('join_p_teeltschema', 't'),
		array(array('p_bezigheden', 'b', 't.bezigheid_id = b.bezigheid_id')),
		't.plant_id = ' . $pid,
		't.teeltmoment_id');

	if (isset($query_result)) {
		$exclude = array();
		foreach ($query_result as $row) {
			$x = 0;
			foreach ($locations as $loc) {
				if ($checkspace[$loc] AND $row["loc_$loc"] == 1) $x = 1;
			}
        	if ($x == 0) $exclude[] = $row['teeltmoment_id'];
		}
	}

	$query = new query;
	$query_result = $query->getResult(
		array('b.bezigheid', 't.teeltmoment_id', 't.teeltmoment_naam', 't.jaar', 't.loc_binnen', 't.loc_kas', 't.loc_buiten', 't.periode_id'),
		array('join_p_teeltschema', 't'),
		array(array('p_bezigheden', 'b', 't.bezigheid_id = b.bezigheid_id')),
		't.plant_id = ' . $pid,
		't.plant_id, t.teeltmoment_id, t.bezigheid_id');

	if (isset($query_result)) {
		foreach ($query_result as $row) {
			if (in_array($row['teeltmoment_id'], $exclude) != true) {
				($row['loc_binnen'] == 1) ? ($a = true) : ($a = false);
				($row['loc_buiten'] == 1) ? ($b = true) : ($b = false);
				($row['loc_kas'] == 1) ? ($c = true) : ($c = false);
				$teeltschema[$row['teeltmoment_id']][$row['jaar']][$row['bezigheid']] = array($row['teeltmoment_naam'], substr($row['periode_id'], 0, 2), substr($row['periode_id'], 2, 2), $a, $b, $c);
			}
		}
	}

	$query = new query;
	$query_result = $query->getResult(
		array('z.kiemduur_min', 'z.kiemduur_max', 'z.kiemtemp_min', 'z.kiemtemp_max', 'zaaidiepte', 'lichtkiemer'),
		array('join_p_zaaiinfo', 'z'),
		NULL,
		'z.plant_id = ' . $pid,
		NULL);

	if (isset($query_result)) {
		foreach ($query_result as $row) {
			$kiemduur = array($row['kiemduur_min'], $row['kiemduur_max']);
			$kiemtemp = array($row['kiemtemp_min'], $row['kiemtemp_max']);
			$zaaidiepte = $row['zaaidiepte'];
			($row['lichtkiemer'] == 1) ? ($lichtkiemer = true) : ($lichtkiemer = false);
		}
	}


	$query_result = $query->getResult(
		array('w.warn_id', 'w.warnTitel', 'w.warn'),
		array('_planten', 'p'),
		array(
			array('join_p_waarschuwingen', 'pw', 'pw.plant_id = p.plant_id'),
			array('p_waarschuwingen', 'w', 'w.warn_id = pw.warn_id')
		),
		'p.publish = 1 AND p.plant_id = ' . $pid,
		NULL);

	if (isset($query_result)) {
		foreach ($query_result as $row) {
	    	$p_warning[$pid][$row['warn_id']] = array($row['warnTitel'], $row['warn']);
	    }
	}

	/*$query_result = $query->getResult(
		array('r.receptnaam_id', 'r.receptnaam'),
		array('r_receptnamen', 'r'),
		NULL,
		NULL,
		NULL);

	if (isset($query_result)) {
		foreach ($query_result as $row) {
	    	$receptnamen[$row['receptnaam_id']] = $row['receptnaam'];
	    }
	}*/

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


	$stof['inhoudsstoffen'] = array(array('Eiwit (g)', 'eiwit_g'), array('Koolhydraten (g)', 'koolhydraten_g'), array('Vet (g)', 'vet_g'), array('Verzadigd vet (g)', 'vet_verz_g'), array('Cholesterol (mg)', 'cholesterol_mg'), array('Vezels (g)', 'vezels_g'), array('Water (g)', 'water_g'));
	$stof['mineralen'] = array(array('Natrium (mg)', 'na_mg'), array('Kalium (mg)', 'k_mg'), array('Calcium (mg)', 'ca_mg'), array('Magnesium (mg)', 'ma_mg'));
	$stof['vitaminen'] = array(array('Vitamine A (µg)', 'vitA_mcg'), array('Vitamine B1 (µg)', 'vitB1_mcg'), array('Vitamine B2 (µg)', 'vitB2_mcg'), array('Vitamine B6 (µg)', 'vitB6_mcg'), array('Vitamine B12 (µg)', 'vitB12_mcg'), array('Vitamine C (µg)', 'vitC_mcg'), array('Vitamine D (µg)', 'vitD_mcg'), array('Foliumzuur (µg)', 'foliumzuur_mcg'));
	$get_stof = array('gekookt', 'kcal');
	foreach ($stof as $stofsoort => $stofgroep) {
		foreach ($stofgroep as $key => $label) {
			$get_stof[] = $label[1];
		}
	}

	$query_result = $query->getResult($get_stof, array('p_voedingswaarde'), NULL, 'plant_id = ' . $pid, NULL);
	if (isset($query_result)) {
		foreach ($query_result as $row) {
	    	($row['gekookt'] == 1) ? ($x = 'gekookt') : ($x = 'rauw');
	    	$voedingswaarde[$x]['kcal'] = $row['kcal'];
	    	foreach ($stof as $stofsoort => $stofgroep) {
				foreach ($stofgroep as $key => $label) {
					$voedingswaarde[$x][$stofsoort][$key] = $row[$label[1]];
				}
			}
	    }
	} else {
		$voedingswaarde = false;
	}

?>