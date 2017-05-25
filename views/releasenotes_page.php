<?php
setcookie('test', 'This is a test', time() + 3600);
if(isset($_COOKIE['test'])){
$cookieSet = 'The cookie is ' . $_COOKIE['test'];
} else {
$cookieSet = 'No cookie has been set';
}

//echo $cookieSet;
?>


<script>

 $(document).ready(function()
 {

    $(":input").focus(function() {
       $(":input").addClass('form-whiteback-rgba');
    });

     $(":input").blur(function() {
       $(":input").removeClass('form-whiteback-rgba');
    });

	setTimeout(function() {
	    $('#error_box').fadeOut('10000');
	}, 3000);

    $('#message').focus(function() {
        $('#humancheck_field').fadeIn();
    });

    // $('#message').focus(function() {
    //    $('#message').css('background-color','#FFF');
    // });

    // $('#humancheck').focus(function() {
    //    $('#humancheck').css('background-color','#FFF');
    // });

    $('#button_bugreport').click(function(e) {
        e.preventDefault();

        if( $('#message').val() != "" ) {

            if( $('#humancheck').val() == '126' ) {
                $('#form_bugreport').submit();
            } else {
                $('#humancheck').css('background-color','rgba(210,0,0,.3)');
            }

        } else {
            $('#message').css('background-color','rgba(210,0,0,.3)');
        }

    });

});

</script>

<div id="whitebackrgba" class="padbottom">

<?php
    if($_SESSION['mail_sent'] == 1) {
?>

<div id="error_box">
	<h3>bug report sent</h3>
	<p class="padtop">thank you for taking the time to report a bug.</p>
</div>

<?php } ?>

    <div id="writingareawrapper" class="section_blue section_blue_padding">

<h3 style="font-weight: 300;">release notes</h3>
<p class="padtop">PageOneTwentySix is like a great big piece of play doh constantly changing it's shape and sometimes color. This is the place where you can read about what we've done.</p>


<div id="hr"></div>

<?php if( !isSet($_SESSION['mail_sent']) ) { ?>

<!-- <a name="bugs"></a>
<h4 class="padtop" style="font-weight: 300;">submit a bug or problem</h4>

	<form id="form_bugreport" style="padding-top: 15px;" action="/?func=send_mail" id="bug" method="post">
	<input type="hidden" name="no_captcha" value="1" />
	<input type="hidden" name="subject" value="BUG REPORT for PAGE126" />
	<input type="hidden" name="user_agent" value="<?= $_SERVER['HTTP_USER_AGENT'] ?>" />

    <?php if($_SESSION['_auth'] == '1') {
        print '<input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '" />';
        print '<input type="hidden" name="login_email" value="' . $_SESSION['login_email'] . '" />';
    }
    ?>

	<p>
	<input type="text" name="message" id="message" size="10" placeholder="description of the bug">
	</p>

	<p id="humancheck_field">
    prove you're human. type the answer to what (twenty six + 100) is below:<br />
    <label>captcha</label>
    <input type="text" name="humancheck" id="humancheck"  placeholder="your answer" />
    </p>

     <p style="padding-bottom: 0px;">
            <span class="button"><a id="button_bugreport" href="#">Submit</a></span>
        </p>

	</form>

 -->
<!-- <div id="hr"></div> -->

<?php } ?>

<p class="padtop">v2.0.9 : February 6, 2015</p>
	<ul class="ul-release_notes">
	<li> Bugfix: Adding a new page redirect loop fixed.</li>
	<li> Added mobile web version (reader only)</li>
	<li> Profile bio now limited to 45 characters</li>
	</ul>
</p>

<p class="padtop">v2.0.8 : January 26, 2015</p>
	<ul class="ul-release_notes">
	<li> Bugfix: privacy/security issue when viewing other profiles after logging in.</li>
	<li> Added comment badge on shared listings for homepage and dashboard</li>
	<li> Added dashboard as new login landing page</li>
	<li> Added rough newsfeed in prep for followers and following</li>
	<li> Bugfix: redirecting to home/login page after session expires fixed.</li>
	</ul>
</p>

<p class="padtop">v2.0.7 : January 25, 2015</p>
	<ul class="ul-release_notes">
	<li> Changed default page list on login to show folder list</li>
	<li> Added X icon to either close the writing are or logout</li>
	<li> Centered title on writing area and made .2em larger than body font</li>
	<li> Added title into Markdown preview screen</li>
	<li> Removed the gray text on blur in writing area</li>
	<li> Add margin-right: 20px to last toolbar item</li>
	<li> bugfix in comment count in comment icon in toolbar</li>
	<li> bugfix in writing area font-size session data not reading properly</li>
	</ul>
</p>

<p class="padtop">v2.0.6 : January 19, 2015</p>
	<ul class="ul-release_notes">
	<li> Added API support to fetch single pages from your account and show on your own website.<br />     Hover over the current document icon in the toolbar to see the URI.</li>
	<li> Added Public/Private page toggle, Move to trashto the toolbar.	</li>
	<li> Reduced size of toolbar icons to accomodate more and changed the tooltip behavior and font-size.</li>
	</ul>
</p>

<p class="padtop">v2.0.5 : January 16, 2015</p>
	<ul class="ul-release_notes">
	<li> Bugfix: blue shadow lines around input boxes in Chrome fixed in login, tag fields</li>
	<li> Changed tag icon in taglist to be smaller</li>
	<li> Added support for markdown preview in editor and public pages. </li>
	<li> Changed look and feel a little bit by adding blue background.</li>
	<li> Updated user public pages to be a little more responsive, still lots of work needed.</li>
	</ul>
</p>

<p class="padtop">v2.0.4 : December 28, 2014</p>
	<ul class="ul-release_notes">
	<li> Bugfix: blue glow around input fields in Chrome fixed.</li>
	<li> Bugfix: public profile now doesn't show on locked accounts. fixed with warning message.</li>
	<li> Bugfix: disabled the public profile only followers options until follower implementation.</li>
	<li> Updated header to be sticky (or fixed position top).</li>
	<li> Updated account locking process and notification +disabling of public profile.</li>
	</ul>
</p>

<p class="padtop">v2.0.3 : July 8, 2014</p>
	<ul class="ul-release_notes">
	<li> Bugfix: default journal was not being set on account creation</li>
	<li> Bugfix: font-size preference not being displayed in settings properly</li>
	<li> Added counts for page notes and comments when deleting your account</li>
	<li> Bigfix: settings validation, when entering a username, use public profile is now auto-checked</li>
	</ul>
</p>

<p class="padtop">v2.0.2 : June 28, 2014</p>
	<ul class="ul-release_notes">
	<li> Added "Quick Notes" for all pages</li>
	<li> Updated additional code for cleaner URI cleanup from existing 2010 build</li>
	<li> Added visual feedback icon in lower left corner of the write page when autosaving</li>
	</ul>
</p>

<p class="padtop">v2.0.1 : June 21, 2014</p>
	<ul class="ul-release_notes">
	<li> Updated home page</li>
	<li> Modified activation process</li>
	<li> Bugfix: in comments view</li>
	<li> Added floating labels (tooltips) to navbar items</li>
	</ul>
</p>

<p class="padtop">v2.0.0 : June 5, 2014</p>
	<ul class="ul-release_notes">
	<li> Version 2.0 launch</li>
	<li> Added Public profile page</li>
	<li> Added Comments for public pages</li>
	<li> Added short url's for /about, /settings, /logout, /pages, etc.</li>
	<li> New UI/UX</li>
	</ul>
</p>

<!-- legacy -->
<div id="legacy" style="display: none;">

<p class="padtop">v1.0.9.2.2 : February 19, 2013</p>
	<ul class="ul-release_notes">
	<li> made shared (non-encrypted) default
	<li> removed date created to create cleaner look
	<li> bugfix: tags ajax now getting updated properly
	<li> removed most of homepage to make it more simplistic.
	<li> BUG: change password is broken, but that's ok because we are changing how that works.
	</ul>
</p>

<div class="old">

<p class="padtop">v1.0.9.2.1 : February 19, 2011</p>
	<ul class="ul-release_notes">
	<li> bugfix: export to CSV now works &saves with journal name prefix
	<li> bugfix: added session_timeout after 3 hours; do not leave the writing page open (buggy)
	<li> Added a 1 hour inactivity timer - you will be prompted from more time or logged out
	</ul>
</p>

<p class="padtop">v1.0.9.2 : February 11, 2011</p>
	<ul class="ul-release_notes">
	<li> Modified some setting preferences after logging in
	<li> Minor UI/button changes on various pages
	</ul>
</p>

<p class="padtop">v1.0.9.1 : February 8, 2011</p>
	<ul class="ul-release_notes">
	<li> Backup Journals Individually (through journal details) or All (through Settings)
	<li> Added Delete Journal password confirmation
	<li> Added trash/word counts to pages index
	<li> You can now change creation date for each page by clicking the date link
	<li> Added password strength check
	</ul>
</p>

<p class="padtop">v1.0.9 : February 7, 2011</p>
	<ul class="ul-release_notes">
	<li> New slogan banner
	<li> Last update date on journals added
	<li> Added more to come page + donation
	<li> Minor changes to create account page
	<li> Reactivated keyboard shortcuts (may be be buggy on WinPCs)
	<li> Added links to social memes and twitter account
	<li> Updated buttons to something more cool
	<li> Added journal details (pages, trashed, words, last update)
	<li> Modified the "hide" screen when toggling during writing
	<li> Reactivated Delete Journal feature
	</ul>
</p>

<p class="padtop">v1.0.8.9 : February 2, 2011</p>
	<ul class="ul-release_notes">
	<li> Removed change journals from pages index (use link in top nav)
	<li> Cleaned up CSS for link colors
	<li> Shortened Create Account form (merged name fields)
	<li> Made delete account 1 step (no more confirmation)
	<li> New homepage design (updated header)
	</ul>
</p>

<p class="padtop">v1.0.8.8 : February 1, 2011</p>
	<ul class="ul-release_notes">
	<li> Changes to some options under Settings (click username)
	<li> Added Google Checkout for Donation
	<li> Removed filtering on page index
	<li> Implemented Google Web Fonts
	</ul>
</p>

<p class="padtop">v1.0.8.7 : October 27, 2010</p>
	<ul class="ul-release_notes">
	<li> Create "new journal" functionality restored
	<li> Unlabeled stats on "manage journals" page for entries, trashed entires, last updated now available
	<li> Disabled keyboard shortcuts (too buggy on Mac OS X)
	</ul>
</p>

<p class="padtop">v1.0.8.6 : October 19, 2010</p>
	<ul class="ul-release_notes">
	<li> Restore trashed entries
	<li> New "manage journals" page started (not fully functional)
	<li> Option to "encrypt" entry or save as plain "non-encrypted" text
	<li> Added javascript confirm when moving entry to trash via link or keyboard shortcut (ctrl-d)
	</ul>
</p>

<p class="padtop">v1.0.8.5 : October 4, 2010</p>
	<ul class="ul-release_notes">
	<li> Background images are now preloaded
	<li> Session IDs are not regenerated with each page load
	<li> Misc. changes to link colors and print-view
	<li> Encryption of entries can be toggled by backend system for future features
	</ul>
</p>

<p class="padtop">v1.0.8.4 : September 29, 2010</p>
	<ul class="ul-release_notes">
	<li> Added favorite and encrypted icons to the pages listing
	<li> Added save&close jscript to all exiting links on writing page
	<li> Added "print" option on entry page
	<li> Added "top" next to word count for quick access to top of page
	<li> Create DB structure for sharing entries
	<li> bugfix: when trashing and emptying trash tags are handled properly
	<li> bugfix: corrected tag_type from ENCRYPTED to PRIVATE in DB
	</ul>
</p>

<p class="padtop">v1.0.8.3 : September 27, 2010</p>
	<ul class="ul-release_notes">
	<li> Added "you have written" on "pages" listing (minor)
	<li> Added "pages" link to top navigation links/ changed settings to link with "username" instead
	<li> Changed "back to writing" to "write" (minor)
	<li> Removed "pages" link from footer bar; to be replaced later with something else
	<li> Add "favorites" structure to the DB:entries
	<li> Added "updated" structure to the DB:journals
	<li> Fixed IE bar (shortened image to allow for dropped logo area)
	<li> Changed clicking on logo behavior to link to "pages" listing
	<li> bugfix: clicking logout from writing page now properly saves & closes
	</ul>
</p>

<p class="padtop">v1.0.8.2 : September 21, 2010</p>
	<ul class="ul-release_notes">
	<li> Changed daily email subject to: It's [day], [date] - [random msg]
	<li> Made the "creation date" in long format [Fri, August 12, 2010]; no longer can edit
	<li> Re-enabled the "nothing to see here box" when hiding entry writing area
	<li> Disabled "pretty" switch journal selects in "table of contents" and entry writing area.
	<li> Included number of "pages created" on "table of contents" listing
	<li> Changed "table of contents" link on trash view to "pages"; added cancel button
	<li> bugfix: clicking hide entry writing are icon hides and re-displays properly
	<li> bugfix: IE7/8 bad password not rendering properly
	</ul>
</p>

<p class="padtop">v1.0.8.1 : August 4, 2010</p>
	<ul class="ul-release_notes">
	<li> minor style changes (animated notice box, home & write icons in header logo)
	<li> changed "entries" label to "pages"
	<li> changed "table of contents" label to "pages"
	<li> changed font color to darker gray on writing page
	<li> bugfix: auto-expand of writing increased
	</ul>
</p>

<p class="padtop">v1.0.8 : August 1, 2010</p>
	<ul class="ul-release_notes">
	<li> new, super-slim header design and navigation links moved to standard top-right
	<li> Password hint is not shown by default in settings page
	<li> Changed style of "journal" select boxes on index & entry pages
	<li> Name of journal to "Back Up" is now displayed in settings page
	</ul>
</p>

<p class="padtop">v1.0.7 : July 26, 2010</p>
	<ul class="ul-release_notes">
	<li> New tagging design	(click the Add Tag icon in writing area)
	<li> Set a daily reminder to write via email
	<li> Added small "close x" box in writing toolbar to exit to entry list
	<li> Added random "welcome" phrases on homepage
	<li> Password no longer required to update settings, unless changing password
	<li> Redesigned toolbar for hopefully better use
	<li> Added main nav-links (user, settings, logout) to header
	</ul>
</p>

<p class="padtop">v1.0.6.1 : July 13, 2010</p>
	<ul class="ul-release_notes">
	<li> bugfix: fixed console.log for non-firebug installed browsers such as Firefox.</li>
	<li> beta: enabled "email me daily reminder at" preference. (<a href="#bugs">report bugs</a>)</li>
	</ul>
</p>

<p class="padtop">v1.0.6 : July 12, 2010</p>
	<ul class="ul-release_notes">
	<li> bugfix: after creating new account and logging in, new entry page is displayed</li>
	<li> bugfix: default background color is restored when logging in.</li>
	<li> bugfix: adding new preferences that didn't previously exist in database.</li>
	</ul>
</p>

<p class="padtop">v1.0.5 : July 11, 2010</p>
	<ul class="ul-release_notes">
	<li> Complete rewrite of how users preferences are stored</li>
	<li> Started "email me daily reminder to write" preference ** (started)</li>
	<li> Switching journals from "Manage Journal" view now loads journal entries for that journal</li>
	<li> Complete rewrite of how tags are stored and displayed* (started)</li>
	<li> After logging out, login page loads after two seconds</li>
	</ul>
</p>

<p class="padtop">v1.0.4 : July 5, 2010</p>
	<ul class="ul-release_notes">
	<li> Minor database modifications</li>
	<li> jQuery installed</li>
	<li> Autosave while writing implemented (see <a href="?func=help">help page</a>)</li>
	<li> Autoresize of entry form while typing added</li>
	<li> HOT-keys for saving, hiding, etc. added (see <a href="?func=help">help page</a>)</li>
	<li> When hiding/showing entry area, smooth slide animation added</li>
	<li> Warns when closing window or browser tab</li>
	<li> Help additions: shortcut keys, sessions, and autosave (see <a href="?func=help">help page</a>)</li>
	<li> bugfix: default theme now correctly chosen by default</li>
	<li> bugfix: multiple line breaks in "email to yourself"</li>
	<li> bugfix: tag count recalculates after deleting tags </li>
	<li> bugfix: csv export header saved correctly</li>
	<li> bugfix: tab out of text area no longer replaces text with "1"</li>
	</ul>

<p class="padtop">v1.0.3 : June 24, 2010</p>
	<ul class="ul-release_notes">
	<li>SSL certificate installed</li>
	<li>Backend database modifications for tags and user preferences</li>
	<li>Removed Twitter and Facebook badges to make site 100% SSL compliant</li>
	<li>https is forced on page load and www is redirected</li>
	<li>New Facebook & twitter links on homepage</li>
	<li>bugfix: word count counter</li>
	<li>bugfix: toolbar icons now aligned for "simple" users</li>
	<li>bugfix: multiple line breaks in email entry to self fixed</li>
	</ul>

<p class="padtop">v1.0.2 : June 18, 2010</p>
	<ul class="ul-release_notes">
	<li>Entry list filters</li>
	<li>Divider lines for toolbar block on entry page</li>
	<li>Writing area now automatically expands height while typing</li>
	<li>Added help questions about multiple journals</li>
	<li>Added manage journals view to settings page</li>
	<li>New copy for "why journal" & added twitter feed</li>
	<li>bugfix: tag count no longer defaults to 1 after editing</li>
	</ul>

<p class="padtop">v1.0.1 : May 28, 2010</p>
	<ul class="ul-release_notes">
	<li>Multiple journal support.</li>
	<li>bugfix: journal_id was being stored incorrectly in database</li>
	</ul>

<p class="padtop">v1.0.0 : May 16, 2010</p>
	<ul class="ul-release_notes">
	<li>Initial release.</li>
	<li>Base feature set for free user; "more complicated" tools in development.</li>
	</ul>
</div>

</div> <!-- legacy -->

<!--
<div id="hr"></div>

	<p class="padtop"><a href="?func=contact">Suggest a Feature/Feedback</a></p>
-->

    </div>
</div>

<? 	unset($_SESSION['mail_sent']); ?>
