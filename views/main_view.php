<?
    if(!defined('APP-START')) { echo 'RESTRICTED-PAGE-LOGIN-REQUIRED'; exit; }

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

    $("#notearea").focus(function() {
       $("#notearea").addClass('form-whiteback-rgba');
    });

     $("#notearea").blur(function() {
       $("#notearea").removeClass('form-whiteback-rgba');
    });

    $('html').css('background-image', 'none');

    if( $('body').attr('id') == "main") {
        $('#navbar').addClass('navbar_writing_mode');
    } else {
        $('#navbar').removeClass('navbar_writing_mode');
    }
 
    setTimeout(function() {
    $('#error_box').fadeOut('10000');
    }, 3000);

    var tagCount = <?= $tags ?>;

    $("#notes_icon_toolbar, #close_notes_link").on('click', function() {
        $('#notes').toggle();
    });

    $(".export_navicon").on('click', function(e) {
        e.preventDefault();
        $(".export_subnav").toggle();
        $(".tipper").hide();
    });

    $('.export_subnav').on('click', function(e) {
        e.preventDefault();
        $(".export_subnav").toggle();
    });

    $('.btn-markdown, #close-md-preview').on('click', function(e) {
        e.preventDefault();
        $("#entry_form_area").toggle();
        $("#entry_content_md_preview").toggle();
        //$("#entry_title").toggle();
        $("#entry_status_bar").toggle();
        $('#close-md-preview').toggle();
        $('.btn-markdown-on').fadeToggle();
        $('html, body').animate({
            scrollTop: 0
        }, 800);
    });

    $('.btn-fullscreen, .fullscreen_hidden').on("click", function(e) {
       
        $('#navbar').fadeToggle("slow", function () {

            $('.fullscreen_hidden').fadeToggle("slow");

            if ($("#navbar").is(":visible"))
                $("#writingareawrapper-main").animate({'margin-top': '0'}, 300);
            else
                $("#writingareawrapper-main").animate({'margin-top': '-100px'}, 300);

        });


        
        
        

    });

$(document).on("click", ".notes_ui li .notetext", function(e){

    //$('.notes_ui li .notetext').click(function(e) {
        //alert( $(this).text() );
        var ele = $(this).attr('id');
        //alert (ele);
        //alert ($('#note_' + ele).text());
        e.preventDefault();
        $("#entry_form_area").insertAtCaret( $('#note_' + ele).text() );
    });

$('#button_addnote').on('click', function(e) {

        if( $('#notearea').val() != '') {

            var formData = $("#addnote").serializeArray();
                var URL = $("#addnote").attr("action");

            $.post(URL,
                formData,
                function(data, textStatus, jqXHR)
                {

                    /* alert('success'); */
                    $.get( "/lib/ajax/getNotes.php", function( data ) {
                        $( "#notes-ajax-area" ).html( data );
                    });

                     /* update the count number */
                    var count = $('#navbariconlist .navitem .notification_notes').text();
                    var count_new = parseInt(count)+1;
                    $('.notification_notes').show();
                    $('#navbariconlist .navitem .notification_notes').text(count_new);

                })
                .done(function() {
                    /* alert("done"); */
                    $('#notearea').val("");
                })
                .fail(function() {
                    /* alert('failed'); */
                });

        } else {
            alert('no-submit.button_addnote(form)');
        }

         e.preventDefault();
});

$(document).on("click", ".deleteitems .deletethis", function(e){

            // var id = $(this).attr("id");
            var id = this.getAttribute("id");
            var formData = '';
            //var URL = $(this).attr("href");
            var URL = this.getAttribute("href");

            // alert(URL);

            $.post(URL,
                formData,
                function(e, data, textStatus, jqXHR)
                {
                    /* alert("success-del"); */
                    $.get( "/lib/ajax/getNotes.php", function( data ) {
                        $( "#notes-ajax-area" ).html( data );
                    });

                    /* update the count number */
                    var count = $('#navbariconlist .navitem .notification_notes').text();
                    var count_new = parseInt(count)-1;
                    if(count_new == 0) {
                        $('.notification_notes').hide();
                    }
                    $('#navbariconlist .navitem .notification_notes').text(count_new);

                })
                .done(function(e) {
                    /* alert("done-del"); */
                })
                .fail(function() {
                    /* alert('failed-del'); */
                });

            e.preventDefault();
            e.stopPropagation();
});


   /*
     if(tagCount > 0) {
            $('#entry_tags_list').show();
        } else {
            $('.add_tags_icon').hide();
            $('#add_tags_icon_toolbar').css('opacity','.6');
        }
    */

    /* set font-size prefs */
    var fontTitleSize = (<?= $_SESSION['pref_font_size'] ?> + .2);
    $('#entry_form_input').css('font-size', fontTitleSize + 'em');
    $('#entry_form textarea').css('font-size', <?= $_SESSION['pref_font_size'] ?> + 'em');

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

    // $('#fullscreen').click(function() {
    //     $('#inspirational_quote, #entry_tags_list, .add_tags_icon').fadeOut('slow', function() {

    //         $('#navbar').fadeOut();
    //         $('.fullscreen_hidden').fadeIn('slow');

    //     });

    // });

    // $('.fullscreen_hidden').click(function() {
    //         $('.fullscreen_hidden').fadeToggle('slow', function() {
    //             $('#navbar').fadeToggle();
    //             //$('#inspirational_quote').fadeIn('slow');
    //         });
    // });

    // $('#entry_form textarea').focus(function() {
    //     $('#wc').css('color','#B3B3B3');
    //     $('#entry_form_last_modified').css('color','#B3B3B3');
    // });

    // $('#entry_form textarea').focusout(function() {
    //     $('#wc').css('color','#AAA');
    //     $('#wc').css('color','#E6E6E6');
    //     $('#entry_form_last_modified').css('color','#B3B3B3');
    // });

	$(function() {

		/* Auto-resize textarea - content */
		$('textarea#entry_form_area').autoResize({
    		animate:true,
    		animateDuration:300
		}).trigger('change');

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

        <div id="notes">

            <h3 style="float: left;">Quick Notes</h3>

            <p style="float: right; margin-top: 10px;"><a href="#" id="close_notes_link"><img src="/images/v2-icon_x.png" /></a></p>

            <form id="addnote" name="addnote" action="/?func=addnote" method="POST">
            <input type="hidden" name="id" value="<?= $data['id']; ?>" />
            <input type="hidden" name="journal_id" value="<?= $_SESSION['fk_journal_id'] ?>" />

                <textarea id="notearea" name="notearea"></textarea>

                <p style="text-align: left;">
                     <span class="button"><a id="button_addnote" href="#">Add Note</a></span>
                </p>

            </form>

            <div id="notes-ajax-area">
            <ul class="notes_ui">

            <?php
                $i=0;
                while($i <= count($data['notesTBL']) -1) {

                    print '<li id="deletenotenode_' . $data['notesTBL'][$i]['id'] . '" class="deleteitems">';
                    print '<p class="notetextcopy" id="note_' . $data['notesTBL'][$i]['id'] . '">' . $data['notesTBL'][$i]['notes'] . '</p>';

                    print '<p>';

                    print '<a class="notetext" data-title="paste note into page" id="' . $data['notesTBL'][$i]['id'] . '" href="#">';
                    print '<img class="deletenote" style="width: 13px" src="/images/v2-icon_pasteinto.png" />';
                    print '</a><span class="notes_tools">paste</span>';

                    print '<a class="deletethis" data-title="delete note" id="deletelink_' . $data['notesTBL'][$i]['id'] . '" href="/?func=deletenote&i=' . $data['notesTBL'][$i]['id'] . '">';
                    print '<img style="width: 13px" class="deletenote" src="/images/v2-icon_deletenote.png" />';
                    print '</a><span class="notes_tools">delete</span>';

                    print '</p>';
                    print '<br style="clear: both" />';
                    print '</li>';

                $i++;
                }
            ?>

            </ul>
            </div>

        </div>

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

        </div>

        <?php } ?>


<form action="/?func=save" method="post" id="entry_form" name="entry_form">
<input type="hidden" name="id" value="<?= $data['id']; ?>" />
<input type="hidden" name="journal_id" value="<?= $_SESSION['fk_journal_id'] ?>" />

<div id="entry_date">
<input id="entry_form_date" type="hidden" onblur="editDate(0);" name="created" size="8" value="<?= $data['created']; ?>" />
</div>

<div id="entry_title">
<p>
<input id="entry_form_input" tabindex="1" type="text" name="title" value="<?= $data['title']; ?>" onfocus="clear_field('entry_form_input');" onblur="chk_clear_field('entry_form_input');"/>
</p>
</div>

<div id="entry_content">

<div class="mdhtmlform-html" id="entry_content_md_preview">
</div>

<p>
<!-- sz(this); -->
<textarea class="mdhtmlform-md" tabindex="2" style="display: block" onkeypress="return resetTimer(event);" onkeyup="cnt(this,document.entry_form.w_count);" id="entry_form_area" name="content" onfocus="clear_textarea('entry_form_area');" onblur="chk_clear_textarea('entry_form_area');" style="width: 100%;">
<?= $data['content']; ?>
</textarea>
</p>
</div>

<div id="entry_status_bar">
<p style="text-align: center"><input id="wc" tabindex="99" type="text" name='w_count' value="<? print $data['word_count']; ?>" READONLY /></p>
<p style="color: #000; text-align: center; margin-top: 5px;">about <? print round( ($data['word_count'] / 250), 2 ); ?> pages</p>
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

<div id="close-md-preview">
    <p>CLOSE MARKDOWN PREVIEW</p>
</div>

<div id="autosave_icon">&nbsp;</div>

<div id="returntotop">
    <img src="/images/v2-backtotop.png" />
</div>

</div><!-- /whitebackrgba -->
</div><!-- /writingareawrapper -->

</fieldset>
</form>