<div id="whitebackrgba" class="section_blue section_blue_padding padbottom">

    <div id="writingareawrapper">

<script type="text/javascript">
$(document).ready(function()
{

    $(":input").focus(function() {
       $(":input").addClass('form-whiteback-rgba');
    });

     $(":input").blur(function() {
       $(":input").removeClass('form-whiteback-rgba');
    });

 	setTimeout(function() { $('#error_box').fadeOut('10000'); }, 3000);
 	$('#pswd').password_strength();

    $('#change_type').on('click', function(e) {
         e.preventDefault();

         if($('#password_field').attr('type') == 'password') {
            $('#password_field').attr('type', 'text');
            $('#actionword').html('hide');
         } else {
            $('#password_field').attr('type', 'password');
            $('#actionword').html('show');
         }
    });

});
</script>

<? if($_SESSION['pswd_reset']  == 1) {?>
	<h3>success</h3>
	<p>Your password has been reset and secret key or pass phrase has been re-encrypted.</p>
	<div id="hr"></div>
	<p class="padtop"><a href="/">login</a></p>
<? } else if($_SESSION['reset_error'] == 1) {?>
	<div id="error_box" class="error_box-red">
	<h3>error</h3>
	<p><?= $_SESSION['error_details'] ?></p>
	</div>
<? } else if($_SESSION['reset_error'] == 2) {?>
	<!-- <div id="error_box">
	<h3>Thank you!</h3>
	<p><?= $_SESSION['error_details'] ?></p>
	</div> -->
<? } else if($_GET['unlock'] == 1) {?>
	<h3>unlock account</h3>

	<p class="padtop">Phew, you remembered your password or just clicked the 'reset password' link for the fun of it. Type in the unlock code below that was emailed to you and you're ready to log back in. Sorry for the inconvenience, but we just want to make sure your data stays as safe as possible and why we locked your account &mdash; keeping you safe.</p>

	<form id="form_reg" action="?func=resetpswd" method="post" >
	<input type="hidden" name="unlock" value="1" />

	<p>
	unlock code:<br />
	<input type="text" name="r_code" size="35" value="<?= $_GET['r_code'] ?>" />
	</p>

	<p>
	<input id="button_unlock" type="image" src="/images/button_unlock.png" onmouseover="javascript:image_swap('button_unlock','1');" onmouseout="javascript:image_swap('button_unlock',0);"/>
	</p>

	</form>

<? } ?>


<!-- reorganize this page into two separate forms just so it's cleaner to read -->

<?php
    if(!isSet($_GET['code'])) {
?>

        <?php if($_SESSION['reset_error'] == 2) { ?>

        <h3>your reset email has been sent</h3>
        <p style="padding-top: 10px; margin-left: 10px;"><a href="/">back to homepage</a></p>

        <?php } else { ?>

    	<h3>reset password</h3>

    	<p class="padtop">Enter your email below and we will email a reset-password link to you.
    	</p>

    	<form id="form_reg" action="/begin-password-reset" method="post" >
    	<input type="hidden" id="secret_phrase" name="secret_phrase" value="0123456789012345678901234567" />
    	<input type="hidden" name="sudo" value="1" />

    	<p>
    	<input type="text" name="email" size="35" value="<?= $_SESSION['sent_to'] ?>" placeholder="enter your email address" />
    	</p>

        <p style="padding: 0px;">
        <span class="button"><a id="button_login" href="#">Reset Password</a></span> | <a href="/">Cancel</a>
        </p>

    	</form>

        <?php } ?>

<?php } else { ?>

    <h3>new password</h3>

	<p class="padtop">please type a new password below to complete the reset process.
	</p>

	<form id="form_reg" action="/begin-password-reset" method="post" >
    <input type="hidden" id="secret_phrase" name="secret_phrase" value="0123456789012345678901234567" />
	<input type="hidden" name="sudo" value="3" />
    <input type="hidden" name="code" value="<?= $_GET['code'] ?>" />

    <p>
	<input id="password_field" type="password" name="password" size="35" autocomplete="off" placeholder="enter your new password" style="width: 50%" />
	<a href="#" id="change_type" style="margin-left: 10px;"><span id="actionword">show</span> password</a>
	</p>

    <p style="padding: 0px;">
    <span class="button"><a id="button_login" href="#">Reset Password</a></span>
    </p>

	</form>

<?php } ?>

    </div>
</div>

<?php
unset($_SESSION['error_details']);
unset($_SESSION['reset_error']);
?>
