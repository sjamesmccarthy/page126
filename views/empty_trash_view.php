<div id="whitebackrgba" class="padbottom">
    <div id="writingareawrapper">

<? if($_SESSION['trashed']  == '1') {?>
	<h3>thank you</h3>
	<p class="padtop">Your trash is now empty. Don't forget to put in another bag.</p>
	<div id="hr"></div>
	<p class="padtop"><a href="?func=entries">pages</a></p>
	</div>
<? } else if($_SESSION['trash_confirm'] == 1) { ?>
	<h3 style="margin-top: 35px;">Empty Trash</h3>
	<p class="padtop">Permanently delete these <?= $data['trash_count'] ?> entries from the trash?</p>
	<ul class="ul-settings" style="padding-bottom: 20px; margin-left: 20px;"><?= $data['trash_items'] ?></ul>
	<form id="empty_trash" action="?func=empty_trash" method="post">
	<input type="hidden" name="takeitout" value="1" />

		<? if($data['trash_count'] > 0) { ?>
		<!--
<input id="button_trash" type="image" src="/images/button_trash.png" onmouseover="javascript:image_swap('button_trash','1');" onmouseout="javascript:image_swap('button_trash',0);"/>
-->

		<!-- <a href="/?func=journals"><img id="button_cancel" type="image" src="/images/button_cancel.png" onmouseover="javascript:image_swap('button_cancel','1');" onmouseout="javascript:image_swap('button_cancel',0);"/></a> -->

		<p style="padding-bottom: 0px;">
            <span class="button"><a id="button_login" href="#">Empty Trash</a></span> | <a href="/?func=journal_detail&m=edit&more=1">Cancel</a>
        </p>

		<? } ?>

	</form>
<? } else {?>
	<h3>logout</h3>
<? } ?>

<!--

<div id="hr"></div>
<p class="padtop"><a href="?func=settings">settings</a> | <a href="?func=entries">pages</a> | <a href="/">back to writing</a></p>
</div>
-->

</div>
</div>

<? unset($_SESSION['trash_confirm']); unset($_SESSION['trashed']); ?>