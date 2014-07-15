<?php
	$link = '?pr=' . $_GET['pr'];
	$menu_items = array('planten', 'recepten');
	$menu_code = "";
	foreach ($menu_items as $item) { // doorloop alle menu-items
		$menu_code .= "<li class=\"nav_button\"><a ";
		if ($item == $menu_selected) { // is het het geselecteerde item?
			$menu_code .= "class=\"selected\" ";
		}
		$menu_code .= "href=\"$item.php$link\">$item</a></li>";
	}

	$profile_display = '';
	foreach ($spaces as $key=>$space) {
		if (array_filter($profile[$space])) {
			$profile_display .= '<img class="icon" src="images/profile_' . ($key + 1) . '.png" alt="test">';
			foreach ($sunhours as $x=>$sun) {
				if ($profile[$space][$x] == 1) {
					$profile_display .= '<section class="sun">' . $sun . '</section>';
				}
			}
		}
	}
?>
<header id="pageheader">
	<section id="topbar">
		<div class="container">
			<button type="button" id="menu_button"></button>
			<h1><a href="index.php"><img id="logo" src="images/achthoog_logo.png" alt="Achthoog"></a></h1>
			<button type="button" id="search_button"></button>
			<form id="search" action="zoekresultaten.php" method="get" tabindex="1">
				<div class="left">
					<select name="cat" class="dropdown">
						<option value="plant_name_nl" selected>Plantnaam (Nederlands)</option>
						<option value="plant_name_lat">Plantnaam (Latijn)</option>
						<option value="recipe_name">Receptnaam</option>
						<option value="ingredient">Ingrediënt</option>
					</select>
				</div>
				<input type="text" name="s" placeholder="" onfocus="if(this.value==this.defaultValue){this.value='';}" onblur="if(this.value==''){this.value='plant (* = alle)';}" value="plant (* = alle)" method="post" />
				<input type="submit" value="" method="post"/>
				<input type="hidden" name="pr" <?php echo 'value="' . $_GET['pr'] . '"';?> />
			</form>
			<nav>
				<ul><li id="profilebar_button">profiel</li></li><?php echo $menu_code ?></ul>
			</nav>
		</div>
	</section>
	<section id="profilebar">
		<div class="container">
			
			<p>Huidig:</p>
			<?php echo $profile_display; ?>
			<a href="index.php"><button type="button" id="profile_button">aanpassen</button></a>
		</div>
	</section>
	<section id="menu_smallscreens" class="hide">
		<div class="container">
			<nav>
				<ul><?php echo $menu_code ?></ul>
			</nav>
			<section id="profilebar_small">
				<p>Huidig profiel: </p>
				<?php echo $profile_display; ?>
				<a href="index.php"><button type="button" id="profile_button_small">aanpassen</button></a>
				<div class="clear">&nbsp;</div>
			</section>
		</div>
	</section>
</header>
