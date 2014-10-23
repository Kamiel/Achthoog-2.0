<!DOCTYPE html>
<?php
	spl_autoload_register(function ($class) {
	    include 'inc/' . $class . '.class.php';
	});

	$pid = $_GET['p'];
	$rid = $_GET['r'];
	
	include 'inc/set_spaces.php'; // Set array $spaces
	include 'inc/set_sunhours.php';

	include 'inc/get_profile.php'; // Get profile
	include 'inc/get_plantsinprofile.php'; // Get all plants that fit the profile

	include 'inc/get_planttypes.php'; // Get all plant types
	include 'inc/get_basicplantinfo.php'; // Get most common Dutch name, description, plant type and amount of plants per type*/
	include 'inc/get_allrecipeinfo.php'; // Get all plant info

	$menu_selected = 'recepten';

	$serv = "";
	if (isset($serveert)) {
		if (isset($serveert[0])) $serv .= $serveert[0] . "–";
		$serv .= $serveert[1];
		if (isset($serveert[2])) $serv .= " " . $serveert[2];
	}

	$ingredientenlijst = "";
	$ingr_dropdown = array();
	foreach ($ingredienten as $ingr_id => $ingredient) {
		($ingredient[1] > 1) ? ($meerv = true) : ($meerv = false);
		$ingredientenlijst .= "
								<li";
		if (isset($ingredient[7])) {
			foreach ($ingredient[7] as $ingr_plant_id) {
				if (isset($plantinprofile[$ingr_plant_id]) AND $plantinprofile[$ingr_plant_id] == 1) {
					$ingr_plants[] = $ingr_plant_id;
					if (!in_array($ingr_id, $ingr_dropdown)) $ingr_dropdown[] = $ingr_id;
					if (!isset($first[$ingr_id])) $ingredientenlijst .= " id=\"ingr" . $ingr_id . "_button\" class=\"ingr_dropdown\"";
					$first[$ingr_id] = false;
				}
			}
		}
		$ingredientenlijst .= ">";
		if (isset($ingr_plants)) $ingredientenlijst .= "
									<div><span>▾</span><span class=\"hide\">-</span></div>";
		if (isset($ingredient[5])) $ingredientenlijst .= $ingredient[5] . " ";
		if (isset($ingredient[0])) $ingredientenlijst .= Breuken($ingredient[0]) . "–";
		if (isset($ingredient[1])) $ingredientenlijst .= Breuken($ingredient[1]) . " ";
		if (isset($ingredient[4])) {
			($meerv AND isset($ingredient[4][1])) ? ($ingredientenlijst .= $ingredient[4][1] . " ") : ($ingredientenlijst .= $ingredient[4][0] . " ");
		}
		($meerv AND isset($ingredient[3])) ? ($ingredientenlijst .= $ingredient[3]) : ($ingredientenlijst .= $ingredient[2]);
		if (isset($ingredient[6])) $ingredientenlijst .= " " . $ingredient[6];
		$ingredientenlijst .= "
								</li>";
		if (isset($ingr_plants)) {
			$x = 0;
			foreach ($ingr_plants as $id) {
				if ($x == 0) $ingredientenlijst .= "
								<ul id=\"ingr" . $ingr_id . "\" class=\"test hide\">";
				$ingredientenlijst .= "
									<li>
										<a href=\"plant.php?pr=" . $_GET['pr'] . "&p=$id\">" . $p_name_nl[1][$id] . "</a>
									</li>";
				$x++;
			}
			if ($x > 0) $ingredientenlijst .= "
								</ul>";
		}
		
		unset($ingr_plants);
	}
	
	function Breuken($check){
    	$c = strstr($check, ".");
    	if (!$c == ""){
    		$b = substr($check, 0, strlen($check) - strlen($c));
    		if ($b == "0") $b = "";
	    	switch ($c){
	            case ".5":
	                $b .= "&frac12;";
	                break;
	            case ".33":
	                $b .= "&frac13;";
	                break;
	            case ".75":
	                $b .= "&frac34;";
	                break;
                case ".25":
                    $b .= "&frac14;";
                    break;
	            default:
	        }
	    } else {
	    	$b = $check;
	    }
        return $b;
    }

?>
<html>
<?php require('inc/html_head.php') ?>
	<body class="recipes">
<?php require('inc/html_header.php') ?>
		<section id="main">
			<div class="container">
				<article id="main_article">
					<header id="plant">
						<h2><?php echo ucfirst($receptnamen[$rid]) ?></h2>
					</header>
					<section>
						<section class="maininfo">
							<?php
								if ($serv != "") echo "<p>Voor $serv</p>";
								if (isset($voorbereiding)) echo "<p>" . nl2br($voorbereiding) . "</p>";
							?>
						</section>
						<section class="sideinfo">
							<h5>Ingrediënten</h5>
							<ul id="ingredienten">
								<?php echo $ingredientenlijst; ?>
							</ul>
						</section>
						<section class="secondinfo">
							<?php echo "<p>" . nl2br($bereiding) . "</p>"; if (isset($serveertips)) echo "<p>" . nl2br($serveertips) . "</p>"; ?>
						</section>
					</section>
					
				</article>
				<aside class="side">
					<?php foreach ($plantinprofile as $pid => $test) {
						if ($test) echo "
					<a class=\"plantlist\" href=\"plant.php?pr=" . $_GET['pr'] . "&p=" . $pid . "\">
						<section>
							<div class=\"plant_photo\" style=\"background-image: url('images/plantphotos/" . $pid . "_168x224.jpg')\"></div>
							<p>" . $p_name_nl_cap[1][$pid] . "</p>
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
	      		foreach ($ingr_dropdown as $id) {
	      			echo "
	      	$('#ingr" . $id . "_button').click(function() {
	            $('#ingr$id').slideToggle(200);
	            $('#ingr" . $id . "_button').toggleClass('dropped');
	            $('span', this).toggleClass('hide');
	        });";    
	        	}
	        ?>    
		});	
	</script>
</html>