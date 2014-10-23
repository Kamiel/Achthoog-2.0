<!DOCTYPE html>
<?php
	spl_autoload_register(function ($class) {
	    include 'inc/' . $class . '.class.php';
	});
	include 'inc/set_spaces.php'; // Set array $spaces
	include 'inc/set_sunhours.php';

	include 'inc/get_profile.php'; // Get profile
	include 'inc/get_plantsinprofile.php'; // Get all plants that fit the profile

	include 'inc/get_planttypes.php'; // Get all plant types
	include 'inc/get_basicplantinfo.php'; // Get most common Dutch name, description, plant type and amount of plants per type
	include 'inc/get_sowingdates.php'; // Get information on sowing dates

	$menu_selected = 'planten';
	$css = 'planten.css';

	$chosen_spaces = 0;
	foreach ($spaces as $key => $space) {
		if (array_filter($profile[$space])) {$chosen_spaces++;}
	}

?>
<html>
	<?php require('inc/html_head.php') ?>

	<body class="plants">
		<div id="footerpusher">
			<?php require('inc/html_header.php') ?>
			<section id="main_page">
				<div class="container">
					<article>
						<p id="introduction">Ok, je hebt je ruimtes geselecteerd, maar wat kun je er nou precies mee? Wat voor superheerlijke groenten en fruit passen er op jouw balkonnetje? En wat voor kruiden zijn goed voor in je donkere vensterbank?</p>
						<section>
							<h2>Mijn plantenselectie</h2>
							<form name="showhide" id="showhide" action="create_profile.php" class="profiler" method="post">
							<?php
								if ($chosen_spaces > 1) {
							?>
								<p>Laat alleen planten zien voor:</p><!--
							 --><?php
									foreach ($spaces as $key => $space) {
										if (array_filter($profile[$space])) {
											$checked = $active = '';
											if ((isset($_GET['show']) && substr($_GET['show'], $key, 1) == '1') || !isset($_GET['show'])) {
												$active = ' active';
												$checked = ' checked';
											}
								?><!--
							 --><button type="button" <?php echo "id=\"$space\" class=\"resultbutton$active\"";?>>
									<img <?php echo "src=\"images/icons/profile_" . ($key + 1) . ".svg\"";?>>
								</button><!--
							 --><input type="checkbox" <?php echo "id=\"show$space\" name=\"$space\" class=\"hide\"$checked";?> value="true"><!--
							 --><?php
										}
									}
								}
							?>
								<select id='sort_select' class="dropdown" name='sort' onchange=<?php echo "\"window.location='?pr=" . $_GET['pr'] . "&sort=' + this.value\""; ?>>
								   	<?php
								   		foreach ($sort_options as $key=>$value) {
									   		$sort_selected = '';
									   		if ($key == 0) {
									   			if (!isset($_GET['sort']) || $_GET['sort'] == $value) $sort_selected  = ' selected';
									   		} else {
									   			if (isset($_GET['sort']) && $_GET['sort'] == $value) $sort_selected  = ' selected';
									   		}
									   		echo "
								<option value='$value'$sort_selected>" . $sort_option_labels[$key] . "</option>";
										}
									?>
								</select>
							</form>
							<section id="plant_result">
							<?php
								for($type = 1; $type <= $p_types_total; $type++){
									if ($p_type_count[$type] > 0) {
										echo '
								<section class="plant_category">
									<h3>' . ucfirst($p_type_name[$type]) . '</h3>';
										
										//$x = (count($p_sort_by_type[$type]) > 3) ? (3) : (count($p_sort_by_type[$type]));
										$x = count($p_sort_by_type[$type]);
										for ($i = 0; $i < $x; $i++){
											$pid = $p_sort_by_type[$type][$i+1];
											echo '<!--
								 --><figure class="plant"><!--
									 --><a href="plant.php?pr=' . $_GET['pr'] . '&p=' . $p_sort_by_type[$type][$i+1] . '"><!--
										 --><p>' . $p_zon[$pid] . '</p><!--
										 --><div><img src="images/plantphotos/' . $p_sort_by_type[$type][$i+1] . '_168x224.jpg"></div><!--
										 --><figcaption>' . $p_name_nl_cap[1][$p_sort_by_type[$type][$i+1]] . '</figcaption><!--
									 --></a><!--
								 --></figure>';
										}	
										echo '	</section>';
									}	
								}
							?>
							</section>
						</section>
					</article><!--
				 --><aside>
						<section>
							<h2>Nu zaaien</h2>
							<?php 
								if (!empty($sowplantnow)) {
									foreach ($sowplantnow as $pid) { ?><!--
						 --><a <?php echo 'href="plant.php?pr=' . $_GET['pr'] . '&p=' . $pid . '"'; ?>><!--
						 --><figure><!--
							 --><img class="plant_photo" <?php echo 'src="images/plantphotos/' . $pid . '_168x224.jpg"'; ?>><!--
							 --><figcaption><?php echo $p_name_nl_cap[1][$pid]; ?></figcaption><!--
							 --></figure><!--
						 --></a>
								<?php	}
								} else {
									echo "<p>Er valt helaas niets te zaaien op dit moment</p>";
								}
							?>
						</section>
						<section>
							<h2>Nu oogsten</h2>
							<?php
								if (!empty($harvestnow)) {
									foreach ($harvestnow as $pid) {
										echo "<!--
							 --><a href=\"plant.php?pr=" . $_GET['pr'] . "&p=" . $pid . "\"><!--
								 --><figure><!--
									 --><img class=\"plant_photo\" src=\"images/plantphotos/" . $pid . "_168x224.jpg\"><!--
									 --><figcaption>" . $p_name_nl_cap[1][$pid] . "</figcaption><!--
								 --></figure><!--
							 --></a>";
									}
								}
							?>
						</section>
						<!--<?php
							foreach ($sowplantnow as $s) {
								echo $p_name_nl[1][$s] . "<br>";
							}
						?>-->	
						<section>	
							<h2>Later dit jaar zaaien</h2>
							<?php
								foreach ($sowplantlater as $pid) {
									echo "<!--
							 --><a href=\"plant.php?pr=" . $_GET['pr'] . "&p=" . $pid . "\"><!--
								 --><figure><!--
									 --><img class=\"plant_photo\" src=\"images/plantphotos/" . $pid . "_168x224.jpg\"><!--
									 --><figcaption>" . $p_name_nl_cap[1][$pid] . "</figcaption><!--
								 --></figure><!--
							 --></a>";
								}
							?>
						</section>
					</aside>
				</div>
			</section>
			<?php require('inc/html_footer.php') ?>
		</div>
		


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
                sort = getURLParameter('pr');
                
    			<?php
    				$spaces2 = $spaces;
    				foreach ($spaces as $key => $space) {
						if (array_filter($profile[$space])) {
							echo "
				$('#$space').click(function() {
                    document.getElementById('show$space').checked ^= true;
                    $('#$space').toggleClass('active');";
                    		echo "show = '';";
		    				foreach ($spaces2 as $space2) {
								if (array_filter($profile[$space2])) {
									echo "if (document.getElementById('show$space2').checked) {show += '1';} else {show += '0';}";
								} else {
									echo "show += '0';";
								}
							}
							echo "
                    window.location='?pr=" . $_GET['pr'] . "&sort=' + document.getElementById('sort_select').value + '&show=' + show;
                });";
						}
					}
    			?>
        	});	

        	function getURLParameter(name) {
				return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
			}
        </script>
	</body>
</html>