<?
	global $no_cache_page;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head> 

<!-- required: plops the page title -->
<title>PageOneTwentySix - Your Page, Write It - [<?= $data['title'] ?>]</title>

<!-- meta_tags -->
<meta name="description" content="A simple, private and secure online journal where you can jot down your personal thoughts for safe keeping later or explore some creative writing. In the book of life, this page is yours - write it." />
<meta name="keywords" content="journal, writing, privacy, books, magazines, creative" />

<? 	if(in_array($_GET['func'], $no_cache_page)) { ?>
<!-- meta_tags:robots -->
<meta name="robots" content="noindex, nofollow, noarchive">
<meta name="robots" content="noimageindex,nomediaindex" />
<meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet" />

<? } else { print "<!-- meta_tags: no_cache -->\n"; } ?>

<!-- css -->
<link rel="stylesheet" href="css/print.css" type="text/css" media="screen">

<!-- fav_icon -->
<link rel="shortcut icon" href="images/favicon.ico">

<!--[if lt IE 7]>
<script type="text/javascript">
window.location = "http://www.pageonetwentysix.com/un_supported.html"
</script>
<![endif]-->

</head>

<body>
	
	<div id="printable_area">
		<!-- <div id="notification" style="display: block;">
		<p>to print this page, press ctrl-p, or select FILE->Print |
		<a href="#" onclick="document.getElementById('notification').style.display = 'none';">close</a></p>
		</div> -->
	
		<div id="body_content">
		
		<?= get_view(); ?>
		
		</div>
		<div id="footer">
		<p>made with pageonetwentysix.com</p>
		</div>
		
	</div>
	
<!-- Insert Google Ads Here -->
<!-- Analytics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16406151-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<? profiler(); ?>


</body>
</html>
