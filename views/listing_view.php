<?php if(!defined('APP-START')) { echo 'RESTRICTED-PAGE-LOGIN-REQUIRED'; exit; } ?>

<script type='text/javascript'>
$(document).ready(function()
{

	$(":input").focus(function() {
       $(":input").addClass('form-whiteback-rgba');
    });

     $(":input").blur(function() {
       $(":input").removeClass('form-whiteback-rgba');
    });


	//$('#journal_id').selectbox({debug: false});
	//$(this).trigger('change');

	setTimeout(function() { $('#error_box').fadeOut('10000'); }, 3000);
	/* $('#manage_journal').tipsy({fade: true, gravity: 'n'}); */

});
</script>



<div id="error_box_container">
<? if($_SESSION['trash'] == 1) { ?>
<div id="error_box">
<h3>trashed</h3>
<p class="padtop">The entry has been moved to the trash
	<? if($_SESSION['pref_trash'] == 1) { ?>
	and a new entry has been created
	<? } ?>
</p>
<!--
<div id="hr"></div>
<p><a href="/?funcentries"><img src="/images/icon_close_x.png" border="0" /></a></p>
-->
</div>
<? } unset($_SESSION['trash']); ?>
</div>

<?php
    if($data['results_count'] > 1) {
        $make_plural = "s";
    }
?>

<div id="whitebackrgba" class="section_blue section_blue_padding padbottom">

<div id="writingareawrapper">

<div class="listing-stats">
<p style="font-size: 2.0em; padding-bottom: 10px; margin-top: -10px"><?= $data['results_count']; ?> page<?= $make_plural ?>
 totaling <?= number_format($data['total_words']); ?> words</p><p style="font-size: 1.2em; margin-top: 0px; margin-left: 20px">in your <span style="font-weight: 800;"><?= $_SESSION['fk_journal_title'] ?></span> folder | <img src="/images/v2-icon_folder.png" height="14\" /> <a href="/folders">change folder</a></p>
</div>

<!-- <p style="margin-left: 15px; padding-bottom: 20px;">
in your <a id='manage_journal' href="/?funcjournal_detail&m=edit&more=<?= $_SESSION['fk_journal_id'] ?>"><?= substr($_SESSION['fk_journal_title'], 0, 30) ?></a> journal
</p> -->

<div id="new_entry">
<a href="/?func=new_entry"><img src="/images/v2-new_entry.png" style="float: left;" /></a>
<!-- <p style="float: left; margin-top: 12px; margin-left: 17px;">&raquo; <a href="/?funcnew_entry">new entry</a> &raquo; <a href="/?funcjournals">change journal</a> &raquo; <a href="/?funcempty_trash">view trash (<?= $data['total_trashed']; ?>)</a></p> -->
</div>

<p style="float: right; margin-top: 20px;">
        <!--filter by<br />
        <a href="/?func=entries">everything</a> | <a href="/?func=entries&filter=TODAY">today</a> | <a href="/?func=entries&filter=YESTERDAY">yesterday</a> | <a href="/?func=entries&filter=MONTH">last 30 days</a> --><!-- <a href="/?funcentries&filter=YESTERDAY">Trash</a> --></p>

<!-- removed code: 9-24-10.1 / listing_view.php -->

<div id="clear"></div>

<!-- removed code: 02-01-11.1 / listing_view.php -->

<div style="margin-top: 10px;" id="hr"></div>

<!-- removed code: 9-24-10.2 / listing_view.php -->

<div id="clear"></div>

<table id="page_listings" width="100%"  >

<!-- <tr style="color: #FFFFFF; font-size: 10pt;" align="left">
<td width="10"></td>
<td width="250">entry title</td>
<td width="50">words</td>
<td width="100">last updated</td>
</tr> -->


<?= $data['results']; ?>

</table>

</div>

</div>
