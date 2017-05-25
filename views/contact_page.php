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

<div id="whitebackrgba" class="padbottom">
<?
	if($_SESSION['login_email']) $_SESSION['email'] = $_SESSION['login_email'];
	if($_SESSION['first_name']) $_SESSION['name'] = $_SESSION['first_name'];
?>

<? if($_SESSION['mail_sent'] == 1) { ?>
<h3>message sent</h3>
<p class="padtop">Thanks for your message. We try to get back to all of our mail in a timely manner, but if you sent it over a weekend or late at night, please be patient and give us a little time to respond.</p>

<div id="hr"></div>

	<? if($_SESSION['login_email']) { ?>
	<p class="padtop"><a href="?func=settings">settings</a> | <a href="/">back to writing</a></p>
	<? } else { ?>
	<p class="padtop"><a href="/">login</a></p>
	<? } ?>

<? } else { ?>

	<? if($_SESSION['error'] == 1) { ?>
	<div id="error_box">
	<h3>error</h3>
	<p class="padtop"><?= $_SESSION['error_msg'] ?></p>
	</div>
	<? }
		unset($_SESSION['error']);
		unset($_SESSION['error_msg']);
	?>

<h3>direct message us</h3>
<p class="padtop">If you have any questions, or ideas to improve this project please drop us a note. And, just so you know, this really doesn't send your message to <a target="_new" href="http://twitter.com/page126">Twitter</a> &mdash; follow us though.</p>

<form id="dm" style="padding-top: 20px;" action="?func=send_mail" name="contact_form" id="contact_form" method="post">
<input type="hidden" name="subject" value="QUESTION for PAGE126 FAQ" />
<input type="hidden" name="no_captcha" value="0" />

<div id="username_contact" style="width: 300px">
<p class="padtop">
Name:<br />
<input type="text" name="name" size="30" value="<?= $_SESSION['name'] ?>" style="width: 300px"/>
</p>
</div>

<div id="password_contact">
<p class="padtop">
Email:<br />
<input type="text" name="email" size="30" value="<?= $_SESSION['email'] ?>" style="width: 300px"/>
</p>
</div>

<div style="clear: both;"></div>

<p class="padtop">
Message:<br />
<textarea name="message" id="message" cols="99" rows="10"><?= $_SESSION['msg'] ?></textarea>
</p>

<p class="padtop">
<?= $captcha ?>
</p>
<div><!-- empty div for captcha -->

<p class="padtopextra">
<input id="button_dm" type="image" src="/images/button_dm.png" onmouseover="javascript:image_swap('button_dm','1');" onmouseout="javascript:image_swap('button_dm',0);"/>

<a href="/"><img id="button_cancel" type="image" src="/images/button_cancel.png" onmouseover="javascript:image_swap('button_cancel','1');" onmouseout="javascript:image_swap('button_cancel',0);"/></a>
</p>

</div>
</form>

<? } unset($_SESSION['mail_sent']); ?>