<?
require_once(DOC_ROOT . '/lib/recaptchalib.php');
$publickey = "6LeHJboSAAAAAAYZ4RusHhJaLbhG_mSfjJ6Nu3PZ"; // you got this from the signup page
$captcha = recaptcha_get_html($publickey);
?>

<script type="text/javascript">
var RecaptchaOptions = {
   theme : 'white'
};
</script>

<script type="text/javascript">
 $(document).ready(function()
 {
 	setTimeout(function() { $('#error_box').fadeOut('10000'); }, 3000);
 	$('#pswd').password_strength();

 	$("#first_name").keyup(function() {

        if( $("#first_name").val() != "" ) {
            $('#label_x_signup_first_name').fadeIn('slow');
        } else {
            $('#label_x_signup_first_name').hide();
        }
    });

    $('#label_x_signup_first_name').click(function() {
         $('#first_name').val("");
          $('#label_x_signup_first_name').fadeOut('slow');
    });

    $("#first_name").blur(function() {
        $('#label_x_signup_first_name').hide();
    });

    $("#email").keyup(function() {
        if( $("#email").val() != "" ) {
            $('#label_x_signup_email').fadeIn('slow');
        } else {
            $('#label_x_signup_email').hide();
        }
    });

    $('#label_x_signup_email').click(function() {
         $('#email').val("");
          $('#label_x_signup_email').fadeOut('slow');
    });

    $("#email").blur(function() {
        $('#label_x_signup_email').hide();
    });

    $("#pswd").keyup(function() {
        if( $("#pswd").val() != "" ) {
            $('#label_x_signup_password').fadeIn('slow');
        } else {
            $('#label_x_signup_password').hide();
        }
    });

    $('#label_x_signup_password').click(function() {
         $('#pswd').val("");
          $('#label_x_signup_password').fadeOut('slow');
    });

    $("#pswd").blur(function() {
        $('#label_x_signup_password').hide();
    });

    /*
    $("#pswd_chk").keyup(function() {
        if( $("#pswd_chk").val() != "" ) {
            $('#label_x_signup_passwordcheck').fadeIn('slow');
        } else {
            $('#label_x_signup_passwordcheck').hide();
        }
    });
    */

    /*
    $('#label_x_signup_passwordcheck').click(function() {
         $('#pswd_chk').val("");
          $('#label_x_signup_passwordcheck').fadeOut('slow');
    });
    */


  /*
  $("#invite_code").keyup(function() {
        if( $("#invite_code").val() != "" ) {
            $('#label_x_signup_invite').fadeIn('slow');
        } else {
            $('#label_x_signup_invite').hide();
        }
    });
*/

    $('#label_x_signup_invite').click(function() {
         $('#invite_code').val("");
          $('#label_x_signup_invite').fadeOut('slow');
    });

    $('#button_create').click(function(e) {

        e.preventDefault();

        if( $('#first_name').val() == "" ) {
            $('#first_name').css('background-color','rgba(210,0,0,.3)');
        }

        else if( $('#email').val() == "" ) {
            $('#email').css('background-color','rgba(210,0,0,.3)');
        }

        else if( $('#pswd').val() == "" ) {
            $('#pswd').css('background-color','rgba(210,0,0,.3)');
        }

        else {
            $('#createact').submit();
        }

    });

    $(":input").focus(function() {
       $(":input").css('background-color','#FFF');
    });

 });
</script>

<div id="whitebackrgba" class="padbottom">

    <div id="writingareawrapper" style="width: 45%">

<script type="text/javascript">
	function check_pswd()
	{
		/*
            if(document.getElementById('pswd').value == document.getElementById('pswd_chk').value)
		{
			//
		} else {
			alert('passwords DO NOT match');
			return false;
		}
        */
	}

	function error_chk()
	{

		var errors = '';

		/*
<? if($_SESSION['email_exists'] == 1) { ?>
			errors += '- email (username) already exists\n';
		<? } ?>
*/

		if(document.getElementById('pswd').value != document.getElementById('pswd_chk').value)
		{
			errors += '- passwords DO NOT match\n';
		}

		if(!document.getElementById('first_name').value)
		{
			errors += '- First Name Missing\n';
		}

		/* if(!document.getElementById('last_name').value)
		{
			errors += '- Last Name Missing\n';
		} */

		if(!document.getElementById('email').value)
		{
			errors += '- Email (username) Missing\n';
		}

		if(!document.getElementById('pswd').value)
		{
			errors += '- Password Missing\n';
		}

		if(!document.getElementById('pswd_chk').value)
		{
			errors += '- Password Again Missing\n';
		}

		/*
if(document.getElementById('secret_phrase').value.length < 29)
		{
			errors += '- Secret Phrase Needs To Be 29 Characters\n';
		}
*/

		/*
if(!document.getElementById('secret_phrase').value)
		{
			errors += '- Secret Phrase Missing\n';
		}
*/

		if(errors != '')
		{
			alert('ERRORS\n' + errors);
			return false;
		} else {
            document.getElementById("createact").submit();
		}
	}

	function textCounter(textarea, countdown, maxlimit)
    {
        textareaid = document.getElementById(textarea);
        if (textareaid.value.length > maxlimit)
          textareaid.value = textareaid.value.substring(0, maxlimit);
        else
          document.getElementById(countdown).value = (maxlimit-textareaid.value.length)+' characters needed';
    }

</script>

<div id="error_box_container">
		<? if($_SESSION['email_exists'] == 1) { ?>
		<div id="error_box" class="error_box-red">
		<h3>error</h3>
		<p>The email you submitted is already taken, please try another.</p>
		</div>
		<? } else if(isSet($_SESSION['login_email']) && $_SESSION['locked'] == 1) { ?>

		<div id="error_box">
		<h3>account locked</h3>
		<p>We found your account, but it appears to be locked. <a href="/?func=contact">Contact us</a> for help.</p>
		</div>

		<h3>locked-out!</h3>
		<p class="padtop"> Have you ever locked yourself out of your house? Neither have we. Your account was locked for, most likely, a security reason. Please <a href="/?func=contact">contact us</a> and we will try to help out.</p>

		<? } else if(isSet($_SESSION['login_email']) && $_SESSION['v_code'] != 0) { ?>

		<div id="error_box">
		<h3>activate your account</h3>
		<p>We found your account, but it appears to be inactive. Please locate the email that we sent you and click the link in it to activate your account.</p>
		</div>
		<? } else if ($_SESSION['login_email']) { ?>

		<div id="error_box">
		<h3>uh-oh!</h3>
		<p>We couldn't match that username + password combination that you submitted.<br /><b>Please create an account, or reset your password if you think that you forgot it.</b></p>
		</div>

		<h3>confused!</h3>
		<p class="padtop">We couldn't match that username + password combination that you submitted so maybe you need an account? or try the options to the right. If they don't work out for you then <a href="/?func=contact">contact us</a> and we will try to help out.</p>

		<? } else if ($_SESSION['invite_bad']) { ?>

		<div id="error_box" class="error_box-red">
		<h3>Bummer</h3>
		<p>The invite you tried has expired.</b></p>
		</div>
		<? }
		else {?>


		<!-- <p class="padtop">Complete the form below and then click the confirmation you will receive in your email and that's it! Piece of cake.</p> -->


		<? } ?>
</div> <!-- /error_box_container -->

        <h3 style="margin-top: 35px; margin-bottom: 35px;">Sign-up</h3>

		<!-- <div id="left_col_create"> -->
		<form id="createact" action="/signup" method="post">
		<input type="hidden" name="new_user" value="<?= time(); ?>" />
        <input type="hidden" name="invite_code" value="forbiddenlove" />

		<label>name:</label>
		<input type="text" id="first_name" name="first_name" value="<?= $_SESSION['first_name'] ?>" placeholder="enter your name" />
		<span id="label_x_signup_first_name"><img class="label_x_signup_first_name" src="/images/v2-icon_x.png" /></span>


		<!-- <p>
		last name:<br />
		<input type="text" id="last_name" name="last_name" size="45" />
		</p> -->

		<label>email (will be used to login):</label>
		<input type="text" id="email" name="email" size="45" value="<?= $_SESSION['email'] ?>" placeholder="enter your email" />
		<span id="label_x_signup_email"><img class="label_x_signup_email" src="/images/v2-icon_x.png" /></span>

		<label>password (twice):</label>
		<input id="pswd" type="password" name="password" size="45" value="<?= $_SESSION['tmp_pswd'] ?>" autocomplete="off" placeholder="enter your password" /><span id="label_x_signup_password"><img class="label_x_signup_password" src="/images/v2-icon_x.png" /></span>
		<!-- <input id="pswd_chk" type="password" name="password_chk" size="45" onblur="check_pswd();" autocomplete="off" placeholder="please retype your password" /><span id="label_x_signup_passwordcheck"><img class="label_x_signup_passwordcheck" src="/images/v2-icon_x.png" /></span> -->

		<!--
<label>invitation code:</label><br />
		<input type="text" id="invite_code" name="invite_code" size="45" placeholder="enter your invitiation code" />
		<span id="label_x_signup_invite"><img class="label_x_signup_invite" src="/images/v2-icon_x.png" /></span>
-->

		<!-- <p>
		password hint:<br />
		<input id="password_hint" type="text" name="password_hint" size="45" />
		</p> -->

		<!--
<p>
		secret key or pass phrase:
		<input type="text" id="secret_phrase" name="secret_phrase" size="45" onKeyDown="textCounter('secret_phrase','wc_register',29);"
      onKeyUp="textCounter('secret_phrase','wc_register',29);"/> <input type="text" id="wc_register" size="30" value="29 characters needed" READONLY /> <p style="font-size: 8pt; margin-top: -30px; margin-left: 40px;">this is used to encrypt your data + your password</p>
		</p>
-->

        <input type="hidden" id="secret_phrase" name="secret_phrase" value="0123456789012345678901234567" />

<!--
		<p>
		<input id="button_create" type="image" src="/images/button_create.png" onmouseover="javascript:image_swap('button_create','1');" onmouseout="javascript:image_swap('button_create',0);"/>

		<a href="/"><img id="button_cancel" type="image" src="/images/button_cancel.png" onmouseover="javascript:image_swap('button_cancel','1');" onmouseout="javascript:image_swap('button_cancel',0);"/></a>
		</p>
-->

        <p style="margin-top: 10px;"><!-- onclick="return error_chk();" -->
            <span class="button"><a id="button_create" href="#">Create Account</a></span> | <a href="/">Cancel</a>
        </p>

        <p style="padding-top: 20px;font-size: .82em;">
		I have read the <a target="_terms" href="/?func=terms">Terms and Conditions of Use</a> and by clicking the "Create Account" button I accept them and acknowledge I am over 13 years of age.
		</p>

		</form>


		<!-- </div> --><!-- left_col -->


		<? if($_SESSION['create_new_account'] == 1)  { ?>
		<!-- <div id="right_col_create"> -->

		<? /* insert_snip('features.php'); */ ?>

		<? } else { ?>

			 <? if($_SESSION['activated'] == 1) { ?>
				<div id="right_col_create">
				<h3 style="padding: 0; color: #A15000;">try again</h3>
				<p style="color: #A15000;" class="padtop">Give it another shot, maybe you left the caps lock key on.</p>

				<? insert_snip('login_form.php'); ?>
				</form>

				<?
		if (isSet($_SESSION['password_hint']) && $_SESSION['activated'] == 1 && $_SESSION['locked'] != 1)
		{
			if($_SESSION['activated'] == 1) { $color = 'A15000'; } else { $color = '666666'; }
		?>

		<h3 style="padding: 0; color: #<?= $color ?>;">hint found</h2>
		<p class="padtop" style="color: #<?= $color ?>;">We did however find <?= $_SESSION['login_email'] ?> and the hint you provided.</p>
		<!--
		<p class="padtop" style="color: #666666; top; margin-top: 20px; padding-left: 10px; padding-bottom: 10px; border-left: 2px solid #666666;"><?= $_SESSION['password_hint'] ?></p>
		-->
		<p class="padtop"><a href="?func=emailhint">Email me my hint</a></p>

				<h3 style="padding: 0; color: #<?= $color ?>;"">reset pswd</h3>
				<p class="padtop" style="color: #<?= $color ?>;">So, you can't remember your password?
				Type your email and we'll send a link to you to reset it.</p>

				<!-- <form id="form_reset" name="form_reset" action="?func=resetpswd" method="post">
				<p style="padding-top:10px;">
				<input type="text" id="email_reset" name="email" size="25" onclick="javascript: document.getElementById('email_reset').value='<?= $_SESSION['login_email'] ?>'" value="" /> <input type="submit" value="reset" />
				</p>
				</form> -->
				<p style="padding-top: 10px">
				<a href="/?func=resetpswd&email_reset=<?= $_SESSION['login_email'] ?>">Click here to send reset link</a>
				</p>
				<p class="padtop" style="font-size: 8pt;">As a security precaution your account will be locked once you click reset.</p>
			<? } ?>

		<? } ?>

		<!-- hint found -->

		<? }
		if(isSet($_SESSION['login_email']) && $_SESSION['activated'] === 0 && !$_SESSION['new_user'] && $_SESSION['locked'] != 1)
		{
			if($_SESSION['activated'] == 1) { $color = '666666'; } else { $color = 'A15000'; }
		?>

		<h3 style="padding: 0; color: #<?= $color ?>;">maybe this</h3>
		<p class="padtop" style="color: #<?= $color ?>;">We did find <?= $_SESSION['login_email'] ?> and that account has not been activated yet. Go find the email we sent you!</p>
		<p class="padtop"><a href="/?func=resendcode">Resend Activation Code</a></p>
		<? } ?>

		<!-- </div> --><!-- right_col -->

</div>
</div><!-- /inside_container -->

<?php

unset($_SESSION['email_exists']);
unset($_SESSION['invite_bad']);
unset($_SESSION['email']);
unset($_SESSION['v_code']);
unset($_SESSION['first_name']);

?>

