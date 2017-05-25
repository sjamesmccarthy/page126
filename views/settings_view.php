<?php if(!defined('APP-START')) { echo 'RESTRICTED-PAGE-LOGIN-REQUIRED'; exit; } ?>

<style>
div.upload {
    width: 100px;
    height: 27px;
    background: url(/images/v2-upload_bkg.png);
    background-color: #FF6666;
    border-bottom: 4px solid rgba(51,51,51,1);
    background-color: rgba(51,51,51,.2);
    overflow: hidden;
    opacity: .4;
}

div.upload:hover {
    opacity: 1;
}

div.upload input {
    display: block !important;
    width: 100px !important;
    height: 27px !important;
    opacity: 0 !important;
    overflow: hidden !important;
    cursor: pointer;
}
</style>

<script type="text/javascript">
 $(document).ready(function()
 {

	$(":input").addClass('form-whiteback-rgba');

    // $(":input").focus(function() {
    //    
    // });

    //  $(":input").blur(function() {
    //    $(":input").removeClass('form-whiteback-rgba');
    // });


    $('#phintlink').on('click', function(e) {
        e.preventDefault();
        $('#password_hint').toggle();
    });

    $('#file').change(function() {
        if ( $('#file').val() != '' ) {
            $('#profile_pic').css('opacity','.2');
            $('#form_settings').submit();
        }
    });

     $('#cpf').click(function(e) {
        e.preventDefault();
        $('#password_fields').toggle();
     });

var session_username;
session_username = '<?= $_SESSION['username'] ?>';

    $('#pref_profile_public').click(function() {

        if(session_username == '') {
         var checkstate = $('#pref_profile_public').is(':checked');

             if(checkstate == true) {
                alert('please select a username');
                $('#username').focus();
                $("#username").css('background-color','rgba(255,255,102,.2)');
             }

        }

    });

    if(session_username == '') {
        // $(".username_status").show();
        // $(".username_status").attr("src","/images/v2-username_taken.png");
    } else {

        var profileUrl = "http://page126.com/" + session_username;

        $(".username_url").show();
        $(".username_url").html('<a target="_profile" href="/' + session_username + '">' + profileUrl + '</a>');
        $(".username_exists").show();
        $(".username_label").html('This is your public profile');

        /*
        $(".username_status").show();
        $(".username_status").attr("src","/images/v2-username_current.png");
        $("#username").css('background-color','rgba(51,51,51,.2)');
        $("#username").css('color','#AAA');
        */
    }

    /*
$('#username').focus(function() {
        $("#username").css('background-color','#FFF');
        $("#username").css('color','#000');
    });

    $('#username').blur(function() {
        $("#username").css('background-color','rgba(51,51,51,.2)');
        $("#username").css('color','#AAA');
    });
*/

	$('#new_password').password_strength();
	setTimeout(function() {
	    $('#error_box').fadeOut('10000');
	}, 3000);

    $("#username").keyup(function(event) {

                name = $("#username").val();
                name = name.replace(/[^a-zA-Z0-9_]+/g,'');
                $("#username").val(name);

                if( $('#username').val() != '') {

                    $.post( "lib/ajax/checkusername.php?name=" + name, function( data ) {

                     console.log(data);
                         if(data == 'available') {
                            $(".username_status").attr("src","/images/v2-username_available.png");
                         } else {
                            $(".username_status").attr("src","/images/v2-username_taken.png");
                         }

                   });

                } else {
                    $(".username_status").attr("src","/images/v2-username_taken.png");
                }

                $(".username_status").show();
                $('#pref_profile_public').prop('checked', true);

    });

 });
</script>

<?

	// Check for valid login
	if (!$_SESSION['login_email']) exit(NO_SESSION_ERROR);

	/* Create Timezone Drop Down from $tz_array in config file */
	global $tz_array;
	foreach($tz_array as $key => $value)
	{
		if($value == $_SESSION['pref_timezone']) { $SELECTED = 'SELECTED'; } else { $SELECTED = ''; }
		$tz_list .= '<option ' . $SELECTED . ' value="' . $key . ':' . $value . '">' . $value . "</option>\n";
	}

	/* Create Theme Drop Down from $theme_array in config file */
	global $theme_array;
	foreach($theme_array as $key => $value)
	{
		if($key == $_SESSION['pref_default_theme']) { $SELECTED_THEME = 'SELECTED'; } else { $SELECTED_THEME = ''; }
		$theme_list .= '<option ' . $SELECTED_THEME . ' value="' . $key . '">' . $value . "</option>\n";
	}

	/* Preferences */
	if($_SESSION['pref_trash'] == 1)
	{
		$CHECKED_TRASH = 'CHECKED';
	}

	if($_SESSION['pref_entry_new'] == 1)
	{
		$CHECKED_ENTRY = 'CHECKED';
	}

	if($_SESSION['pref_show_index'] == 1)
	{
		$CHECKED_SHOW = 'CHECKED';
	}

	if($_SESSION['pref_show_quote'] == 1)
	{
		$CHECKED_QUOTE = 'CHECKED';
	}

	if($_SESSION['pref_profile_public'] == 1)
	{
		$CHECKED_PUBLIC = 'CHECKED';
	}

	if($_SESSION['pref_profile_public_private'] == 1)
	{
		$CHECKED_PUBLIC_PRIVATE = 'CHECKED';
	}

    if($_SESSION['pref_font_size'] == '0.8')
	{
		$SELECTED_FONTSIZE_SQUINT = 'SELECTED';

	} elseif($_SESSION['pref_font_size'] == '1.0')
	{
		$SELECTED_FONTSIZE_SMALL = 'SELECTED';

	} elseif($_SESSION['pref_font_size'] == '1.2')
	{
		$SELECTED_FONTSIZE_NORMAL = 'SELECTED';

	} elseif($_SESSION['pref_font_size'] == '2.0')
	{
		$SELECTED_FONTSIZE_LARGE = 'SELECTED';
	} else {
    	$SELECTED_FONTSIZE = NULL;
	}

	/* Determine Default Journal */
	foreach($data['journals_array'] as $key)
	{
		if($_SESSION['pref_default_journal'] == $key['id'])
		{
			$SELECTED = 'SELECTED';
		} else {
			$SELECTED = NULL;
		}
		$data['journal_list_settings'] .= '<option ' . $SELECTED . ' value="' . $key['id'] . '"> ' . substr($key['title'], 0, 27) . '</option>' . "\n";
	}

?>

<script type="text/javascript">
function check_pswd()
	{

		if(document.getElementById('new_password').value != '')
		{
			if(document.getElementById('new_password').value == document.getElementById('new_password_check').value)
			{
				//
			} else {
				alert('New Passwords DO NOT match');
				return false;
			}
		}
	}

function check_req()
{
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if(!emailReg.test( $("#email").val() ) ) {
        alert("Please enter valid email id");
        return false;
    }

}

</script>

<? if($_SESSION['settings_updated_profile_image'] == 2) { ?>
		<div id="error_box">
		<h3>profile image & settings updated</h3>
		<p class="padtop">Your profile image and settings have been updated.</p>
		<?= $_SESSION['settings_new_password_ok'] ?>
		</div>
<? } elseif($_SESSION['settings_updated_profile_image'] == 3) { ?>
		<div id="error_box">
		<h3>profile image removed</h3>
		<p class="padtop">Your profile image has been removed.</p>
		<?= $_SESSION['settings_new_password_ok'] ?>
		</div>
<? } elseif($_SESSION['settings_updated'] == 1) { ?>
		<div id="error_box">
		<h3>settings updated</h3>
		<p class="padtop">Your settings have been updated.</p>
		<?= $_SESSION['settings_new_password_ok'] ?>
		</div>
<? } ?>


<? if($_SESSION['settings_updated'] == 3) { ?>
		<div id="error_box" class="error_box-red">
		<h3>profile image too big</h3>
		<p class="padtop">Your profile image was too big. Reduce file size and try again.</p>
		<?= $_SESSION['settings_new_password_ok'] ?>
		</div>
<? } ?>

<? if(isSet($_SESSION['settings_errors'])) { ?>
		<div id="error_box">
		<p><?= $_SESSION['settings_errors']?></p>
		</div>
<? } ?>

<? if($_SESSION['backup_success'] == 1) { ?>
		<meta http-equiv="refresh" content="2;url=/download.php?get=<?= $_SESSION['backup_file'] ?>">
		<div id="error_box">
		<h3>backup ok</h3>
		<p class="padtop"><a href="download.php?get=<?= $_SESSION['backup_file'] ?>">Download it now</a>, if your download doesn't automatically start in a few seconds.</p>
		<!-- <div id="hr"></div>
		<img src="/images/icon_close_x.png" border="0" /> -->
		</div>
<? } unset($_SESSION['backup_success']); unset($_SESSION['backup_file']);?>

<? if($_SESSION['trash_untitled'] == 1) { ?>
		<div id="error_box">
		<h3>cleaned up</h3>
		<p class="padtop">We have deleted all Untitled entries.</p>
		<!-- <div id="hr"></div>
		<img src="/images/icon_close_x.png" border="0" /> -->
		</div>
<? } unset($_SESSION['trash_untitled']); ?>

<div id="whitebackrgba" class="padbottom">
<div id="writingareawrapper" class="section_blue section_blue_padding">

<h4>settings</h4>

	<div id="left_col" style="margin-left: 10px;">
	<h3>Profile</h3>

	<form id="form_settings" name="form_settings" style="padding-top: 20px;" action="/?func=update_settings" method="post" onsubmit="return check_req();" enctype="multipart/form-data">
        <!-- onsubmit="return error_chk();" -->

	<input type="hidden" name="update" value="1" />

<?php
if($_SESSION['profileimage'] == '') { $_SESSION['profileimage'] = 'default.png'; }
?>

	    <p style="text-align: left">
        <img id="profile_pic" src="/images-profiles/<?= $_SESSION['profileimage'] . '?' . time(); ?>" alt="public profile avatar"
            style="background-color: rgba(51,51,51,.2); border-radius: 10px; width:75px; height: 75px; margin-right: 10px;" />
	    </p>

       <!--  <input type="file" name="file" id="file" placeholder="Upload Profile Image" style="overflow: hidden: margin: 10px;" /> -->
        <div class="upload" style="clear: both; margin-top: 0px;">
            <input type="file" name="file" id="file" class="upload" />
        </div>

        <p style="padding-bottom: 30px">
        <span style="font-size: .9em;">Profile images are displayed 75 pixels by 75 pixels and can be no larger than 150k. Crop yourself before uploading | <a href="/?func=remove-profile-image">Delete Image</a></span>
        </p>

        <!--
            <p style="padding-top: 0; text-align: left;">
            <span class="button"><a id="button_login" href="#">Change Photo</a></span>
        </p>
        -->

		first name:<br />
		<input type="text" id="first_name" name="first_name" size="45" value="<?= $_SESSION['first_name'] ?>" />

		<p>
		last name:<br />
		<input type="text" id="last_name" name="last_name" size="45" value="<?= $_SESSION['last_name'] ?>"/>
		</p>

        <p>
		email:<br />
		<input type="text" id="email" name="email" size="45" value="<?= $_SESSION['login_email'] ?>"/>
		</p>

		<p>
		<span class="username_label">Username:</span><br />
		<input type="text" id="username" name="username" size="35" MAXLENGTH="15" value="<?= $_SESSION['username'] ?>" />
		<img class="username_status" src="/images/v2-username_taken.png" alt="username status" style="display: none" /><br />
		<div class="username_url"></div>
		</p>

		<p>
        location:<br />
		<input type="text" id="location" name="location" size="45" value="<?= $_SESSION['location'] ?>"/>
		</p>

		<p>
        website:<br />
		<input type="text" id="website" name="website" size="45" value="<?= $_SESSION['website'] ?>"/>
		</p>

		<p>
        bio:<br />
		<textarea style="margin-top: 10px; height: 75px;" id="bio" name="bio"><?= $_SESSION['bio'] ?></textarea><br />
		<span style="font-size: .8em">About yourself in 160 characters or less.</span>
		</p>

<!--
		<p>
		current password:<br />
		<input type="password" id="current_password" name="current_password" size="45" autocomplete="off" />
		</p>
-->


        <!-- <p>
        <a href="https://twitter.com/nomoreblacktea" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @nomoreblacktea</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        </p>
-->
</li>
</ul>

	</div><!-- left_col -->

	<div id="right_col">

	<h3>Options</h3>
	<!-- <p class="padtop">You are currently a <b><?= $user_type ?></b> user.</p> -->

		<ul class="ul-settings">

<!--         <h4 style="padding-top: 20px">After logging in:</h4>
		<li><p> -->
		<!-- pref_login_redir -->
		<input type="hidden" name="pref_login_redir" value="createnew" <?= $CHECKED_ENTRY ?>  /> <!-- Start writing! (Show today's page)<br /> -->
		<!-- <span style="font-size: 8pt;">if no page for today exists, a new page will be created</span>
		</p></li> -->

        <!-- <li><p> -->
		<!-- pref_show_index_redir -->
		<input type="hidden" name="pref_login_redir" value="openlast" <?= $CHECKED_SHOW ?> /> <!-- Display the folder list<br /> -->
		<!-- <span style="font-size: 8pt;">shows all the pages for the default folder</span>
		</p></li> -->

        <h4 style="padding-top: 20px">Writing</h4>

        <li>Font size:
        <select name="pref_font_size">
    	    <option value="0.8" <?= $SELECTED_FONTSIZE_SQUINT ?>>Squint</option>
    	    <option value="1.0" <?= $SELECTED_FONTSIZE_SMALL ?>>Small</option>
    	    <option value="1.2" <?= $SELECTED_FONTSIZE_NORMAL ?>>Medium (normal)</option>
    	    <option value="2.0" <?= $SELECTED_FONTSIZE_LARGE ?>>Large</option>
    	</select>
        </li>

        <li style="color:  <?= $bk_color ?>">Default folder:<br />
    	<select name="pref_default_journal">
    	<?= $data['journal_list_settings'] ?>
    	</select> |
    	<!-- <a href="?func=entries">View Current Journal</a> | --> <a href="/folders">Manage Folders</a></li>

        <li style="padding-top: 10px; color: #CCC;"><p>
		<input style="opacity: .2" type="checkbox" name="pref_show_quote" value="1" <?= $CHECKED_QUOTE ?> DISABLED /> Display writing prompt<br />
		<span style="font-size: 8pt;">a random writing prompt will display on the top of your writing area. Submit a prompt (100 words or less) by emailing us: submityours^nomoreblacktea|com</span>
		</p></li>

		<li style="padding-top: 10px;"><p>
		<input type="checkbox" name="pref_trash_redir" value="1" <?= $CHECKED_TRASH ?> /> After trashing page create a new one<br />
		<span style="font-size: 8pt;">otherwise the pages list (index) will be displayed</span>
		</p></li>


	<li style="color:  <?= $bk_color ?>;">
		Email writing reminders:
		<select name="pref_email_reminder_time" />
		 <option value="-1">none</option>

         <?	$h=0;
			for ($i=0; $i<=25; $i++)
			{
				if($i > 11) { $AMPM = 'PM'; $h = $i - 12; }
				if($i < 11) { $AMPM = 'AM'; $h = $i; }
				if($i == 0 || $i == 12) { $h = 12; }
				if($i == 25) { $h = 'none'; $AMPM = NULL; }
				if($_SESSION['pref_email_reminder'] == $i)
				{
					$SELECTED = 'SELECTED';
				} else {
					$SELECTED = NULL;
				}

				echo "<option " . $SELECTED . " value=" . $i . ">" . $h . " $AMPM EST</option>\n";
				$h++;
			}
		?>

		EST </select><br /><span style="font-size: 8pt;">Sorry, this is not localized yet, please convert correctly if you're not located in the Eastern timezone USA.</span>


	</li>

        <h4 style="padding-top: 20px">Privacy</h4>
		<li><p>
		<!-- pref_profile_public -->
		<input type="checkbox" id="pref_profile_public" name="pref_profile_public" value="1" <?= $CHECKED_PUBLIC ?>  /> Enable my profile public<br />
		<span style="font-size: 8pt;">and share my public writing with the world</span>
		<!-- <img style="margin-top: -30px; margin-left: 10px;" src="/images/v2-listings_public.png" /> -->
		</p></li>

        <li style="color: #CCCCCC"><p>
		<!-- pref_profile_public -->
		<input style="opacity: .2" type="checkbox" id="pref_profile_protect_public" name="pref_profile_protect_public" value="1" <?= $CHECKED_PUBLIC_PRIVATE ?>  DISABLED /> Protect my public pages<br />
		<span style="font-size: 8pt;">Only people who follow you and are logged into their page126 account will be able to see your public pages.</span>
		<!-- <img style="margin-top: -30px; margin-left: 10px;" src="/images/v2-listings_public.png" /> -->
		</p></li>

        <h4 style="padding-top: 20px">Location Information</h4>
        <li><p>Timezone:
		<select name="pref_timezone">
		<?= $tz_list ?>
		</select>
		</p>
        </li>

        <li style="padding-top: 10px;"><p>
        Zip Code: <input type="text" id="pref_zipcode" name="pref_zipcode" value="<?= $_SESSION['pref_zipcode'] ?>" maxlength="5" size="5" style="width: 20%; height: 25px; font-size: 1.0em; padding: 2px;" />
		</p></li>

        <h4 style="padding-top: 20px">Password</h4>

        <a id="cpf" href="#">change password</a>
        <div id="password_fields" style="display: none; padding-top: 20px; padding-bottom: 20px;">
            <p>
    		new password:<br />
    		<input class="bigger-input" type="password" id="new_password" name="new_password" size="45" autocomplete="off"/></p>

    		<p style="margin-top: 10px;">
    		new password verify (type it again)<br />
    		<input class="bigger-input" type="password" id="new_password_check" name="new_password_check" size="45" onblur="check_pswd();" autocomplete="off"/>
    		</p>
        </div>

		<p>
		<!-- password hint:  --><a id="phintlink" href="#">password hint</a><br />
		<input class="bigger-input" style="display: none;" type="text" id="password_hint" name="password_hint" size="45" value="<?= $_SESSION['password_hint'] ?>"/>
		</p>

		<input type="hidden" name="pref_hide_entry_pswd" value="0" />
		<input type="hidden" name="pref_show_index_redir" value="0" />
		<input type="hidden" name="pref_hide_entry_pswd" value="0" />
		<input type="hidden" name="theme_pref" value="blue" />


	</ul>

    <h4 style="padding-top: 20px;">Account Tools</h4>

        <ul>
        <li> <a href="/?func=backup">Backup To CSV File (all Folders)</a></li>
        <li> <a href="/?func=empty_trash">Empty Trash in <?= $_SESSION['fk_journal_title'] ?> Folder</a></li>
        <!-- <li><a href="?func=help">Frequently Asked Questions</a></li> -->
        <li><a href="/?func=releasenotes">Release Notes</a></li>
        <li><a href="/?func=about">Development & Credits</a></li>
        <li><a href="/?func=delete_account">Delete Account</a></li>
		<!-- <li> <a href="?func=trash_untitled">Delete Untitled pages (all journals)</a></li> -->


    </div><!-- rt_col -->

<br style="clear: both" />

        <p style="padding-top: 30px; text-align: left;">
            <span class="button"><a id="button_login" href="#">Update Settings</a></span>
        </p>

</div><!-- /inside_container -->

<script type="text/javascript">
function loadImageFile() {
    if (document.getElementById("uploadfile").files.length === 0) return;
    var e = document.getElementById("uploadfile").files[0];
    if (!rFilter.test(e.type)) {
        return
    }
    oFReader.readAsDataURL(e)
}
var one = new CROP;
one.init(".default");
one.loadImg("example.jpg");
$("body").on("click", "button", function() {
    $("canvas").remove();
    $(".default").after('<canvas width="240" height="240" id="canvas"/>');
    var e = document.getElementById("canvas").getContext("2d"),
        t = new Image,
        n = coordinates(one).w,
        r = coordinates(one).h,
        i = coordinates(one).x,
        s = coordinates(one).y,
        o = 240,
        u = 240;
    t.src = coordinates(one).image;
    t.onload = function() {
        e.drawImage(t, i, s, n, r, 0, 0, o, u);
        $("canvas").addClass("output").show().delay("4000").fadeOut("slow")
    }
});
$("body").on("click", ".newupload", function() {
    $(".uploadfile").click()
});
$("body").change(".uploadfile", function() {
    loadImageFile();
    $(".uploadfile").wrap("<form>").closest("form").get(0).reset();
    $(".uploadfile").unwrap()
});
oFReader = new FileReader, rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
oFReader.onload = function(e) {
    $(".example").html('<div class="default"><div class="cropMain"></div><div class="cropSlider"></div><button class="cropButton">Crop</button></div>');
    one = new CROP;
    one.init(".default");
    one.loadImg(e.target.result)
}
</script>

<? 	unset($_SESSION['settings_updated']);
	unset($_SESSION['settings_errors']);
	unset($_SESSION['settings_new_password_ok']);
	unset($_SESSION['settings_updated_profile_image']);
?>