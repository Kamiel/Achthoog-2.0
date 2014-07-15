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
	include 'inc/search_results.php'; // Get all plants that match the result

	if ($_GET['cat'] == 'plant_name_nl' or $_GET['cat'] == 'plant_name_lat') {
		$menu_selected = 'planten';
		$palette = 'plants';
	}
	if ($_GET['cat'] == 'recipe_name' or $_GET['cat'] == 'ingredient') {
		$menu_selected = 'recepten';
		$palette = 'recipes';
	}


?>
<html>
	<?php require('inc/html_head.php') ?>
	<body <?php echo "class=\"$palette\""; ?>>
		<?php require('inc/html_header.php') ?>
		<section id="main">
			<div class="container">
				<?php
					echo '
					<h6 id="resultaataantal">' . $nr_results . ' planten gevonden voor ‘' . $search_term . '’ in ' . $category . '</h6>';
				?>
				<article id="main_article">
					<?php
						for($type = 1; $type <= $p_types_total; $type++){
							if ($p_type_count[$type] > 0) {
								echo '
									<section class="plant_group">
										<h3>' . ucfirst($p_type_name[$type]) . '</h3>
										<section class="main_plants">';
								
								for ($i = 0; $i < count($p_sort_by_type[$type]); $i++){
									(($i + 1) % 3 != 0) ? ($gutter = '') : ($gutter = ' no_gutter'); //checks if it is the third column. If so, add the class ‘no_gutter’.
									echo '
											<section class="column' . $gutter . ' column_grid">
												<div class="plant_photo" style="background-image: url(\'images/plantphotos/' . $p_sort_by_type[$type][$i+1] . '_168x224.jpg\')"></div>
												<h4>' . ucfirst($p_name_nl[1][$p_sort_by_type[$type][$i+1]]) . '</h4>
												<p class="ellipsis">' . $p_descr[$p_sort_by_type[$type][$i+1]] . ' <a class="meer"></a></p>
											</section>';
								}
								echo '	</section>
									</section>';
							}
						}
					?>
				</article>
				<aside class="side">
				</aside>
			</section>
		</div>


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