<!DOCTYPE html>
<?php
	spl_autoload_register(function ($class) {
	    include 'inc/' . $class . '.class.php';
	});

	include 'inc/set_spaces.php'; // Set array $spaces
	include 'inc/set_sunhours.php';
	$css = 'welcome.css';
	
?>
<html>
	<?php require('inc/html_head.php') ?>
	<body>
		<div id="footerpusher">
			<header id="pageheader">
				<section id="topbar">
					<div class="container">
						<p><a href="index.php"><img id="logo" src="images/achthoog_logo.svg" alt="Achthoog"></a></p>
					</div>
				</section>
			</header>
			<section id="main_page">
				<div class="container">
					<p class="payoff">Je eigen groenten en fruit verbouwen? Dat kan zelfs in een flat op acht hoog!</p>
					<div class="order">
						<section id="info">
							<article>	
								<p>Met achthoog.nl heb je alle info binnen handbereik om je eigen groenten en fruit te kweken. Als je invult wat voor ruimte(s) je tot je beschikking hebt, vertelt achthoog.nl je binnen no-time alles over tuinieren in de stad, helemaal toegespitst op jouw locatie.<span class="button_extra_info" onclick="$('.extra_info').removeClass('more'); $(this).addClass('more');">+</span></p>
								<article class="extra_info more">
								<section>
									<h1>Uitgebreide teeltinfo.</h1>
									<p>Per plant staat helder uitgelegd hoe je deze kan zaaien, stekken, planten, etc., welke verzorging het nodig heeft en wanneer en hoe je kan oogsten.</p>
								</section>
								<section>
									<h1>Lekkere recepten</h1>
									<p>Hoe kan je de oogst het beste bewaren? En wat kan je er eigenlijk mee maken? Dat vind je ook allemaal terug op achthoog.nl.</p>
								</section>
							</article>
						</section>
						<section id="rtl-margin">&nbsp;</section>
						<section id="profiler">
							<h1>Ik heb (ruimte voor) een…</h1>
							<ul>
								<?php
								for ($i = 0; $i < count($spaces); $i++) {
									$x = $spaces[$i];
									echo '<li id="' . $x . '">
									<section class="space" onclick="clickSpace(\'' . $x . '\');">
										<img src="images/icons/profile_' . ($i + 1) . '.svg" alt="' . $x . '">
										<p>' . $x . '</p>
									</section>
									<section id="sun_' . $x . '" class="sun hide">
										<p>uren zon:</p>';
										for ($j = 1; $j <= 3; $j++) {
											echo '
										<div id="sunbutton_' . $x . '_zon' . $j . '" class="sunbutton" onclick="clickSun(\'' . $x . '\', \'' . $j . '\');">' . $sunhours[$j-1] . '</div>';
										}
										echo '
									</section>
								</li>
								';
								}?>
								<button id="submit" type="button" disabled="disabled" onClick="document.forms['profiler'].submit();">Aan de slag!</button>
							</ul>
						</section>
					</div>
				</div>
			</section>
			<?php require('inc/html_footer.php') ?>
			<form name="profiler" id="profiler" action="create_profile.php" class="profiler" method="post">
				<?php
					for ($i = 0; $i < count($spaces); $i++) {
						$x = $i + 1;
						echo '<input type="checkbox" id="cb_' . $spaces[$i] . '" name="' . $spaces[$i] . '" value="true" class="hide"/>';
						for ($j = 1; $j <= 3; $j++) {
							echo '
				<input type="checkbox" id="cb_' . $spaces[$i] . '_zon' . $j . '" name="' . $spaces[$i] . 'zon' . $j . '" value="true" class="hide"/>';
						}
					}
				?>
			</form>
		</div>

		

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  		<script type="text/javascript">
        	<?php
        		$go = $ruimtes = '';
        		for ($i = 0; $i < count($spaces); $i++) {
        			if ($i != 0) {
        				$go .= ',';
        				$ruimtes .= ',';
        			}
        			$go .= '0';
        			$ruimtes .= '\'' . $spaces[$i] . '\'';
        		}
        		echo 'go = [' . $go . '];
        	ruimtes = [' . $ruimtes . '];';
        	?>
        	spaceClick = true;

            function clickSpace(ruimte) {
            	if (spaceClick == true) {
	            	var ruimte_nr = $.inArray(ruimte, ruimtes);
	        		var ruimte_id = '#' + ruimte;
	        		var cb_id = '#cb_' + ruimte// ruimtecheckbox
	        		$(cb_id).prop('checked', !$(cb_id).prop('checked')); // verander de stand van de ruimtecheckbox van aan naar uit of vice versa
	        		if ($(cb_id).prop('checked')) { // knop wordt aangezet
	        			go[ruimte_nr]++;
	        			$(ruimte_id).addClass('selected'); // selecteer de ruimteknop
	        			$('#sun_' + ruimte).removeClass('hide'); // toon de opties voor zonne-uren
	        		} else { // knop wordt uitgezet
	    				go[ruimte_nr] = 0;
	    				$(ruimte_id).removeClass('selected'); // deselecteer de ruimteknop
	    				$('#sun_' + ruimte).addClass('hide'); // verberg de opties voor zonne-uren
	    				for (i = 1; i <= 3; i++) { // zet alle zonknoppen ook uit
	    					$('#sunbutton_' + ruimte + '_zon' + i).removeClass('selected'); // deselecteer de zonknop
	    					$(cb_id + '_zon' + i).prop('checked', false); // deselecteer de zoncheckbox
	    				}
	        		}
            		aan_de_slag();
            	}
            	spaceClick = true;
            }

        	function clickSun(ruimte, zon) {
        		ruimte_nr = $.inArray(ruimte, ruimtes);
        		cb_id = '#cb_' + ruimte + '_zon' + zon // zoncheckbox

        		$(cb_id).prop('checked', !$(cb_id).prop('checked')); // verander de stand van de zoncheckbox van aan naar uit of vice versa
        		if ($(cb_id).prop('checked')) { // knop wordt aangezet
        			go[ruimte_nr]++;
        			$('#sunbutton_' + ruimte + '_zon' + zon).addClass('selected'); // selecteer de zonknop
        		} else { // knop wordt uitgezet
        			go[ruimte_nr]--;
        			$('#sunbutton_' + ruimte + '_zon' + zon).removeClass('selected'); // deselecteer de zonknop
        		}
        		aan_de_slag();
        	}

        	function aan_de_slag() {
        		$('button#submit').prop('disabled', true);
        		var verder = true;
        		var check = 0;
        		for (i = 0; i < ruimtes.length; i++) { // doorloop alle ruimtes en check in welke fase ze verkeren
        			if (go[i] == 1) {verder = false;}
        			check = check + go[i];
        		}
        		if (check == 0) {verder = false;}
        		if (verder == true) {$('button#submit').prop('disabled', false);}
        		if (space) {$('#sun' + waarde).toggleClass('hide');}
        	}
        </script>
	</body>
</html>