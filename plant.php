<!DOCTYPE html>
<?php
	spl_autoload_register(function ($class) {
	    include 'inc/' . $class . '.class.php';
	});

	$pid = $_GET['p'];
	
	include 'inc/set_spaces.php'; // Set array $spaces
	include 'inc/set_sunhours.php';

	include 'inc/get_profile.php'; // Get profile
	include 'inc/get_plantsinprofile.php'; // Get all plants that fit the profile

	include 'inc/get_planttypes.php'; // Get all plant types
	include 'inc/get_basicplantinfo.php'; // Get most common Dutch name, description, plant type and amount of plants per type
	include 'inc/get_allplantinfo.php'; // Get all plant info

	$menu_selected = 'planten';

	
	$descr = explode("\n\n", $p_descr[$pid]);
	(isset($vermeerderinfo[$pid])) ? ($vermeerder = explode("\n\n", $vermeerderinfo[$pid])) : ($vermeerder[0] = 'Geen informatie over vermeerdering beschikbaar');
	(isset($teeltinfo[$pid])) ? ($teelt = explode("\n\n", $teeltinfo[$pid])) : ($teelt[0] = 'Geen teeltinformatie beschikbaar');
	(isset($oogstinfo[$pid])) ? ($oogst = explode("\n\n", $oogstinfo[$pid])) : ($oogst[0] = 'Geen informatie over oogsten en bewaren beschikbaar');


	$plantnavcontent = array('algemeen', 'vermeerderen', 'teelt', 'oogst & bewaren', 'culinair');
	$plantnav = array('nav_algemeen', 'nav_vermeerderen', 'nav_teelt', 'nav_oogst', 'nav_culinair');
	$plantnavstring = "";
	foreach ($plantnavcontent as $key => $item) {
		($key == 0) ? ($plantnavstring .= "<li id=\"" . $plantnav[$key] . "\" class=\"selected\">$item</li>") : ($plantnavstring .= "<li id=\"" . $plantnav[$key] . "\">$item</li>");
	}

	$kiemduur_output = $kiemtemp_output = "";
	if (isset($kiemduur[0])) $kiemduur_output .= $kiemduur[0] . "–";
	if (isset($kiemduur[1])) $kiemduur_output .= $kiemduur[1];
	if (isset($kiemtemp[0])) $kiemtemp_output .= $kiemtemp[0] . "–";
	if (isset($kiemtemp[1])) $kiemtemp_output .= $kiemtemp[1];

	$maanden = array('JAN', 'FEB', 'MRT', 'APR', 'MEI', 'JUN', 'JUL', 'AUG', 'SEP', 'OKT', 'NOV', 'DEC');
	$maandenkort = array('J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D');
	$colors = array(array('zaaien', 1, '#22a98b'), array('verspenen', 1, '#95bf33'), array('uitplanten', 2, '#e6512a'), array('poten', 1, '#7d4f29'), array('aanplanten', 1, '#f09125'), array('oogsten', 3, '#2397c5'), array('snoeien', 3, '#652a7f'), array('stekken', 1, '#a11e5c'));

	$rauw = $kook = false;
	if ($voedingswaarde) {
		if (isset($voedingswaarde['rauw'])) {
			$rauw = true;
			$r = $voedingswaarde['rauw'];
		}
		if (isset($voedingswaarde['gekookt'])) {
			$kook = true;
			$g = $voedingswaarde['gekookt'];
		}
		$vw = "<h5>Voedingswaarde (100g)</h4>
			<table>
				<tr>
					<th></th><th>rauw</th><th>gekookt</th>
				</tr>
				<tr>
					<th class=\"stof\">Energie (kCal)</th>";
		if ($rauw) {
			$vw .= "<td>" . $r['kcal'] . "</td>";
		} else {
			$vw .= "<td><span>-</span></td>";
		}
		if ($kook) {
			$vw .= "<td>" . $g['kcal'] . "</td>";
		} else {
			$vw .= "<td>-</td>";
		}
		foreach ($stof as $stofsoort => $stofgroep) {
			if (isset($r[$stofsoort]) OR isset($g[$stofsoort])) {
				$vw .= "</tr>
				<tr><th class=\"stofsoort\" colspan=\"3\">$stofsoort</th>";
			}
			foreach ($stofgroep as $key => $label) {
				if (isset($r[$stofsoort][$key]) OR isset($g[$stofsoort][$key])) {
					$vw .= "</tr>
					<tr><th class=\"stof\">" . $label[0] . "</th>";
					if ($rauw) {
						$vw .= "<td>" . $r[$stofsoort][$key] . "</td>";
					} else {
						$vw .= "<td>-</td>";
					}
					if ($kook) {
						$vw .= "<td>" . $g[$stofsoort][$key] . "</td>";
					} else {
						$vw .= "<td>-</td>";
					}
				}
			}
		}
		$vw .= "</tr>
			</table>";
	}
?>
<html>
<?php require('inc/html_head.php') ?>
	<body class="plants">
<?php require('inc/html_header.php') ?>
		<section id="main">
			<div class="container">
				<article id="main_article">
					<header id="plant">
						<div id="main_photo" class="plant_photo" <?php echo "style=\"background-image: url('images/plantphotos/" . $pid . "_168x224.jpg')\""; ?>></div>
						<h2><?php echo ucfirst($p_name_nl[1][$pid]) ?></h2>
						<h3><?php echo $p_name_lat[$pid][0] ?></h3>
					</header>
					<nav id="plantnav">
						<ul><?php echo $plantnavstring; ?></ul>
					</nav>
					<!-- A L G E M E E N -->
					<section id="algemeen">
						<section class="maininfo">
							<?php echo '
							<p>' . nl2br($descr[0]) . '</p>';
							?>
						</section>
						<section class="sideinfo">
							<?php
								if(isset($p_warning)) {
									$x = 0;
									foreach ($p_warning[$pid] as $key => $warning) {
										if ($x == 0) {
											echo '
								<h5>Let op!</h5>';}
										echo '
								<h6>' . $warning[0] . '</h6>
								<p>' . $warning[1] . '</p>';
										$x++;
									}
								} else {
									echo '
								<h5>Geen waarschuwingen bekend.</h5>';
								}
							?>
						</section>
						<section class="secondinfo">
							<?php foreach ($descr as $key => $textbit) {
								if ($key > 0) {
									echo '
							<p>' . nl2br($textbit) . '</p>
							<!--<div style="-->';
								}
							} ?>
						</section>
					</section>
					<!-- V E R M E E R D E R E N -->
					<section id="vermeerderen"  class="hide">
						<section class="maininfo">
							<?php
								include 'inc/html_teeltschema.php';
								echo '
							<p>' . nl2br($vermeerder[0]) . '</p>';?>
						</section>
						<section class="sideinfo">
							<?php if(isset($kiemduur)) { ?>
							<h6>Zaaien</h6>
							<p>Kiemduur: <?php echo $kiemduur_output; ?> dagen<br/>
							Kiemtemperatuur: <?php echo $kiemtemp_output; ?> °C<br/>
							Zaaidiepte: <?php echo $zaaidiepte; ?> mm
							<?php if ($lichtkiemer) echo "<br/>" . ucfirst($p_name_nl[1][$pid]) . " is een lichtkiemer"; ?></p>
							<?php } ?>
						</section>
						<section class="secondinfo">
							<?php
								foreach ($vermeerder as $key => $textbit) {
									if ($key > 0) {
										echo '
							<p>' . nl2br($textbit) . '</p>';
									}
								}
							?>
						</section>
					</section>
					<!-- T E E L T -->
					<section id="teelt" class="hide">
						<section class="maininfo">
							<?php 
								include 'inc/html_teeltschema.php';
								echo '
							<p>' . nl2br($teelt[0]) . '</p>';?>
						</section>
						<section class="sideinfo">
						</section>
						<section class="secondinfo">
							<?php 
								foreach ($teelt as $key => $textbit) {
									if ($key > 0) {
										echo '
							<p>' . nl2br($textbit) . '</p>';
									}
								}
							?>
						</section>
					</section>
					<!-- O O G S T -->
					<section id="oogst" class="hide">
						<section class="maininfo">
							<?php 
								include 'inc/html_teeltschema.php';
								echo '
							<p>' . nl2br($oogst[0]) . '</p>';?>
						</section>
						<section class="sideinfo">
						</section>
						<section class="secondinfo">
							<?php foreach ($oogst as $key => $textbit) {
								if ($key > 0) {
									echo '
							<p>' . nl2br($textbit) . '</p>';
								}
							} ?>
						</section>
					</section>
					<!-- C U L I N A I R -->
					<section id="culinair" class="hide">
						<section class="maininfo">
						</section>
						<section class="sideinfo">
							<?php if (isset($vw)) {echo $vw;} else {echo "<h5>Geen voedingsinformatie beschikbaar</h5>";} ?>
						</section>
						<section class="secondinfo">
							<ul>
							<?php
								foreach ($receptnamen as $rid => $receptnaam) {
									echo "<li><a href=\"recept.php?pr=" . $_GET['pr'] . "&r=$rid&p=$pid\">$receptnaam</a></li>";
								}
							?>
							</ul>
						</section>
					</section>
				</article>
				<aside class="side">
					<?php foreach ($plantinprofile as $pid => $test) {
						if ($test) echo "
					<a class=\"plantlist\" href=\"plant.php?pr=" . $_GET['pr'] . "&p=" . $pid . "\">
						<section>
							<div class=\"plant_photo\" style=\"background-image: url('images/plantphotos/" . $pid . "_168x224.jpg')\"></div>
							<p>" . ucfirst($p_name_nl[1][$pid]) . "</p>
						</section>
					</a>";
					} ?>
				</aside>
			</div>
		</section>
	</body>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="js/jquery.easydropdown.min.js" type="text/javascript"></script>
	<script type="text/javascript">
	    $(document).ready(function() {
	        $('#profilebar_button').click(function() {
	            $('#profilebar').slideToggle(400);
	            $('#profilebar_button').toggleClass('selected');
	        });

	        $('#menu_button').click(function() {
                $('#menu_smallscreens').slideToggle(400);
            });

	        <?php
	        	$plantnav2 = $plantnav;
	        	foreach ($plantnav as $key => $nav) {
	        		$element = substr($nav, 4);
	        		echo "
	        $('#$nav').click(function() {
	        	$('#$element').removeClass('hide');
	        	$('#$nav').addClass('selected');";
	        		foreach ($plantnav2 as $key2 => $nav2) {
	        			$element2 = substr($nav2, 4);
	        			if ($key != $key2) {
	        				echo "
	        	if ($('#$nav2').hasClass('selected')) {
	        		$('#$element2').addClass('hide');
	        		$('#$nav2').removeClass('selected');
	        	}";
	        			}
	        		}
	        		echo "
	        });";
	        	}
	        ?>
	        
		});	
	</script>
</html>