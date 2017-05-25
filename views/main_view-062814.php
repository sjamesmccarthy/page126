<?
    if(!defined('APP-START')) { echo 'RESTRICTED-PAGE-LOGIN-REQUIRED'; exit; }

   /*
$file_name = DOC_ROOT . "/inspirationalquotes.csv";
   $f_contents = file($file_name);
   $line = $f_contents[rand(0, count($f_contents) - 1)];
   $random_quote = explode(',', $line);
*/
   //print_r($random_quote);
   //exit;

	if(isSet($data['tagTBL']))
	{
		foreach($data['tagTBL'] as $key => $value)
		{
			$tag_cloud 	.= '<li>' . $value . '</li>' . "\n";
			$tag_list 	.= $value . ' ';
			$tags = 1;
		}
	} else {
        //$tag_cloud = '<li>click here to add tags or tag icon above to hide this</li>';
        $tags = '0';
	}

	/*
    $journal_title_L = strlen($_SESSION['fk_journal_title']);


	if($journal_title_L > 17)
	{
		$journal_title_S = substr($_SESSION['fk_journal_title'], 0, 17);
	} else {
		$journal_title_S = $_SESSION['fk_journal_title'];
	}
    */

?>

<!-- FS -->
<style>
:-webkit-full-screen {
  background: #FFF;
}

:-moz-full-screen {
  background: #FFF;
}

:-ms-fullscreen {
  background: #FFF;
}

:full-screen { /*pre-spec */
  background: #FFF;
}

:fullscreen { /* spec */
  background: #FFF;
}
</style>
<script>
// Find the right method, call on correct element
function launchFullscreen(element) {
  if(element.requestFullscreen) {
    element.requestFullscreen();
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
  } else if(element.webkitRequestFullscreen) {
    element.webkitRequestFullscreen();
  } else if(element.msRequestFullscreen) {
    element.msRequestFullscreen();
  }
}

function exitFullscreen() {
  if(document.exitFullscreen) {
    document.exitFullscreen();
  } else if(document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  } else if(document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
  }
}

function dumpFullscreen() {
  console.log("document.fullscreenElement is: ", document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
  console.log("document.fullscreenEnabled is: ", document.fullscreenEnabled || document.mozFullScreenEnabled || document.webkitFullscreenEnabled || document.msFullscreenEnabled);
}

// Events
document.addEventListener("fullscreenchange", function(e) {
  console.log("fullscreenchange event! ", e);
});
document.addEventListener("mozfullscreenchange", function(e) {
  console.log("mozfullscreenchange event! ", e);
});
document.addEventListener("webkitfullscreenchange", function(e) {
  console.log("webkitfullscreenchange event! ", e);
});
document.addEventListener("msfullscreenchange", function(e) {
  console.log("msfullscreenchange event! ", e);
});

// Add different events for fullscreen
</script>

<!-- /FS -->

<script type='text/javascript'>

 $(document).ready(function()
 {

    $('html').css('background-image', 'none');

    setTimeout(function() {
    $('#error_box').fadeOut('10000');
    }, 3000);

    var tagCount = <?= $tags ?>;
   /*
 if(tagCount > 0) {
        $('#entry_tags_list').show();
    } else {
        $('.add_tags_icon').hide();
        $('#add_tags_icon_toolbar').css('opacity','.6');
    }
*/

    /* set font-size prefs */
    $('#entry_form textarea, #entry_form_input').css('font-size', '<?= $_SESSION['pref_font_size'] ?>em');

    $('#tools_writing').show();

    $('#inspirational_quote, #entry_form, #entry_title').click(function() {
        $('#inspirational_quote').fadeOut();
    });

    $('.inspiration_quote_x').click(function() {
        $('#inspirational_quote').fadeOut();
    });

    $('#entry_tags_list, #entry_tags_list.add_tags_icon').click(function() {
        showTagsInput();
        $('#add_tags_icon_toolbar').css('opacity', '1');
    });

    $('#returntotop').click(function() {
        $("html, body").animate({
        scrollTop: 0
        }, 900, function() {
            $('#returntotop').hide();
            $('#entry_tags_list').hide();
        });
    });

    $('#add_tags_icon_toolbar').click(function(e) {

        e.preventDefault();
        var height = $(document).height() - $(window).height();

        /* check its state */
        if( $('#entry_tags_list').is(":visible") ) {
            /* console.log('visibile'); */
            $('#entry_tags_list').fadeToggle();
            //$('#entry_form_tags_input').hide();
            $('#add_tags_icon_toolbar').css('opacity', '.6');
            $('.add_tags_icon').show();
        } else {
            /* console.log('notvisibile / ' + tagCount); */
            $('#add_tags_icon_toolbar').css('opacity', '1');

            if(tagCount > 0) {
                $('#entry_tags_list').fadeToggle();
            } else {
                $('#entry_form_tags_input').fadeToggle();
            }
        }

            $("html, body").animate({
            scrollTop: height
            }, 900, function() {
                if( $(document).height() > 1500 ) {
                    /* console.log($(document).height()); */
                    $('#returntotop').show();
                }
            });

    });

    $('#fullscreen').click(function() {
        $('#inspirational_quote, #entry_tags_list, .add_tags_icon').fadeOut('slow', function() {

            $('#navbar').fadeOut();
            $('.fullscreen_hidden').fadeIn('slow');

        });

    });

    $('.fullscreen_hidden').click(function() {
            $('.fullscreen_hidden').fadeToggle('slow', function() {
                $('#navbar').show();
                $('#inspirational_quote, #entry_tags_list').fadeIn('slow');
            });
    });

	//$('#save_to').selectbox({debug: false});

    $('#entry_form textarea').focus(function() {
        $('#wc').css('color','#B3B3B3');
        $('#entry_form_last_modified').css('color','#B3B3B3');
    });

    $('#entry_form textarea').focusout(function() {
        $('#wc').css('color','#AAA');
        $('#wc').css('color','#E6E6E6');
        $('#entry_form_last_modified').css('color','#B3B3B3');
    });

	$(function() {

		/* Auto-resize textarea - content */
		$('textarea#entry_form_area').autoResize({
		/* options */
		animate:true,
		animateDuration:300
		}).trigger('change');

		/* Auto-resize textarea - tags
		$('textarea#entry_form_tags_input').autoResize({
		animate:true,
		animateDuration:300
		}).trigger('change');
		*/

		// key shortcuts
        $(document).keyup(function(e){

            if( $('#entry_form_tags_input').is(":visible") ) {
                //console.log('is.visible');
                return(false);

            } else {
                //console.log('not.visible');
                if(e.keyCode === 27){
                inFormOrLink = true;
       			$("form#entry_form").trigger('submit');
       			window.location = "/?func=entries";
       			}
            }

        });

		$.ctrl('Z', function() {
			$('#fadewrapper').fadeToggle('slow', function() {});
		});

		$.ctrl('S', function() {
			inFormOrLink = true;
   			$("form#entry_form").trigger('submit');
		});

		$.ctrl('N', function() {
			inFormOrLink = true;
   			window.location = "/?func=new_entry";
		});

		$.ctrl('J', function() {
			inFormOrLink = true;
   			window.location = "/folders";
		});

		$.ctrl('Q', function() {
   			window.location = "/logout";
		});

		$.ctrl('I', function() {
			inFormOrLink = true;
   			window.location = "/pages";
		});


		$.ctrl('F', function() {
		    $('#inspirational_quote, #entry_tags_list').fadeOut('slow', function() {

                $('#navbar').fadeOut();
                $('.fullscreen_hidden').fadeIn('slow');

            });
		});

		$.ctrl('D', function() {
			inFormOrLink = true;
			if(trash_confirm() == true)
			{
   				window.location = "/?func=trash&id=<?= $data['id']; ?>";
   			}
   		});

		/* Slide Writing Area Up or Down */
		$('#writing_area_toggle').click(function() {
			$('#inside_container').slideToggle('slow', function() { });
			//$("#writing_area_toggle").attr("src","images/hide_window_on.png");
			var src = ($('#writing_area_toggle').attr("src") === "images/hide_window.png")
                    ? "images/hide_window_on.png"
                    : "images/hide_window.png";
      		$('#writing_area_toggle').attr("src", src);
	        $('#inside_container_hidden').slideToggle('slow', function() { });
		});

		/* Auto-Save Entire Form */
		$('form#entry_form').autosave();

	});

		/* Auto-Save Entire Form IF textarea is modified */
		/* added timer function to jquery.autosave.js */
		var options = {
		callback:function(){
			if (window.console) { console.log('textarea.changed'); }
			$("form#entry_form").trigger('submit');
			},
		wait:750,            // milliseconds
		highlight:false,     // highlight text on focus
		enterkey:false,      // allow "Enter" to submit data on INPUTs
		}
		$("#entry_form_area").typeWatch( options );

 });

function closensave()
{
	/* Auto-Save Entire Form */
	//console.log('autosaving(<?= $data['id']; ?>)');
	$('form#entry_form').autosave();
	window.location = "/?func=entries";
}

function editDate(mode)
{
	if(mode == 1)
	{
		document.getElementById('editDate').style.position = 'absolute';
		document.getElementById('editDate').style.display = 'inline';
	} else {
		document.getElementById('editDate').style.display = 'none';
	}
}

</script>

<form action="?func=save" method="post" id="entry_form" name="entry_form">
<input type="hidden" name="id" value="<?= $data['id']; ?>" />

<fieldset>
<legend></legend>

<div id="inside_container_hidden" style="display:hidden;"><? insert_snip('loremipsum.php'); ?></div>
<div id="inside_container" style="display:block;">
<?
	// Check for valid login
	if (!$_SESSION['_auth']) exit(LOGIN_ERROR);

	// Populate Field Values
	if(!$data['created'])
	{
		$data['created_short'] = date('m/d/y h:i a');
	} else {
		$data['created_short'] = date('m/d/y h:i a', strtotime($data['created']));
		$data['created_long'] = date('l, F j, Y, h:i a', strtotime($data['created'])); //D
	}

	if(!$data['last_modified'])
	{
		$data['last_modified'] = date('m/d/y h:i a T');
	} else {
		$data['last_modified'] = date('l, F j, Y, h:i a', strtotime($data['last_modified']));
	}

	if(!$data['title'])
	{
		$data['title'] = 'entry title';
	}


	if(!$data['content'])
	{
		$data['content'] = 'click here to capture that moment; click!';
	}

	if(!$data['word_count'])
	{
		$data['word_count'] = '0';
	}

	// Todays Entries
	if($_SESSION['pro'] == 1)
	{
		// display all other entries
		$pro_create_new = "<a id='new_entry' href=\"?func=new_entry\" title=\"Create A New Entry (ctrl+n)\"><img name=\"icon_new\" onmouseover=\"javascript:image_swap('icon_new','1');\" onmouseout=\"javascript:image_swap('icon_new',0);\" src=\"images/icon_new.png\" border=\"0\" /></a>\n";
	} else {
		$todays_entries = "<b>Free accounts are limited to 1 entry per day.</b> <a href=\"/?func=upgrade\"><br />Upgrade today for $1 and create unlimited entries per day.</a>";
	}

	switch($_SESSION['encrypted'])
	{
		case "1":
		$SELECTED_ENC = 'SELECTED';
		$enc_image = 'icon_padlock_on.png';
		break;

		case "0":
		$SELECTED_NOT_ENC = 'SELECTED';
		$enc_image = 'icon_padlock.png';
		break;

		default:
		break;
	}

?>

<script type="text/javascript">

function switch_padlock()
{
	if(document.getElementById('encrypt_toggle').selectedIndex == 0)
	{
		document.getElementById('enc_status').src = '/images/icon_padlock_on.png'
	} else {
		document.getElementById('enc_status').src = '/images/icon_padlock.png'
	}
}

function trash_confirm() {
	var answer = confirm("move this entry to the trash?")
	if (answer){
		//
		return true;
	}
	else{
		return false;
	}
}

function clear_field(field)
{
	if(document.getElementById(field).value == 'entry title')
	{

		document.getElementById(field).value = '';
	}
}

function chk_clear_field(field)
{
	if(document.getElementById(field).value == '')
	{
		document.getElementById(field).value = 'entry title';
	}
}

function clear_textarea(field)
{
	if(document.getElementById(field).value == 'click here to capture that moment; click!')
	{
		document.getElementById(field).value = '';
	}
}

function chk_clear_textarea(field)
{
	if(document.getElementById(field).value == '')
	{
		document.getElementById(field).value = 'click here to capture that moment; click!';
	}
}

function clear_tag_field(field)
{
	if(document.getElementById(field).value == 'type entry tags here')
	{
		document.getElementById(field).value = '';
	} else {
		document.getElementById(field).value = 'type entry tags here';
	}
}


function cnt(w,x){
var y=w.value;
var r = 0;
a=y.replace(/\s/g,' ');
a=a.split(' ');
for (z=0; z<a.length; z++) {if (a[z].length > 0) r++;}
x.value=r;
}

    function showTagsInput()
{
	if($('#entry_form_tags_input').css("display") == 'none')
	{
		$('#entry_form_tags_input').css("display","inline");
		// $('#add_tags_icon').css("display","none");
		$('#entry_tags_list').css("display","none");
		$('#entry_tags_list_instructions').show();
		$('#entry_form_tags_input').focus();
	} else {
		$('#entry_form_tags_input').css("display","none");
	}
}

function hideTagsInput()
{

	//$("form#entry_form").trigger('submit');

	$('#entry_form_tags_input').css("display","none");
	$('#add_tags_icon').css("display","inline");
	$('#entry_tags_list').css("display","inline");
    $('#entry_tags_list_instructions').hide();

	$('#entry_tags_list').html('');

	// delay then read

	setTimeout(function() {$.ajax({
	url: "/lib/ajax/getTags.php",
	cache: false,
	success: function(html){
        $("#entry_tags_list").html(html);

        /*
        $("html, body").animate({
                scrollTop: 0
                }, 900);
        */
	}
	});} , 750); // 1.5 second delay on fetch


	// return php and then do a .innerHTML; hm.
}

function image_swap(name,state) {
	if(state == 1) { var toggle = name + '_on'; }
	else { var toggle = name; }

	document[name].src='images/' + toggle + '.png';
}

</script>

<div id="error_box_container">
<? if($_SESSION['trash'] == 1) { ?>
<div id="error_box">
<h3 style="padding-top: 35px;">trashed</h3>
<p>The entry has been moved to the trash
	<? if($_SESSION['pref_trash'] == 1) { ?>
	and a new entry has been created
	<? } ?>
</p>
<!-- <div id="hr"></div>
<img src="/images/icon_close_x.png" border="0" /> -->
</div>
<? } unset($_SESSION['trash']); ?>

<? if($_SESSION['emailed_entry'] == 1) { ?>
<div id="error_box">
<h3 style="padding-top: 35px;">emailed</h3>
<p>This entry has been emailed to you at <?= $_SESSION['login_email'] ?>
<!-- <div id="hr"></div>
<img src="/images/icon_close_x.png" border="0" /> -->
</div>
<? } unset($_SESSION['emailed_entry']); ?>
</div>

<div id="writingareawrapper-main">
    <div id="whitebackrgba" class="padbottom">

        <img class="fullscreen_hidden" src="/images/v2-toolbar_fullscreen.png" />

        <?php
        if($_SESSION['pref_show_quote'] == 1) {
        ?>

        <div id="inspirational_quote" style="display: block; background-clor: #AAA">

            <p>
            "<?= $data['inspiration']['content']; ?>"
            <span class="inspirational_quote_credit">
            &mdash; inspiration from <?= $data['inspiration']['credit']; ?></span><br />
            </p>
            <!-- <span class="inspiration_quote_x"><img src="/images/v2-icon_x.png" /></span> -->

            <!-- <div style="float: right; width: 75px; height: 24px; background-color: #CCC; color: #FFF; font-size: 1.0em;"><p class="inspirational_quote_close" style="text-align: center">close</p></div> -->
        </div>

        <?php } ?>


<div id="entry_date">
<input id="entry_form_date" type="hidden" onblur="editDate(0);" name="created" size="8" value="<?= $data['created']; ?>" />
<!-- <?= $data['created_short'] ?> -->
<!-- <p style="padding: 0 0 5px 0;">created</p>
<b><a id='last_update_tip' title="Last modified <?= $data['last_modified'] ?> EST - cick to edit" href="javascript:editDate(1);"><?= $data['created_long'] ?></a></b>
<span id="editDate" style="display: none; font-size: 10pt; margin-left: -160px; margin-top: 0px;"><br />edit the date created: <input id="entry_form_date" type="text" onblur="editDate(0);" name="created" size="8" value="<?= $data['created']; ?>" /> (24 hour, not immediately displayed) <img src="/images/icon_close_x30png" margin-top: -20px; height="12" onclick="editDate(0);" style="vertical-align: middle;" /></span> -->
<!-- <div id="entry_form_last_modified">Last modified on <?= $data['last_modified']; ?></div><div id="entry_form_autosaved" style="font-size: 8pt; margin-right: 3px; padding-top: 3px; color: #333333;">
</div> -->
</div>

<div id="entry_title">
<p>
<input id="entry_form_input" tabindex="1" type="text" name="title" value="<?= $data['title']; ?>" onfocus="clear_field('entry_form_input');" onblur="chk_clear_field('entry_form_input');"/>
</p>
</div>

<div id="entry_content">
<p>
<!-- sz(this); -->
<textarea tabindex="2" style="display: block" onkeypress="return resetTimer(event);" onkeyup="cnt(this,document.entry_form.w_count);" id="entry_form_area" name="content" onfocus="clear_textarea('entry_form_area');" onblur="chk_clear_textarea('entry_form_area');" style="width: 100%;">
<?= $data['content']; ?>
</textarea>
</p>
</div>

<div id="entry_status_bar">
<p><input id="wc" tabindex="99" type="text" name='w_count' value="<? print $data['word_count']; ?>" READONLY /> <!-- | <a href="#t">top</a> --></p>
<!-- <p id="last_edit_line">Last modified on <?= $data['last_modified']; ?><div id="entry_form_autosaved" style="font-size: 8pt; margin-right: 3px; padding-top: 3px; color: #333333;"></div></p> -->
<p id="entry_form_last_modified" class="last_edit_line">Edited <?= date("F d g:i a", strtotime($data['last_modified'])); ?> <!-- <?= $_SESSION['timezone'] ?> --></p>
</div> <!-- /entry_status_bar -->

<br style="clear: both" />

    <ul id="entry_tags_list">
    	<?= $tag_cloud ?>
    	<li class="add_tags_icon"><img src="/images/v2-icon_addtag.png" /></li>
    </ul>

<div id="entry_tags_form">
<input id="entry_form_tags_input" type="text" name="tags" value="<?= $tag_list; ?>" size="10" onBlur="hideTagsInput();" style="padding-bottom: 15px;" placeholder="Enter Your Tags" /><br /><span id="entry_tags_list_instructions">tags are space separated</span>
</div>

<div id="returntotop">
    <img src="/images/v2-backtotop.png" />
</div>

</div><!-- /whitebackrgba -->
</div><!-- /writingareawrapper -->

<div onmouseover="document.getElementById('save_icon').src='/images/icon_save_on.png';" onmouseout="document.getElementById('save_icon').src='/images/icon_save.png';" id="entry_save_alert">
</div><!-- /entry_save_alert -->


<!-- everything below this line could be deleted -->
<div id="entry_icons">

	<? if($_SESSION['pro'] == 1) {
			$style_icons_left = 'entry_icons_left_pro';
	?>

	<!-- <div id="new_icon">
	<?= $pro_create_new ?>
	</div> -->

	<div id="autosave_icon">&nbsp;</div>

	<div id="journal_list">
	you are writing in <a id='manage_journal' title="Change Journal You Are Writing" href="?func=journals"><?= $journal_title_S ?></a>
	<input type="hidden" name="journal_id" value="<?= $_SESSION['fk_journal_id'] ?>" />
	<!-- <span style="font-size: 9pt; color: #CCCCCC;">
	<select id="save_to" title="Save Entry To This Journal" name="journal_id">
	<?= $data['journal_list'] ?>
	</select>
	</span> -->
	</div>

	<div id="entry_vertical_bar">
	<img src="/images/vertical_bar.png" />
	</div>

	<? } else {
		$style_icons_left = 'entry_icons_left';
	?>
	<input type="hidden" name="journal_id" value="<?= $_SESSION['fk_journal_id'] ?>" />
	<? } ?>

	<div id="<?= $style_icons_left ?>">
	<div id="<?= $div_id ?>"><p style="color: #000000;" id="<?= $p_id ?>"></p></div>

		<!-- <?= $pro_create_new ?> -->

		<!--
		<a id='save_icon' href='#' title='Save This Entry (ctrl+s)'><input id="autosave_icon" name="icon_save" type="image" onmouseover='this.src="/images/icon_save_on.png"' onmouseout='this.src="/images/icon_save.png"' src="images/icon_save.png" value="Submit" alt="Submit"></a>
		-->

		<select id="encrypt_toggle" name="encrypt_toggle" style="width: 80px"; onChange="switch_padlock();"><option <?= $SELECTED_ENC ?> value="1">encrypted</option><option <?= $SELECTED_NOT_ENC ?> value="0">shared (not encrypted)</option></select><!-- <img id="enc_status" src="/images/<?= $enc_imag30 ?>" margin-top: -20px; height="10" /> -->

		&raquo; <a id='trash' onClick="return trash_confirm();" title="Move Entry To Trash (ctrl+d)" href="?func=trash&id=<?= $data['id']; ?>" style="font-size: 9pt">trash</a>
		&raquo; <a id='email_entry' title="Email Entry To Yourself" href="?func=email&id=<?= $data['id']; ?>" style="font-size: 9pt">email</a>
		<!-- &raquo; <a id='view_entries' title="View Entry List (ctrl+i)" href="?func=entries" style="font-size: 9pt">pages</a> -->
		&raquo; <a target="_new" id='print_entry'href="?func=main&id=<?= $data['id'] ?>&v=print" style="font-size: 9pt">print</a>
		<!-- &raquo; <a target="_print" id='share_entry' title="Print Entry (coming soon!)" href="?func=main&id=<?= $data['id'] ?>&v=print" style="font-size: 9pt">share</a> -->

		<? if($_SESSION['pro'] != 1)
		{ ?>
			<span style="margin-left: 50px; color:#999999;">+ learn how to <a href="?func=upgrade">create more than 1 entry per day</a> - everything is auto-saved</span?
		<?
		}
		?>

		<!-- <a id='view_entries' href="?func=entries" title="View Entry List (ctrl+i)"><img name="icon_list" onmouseover="javascript:image_swap('icon_list','1');" onmouseout="javascript:image_swap('icon_list',0);" src="images/icon_list.png" /></a> -->

		<!-- <a id='add_tags' href="/" onclick="showTagsInput(); return false;" title="Add Tags (ctrl+t)"><img id="icon_tags" name="icon_tags" src="images/icon_tags.png" onmouseover="javascript:image_swap('icon_tags',1);" onmouseout="javascript:image_swap('icon_tags',0);" /></a> -->

		<!-- <a id='email_entry' href="?func=email&id=<?= $data['id']; ?>" title="Email Entry To Yourself"><img name="icon_email" onmouseover="javascript:image_swap('icon_email','1');" onmouseout="javascript:image_swap('icon_email',0);" src="images/icon_email.png" /></a> -->

		<!-- <a id='trash' href="?func=trash&id=<?= $data['id']; ?>" title="Move Entry To Trash (ctrl+d)"><img name="icon_trash" onmouseover="javascript:image_swap('icon_trash','1');" onmouseout="javascript:image_swap('icon_trash',0);" src="images/icon_trash.png" /></a> -->

	</div><!-- /entry_icons_left -->

	<? if($_SESSION['pro'] == 1)
	{ ?>
	<div id="entry_vertical_bar">
	<img src="/images/vertical_bar.png" />
	</div>

	<div id="entry_icons_right">
		<!-- <a id="pink" title="Change Background To Pink" href="javascript:change_color('pink');" ><img name="swatch_pink" onmouseover="javascript:image_swap('swatch_pink','1');" onmouseout="javascript:image_swap('swatch_pink',0);" src="images/swatch_pink.png" border="0" /></a> -->
		<!-- <a id="orange" title="Change Background To Orange" href="javascript:change_color('orange');" ><img name="swatch_orange" onmouseover="javascript:image_swap('swatch_orange','1');" onmouseout="javascript:image_swap('swatch_orange',0);" src="images/swatch_orange.png"border="0" /></a> -->
		<a id="blue" title="Change Background To Blue" href="javascript:change_color('blue','dark');" ><img name="swatch_blue" onmouseover="javascript:image_swap('swatch_blue','1');" onmouseout="javascript:image_swap('swatch_blue',0);" src="images/swatch_blue.png" border="0" /></a>
		<!-- <a id="green" title="Change Background To Green" href="javascript:change_color('green');" ><img name="swatch_green" onmouseover="javascript:image_swap('swatch_green','1');" onmouseout="javascript:image_swap('swatch_green',0);" src="images/swatch_green.png" border="0" /></a> -->
		<a id="white" title="Change Background To White" href="javascript:change_color('white','dark');" ><img name="swatch_white" onmouseover="javascript:image_swap('swatch_white','1');" onmouseout="javascript:image_swap('swatch_white',0);" src="images/swatch_white.png" border="0" /></a>
		<a id="black" title="Change Background To Black" href="javascript:change_color('black','light');" ><img name="swatch_black" onmouseover="javascript:image_swap('swatch_black','1');" onmouseout="javascript:image_swap('swatch_black',0);" src="images/swatch_black.png" border="0" /></a>
		<a id="gray" title="Change Background To Gray" href="javascript:change_color('gray','light');" ><img name="swatch_gray" onmouseover="javascript:image_swap('swatch_gray','1');" onmouseout="javascript:image_swap('swatch_gray',0);" src="images/swatch_gray.png" border="0" /></a>

		<img title="Hide/Show Writing Area (ctrl+h/z)" id="writing_area_toggle" name="hide_window" src="images/hide_window.png"> <!-- onclick="toggle_writing_area();" -->
		<a id="writing_area_close" title="Save & Close" href="#" onclick="closensave();"><img name="icon_close" onmouseover="javascript:image_swap('icon_close','1');" onmouseout="javascript:image_swap('icon_close',0);" src="images/icon_close.png" /></a>
	</div><!-- /entry_icons_right -->
	<? } ?>

<div id="saving_entry"></div>

</fieldset>
</form>

<script type="text/javascript">
	/*
	var ele = document.getElementById('entry_form_tags');

	if(ele.value != '')
	{

		<? if($_SESSION['pro'] == 1) { ?>
		var pro = '_pro';
		<? } else { ?>
		var pro = '';
		<? } ?>

	var myStr = document.getElementById('entry_form_tags').value;
		var lastPos = myStr.length-1;

		if(myStr.charAt(lastPos) == ' ')
		{
			var strLen = myStr.length;
			myStr = myStr.slice(0,strLen-1);
		}

	document.getElementById('tag_badge' + pro).style.display = 'block';
	document.getElementById('tag_badge_count' + pro).innerHTML = myStr.split(' ').length;
	}
	*/
</script>