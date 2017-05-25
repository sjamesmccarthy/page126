<div id="whitebackrgba" class="section_blue section_blue_padding padbottom">
        <div id="writingareawrapper">
<?
if($_GET['activate'] == 'true')
{
?>

<h3>congrats! </h3>
<p class="padtop">Your account has been activated and you are ready to write.</p>
<p class="padtop"><a href="/">login</a></p>

<? } else { ?>

<h3>confirm you are human</h3>
<p class="padtop">Please check your email for the Activation Code we just sent to <span style="font-weight: 700;"> <?= $_SESSION['email'] ?>.</span><br />
Once activated, you can come back, log in and start writing.</p>

<form id="form_reg" action="/" method="get" style="padding-top: 20px;">
<input type="hidden" name="func" value="activate" />

<input type="text" name="code" placeholder="enter the activation code emailed to you"/> <span class="button"><a id="button_login" href="#">Activate Account</a></span> | <a href="/">Not Right Now</a>

</form>

<? } ?>
        </div>
</div>