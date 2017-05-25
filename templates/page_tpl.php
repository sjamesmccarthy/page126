<?
	global $no_cache_page;
	$add_journals = NULL;

	if($_REQUEST['m'] == 'open') {
	    $add_writing_mode_close = "navbar_writing_mode";
	    $beta_tag = "awesome! you're writing.";
	} else {
		$beta_tag = '<a target="_new" href="http://twitter.com/page126">Follow Us On Twitter <span class="fa fa-twitter"></span></a>';
	}

	// nav_links after logging in
	if(isSet($_SESSION['login_email']) && isSet($_SESSION['SK']) && $_GET['func'] != 'logout')
	{

		$page_timer = 'onkeydown="resetTimeoutTimer();" onmousedown="resetTimeoutTimer();" onload="timeoutObject=setTimeout(\'ShowTimeoutWarning()\', 600000*6);"';

		// if not on the writing entry page
		if($_GET['func'] != 'main')
		{
			//$back_to_writing = " | ";
			$back_to_writing = "";

			if($_GET['func'] != 'entries')
			{
				$back_to_writing .= " | <a href=\"/pages\">pages</a>";
			}

			if($_GET['func'] != 'journals') { $add_journals = " | <a href='?func=journals'>journals</a>"; }

			$back_to_writing .= $add_journals . " <!-- | <a href=\"?func=main\">write</a> -->";

		// on the writing page
		} else {
			$savenclose = "onclick=\"closensave();\"";
			$back_to_writing = " | <a href=\"/pages\" $savenclose>pages</a>";
		}

		$logged_in = "<a href=\"/settings\" $savenclose>" . strtolower($_SESSION['first_name']) . "</a> &raquo; <a href=\"/logout\" $savenclose>logout</a> " . $back_to_writing;

	// nav_links when not loged in
	} else {
		if($_GET['func'] == 'home')
		{
			$logged_in = "<a href=\"/signup\">sign-up</a>";
		} else {
			// &raquo; login below |
			//$logged_in = "<a href=\"?func=create\">create an account</a> if you don't have one &mdash; free!";
			//$logged_in = date('l, F j, Y') . "&mdash; Have you journaled today?";
			$logged_in = '<img style="margin-top: -20px;" src="/images/slogan.png" />';
		}
	}

	if(!$_GET['func'] || $_GET['func'] == 'main')
	   {
			if($_GET['func'] == 'main')
			{
				$link ='/pages';
				$home_image ='v2-logo_40px_home.png';
			} else {
				$link ='/about';
				$home_image ='v2-logo_40px.png';
			}
	 } else {
			/*
			if($_SESSION['_auth'] == 1 AND $_GET['func'] != 'logout') {
				$link ='/';
				$home_image ='v2-logo_40px_write.png'; // could be the write_icon
			} else if($_GET['func'] == 'journals') {
				$link ='?func=entries';
				$home_image ='v2-logo_40px_home.png';
			} else {
				$link ='/';
				$home_image ='v2-logo_40px_home.png';
		 	}
		 	*/

		 	if($_GET['func'] == 'journals' || $_GET['func'] == 'settings')
		 	{
		 		$link ='/pages';
				$home_image ='v2-logo_40px_write.png'; // could be the write_icon
		 	} else if($_GET['func'] == 'logout' || !$_SESSION['_auth']) {
			 	$link ='/';
				$home_image ='v2-logo_40px_home.png'; // could be the write_icon
		 	} else {
			 	$link ='/dashboard';
				$home_image ='v2-logo_40px_write.png'; // could be the write_icon
		 	}
	}

	if($_SESSION['pref_profile_public'] == 1) {
    	$title = $_SESSION['username'] . ' - PageOneTwentySix Profile';
	} else {
    	$title = "PageOneTwentySix - This Is Your Page, Write It.";
	}

	?>

<!DOCTYPE html>
<head>

<!-- required: plops the page title -->

<title><?= $title ?></title>
<meta charset="UTF-8">

<!-- <meta name="viewport" content="width=device-width, user-scalable=no"> -->

<!-- meta_tags -->
<meta name="description" content="PageOneTwentySix - write, share and publish. In the book of life, this page is yours - write it." />
<meta name="keywords" content="journal, writing, privacy, books, magazines, creative, diary, diaries, online diary, free diary, free online diary, private journal, write, journalism, notepad, creative writing, poetry, blog" />

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- meta_tags:search -->
<meta name="google-site-verification" content="fNZlsm2YWkxq_Fa-imq-WVaWafc_i-AVgtoJX8Q0tcs" />
<meta name="msvalidate.01" content="10BB40F9238C0052DF3F03A0A1870446" />
<meta name="y_key" content="b9f6cc109df29ecb" />

<?  if($_GET['func'] == 'logout' && !$_SESSION['sudoseeya']) { ?>
<meta http-equiv="refresh" content="3;url=/" />
<? } ?>

<? 	if(in_array($_GET['func'], $no_cache_page)) { ?>
<!-- meta_tags:robots -->
<meta name="robots" content="noindex, nofollow, noarchive">
<meta name="robots" content="noimageindex,nomediaindex" />
<meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet" />

<? } else { print "<!-- meta_tags: no_cache -->\n"; } ?>

<!-- css -->
<link rel="stylesheet" href="/css/structure.css?v2.0.1" type="text/css" media="screen">
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<link href="/css/jquery.fs.tipper.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="/css/selectbox.css" type="text/css" /> -->

<!-- Google WebFonts -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Merriweather:400,900,700,400italic,300,300italic' rel='stylesheet' type='text/css'>

<!-- jquery:core-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

<!-- jquery:plugins -->
<script type='text/javascript' src='/lib/js/jquery/jquery.autosave.js'></script>
<script type='text/javascript' src='/lib/js/jquery/jquery.typewatch.js'></script>
<script type='text/javascript' src='/lib/js/jquery/jquery.autoresize.js'></script>
<script type='text/javascript' src='/lib/js/jquery/jquery.ctrlkey.js'></script>
<script type='text/javascript' src='/lib/js/jquery/jquery.placeholder.js'></script>
<script type='text/javascript' src='/lib/js/jquery/jquery.password_strength.js'></script>
<script type='text/javascript' src='/lib/js/jquery/jquery.fs.tipper.js'></script>
<script type='text/javascript' src='/lib/js/jquery/jquery.insertAtCaret.js'></script>

<!-- to Markdown utils -->
<script type='text/javascript' src='/lib/js/jquery/showdown.js'></script>
<!-- <script type='text/javascript' src='/lib/js/jquery/to-markdown.js'></script> -->
<script type='text/javascript' src='/lib/js/jquery/mdhtmlform.js'></script>

<!-- Font Awesome -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<!-- fav_icon -->
<link rel="shortcut icon" href="/images/favicon.ico">

<!-- Determines whether the BackgroundColor has changed and sets it -->
<? if($_COOKIE['theme'])
   {
   		$_SESSION['theme'] = $_COOKIE['theme'];
   } else {
    	// on initial load there is no session set, so we have to force it.
    	if($_SESSION['pref_default_theme'])
    	{
    		$_SESSION['theme'] = $_SESSION['pref_default_theme'];
		$_COOKIE['theme'] = $_SESSION['pref_default_theme'];
	} else {
    		$_SESSION['theme'] = DEFAULT_THEME;
    		$_COOKIE['theme'] = DEFAULT_THEME;
	}
   }
?>

<script type="text/javascript">

$(document).ready(function(e)
{

    $('input').placeholder();

    $(".tipped").tipper({
	    direction: "bottom",
	    follow: "true"
    });

    $('input').keydown(function(e) {
        if (e.keyCode == 13) {
            $(this).closest('form').submit();
        }
    });

    $('#button_login').click(function(e) {
        e.preventDefault();
        // form_reg = form_bugs (releasenotes)
        $('#form_settings, #journal, #delete_journal_form, #empty_trash, #del_form, #form_reg').submit();
    });

});

	function dismiss_ie(c_value)
	{

		document.getElementById('whatbrowser').style.display = "none";
		var name = 'ie_warning';
		document.cookie = name + "=" + escape( c_value )

	}

	function image_swap(name,state) {
	if(state == 1) { var toggle = name + '_on'; }
	else { var toggle = name; }

	var file = 'images/' + toggle + '.png';
	//document[name].src = file;
	document.getElementById(name).src = file;
}

</script>

<!-- Otherwise it just uses the default BackgroundColor as specified in the css file -->

<!--[if lt IE 7]>
<script type="text/javascript">
window.location = "http://www.pageonetwentysix.com/un_supported.html"
</script>
<![endif]-->

</head>

<!-- onLoad="getPattern();" -->
<body id="<?= $_GET['func'] ?>" <?= $page_timer ?>>
<!-- tpl -->
<a name="t"></a>

<script type="text/javascript">
/*
var file = 'pattern_<?= $_SESSION['theme'] ?>.png';
newImage = "url(../images/" + file + ")";
document.getElementById('tpl').style.backgroundImage = newImage;
*/

var timeoutObject;

function startTimeoutTimer()
{
	timeoutObject = setTimeout( 'ShowTimeoutWarning();', 600000*6); // 3 hours; 600,000ms = 10min | 600000*6*3 | 600000*6
}

function resetTimeoutTimer()
{
	clearTimeout(timeoutObject); //stops timer
  	startTimeoutTimer();
}

function ShowTimeoutWarning()
{
	/* Auto-Save Entire Form */
	$("form#entry_form").trigger('submit');

	var answer = confirm ("Do you need more time?")
	if (answer)
	{
		clearTimeout(t);
		startTimer();
		window.location = "/?func=main";
	} else {
		window.location = "/?func=logout";
	}
}

</script>

<div id="fadewrapper">

	<div id="navbar" class="<?= $add_writing_mode_close ?>">
		<!-- <div id="navbar_container"> -->

		 <p class="v2-nav-label-reader"><a href="/">discover<br /><span class="supertinyfont">new writers</span></a></p>


			<div id="navbar_logo">

			<!-- <p class="beta-tag"><?= $beta_tag ?></p> -->

			<p class="v2-header_logo_img">
    			<a href="<?= $link ?>" <?= $savenclose ?>>
    			<!-- <img src="/images/<?= $home_image ?>" border="0" /> -->
    			<img src="/images/v2-header_penslanted.png" />
    			</a>
			</p>
			<p class="v2-header_logo">
			    <a href="<?= $link ?>" <?= $savenclose ?>>page126</a>
            </p>

            </div>

	
           <!--  <p class="join navbar-rightside-btns"><span class="navbar-create-btn">Create Account</span> <a href="#" class="join navbar-signin-btn">Sign-in</a></p>
 -->
			    <?php include('views/snips/nav_logged_in.php'); ?>

		<!-- </div>  --><!-- /navbar_container -->
	</div>

	<div id="header">
	<!--
<? if(!$_GET['func'] || $_GET['func'] == 'main')
	   {
			if($_GET['func'] == 'main')
			{ ?>
				<a href="?func=privacy">
	 	 <? } else { ?>
				<a href="?func=about">
	 	 <? } ?>

		<img src="images/header.png" border="0" /></a>
	<? } else {
			if($_SESSION['_auth'] == 1 AND $_GET['func'] != 'logout') { ?>
			<a href="/"><img src="images/header_write.png" border="0" /></a> | logout
			<? } else { ?>
			<a href="/"><img src="images/header_on.png" border="0" /></a>
			<? } ?>
	<? } ?>
-->
	</div>

	<!-- Green bottom border
	<div style="background-color:#44872D; height: 3px;"></div> -->

	<div id="body_content">

		<?= get_view();?>

	</div><!-- /body_content -->

	<? if(!$_GET['func'] || $_GET['func'] != 'main') { ?>
	<!-- blue bar for all pages but MAIN -->
	<!-- <div id="footer_blue_bar"> -->

	<!-- <div style="width:500; float: left; padding-top: 10px; padding-left: 20px;"> -->
	<!-- <a target="_new" style="color: #0080ff; font-size: 9pt;" href="http://twitter.com/page126"><img style="opacity: 0.25; vertical-align: middle; padding-right: 5px;" src="/images/social_twitter.png" /></a> --> <!-- <a target="_new" style="color: #0080ff; font-size: 9pt;" href="http://twitter.com/page126"><i>Follow us on Twitter!</i></a> -->
	<!-- </div> -->

	<!-- <div style="width: 129px; float: right; padding-top: 10px;">
	<i style="color: #0080ff; font-size: 9pt; padding-right: 5px;">Share the love!</i>
	<a target="_new" href="http://www.stumbleupon.com/submit?url=https://pageonetwentysix.com&title=PageOneTwentySix"><img style="vertical-align: middle; padding-right: 5px;" src="/images/social_stumbleupon.png" /></a>
	<a target="_new" href="http://digg.com/submit?phase=2&url=https://pageonetwentysix.com&title=PageOneTwentySix&bodytext=PageOneTwentySix%20is%a%20an%20online%20journal%20and%20diary"><img style="vertical-align: middle; padding-right: 5px;" src="/images/social_digg.png" /></a>
	<a target="_new" href="http://delicious.com/post?url=https://pageonetwentysix.com&title=PageOneTwentySix&notes=PageOneTwentySix%20is%20a%20an%20online%20journal%20and%20diary"><img style="vertical-align: middle; padding-right: 5px;" src="/images/social_delicious.png" /></a>
	<a target="_new" href="http://www.facebook.com/sharer.php?u=https://pageonetwentysix.com/"><img style="vertical-align: middle; padding-right: 5px;" src="/images/social_facebook.png" /></a>
	<a target="_new" href="http://twitter.com/?status=PageOneTwentySix%20-%20http://www.pageonetwentysix.com%20-%20An%20online%20journal%20and%20diary%20that%20is%20private,%20simple%20and%20free!%20share%20the%20love%20retweet."><img style="vertical-align: middle; padding-right: 5px;" src="/images/social_twitter_box.png" /></a>
	</div> -->


	<? } ?>
	<!-- green bar for all pages including MAIN -->
	<!-- <div id="footer_green_bar"></div>
	<div id="footer"> -->
		<!--
		<p>
		<a href="?func=moretocome">donate</a> | <a href="?func=privacy">privacy</a> | <a href="?func=about">about</a> | <a href="?func=contact">contact</a> | <a href="?func=help">help</a>
		| <a style="text-decoration: none;" href="?func=change_log">v<?= VERSION ?> beta (release notes)</a>
		</p>
		-->
		<!-- <p class="footersmalltext">entry_form_input Invite</p> -->
	<!-- </div> -->

	<!-- </div> -->

<?php if($_GET['func'] != 'main') { ?>

<br style="clear: both" />

<p class="v2-footer">&copy; 2010 - <?= YEAR ?> a Black Tea Press project <!-- | <a id="report" href="/releasenotes">Report a Problem</a>  -->| 
<a href="/releasenotes">v <?= VERSION ?></a> 

<?php if($_SESSION['_auth'] == 1 && $data['userpage_public'] != "1" && $_GET['func'] != 'logout' ) { ?>
| <a href="/logout" onclick="closensave();">logout</a>
<?php } ?>

</p>

<? } ?>

</div>

<!-- Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-16406151-13', 'pageonetwentysix.com');
  ga('send', 'pageview');

</script>

<? profiler(); ?>


</body>
</html>
