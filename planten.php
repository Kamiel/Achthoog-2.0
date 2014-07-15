<!DOCTYPE html>
<?php
	spl_autoload_register(function ($class) {
	    include 'inc/' . $class . '.class.php';
	});
	
	include 'inc/get_sowingdates.php'; // Get information on sowing dates
	
	include 'inc/set_spaces.php'; // Set array $spaces
	include 'inc/set_sunhours.php';

	include 'inc/get_profile.php'; // Get profile
	include 'inc/get_plantsinprofile.php'; // Get all plants that fit the profile

	include 'inc/get_planttypes.php'; // Get all plant types
	include 'inc/get_basicplantinfo.php'; // Get most common Dutch name, description, plant type and amount of plants per type

	$menu_selected = 'planten';

?>
<html>
<?php require('inc/html_head.php') ?>
	<body class="plants">
<?php require('inc/html_header.php') ?>
		<section id="main">
			<div class="container">
				<!--<h6 id="resultaataantal">6 planten gevonden voor ‘sla’</h6>-->
				<article id="main_article">
					<header id="introduction">Ok, je hebt je ruimtes geselecteerd, maar wat kun je er nou precies mee? Wat voor superheerlijke groenten en fruit passen er op jouw balkonnetje? En wat voor kruiden zijn goed voor in je donkere vensterbank?</header>
					<section>
						<h2>Wat kan ik telen?</h2>
						<form name="showhide" id="showhide" action="create_profile.php" class="profiler" method="post">
						<?php
							foreach ($spaces as $key => $space) {
								if (array_filter($profile[$space])) {
									$checked = $active = '';
									if ((isset($_GET['show']) && substr($_GET['show'], $key, 1) == '1') || !isset($_GET['show'])) {
										$active = ' active';
										$checked = ' checked';
									}
									echo "
							<button type=\"button\" id=\"$space\" class=\"resultbutton$active\"><img src=\"images/profile_" . ($key + 1) . ".png\"></button>
							<input type=\"checkbox\" id=\"show$space\" name=\"$space\" value=\"true\" class=\"hide\"$checked/>";
								}
							}
						?>
							<select id='sort_select' name='sort' onchange=<?php echo "\"window.location='?pr=" . $_GET['pr'] . "&sort=' + this.value\""; ?>>
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

						<?php
							for($type = 1; $type <= $p_types_total; $type++){
								if ($p_type_count[$type] > 0) {
									echo '
										<section class="plant_group">
											<h3>' . ucfirst($p_type_name[$type]) . '</h3>
											<section class="main_plants">';
									
									//$x = (count($p_sort_by_type[$type]) > 3) ? (3) : (count($p_sort_by_type[$type]));
									$x = count($p_sort_by_type[$type]);
									for ($i = 0; $i < $x; $i++){
										$pid = $p_sort_by_type[$type][$i+1];
										echo '
												<section class="column">
													<a href="plant.php?pr=' . $_GET['pr'] . '&p=' . $pid . '"><div class="plant_photo" style="background-image: url(\'images/plantphotos/' . $pid . '_168x224.jpg\')"></div></a>
													<h4><a href="' . 'plant.php?pr=' . $_GET['pr'] . '&p=' . $pid . '">' . ucfirst($p_name_nl[1][$pid]) . '</a></h4>
												</section>';
									}
									
									echo '	</section>';
								echo '
										</section>';
								}	
							}
						?>
					</section>
				</article>
				<aside class="side">
					<section id="sow_now">
						<h3>Nu zaaien</h3>
						<?php $temp = array(36, 42, 65, 215);
						foreach ($temp as $pid) {
							echo "
						<a class=\"plantlist\" href=\"plant.php?pr=" . $_GET['pr'] . "&p=" . $pid . "\"><section>
						<div class=\"plant_photo\" style=\"background-image: url('images/plantphotos/" . $pid . "_168x224.jpg')\"></div>
						<p>" . ucfirst($p_name_nl[1][$pid]) . "</p></section></a>";
						}
						?>
					</section>
					<!--<?php
						foreach ($sowplantnow as $s) {
							echo $p_name_nl[1][$s] . "<br>";
						}
					?>-->
					
					<h3>Later dit jaar zaaien</h3>
				</aside>
			</div>
		</section>
		


		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script src="js/jquery.easydropdown.min.js" type="text/javascript"></script>
		<script src="js/jquery.dotdotdot.min.js" type="text/javascript"></script>
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