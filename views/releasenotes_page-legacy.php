<div id="writingareawrapper">

    <div id="whitebackrgba" class="padbottom">

<h3 style="margin-top: 35px;">release notes</h3>
<p class="padtop">PageOneTwentySix is like a great big piece of play doh constantly changing and this is the place where you can read about what we've done.</p>

<!--
<div id="hr"></div>

<a name="bugs"></a>
<h4 class="padtop">submit a bug or problem</h4>
	<ul class="ul-release_notes" style="margin-top: -25px;">

	<form id="form_reg" style="padding-top: 15px;" action="?func=send_mail" id="bug" method="post">
	<input type="hidden" name="no_captcha" value="1" />
	<input type="hidden" name="subject" value="BUG REPORT for PAGE126" />
	<input type="hidden" name="user_agent" value="<?= $_SERVER['HTTP_USER_AGENT'] ?>" />

	<p class="padtop">
	Email<br />
	<input type="text" name="email" size="10" value="<?= $_SESSION['email'] ?>" />
	</p>

	<p>Description of problem<br />
	<input type="text" name="message" id="message" size="10" value="<?= $_SESSION['msg'] ?>">
	</p>
-->

<!--
	<p>
	<input style="vertical-align: middle;" id="button_submit" type="image" src="/images/button_submit.png" onmouseover="javascript:image_swap('button_submit','1');" onmouseout="javascript:image_swap('button_submit',0);"/>
	</p>
-->

<!--
     <p style="padding-bottom: 0px;">
            <span class="button"><a id="button_login" href="#">Submit</a></span>
        </p>

	</form>
	</ul>
-->

<div id="hr"></div>
<h4 class="padtop">revision history</h4>
<p class="padtop">v1.0.9.2.2 : February 19, 2013</p>
	<ul class="ul-release_notes">
	<li> made shared (non-encrypted) default
	<li> removed date created to create cleaner look
	<li> bugfix: tags ajax now getting updated properly
	<li> removed most of homepage to make it more simplistic.
	<li> BUG: change password is broken, but that's ok because we are changing how that works.
	</ul>
</p>

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

<!--
<div id="hr"></div>

	<p class="padtop"><a href="?func=contact">Suggest a Feature/Feedback</a></p>
-->

    </div>
</div>