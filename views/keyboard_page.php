<?php
    if(!defined('APP-START')) { header('location: /?func=about'); }
?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="/lib/js/jquery/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/lib/js/jquery/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>


<div id="whitebackrgba" class="padbottom">

    <div id="writingareawrapper">

<div style="width: 80%; margin: auto;">
                <h3 style="margin-top: 35px; line-height: 1.8em; width: 100%; text-align: center; font-family: 'Open Sans', sans-serif; font-weight: 400;">Sometimes, kismet happens.<br />This is your page &mdash; explore, write, share.</h3>
                </div>

<p style="padding: 30px 60px; font-weight: 700; font-size: 1.3em;">
PageOneTwentySix is a minimalistic writing backdrop where you can cultivate ideas, mash-up words, play with your creativity with other writers and share your work with interested readers.
</p>

<div id="hr" style="width: 60%; margin: auto; margin-top: 20px; padding-bottom: 40px;"></div>

<p>
The story starts in Reno, Nevada, one late night in September 2009 when a beautiful blonde named Aimee text'd founder James McCarthy. The text said, "read page 126". Half awake and with one-eye open, James searched the bookshelf for the book she was reading and turned to the page. His eyes skimmed the black, typed text and then slowed over the words: "Sometimes, kismet happens." Yes, it does he thought, smiled and woke up with the idea of PageOneTwentySix so that he could write down all their grand adventures.
</p>

<h3 style="font-size: 1.8em; font-weight: 400;">It's not complicated.</h3>
<ul id="about_list">
    <li>Every page <span class="bold">autosaves your writing</span> while you type so you don't have to worry about clicking a submit button.</li>
    <li><span class="bold">Organize your writing into folders</span>. Maybe have one for a novel you're thinking about writing, one for daily creative challenges, another for poetry and yet another for a daily journal.</li>
    <li>This line intentionally left blank for a secret.</li>
    <li><span class="bold">Tag your writing using keywords</span> so you can easily find it later.</li>
    <li>Just like the good old fashioned garbage can beside your desk, if you don't like what you wrote, crumple it up and toss it in the <span class="bold">trash, not delete</span>. You can always take it out later or empty it.</li>
    <li><span class="bold">Email a copy to yourself, or print it</span> out with clean edges and no clutter like headers and footers.</li>
    <li><span class="bold">Keyboard shortcuts</span> for hiding your screen [ctrl-z], saving (if you just can't break that [ctrl-s] habit) and a few others like pressing the ESC key saves and displays all your pages.</li>
    <li><span class="bold">Export your writing</span> and folders to a CSV file.</li>
    <li><span class="bold">Share your writing with a public page</span> or make your profile private.</li>
    <li>Have a <span class="bold">"Don't forget to write!" email reminder</span> to you each morning, afternoon or evening.</li>
    <li><span class="bold">White wash your screen</span> so it's just you and your words. No website graphics or toolbar distractions.</li>

    <li style="list-style: none; margin-left: -40px; margin-top: 10px; font-size: 1.1em">View the screenshots to take a quick look inside.</li>
</ul>

<div style="width: 33%; float: right; margin-right: 5px;">

    <p style="text-align: right"><a class="fancybox" rel="screenshot" href="/images/v2-screen1.jpg"><img class="scnshot" src="/images/v2-screen1.jpg" style="width: 100%; margin-bottom: 20px" /></a></p>

    <p style="text-align: right"><a class="fancybox" rel="screenshot" href="/images/v2-screen2.jpg"><img class="scnshot" src="/images/v2-screen2.jpg" style="width: 100%; margin-bottom: 20px" /></a></p>

    <p><a class="fancybox" rel="screenshot" href="/images/v2-screen3.jpg"><img class="scnshot" src="/images/v2-screen3.jpg" style="width: 100%" /></a></p>
</div>

<!-- <div style="clear: both; padding-top: 40px;"> -->

    <p style="display: none"><a class="fancybox" rel="screenshot" href="/images/v2-screen5.jpg"><img class="scnshot" src="/images/v2-screen5.jpg" style="width: 33%; float: left;" /></a></p>

    <p style="display: none"><a class="fancybox" rel="screenshot" href="/images/v2-screen4.jpg"><img class="scnshot" src="/images/v2-screen4.jpg" style="width: 33%; float: left;" /></a></p>

    <!-- <p><a class="fancybox" rel="screenshot" href="/images/v2-screen3.jpg"><img class="scnshot" src="/images/v2-screen3.jpg" style="width: 33%; float: left;" /></a></p> -->

<!-- </div> -->

<br  style="clear: both" />

<h3 style="font-size: 1.8em; font-weight: 400;">What's The Future Look Like</h3>
<p style="padding-top: 20px;">Winnie the Pooh said it best, "As soon as I saw you I knew a great adventure was going to begin." And, PageOneTwentySix paints that expression through the words of its writers. It's an adventure! We hope you come along for the journey. We know we're going to make some friends and share our stories along the way, but as for specifically what, well, that's for another day to talk about. Today, we're going to write.
</p>

<h3 style="font-size: 1.8em; font-weight: 400;">Looking for Inspiration</h3>
<p style="padding-top: 20px;">Follow our literary magazine on Twitter <a target="_twitter" href="http://twitter.com/nomoreblacktea">@nomoreblacktea</a> for #writingprompts or check out the website, <a target="nmbt" href="http://nomoreblacktea.com">http://nomoreblacktea.com</a> for #inspirational ideas and #rollandwrite and #redlightscratch challenges!
</p>

<?php if($_SESSION['_auth'] != 1) { ?>
<p style="padding-top: 40px; text-align: center">
<span class="button"><a href="/?">sign in & write</a></span>
</p>
<? } ?>

<!-- </div> -->

</div>
</div>