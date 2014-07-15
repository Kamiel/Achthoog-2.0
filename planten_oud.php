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
						<?php
							for($type = 1; $type <= $p_types_total; $type++){
								if ($p_type_count[$type] > 0) {
									echo '
										<section class="plant_group">
											<h3>' . ucfirst($p_type_name[$type]) . '</h3>
											<section class="main_plants">';
									
									$x = (count($p_sort_by_type[$type]) > 3) ? (3) : (count($p_sort_by_type[$type]));
									for ($i = 0; $i < $x; $i++){
										(($i + 1) % 3 != 0) ? ($gutter = '') : ($gutter = ' no_gutter'); //checks if it is the third column. If so, add the class ‘no_gutter’.
										echo '
												<section class="column' . $gutter . '">
													<div class="plant_photo" style="background-image: url(\'images/plantphotos/' . $p_sort_by_type[$type][$i+1] . '_168x224.jpg\')"></div>
													<h4>' . ucfirst($p_name_nl[1][$p_sort_by_type[$type][$i+1]]) . '</h4>
													<p class="ellipsis">' . $p_descr[$p_sort_by_type[$type][$i+1]] . ' <a class="meer"></a></p>
												</section>';
									}
									
									echo '	</section>';
									if ($p_type_count[$type] > 3) {
										echo '
											<section class="breed">
												<h5>Maar ook:</h5>
												<p>';
										for ($i = 3; $i < count($p_sort_by_type[$type]); $i++) {
											$string = '<a>';
											$string .= ($i == 3) ? (ucfirst($p_name_nl[1][$p_sort_by_type[$type][$i+1]])) : ($p_name_nl[1][$p_sort_by_type[$type][$i+1]]);
											$string .= ($i < (count($p_sort_by_type[$type]) - 1)) ? ('</a>, ') : ('</a>.');
											
											echo $string;
										}
										echo '
												</p>
											</section>
											<div class="clear"></div>';
								}
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
						<section class="plant_sow_now">
							<div class="plant_photo" style="background-image: url('images/plantphotos/1_168x224.jpg')"></div>
							<p><a>Aardappel</a></p>
						</section>
						<section class="plant_sow_now">
							<div class="plant_photo" style="background-image: url('images/plantphotos/2_168x224.jpg')"></div>
							<p><a>Aardbei</a></p>
						</section>
						<section class="plant_sow_now">
							<div class="plant_photo" style="background-image: url('images/plantphotos/7_168x224.jpg')"></div>
							<p><a>Andijvie</a></p>
						</section>
						<section class="plant_sow_now">
							<div class="plant_photo" style="background-image: url('images/plantphotos/211_168x224.jpg')"></div>
							<p><a>Wilde rucola</a></p>
						</section>
						<section class="plant_sow_now">
							<div class="plant_photo" style="background-image: url('images/plantphotos/57_168x224.jpg')"></div>
							<p><a>Hazelnoot</a></p>
						</section>
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
                    $('#profilebar_button').toggleClass('selected')
                });

    			$('.ellipsis').dotdotdot({
    				ellipsis	: '… ',
    				after: "a",
    				watch: true 
    			});
        	});	
        </script>
	</body>
</html>