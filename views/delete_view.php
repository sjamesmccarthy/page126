<div id="whitebackrgba" class="padbottom">

    <div id="writingareawrapper">

<? if($_SESSION['delete_confirm'] == 'true') { ?>

	<? if($_SESSION['sudo_delete']  == 'failed') { ?>
	<div id="error_box">
	<h3>failed</h3>
	<p>Your delete request has failed. Please check your email and try again.</p>
	</div>
	<div id="hr" style="margin-bottom: 10px;"></div>
	<a href="/"><img src="/images/icon_close_x.png" border="0" /></a>
	<? unset($_SESSION['sudo_delete']); } else { ?>

	<h3>okay, here we go</h3>
<img src="/images/delete.png" style="float:left; padding-bottom: 20px; padding-right: 10px; margin-top: 25px;" />
	<p class="padtop">
	Before you click the okay button, we want to make sure you know what is going to happen. ALL OF YOUR DATA WILL BE DELETED. This is not recoverable. Once the button below is clicked, there is no going back, no canceling, no changing your mind. We hope that you at least downloaded a <a href="?func=backup&prov=dl">backup</a> of your notebooks.
	</p>

	<p class="padtop">In the email that we sent to you confirming that you wish to delete your account there is a link to unlock your account right now. Once you click the button below, that option is gone forever.
	</p>

	<? } ?>

	<form id="del_form" action="?func=delete_account" method="post">
	<input type="hidden" name="sudoseeya" value="1" />

	<p class="padtop">
	username: <br />
	<input type="text" name="login_email" size="37" />
	</p>

	<p class="padtop">
	password: <br />
	<input type="password" name="login_password" size="37" />
	</p>

	<p class="padtop">
	delete authorization code: <br />
	<input type="text" name="d_code" size="37" /><br />
	<span style="font-size: 8pt;">This information was emailed to you.</span>
	</p>

	<p class="padtop" style="background: white">
	<input id="button_delact" type="image" src="/images/button_delact.png" onmouseover="javascript:image_swap('button_delact','1');" onmouseout="javascript:image_swap('button_delact',0);" style="vertical-align: middle"/>
	</p>

	</form>

<? } else { ?>

	<h3>whoa! think about this</h3>
	<img src="/images/delete.png" style="float:left; padding-bottom: 20px; padding-right: 10px; margin-top: 45px;" />
	<p class="padtop" style="margin-top: 20px;">
	Before you click the okay button, we want to make sure you know what is going to happen. ALL OF YOUR DATA WILL BE DELETED. This is not recoverable. Once the button below is clicked, there is no going back, no canceling, no redemption, no changing your mind and no control-z (undo).
	</p>

	<ul class="padtop">
	<li>We hope that you at least downloaded a <a href="?func=backup&prov=dl">backup</a> of your journal.</li>
	</ul>

	<form id="del_form" action="?func=delete_account" method="post">
	<input type="hidden" name="sudoseeya" value="1" />
	<!-- <input type="hidden" name="confirm" value="1" /> -->

	<p class="padtop">
	Your password:
	<input type="password" name="login_password" size="27" autocomplete="off" />
	<!-- <input id="button_delact" type="image" src="/images/button_delact.png" onmouseover="javascript:image_swap('button_delact','1');" onmouseout="javascript:image_swap('button_delact',0);" style="vertical-align: middle"/> -->
	</p>

	        <p style="padding-top: 10px;">
            <span class="button"><a id="button_login" href="#">Okay, Delete My Account</a></span> | <a href="/?func=settings">Cancel</a>
            </p>

	</form>

<? } ?>
    </div>
</div>