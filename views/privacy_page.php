<div id="whitebackrgba" class="padbottom">

<h3>safeguarding your privacy</h3>
<p class="padtop">Creating an online space where your thoughts are kept super secret is equally as important as creating a distraction free writing environment. So we put our heads together and after many white board wipe downs we think we came up with a creative and safe solution.</p>

<p class="padtop">
Encryption and making sure that no one but you can access what you keep in your notebook is easier said than done. Let us quickly explain why.
</p>

<p class="padtop">
There are three parts to encrypting any data:
</p>

	<ul class="encrypt_process">
	<li> The algorithm, AES-256<br />
	<span style="font-size: 8pt; margin-left: 13px;">This stands for the <a target="_new" href="http://en.wikipedia.org/wiki/Advanced_Encryption_Standard" target="_new">Advanced Encryption Standard</a> <img src="images/link.png" alt="external_link" /> and is also used by the U.S. Government</span>
	<li> A secret key (this is not your password)</li>
	<li> The content being encrypted</li>
	</ul>

<h4 class="privacy">the challenge</h4>
<p>
For any site promising to keep your data for your eyes only, the challenge is keeping the "secret key" secret from everyone (including us) while also temporarily storing it so that encrypting/decrypting entries is as easy as logging in without requiring you to constantly re-type it. The following illustrates how we do it.
</p>

<div style="width:600px; margin-left: auto; margin-right: auto; margin-top: 10px;">
<img src="/images/encrypt_process.png" />
</div>

<p class="padtop">
When you log in, using your pageonetwentysix username and password, a 256-bit generated key is freshly created and temporarily stored <sup>1</sup>. This will let you read and write entries freely during your session <sup>2</sup>. Once you log out, or end your session by closing the browser <sup>3</sup>, your encrypted key is destroyed <sup>4</sup>.
</p>

<p class="padtop">
By encrypting your secret key with your pageonetwentysix account password, which is stored in our database as a one-way encrypted MD5 hash, it makes it extremely difficult for us, or anyone else to decrypt your entries without knowing what your password is. We can't even read your password in plain-text and is why we have to reset set it when you forget it (important note: you need to remember your secret key in order to do this, otherwise a reset can't be done).
</p>

<h4 class="privacy">Encrypted Entry Example</h4>
<p>
Your thoughts are written in the browser as plain readable text<sup>1</sup>. Anyone looking over your shoulder can read what you write, even if it is <a target="_new" href="http://www.lipsum.com/">lorem ipsum</a> <img src="images/link.png" alt="external_link" /> text. While writing, our system auto-saves your plain text which is filtered through an encryption algorithm (AES-256) and turned into a nerdy version<sup>2</sup> which makes no sense to anyone but the person who knows the secret key. <!-- which is also encrypted in our database with your pageonetwentysix.com password that is one-way encrypted so the only time your secret key can be accessed is when you log in; keeping it simple for you. -->
</p>

<div style="width:600px; margin-left: auto; margin-right: auto; margin-top: 10px;">
<img style="margin-top: 10px;" src="/images/enc_text.png" />
</div>

<h4 class="privacy">No Page Caching</h4>
<p>
In addition to encrypting your entries, passwords and secret phrase with the utmost diligence, we also employ the strictest rules in protecting your data from search engines. This includes the industry standard robots.txt file, X-Robots-Tag in our headers and Apache configuration as well as standard META tags in the HTML.
</p>

<h4 class="privacy">Dynamic Sessions</h4>
<p>
Each time you log in to your account a session is created. To help protect your data, your session ID is regenerated at random times while you are using pageonetwentysix.com. This ID is stored as a cookie on your local computer as well as temporarily on the server. Once you exit (or quit) your web browser the session ID is deleted.
</p>

<!--
<h4 class="privacy">SSL (Secured Socket Layer)</h4>
<div style="float: right">
<span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=wm93Lj6FV0ut6DItGIZZOHJB5AjZHJySlZUyCRCIqAzzsGs5JSK"></script></span>
</div>

<p style="padding-left: 10px;">
PageOneTwentySix is protected by GoDaddy SSL (Secure Socket Layer). You can read more about what SSL is at <a target="_new" href="http://en.wikipedia.org/wiki/Transport_Layer_Security">Wikipedia</a> <img src="/images/link.png" /> You can validate our certificate by clicking the image to the left, or by visiting <a target="_new" href="http://www.digicert.com/help/?host=pageonetwentysix.com">digicert</a> <img src="/images/link.png" />
</p>
-->

<div id="hr"></div>

<p class="padtop">Read our <a href="?func=privacy_policy">Privacy Policy</a> and <a href="?func=terms">Terms and Conditions of Use</a> </p>

</div>
