<?php
    if(!defined('APP-START')) { header('location: /'); }
?>

<script type="text/javascript">

function why_journal_switch(where)
{
	if(where == 'why')
	{
		document.getElementById('intro').style.display = 'none';
		document.getElementById('whyjournal_content').style.display = 'block';
		document.getElementById('whyjournal_div_x').style.display = 'block';
		document.getElementById('whyjournal_img').style.display = 'none';
		document.getElementById('welcome_img').style.display = 'block';
	}

	if(where == "back")
	{
		document.getElementById('whyjournal_content').style.display = 'none';
		document.getElementById('intro').style.display = 'block';
		document.getElementById('whyjournal_div_x').style.display = 'none';
		document.getElementById('whyjournal_img').style.display = 'block';
	}
}

 $(document).ready(function()
 {

    var d = new Date();
    var weekday = new Array(7);
    weekday[0]=  "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";

    var dayOfWeek = weekday[d.getDay()];
	$('#dayOfWeek').html(dayOfWeek);

    var month = new Array(12);
    month[0] = "January";
    month[1] = "February";
    month[2] = "March";
    month[3] = "April";
    month[4] = "May";
    month[5] = "June";
    month[6] = "July";
    month[7] = "August";
    month[8] = "September";
    month[9] = "October";
    month[10] = "November";
    month[11] = "December";
    var monthName = month[d.getMonth()];
    $('#monthName').html(monthName);

    var dayNumber = d.getDate();
	$('#dayNumber').html(dayNumber);
 });

</script>

<div id="whitebackrgba" class="padbottom">

    <div id="writingareawrapper">

		<? if($_SESSION['hint_sent'] == 1) { ?>
		<div id="error_box">
		<h3>notice</h3>
		<p>Your hint has been emailed to you at <?= $_SESSION['login_email']; ?></p>
		</div>
		<? } else if($_SESSION['resendcode'] == 1)
		{ ?>
		<div id="error_box">
		<h3>notice</h3>
		<p>Your activation code has been emailed to you at <?= $_SESSION['login_email']; ?></p>
		</div>
		<? } else if($_SESSION['sudo_delete']  == 'failed') {
		?>
		<div id="error_box">
		<h3>failed</h3>
		<p>Your delete request has failed. Please check your email and try again.</p>
		</div>
		<? } unset($_SESSION['sudo_delete']); ?>

                <!-- grabbing this from a local javascript above -->
    			<!--
                <h3 id="dayOfWeek" class="h3centered" style="margin-top: 35px"></h3>
    			<p style="text-align: center; font-size: 1.0em; padding: 15px 0 15px 0; width: 100%;">This is your page for <span id="monthName"></span> <span id="dayNumber"></span></p>
                -->
                <div style="width: 80%; margin: auto;">
                <h3 style="line-height: 1.8em; width: 100%; text-align: center; font-family: 'Open Sans', sans-serif; font-weight: 400;">Sometimes, kismet happens.<br />This is your page &mdash; explore, write, share.</h3>
                </div>

    			<? insert_snip('login_form.php'); ?>

           <?
                if(isSet($_SESSION['badlogin_msg'])) {
                    if(isSet($_SESSION['badlogin_msg_bckg'])) { $style_override = 'style="background-color: rgba' . $_SESSION['badlogin_msg_bckg'] . '";'; }
                ?>

           <div class="login_help" <?= $style_override ?>>
           <?= $_SESSION['badlogin_msg'] ?>
           </div>

           <? } unset($_SESSION['badlogin_msg']); ?>


		<?
		session_destroy();
		?>

        </div>
</div>

<!--
<div style="width: 90%; margin: auto; margin-top: 10px;">
    <img src="/images/v2-screen1.jpg" style="width: 328px; float: left;" />
    <img src="/images/v2-screen2.jpg" style="width: 328px; float: left;" />
    <img src="/images/v2-screen3.jpg" style="width: 328px; float: left;" />
</div>
-->


<!-- <p style="position: absolute; width: 100%; bottom: 20px; font-size: 1.0em; color: white; text-align: center">&copy; copyright 2014 a Black Tea Press project</p> -->