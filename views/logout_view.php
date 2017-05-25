
<div id="whitebackrgba" class="section_blue section_blue_padding padbottom">

    <div id="writingareawrapper">

<? if($_SESSION['delete_confirm']  == 'failed') {?>
	<h3>failed</h3>
	<p>Your delete request has failed. Please check your email and try again.</p>
<? } else if($_SESSION['sudoseeya'] == 1) { ?>
	<h3>account deleted</h3>
	<p>Your account and all data associated with your email and user ID has been deleted.</p>

	<!-- <img src="/images/delete.png" style="float:left; padding-bottom: 50px; padding-right: 10px; margin-top: 35px;" /> -->
		<ul class="ul-settings">
		<li> (<?= $_SESSION['c_ENTRY'] ?>) Entries DELETED</li>
		<li> (<?= $_SESSION['c_ENTRY_TRASH'] ?>) Trash Items DELETED</li>
		<li> (1) Notebook DELETED</li>
		<li> (1) User account DELETED</li>
		<li> (<?= $_SESSION['c_ENTRY_NOTES'] ?>) Page notes deleted<li>
		<li> (<?= $_SESSION['c_ENTRY_COMMENTS'] ?>) Page comments deleted</li>
		<li> (<?= $_SESSION['c_SESSION'] ?>) Session variables DELETED</li>
		</ul>

	<p class="padtop">If you didn't mean to do this, we're sorry. There is no way to restore your account. You will need to <a href="?func=create">create a new account</a> and start over.</p>
	<p class="padtop">You are logged out</p>
<? } else {?>
	<h3 style="text-align: center">you are now logged out</h3>
	<p class="padtop" style="padding-bottom: 10px; text-align: center"><!-- <b>You are now logged out.</b><br /> --><!-- <br />Your notebooks are tucked away safe and sound.<br /> -->We'll be redirecting you back to the homepage in just a second.</p>

	<!-- <div id="hr"></div> -->

    <!-- <p style="padding-bottom: 10px;"><a href="/">login</a></p> -->
            <!--
<p style="padding-bottom: 0px; text-align: center;">
            <span class="button"><a href="/">sign in & write</a></span>
        </p>
-->

	<? /* insert_snip('donate.php'); */ ?>

<? } ?>

    </div>

</div>

<?php
    /* removed showlogin cookie */
     setcookie('showlogin', '', time() - 3600); // empty value and old timestamp
?>