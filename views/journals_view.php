<script type="text/javascript">
function switchJournal(id, title)
{
	document.cookie = 'tmp_journal_id=' + id + '; expires=Thu, 2 Aug 2020 20:47:11 UTC; path=/';
	document.cookie = 'tmp_journal_title=' + title + '; expires=Thu, 2 Aug 2020 20:47:11 UTC; path=/';
}
</script>

<script type="text/javascript">
 $(document).ready(function()
 {

    $(":input").focus(function() {
       $(":input").addClass('form-whiteback-rgba');
    });

     $(":input").blur(function() {
       $(":input").removeClass('form-whiteback-rgba');
    });


	/* $('#error_box_container').click(function() {
		$('#error_box').slideToggle('slow', function() { });
	}); */
	setTimeout(function() { $('#error_box').fadeOut('10000'); }, 3000);

 });
</script>

<div id="whitebackrgba" class="section_blue section_blue_padding padbottom">

<?
	// Check for valid login
	if (!$_SESSION['_auth']) exit(LOGIN_ERROR);

?>

<? if($_SESSION['sudo_delete_journal'] == 1) { ?>
<div id="error_box">
<h3>uh-oh!</h3>
<p>The password entered could not verified.</p>
<!-- <p style="text-align: right;"><a href="?func=journals"><img src="/images/icon_close_x.png" border="0" /></a></p>
 -->
</div>
<? } unset($_SESSION['sudo_delete_journal']); ?>

<? if($_SESSION['journal_deleted'] == 1) { ?>
<div id="error_box">
<h3>thank you</h3>
<p>Your journal has been permanently deleted. We're sorry if you made a mistake.</p>
<!-- <p style="text-align: right;"><a href="?func=journals"><img src="/images/icon_close_x.png" border="0" /></a></p> -->
</div>
<? } unset($_SESSION['journal_deleted']); ?>

<? if($_SESSION['journal_deleted_cancelled'] == 1) { ?>
<div id="error_box">
<h3>sorry</h3>
<p>You have to at least have one journal.</p>
<!-- <p style="text-align: right;"><a href="?func=journals"><img src="/images/icon_close_x.png" border="0" /></a></p>
 -->
</div>
<? } unset($_SESSION['journal_deleted_cancelled']); ?>

<? if($_SESSION['journal_updated'] == 1) { ?>
<div id="error_box">
<h3>okay!</h3>
<p class="padtop">The journal has been updated</p>
<!-- <div id="hr"></div>
<p><a href="?func=journals"><img src="/images/icon_close_x.png" border="0" /></a></p> -->
</div>
<? } unset($_SESSION['journal_updated']); ?>

<? if($_SESSION['journal_new'] == 1) { ?>
<div id="error_box">
<h3>congrats!</h3>
<p class="padtop">Your new folder has been created.</p>
<!-- <div id="hr"></div>
<p><a href="?func=journals"><img src="/images/icon_close_x.png" border="0" /></a></p> -->
</div>
<? } unset($_SESSION['journal_new']); ?>

<?

/* Get Journal List */
$journal_cnt = 0;
$journal_cnt_pages = 0;

foreach($data['journals_array'] as $key)
{
	if($key['id'] == $_SESSION['pref_default_journal']) { $df_J = " (Default Journal)"; $bgcolor = "666666"; $fav_icon = "_on"; }  else { $df_J = NULL; $bgcolor = "999999"; $fav_icon = NULL;}
	if($key['updated'] == '0000-00-00 00:00:00') { $key['updated'] = 'save_page_to_update'; } else { $key['updated'] = date("m/d/y @ h:i a", strtotime($key['updated'])); }

	$journal_list .= '<tr id="entry_row">';
	$journal_list .= '<td width="1%" align="left" valign="top"><img src="/images/v2-icon_folder.png" height="10\" /></td>';

	$journal_list .= '<td width="39%">';
	$journal_list .= '<a onclick="switchJournal(\'' . $key['id'] . '\', \'' . $key['title'] . '\');" href="/pages">' . $key['title'] .'</a><!-- (' . $key['records_entries'] . ') -->';
	$journal_list .= '</td>';

	$journal_list .= '<td width="20%" valign="top">' . $key['records_entries'] . ' pages</td>';

	$journal_list .= '<td width="20%" valign="top"> + <a href="#"><a onclick="switchJournal(\'' . $key['id'] . '\', \'' . $key['title'] . '\');" href="/?func=empty_trash">' . $key['records_trash'] . ' in trash</a></td>';
    $journal_list .= '<td width="20%" valign="top"><a href="/?func=journal_detail&m=edit&more=' . $key['id'] . '">more details</a></td>';
	//$journal_list .= '<td width="150" valign="top">' . $key['updated'] . '</td>';
	$journal_list .= '</tr>';

	$journal_cnt++;
	$journal_cnt_pages = $journal_cnt_pages + $key['records_entries'];

}

if($journal_cnt > 1) { $makeplural = "s"; }

?>

<div id="writingareawrapper">

<p style="font-size: 2.0em; padding-bottom: 10px;">
<?= $journal_cnt ?> folder<?= $makeplural ?> totaling <?= $journal_cnt_pages ?> pages
</p>

<div id="new_entry">
<a href="/?func=journal_detail&m=new&more=journal"><img src="/images/v2-icon_create_journal.png" style="float: left;" /></a>
<!-- <p style="float: left; margin-top: 12px; margin-left: 17px;">&raquo; <a href="?func=new_entry">new entry</a> &raquo; <a href="?func=journals">change journal</a> &raquo; <a href="?func=empty_trash">view trash (<?= $data['total_trashed']; ?>)</a></p> -->
</div>


<div id="clear"></div>
<div id="hr"></div>

	<div> <!-- journals_list -->
		<table id="page_listings" width="100%" cellpadding="0" cellspacing="0">
		<?= $journal_list; ?>
		</table>
	</div> <!-- /journals_list -->
</div>

</div>
<!-- End Journal List -->