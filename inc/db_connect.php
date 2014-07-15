<?php
	@ $dbconn = new mysqli('localhost', 'root', 'root', 'achthoog');
	//@ $dbconn = new mysqli('mysql5.achthoog.nl', 'achthoog_user', 'muskuskaasjeskruid', 'achthoog');
    if (mysqli_connect_errno()){
        echo 'Fout: kan niet verbinden met de database. Probeert u het later nog eens.';
        exit;
    }       
    $dbconn->set_charset('utf8');
?>