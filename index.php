<!DOCTYPE html>
<?php
	spl_autoload_register(function ($class) {
	    include 'inc/' . $class . '.class.php';
	});

	include 'inc/set_spaces.php'; // Set array $spaces
	include 'inc/set_sunhours.php';
	
?>
<html>
	<?php require('inc/html_head.php') ?>
	<body class="welcome">
		<section id="desktop">
			<header id="welcomeheader">
				<div class="container">
					<img id="welcomelogo" src="images/achthoog_logo.png" alt="Achthoog">
					<h1>Zelf je eigen groenten en fruit verbouwen?<br>Dat kan zelfs in je vensterbank op acht hoog!</h1>
					<div class="clear"></div>
					<div id="stap1" class="selected">
						<h2>1</h2>
						<h3>Selecteer over welke ruimte(s) je beschikt.</h3>
					</div>
					<div id="stap2" class="dim">
						<h2>2</h2>
						<h3>Geef het aantal uur zon per dag aan.</h3>
					</div>
					<button id="d_submit" class="submit_profile" type="button" disabled="disabled" onClick="document.forms['profiler'].submit();">Aan de slag!</button>
					<div class="clear"></div>
				</div>
			</header>
			<section>
				<div class="container">
					<?php
						for ($i = 0; $i < count($spaces); $i++) {
							$x = $i + 1;
							echo '
					<section class="profile">
						<section id="sun' . $x . '" class="sunprofile hide">';
							for ($j = 3; $j > 0; $j--) {
								echo '
							<section id="cbimg_' . $x . '-' . $j . '" class="sunprofile_button" onclick="javascript:checkbox(\'' . $x . '-' . $j . '\');">' . $sunhours[$j-1] . '</section>';
							}
							echo '
						</section>
						<section id="cbimg_' . $x . '" class="space" onclick="javascript:checkbox(\'' . $x . '\');">
							<img src="images/profile_' . $x . '.png" alt="test">
							<p>' . $spaces[$i] . '</p>
						</section>
					</section>';
						}
					?>
				</div>		
			</section>
		</section>

		<section id="mobile">
			<section id="topbar">
				<div class="container">
					<h1><a href="index.php"><img id="logo" src="images/achthoog_logo.png" alt="Achthoog"></a></h1>
				</div>
			</section>
			<header id="welcomeheader">
				<div class="container">
					<h2>Zelf je eigen groenten en fruit verbouwen? Dat kan zelfs in je vensterbank op acht hoog!</h2>
					<h3 class="extra_info" onclick="info()">meer info…</h3>
					<div class="clear"></div>
					<section class="extra_info hide">
						<p>Met achthoog.nl heb je alle info binnen handbereik om je eigen groenten en fruit te kweken. Geef aan over wat voor ruimte(s) je beschikt, hoe zonnig deze zijn en zie direct wat je daar allemaal kan laten groeien en wat voor lekkere recepten er van zijn.</p>
						<h4>Uitgebreide teeltinfo.</h4>
						<p>Per plant staat helder uitgelegd hoe je deze kan zaaien, stekken, planten, etc., welke verzorging het nodig heeft en wanneer en hoe je kan oogsten.</p>
						<h4>Lekkere recepten</h4>
						<p>Hoe kan je de oogst het beste bewaren? En wat kan je er eigenlijk mee maken? Dat vind je ook allemaal terug op achthoog.nl.</p>
					</section>
				</div>
			</header>
			<section id="profiler">
				<h3>Ik heb (ruimte voor) een…</h3>
				<?php
					for ($i = 0; $i < count($spaces); $i++) {
						$x = $i + 1;
						echo '
				<section id="cbspace_' . $x . '" class="space" onclick="checkbox(\'' . $x . '\');">
					<div class="container">
						<img src="images/profile_' . $x . '.png" alt="test">
						<p>' . $spaces[$i] . '</p>
						<section id="m_sun_chosen' . $x . '" class="m_sun_chosen hide">';
							for ($j = 1; $j <= 3; $j++) {
								echo '
							<section id="m_cbimg_chosen_' . $x . '-' . $j . '" class="sunbutton_chosen hide">' . $sunhours[$j-1] . '</section>';
							}
							echo '
						</section>
					</div>
				</section>
				<section id="m_sun' . $x . '" class="m_sun hide">
					<div class="container">
						<p>uren zon:</p>';
						for ($j = 1; $j <= 3; $j++) {
							echo '
						<section id="m_cbimg_' . $x . '-' . $j . '" class="sunbutton" onclick="javascript:checkbox(\'' . $x . '-' . $j . '\');">' . $sunhours[$j-1] . '</section>';
						}
						echo '
						<button id="m_ok_' . $x . '" type="button" disabled="disabled" onclick="$(m_sun' . $x . ').addClass(\'hide\'); $(m_sun_chosen' . $x . ').removeClass(\'hide\')">&times;</button>
					</div>
				</section>';
					}
				?>
			</section>
			<section id="submit">
				<div class="container">
					<button id="m_submit" class="submit_profile" type="button" disabled="disabled" onClick="document.forms['profiler'].submit();">Aan de slag!</button>
				</div>
			</section>
			<footer id="pagefooter">
				<div class="container">
					2014 Kamiel van Ingen
				</div>
			</footer>
		</section>

		<form name="profiler" id="profiler" action="create_profile.php" class="profiler" method="post">
			<?php
				for ($i = 0; $i < count($spaces); $i++) {
					$x = $i + 1;
					echo '
			<input type="checkbox" id="cb_' . $x . '" name="' . $spaces[$i] . '" value="true" class="hide"/>';
					for ($j = 1; $j <= 3; $j++) {
						echo '
			<input type="checkbox" id="cb_' . $x . '-' . $j . '" name="' . $spaces[$i] . 'zon' . $j . '" value="true" class="hide"/>';
					}
				}
			?>
		</form>
		

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  		<script type="text/javascript">
            $(document).ready(function() {
            	go = new Array(0,0,0,0,0,0,0,0);
        	});	

        	function info() {
        		$('.extra_info').toggleClass('hide');
        	}

        	function checkbox(waarde) {
        		cbid = 'cb_' + waarde;
        		m_space_picker = '#cbspace_' + waarde;
        		cbimg = '#cbimg_' + waarde;
        		mcbimg = '#m_cbimg_' + waarde;
        		mcbimg_chosen = '#m_cbimg_chosen_' + waarde;
        		m_sun_picker = '#m_sun' + waarde;
        		m_sun_picked = '#m_sun_chosen' + waarde;

        		$('cbimg_1').toggleClass('selected');
        		n = Number(waarde.charAt(0)) - 1;
        		s = n + 1;
        		if (waarde.length == 3) {var space = false;} else {var space = true;}

        		document.getElementById(cbid).checked ^= true;
        		if (document.getElementById(cbid).checked) { // knop wordt aangezet
        			go[n] += 1;
        		} else { // knop wordt uitgezet
        			if (space) { // het is een ruimte-knop
        				go[n] = 0;
        				for (i = 1; i <= 3; i++) { // zet alle zon-knoppen ook uit
        					$(cbimg + '-' + i).removeClass('selected');
        					$(mcbimg + '-' + i).removeClass('selected');
        					document.getElementById(cbid + '-' + i).checked = false;
        					document.getElementById(m_ok).disabled = true;
        				}
        			} else { // het is geen ruimte-knop
        				go[n] -= 1;
        			}
        		}
        		
        		if (!space) {
        			check1 = 'cb_' + s + '-1';
        			check2 = 'cb_' + s + '-2';
        			check3 = 'cb_' + s + '-3';
        			m_ok = 'm_ok_' + s;
        			if (document.getElementById(check1).checked || document.getElementById(check2).checked || document.getElementById(check3).checked) {
        				document.getElementById(m_ok).disabled = false;
        			} else {
        				document.getElementById(m_ok).disabled = true;
        			}
        		}

        		$(m_space_picker).toggleClass('selected');
        		$(cbimg).toggleClass('selected');
        		$(mcbimg).toggleClass('selected');
        		if ($(m_sun_picked).hasClass('hide')) {
        			$(m_sun_picker).toggleClass('hide');
        		} else {
        			$($(m_sun_picked).toggleClass('hide'));
        		}
        		$(mcbimg_chosen).toggleClass('hide');

        		
        		$('button.submit_profile').attr("disabled", true);
        		fase1 = fase2 = fase3 = 0;
        		for (i = 0; i < 8; i++) { // doorloop alle ruimtes en check in welke fase ze verkeren
        			if (go[i] == 0) {fase1 += 1;}
        			if (go[i] == 1) {fase2 += 1;}
        			if (go[i] > 1) {fase3 += 1;}
        		}
        		if (fase1 == 8) {
        			$('#stap1').addClass('selected');
        			$('#stap2').removeClass('selected');
        			$('#stap2').addClass('dim');
        			$('#submitprofile_off').removeClass('hide');
					$('#submitprofile_on').addClass('hide');
        		}
        		if (fase2 > 0) {
        			$('#stap1').removeClass('selected');
        			$('#stap2').addClass('selected');
        			$('#stap2').removeClass('dim');
					$('#submitprofile_off').removeClass('hide');
					$('#submitprofile_on').addClass('hide');
        		}
        		if (fase3 > 0 && fase2 == 0) {
        			$('#stap2').removeClass('selected');
					$('#submitprofile_off').addClass('hide');
					$('#submitprofile_on').removeClass('hide');
					$('button.submit_profile').attr("disabled", false);
        		}
        		if (space) {$('#sun' + waarde).toggleClass('hide');}
        	}
        </script>
	</body>
</html>