<script type="text/javascript">
function switchJournal(id, title)
{
	document.cookie = 'tmp_journal_id=' + id + '; expires=Thu, 2 Aug 2020 20:47:11 UTC; path=/';
	document.cookie = 'tmp_journal_title=' + title + '; expires=Thu, 2 Aug 2020 20:47:11 UTC; path=/';
}

function delete_confirm() {
	var answer = confirm("Delete this folder and everything associated with it?")
	if (answer){
		//
		return true;
	}
	else{
		return false;
	}
}

</script>

<script type="text/javascript">
 $(document).ready(function()
 {

 	$(":input").addClass('form-whiteback-rgba');
 	
 	// $(":input").focus(function() {
  //      $(":input").addClass('form-whiteback-rgba');
  //   });

  //    $(":input").blur(function() {
  //      $(":input").removeClass('form-whiteback-rgba');
  //   });


	setTimeout(function() { $('#error_box').fadeOut('10000'); }, 3000);

    $('#button_update_journal').click(function(e) {
        e.preventDefault();
        $('#journal').submit();
    });

    $('#button_delete_journal').click(function(e) {
        e.preventDefault();
        $('#delete_journal_form').submit();
    });

 	$('#delete_journal_toggle, #delete_journal_toggle_cancel').click(function(e) {
 	        e.preventDefault();
            $('#delete_journal_password').fadeToggle('slow', function() {});
 	});

 });
</script>

<div id="whitebackrgba" class="section_blue section_blue_padding padbottom">

    <div id="writingareawrapper">
<?
	// Check for valid login
	if (!$_SESSION['_auth']) exit(LOGIN_ERROR);

    // print "<pre>";
    // print_r($data);
?>

<?
foreach($data['journals_array'] as $key)
{
	if($key['id'] == $_GET['more'])
	{
		$id = $key['id'];
		$title = $key['title'];
		$description = $key['description'];
		$entries = $key['records_entries'];
		$trashed = $key['records_trash'];
		$total_words = $key['total_words'];
		$last_updated = $key['updated'];
	}

	if($_SESSION['pref_default_journal'] == $id)
	{
		$default_J = '<p class="padtop" style="color: green">This is your default journal, you can change this on your <a href="?func=settings">settings</a> page.</p>';
		$default_J = "Yes";
	} else {
		$default_J = "No";
	}
}

switch($_GET['m'])
{
	case "new":
	$sec_title = "New Folder";
	$f_action = 'new=true';
	$actionword = "Type";
	$hidden = '<input type="hidden" name="new" value="1" />';
	$button_label = 'Create New Folder';
	$button_img = '<input id="button_crjournal" type="image" src="/images/button_crjournal.png" onmouseover="javascript:image_swap(\'button_crjournal\',\'1\');" onmouseout="javascript:image_swap(\'button_crjournal\',0);"/>';
	$cancelordelete = '<a href="/folders">cancel</a>';
	break;

	case "edit":
	$sec_title = "Edit Folder";
	$f_action = 'edit=true';
	$actionword = "Edit";
	$hidden = '<input type="hidden" name="id" value="' . $id . '" />';
	$button_label = 'Update Folder';
	$button_img = '<input id="button_updjournal" type="image" src="/images/button_updjournal.png" onmouseover="javascript:image_swap(\'button_updjournal\',\'1\');" onmouseout="javascript:image_swap(\'button_updjournal\',0);"/>';
	$cancelordelete = '<!-- <a id="delete_journal_toggle" href="#">delete</a> --><a href="/folders">cancel</a>';
	break;

	default:
	_redirect('?func=journals');
}

?>

<? if($_SESSION['sudo_delete_journal'] == 1) { ?>
<div id="error_box">
<h3>uh-oh!</h3>
<p>The password entered could not verified.</p>
<!-- <p style="text-align: right;"><a href="?func=journals"><img src="/images/icon_close_x.png" border="0" /></a></p>
 -->
</div>
<? } unset($_SESSION['sudo_delete_journal']); ?>

<? if($_SESSION['backup_success'] == 1) { ?>
		<meta http-equiv="refresh" content="2;url=http://www.pageonetwentysix.com/download.php?get=<?= $_SESSION['backup_file'] ?>">
		<div id="error_box">
		<h3>backup ok</h3>
		<p class="padtop"><a href="http://www.pageonetwentysix.com/download.php?get=<?= $_SESSION['backup_file'] ?>">Download it now</a>, if your download doesn't automatically start in a few seconds.</p>
		<!-- <div id="hr"></div>
		<img src="/images/icon_close_x.png" border="0" /> -->
		</div>
<? } unset($_SESSION['backup_success']); unset($_SESSION['backup_file']);?>

<div id="journals_left_col" style="width: 48%; float: left;">

<!-- EDIT JOURNAL DETAILS -->
<h3><?= $sec_title ?></h3>
<!-- <p class="padtop"><?= $actionword ?> a title and description.</p> -->

<form id="journal" action="/?func=journals&<?= $f_action ?>" method="POST" />
<?= $hidden ?>

<p class="padtop" style="margin-top: 20px;">
<label>Title</label>
<input type="text" name="journal_title" size="35" value="<?= $title ?>" placeholder="Title Your Notebook" />
</p>

<!-- <p class="padtop">
<label>Description</label>
<textarea name="journal_description" style="width: 100%"><?= $description ?></textarea>
</p> -->

<p style="padding: 20px 0 20px 0;">
<span class="button"><a id="button_update_journal" href="#">Update Folder</a></span> | <?= $cancelordelete ?>
</p>

<!-- <?= $button_img ?> -->

<!-- <a href="/?func=journals"><img id="button_cancel" type="image" src="/images/button_cancel.png" onmouseover="javascript:image_swap('button_cancel','1');" onmouseout="javascript:image_swap('button_cancel',0);"/></a> -->
</p>
</div>

<div id="journals_right_col" style="margin-top: 100px; width: 45%; float: right; padding-left: 20px;">

<!-- may use a table here instead -->
<!-- if new; put No Statistics Available -->

<table cellpadding="3" cellspacing="3">
<tr>
<td width="200">
<a onclick="switchJournal('<?= $id ?>', '<?= $title ?>');"href="/pages">Pages</a>
	<ul>
	<?
	/*
	global $months;
	foreach ($months as $key => $value)
	{
		print '<li>' . $value . '</li>';
	}
	*/
	?>
	</ul>
</td>
<td width="100"><a href="/?func=entries"><?= number_format($entries); ?></a></td>
</tr>

<tr>
<td width="200"><a onclick="switchJournal('<?= $id ?>','<?= $title ?>');" href="?func=empty_trash">Trash</a></td>
<td width="100"><a href="/?func=empty_trash"><?= number_format($trashed); ?></a></td>
</tr>

<!--
<tr>
<td width="200"><span style="text-decoration: line-through; color: #8A9DAF;">Shared Entries</span></td>
<td width="100"><span style="text-decoration: line-through; color: #8A9DAF;">N/A</span></td>
</tr>
-->

<tr>
<td width="200">Total Words Written</td>
<td width="100"><?= number_format($total_words); ?></td>
</tr>

<!--
<tr>
<td width="200">is_default notebook (ID: <?= $_SESSION['fk_journal_id'] ?>)</td>
<td width="100"><a href="/?func=settings"><?= $default_J ?></a></td>
</tr>
-->

</table>

<p style="margin-left: 6px; margin-top: 10px;">
<? if($last_updated == "0000-00-00 00:00:00") { print "This folder hasn't been updated yet"; } else { print "Last Updated on "; print date("m/d/y @ h:i a", strtotime($last_updated)); } ?>
</p>

<? if($_GET['m'] != "new") { ?>
<p style="margin-left: 6px; margin-top: 10px; padding-bottom: 20px;">
<a href="?func=backup&j=<?= $id ?>&z=<?= time(); ?>">Backup this folder as CSV file</a>
</p>

<div id="hr"></div>

<!-- <p style="margin-left: 6px; margin-top: 10px;">
<a href="/folders">Manage Folders</a>
</p> -->

<p style="margin-left: 6px; margin-top: 10px;">
<a id="delete_journal_toggle" href="#">Delete this notebook</a>
</p>

<? } ?>

</form>
</div>
<br style="clear: both;" />

<div id="delete_journal_password">
	<form id="delete_journal_form" action="/?func=delete_journal" method="post">
	<input type="hidden" name="sudoseeyajournal" value="1" />
	<input type="hidden" name="j" value="<?= $id ?>" />

	Your password:
	<input type="password" name="login_password" size="27" autocomplete="off"/>

	<p style="padding: 10px 0 20px 0;">
        <span class="button button-red"><a id="button_delete_journal" href="#">Delete Folder</a></span> | <a id="delete_journal_toggle_cancel" href="#">cancel</a>
    </p>

	<!-- <input id="button_deljournal" type="image" src="/images/button_deljournal.png" onmouseover="javascript:image_swap('button_deljournal','1');" onmouseout="javascript:image_swap('button_deljournal',0);" style="vertical-align: middle" /> -->

	</form>
</div>

</div>

<!-- javawscript confirm only -->
<!-- <?= $default_J ?> -->