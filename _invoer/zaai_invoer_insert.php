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

    for ($i=1; $i<=36; $i++) {
    	if (isset($_GET["monthpart$i"])) {
    		list($maand, $deel) = explode('-', $_GET["monthpart$i"]);
    		$insert = '';
    		$insert = "INSERT INTO $join_p_oogsttijd ($col_p_id, $col_p_maandnr, $col_p_maanddeel_id) VALUES ('$_GET[plant]', $maand, $deel)";
    		if (!$dbconn->query($insert)){
				printf("Errormessage: %s\n", $dbconn->error);
			}
			echo "added: P_ID = '$_GET[plant]', MAAND = $maand, DEEL = $deel<br>";
		}
    }
?>