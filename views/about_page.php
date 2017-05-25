<?php
    if(!defined('APP-START')) { header('location: /?func=about'); }
?>

<script type="text/javascript">
	$(document).ready(function() {

	console.log('jqry-docready-about');

		$(".fancybox").fancybox({
    		openEffect	: 'fade',
            closeEffect	: 'fade',
            fitToView : false,
            autoSize : false,
            maxWidth: '70%',
    helpers : {
        overlay : {
            css : {
                'background' : 'rgba(0,0,0, .9)'
            }
        }
    }

		});
	});
</script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="/lib/js/jquery/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/lib/js/jquery/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>


<div id="whitebackrgba" class="padbottom">

    <div id="writingareawrapper">

<h3 style="font-size: 1.8em; font-weight: 400;">development &credits</h3>
<p>Page126 was developed using a variety of software for Mac OS X and Windows.</p>

    <ul id="acknow">
    <li>- Mac OS apps</li>
    <li style="margin-left: 20px;">Pixelmator, Photoshop, Illustrator, PixelStick, ColorPicker, SublimeText, </li>
    <li>- <!-- <a target="_new" href="http://www.staples.com/Staples-7-3-4-x-5-1-Subject-Notebook-3-Pack/product_280354?cmArea=SEARCH"> -->orange journal note book from Staples</li>
    <li>- the creators of Scott Pilgrim vs. the World</li>
    <li>- jQuery</li>
    <li style="margin-left: 20px;">and plugins: Fancybox, Auto-Save, TypeWatch, CtrlKey, placeholder.js, autoresize, password strength, selectbox, insertAtCaret, <a target="_new" href="https://github.com/justinmc/markdown-html-form">markdown-html-form</a></li>
    <li>- flaticons.com, favicon creator, fontawesome</li>
    <li>- MAMP Pro  for local development</li>
    <li>- MySQL</li>
    <li>- PHP</li>
    <li>- Google web fonts</li>

    <li> <p style="margin-top: 10px; font-size: .9em;"><a href="/releasenotes">Release Notes</a></p></li>
    </ul>

<?php if($_SESSION['_auth'] != 1) { ?>
<!--
<p style="padding-top: 40px; text-align: center">
<span class="button"><a href="/?">sign in & write</a></span> | <a href="/signup">sign-up</a>
</p>
-->
<? } ?>

<!-- </div> -->

</div>
</div>