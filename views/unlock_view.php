<div id="inside_container">

<? if($_SESSION['unlocked'] == 1) { ?>
	
	<h3>account unlocked</h3>
	<p class="padtop">
	Your account is now unlocked.
	</p>
	
<? } else { ?>

<h3>error</h3>
<p class="padtop">We couldn't find anything to unlock.</p>
<? } ?>

<div id="hr"></div>
<p><a href="/">login</a></p>

</div>

<? session_destroy(); ?>