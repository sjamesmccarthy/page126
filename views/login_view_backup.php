<script type="text/javascript">
 
function image_swap(name,state) {
	if(state == 1) { var toggle = name + '_on'; }
	else { var toggle = name; }
	
	document[name].src='images/' + toggle + '.png';
}

function why_journal_switch(where)
{
	if(where == 'why')
	{
		document.getElementById('intro').style.display = 'none';
		document.getElementById('whyjournal_content').style.display = 'block';
		document.getElementById('whyjournal_div_x').style.display = 'block';
		document.getElementById('whyjournal_img').style.display = 'none';
		document.getElementById('welcome_img').style.display = 'block';
	}
	
	if(where == "back") 
	{
		document.getElementById('whyjournal_content').style.display = 'none';
		document.getElementById('intro').style.display = 'block';
		document.getElementById('whyjournal_div_x').style.display = 'none';
		document.getElementById('whyjournal_img').style.display = 'block';
	}	
} 

</script> 

<div id="inside_container">

		<? if($_SESSION['hint_sent'] == 1) { ?>
		<div id="error_box">
		<h3>notice</h3>
		<p>Your hint has been emailed to you at <?= $_SESSION['login_email']; ?></p>
		</div>
		<? } else if($_SESSION['resendcode'] == 1) 
		{ ?>
		<div id="error_box">
		<h3>notice</h3>
		<p>Your activation code has been emailed to you at <?= $_SESSION['login_email']; ?></p>
		</div>
		<? } else if($_SESSION['sudo_delete']  == 'failed') {
		?>
		<div id="error_box">
		<h3>failed</h3>
		<p>Your delete request has failed. Please check your email and try again.</p>
		</div>
		<? } unset($_SESSION['sudo_delete']); ?>
		<!-- resetpswd_confirm -->
		
		<div id="login_2c_left"> <!-- login_2c_left -->
		
		<div id="whyjournal_div">
		<a href="#" onclick="why_journal_switch('why');" ><img id="whyjournal_img" name="whyjournal" onmouseover="javascript:image_swap('whyjournal','1');" onmouseout="javascript:image_swap('whyjournal',0);" src="images/whyjournal.png" border="0" /></a>
		</div>
		
		<div id="whyjournal_div_x">
		<a href="#" onclick="why_journal_switch('back');" >
		<img id="whyjournal_x" name="whyjournal_x" onmouseover="javascript:image_swap('whyjournal_x','1');" onmouseout="javascript:image_swap('whyjournal_x',0);" src="images/whyjournal_x.png" />
		</a>
		</div>
		
			<div id="intro">
			<!-- <h3>write</h3> -->
								
			<h2>
			<?
				global $greetings;
				$rand_key = array_rand($greetings, 1);
				print $greetings[$rand_key];
			?>
			</h2>
			
			<p class="mediumsmall padtopextra">
			Start journaling online - explore and capture your creative writing or day to day thoughts in a <a href="?func=privacy">safe and secure place</a> <!-- Breathe life into those words while they're still teasing your heart or just keep a journal and save them for later.--> where you can tuck them away for safe keeping tomorrow or breathe life into your story today.</p> 
			
			<p class="mediumsmall padtop"><!-- Everyone gets the same promise of <a href="?func=privacy">privacy</a>, and, when the time is right, you can have --> Unlike other online journaling sites there is no need to <i>Go Pro</i>. Our <a href="?func=upgrade">more complicated</a> features, <!-- that you won't be able to live without like --> including multiple journals, auto-saving, keyboard shortcuts, <a href="/?func=upgrade">& more</a> are free!
			</p>
			
			<p class="medium padtop"><a href="#" onclick="why_journal_switch('why');" >Why journal?</a></p>
			<p class="small" style="line-height: 12pt;">In the book of life, this is your page - write it</p>
			</div><!-- /intro -->
			
			<div id="whyjournal_content">
						
			<!-- include free SNIP -->
			<? insert_snip('why_journal.php'); ?>
	
			</div>
			
		</div> <!-- /login_2c_left -->
		
		<div id="login_2c_right"> <!-- login_2c_right -->
			
			<div id="login_area"><!-- login_area -->
			<? insert_snip('login_form.php'); ?>
			</form>
			
			<p class="smallest padtop">If you don't have an account yet, sign-up for one!</p>
				
			
				<div id="signup_area">
				<img class="signup_btn_img" src="/images/greybtn.png" />
					
					<div class="signup_btn_link">
					<a href="?func=create"> Create an account &raquo; </a>
					</div>
	
					<div class="sign_btn_link_normalize">
					<p>
					We're always making changes, read about some of the new
					<a href="?func=change_log">features</a> available.
					</p>
					</div>
				
					<div class="fbshare">
					<a target="_new" href="http://www.facebook.com/pages/pageonetwentysix/111046178942989"><img id='badge_fb' name='badge_fb' src='/images/badge_fb.png' onmouseover="javascript:image_swap('badge_fb','1');" onmouseout="javascript:image_swap('badge_fb',0);" border="0" /></a>
					<a target="_new" href="http://www.twitter.com/pageonetwenty6"><img id='badge_twitter' name='badge_twitter' src='/images/badge_twitter.png' onmouseover="javascript:image_swap('badge_twitter','1');" onmouseout="javascript:image_swap('badge_twitter',0);" border="0" /></a>
		
					</div>

				</div><!-- /signup_area -->
			</form>
			
			</div><!-- /login_area -->

		</div><!-- /login_2c_right -->
		
		<div id="clear"></div>
				
		<div class="padtopextra">		
		<img src="/images/godaddy_ssl.png" style="float: left; padding: 10px;" />
		
		</div>
		<p class="small" style="color:#333333; margin-top: 23px;">
		Full circle security - browser to server (SSL) and server to database (AES-256) your personal information is encrypted and safe. More about <a href="?func=privacy">safeguarding your privacy.</a>
		</p>

</div>
		<? 
		session_destroy();
		?>