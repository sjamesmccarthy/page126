<div id="whitebackrgba" class="padbottom">

    <div id="writingareawrapper">

<script type="text/javascript">
	function textCounter(textarea, countdown, maxlimit)
    {
        textareaid = document.getElementById(textarea);
        if (textareaid.value.length > maxlimit)
          textareaid.value = textareaid.value.substring(0, maxlimit);
        else
          document.getElementById(countdown).value = (maxlimit-textareaid.value.length)+' characters needed';
    }

</script>

<? if($_SESSION['pswd_reset']  == 1) {?>
	<h3>success</h3>
	<p>Your password has been reset and secret key or pass phrase has been re-encrypted.</p>
	<div id="hr"></div>
	<p class="padtop"><a href="/">login</a></p>
<? } else if($_SESSION['reset_error']) {?>
	<div id="error_box">
	<h3>error</h3>
	<p><?= $_SESSION['error_details'] ?></p>
	<p class="padtop"><a href="?func=resetpswd&again=1">try again</a></p>
	</div>
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

<? } else { ?>
	<h3>reset password code sent</h3>

	<p class="padtop">You're here so we are guessing that you had problems trying to find your original password, so we have emailed a password reset code to you, but if you have remembered it, just click the <b>unlock my account</b> link in the email sent to you and try to <a href="/">log in again</a>.
	</p>

	<div id="hr"></div>

	<form id="form_reg" action="?func=resetpswd" method="post" >
	<input type="hidden" name="sudo" value="1" />

	<p>
	email:<br />
	<input type="text" name="email" size="35" value="<?= $_SESSION['sent_to'] ?>"/>
	</p>

	<p>
	new password:<br />
	<input type="password" name="password" size="35" autocomplete="off" />
	</p>

	<p>
	retype new password:<br />
	<input type="password" name="password_chk" size="35" onblur="check_pswd();" autocomplete="off" />
	</p>

    <input type="hidden" id="secret_phrase" name="secret_phrase" value="0123456789012345678901234567" />

	<!--
<p>
	secret key or pass phrase:<br />
	<span style="font-size: 8pt;">This needs to match your original secret key or pass phrase.</span><br />
	<input type="text" id="secret_phrase" name="secret_phrase" size="35" onKeyDown="textCounter('secret_phrase','wc_register',29);"
      onKeyUp="textCounter('secret_phrase','wc_register',29);"/><br /><input type="text" id="wc_register" size="30" value="29 characters needed" READONLY />
	</p>
-->

	<p>
	reset confirmation code:<br />
	<input type="text" name="r_code" size="35" style="background-color: rgba(0,0,0,0.2);" />
	</p>

	<!--
<p>
	<input type="checkbox" name="okay"> I understand the risk
	</p>
-->

<!--
	<p>
	<input id="button_pswdreset" type="image" src="/images/button_pswdreset.png" onmouseover="javascript:image_swap('button_pswdreset','1');" onmouseout="javascript:image_swap('button_pswdreset',0);"/>
	</p>
-->

        <p style="padding: 0px;">
            <span class="button"><a id="button_login" href="#">Reset Password</a></span> | <a href="/">Cancel</a>
        </p>

	</form>
<? } ?>

    </div>
</div>