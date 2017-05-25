<?
#!/usr/local/bin/php
// include the config file

#0 * * * * php -e cron.php

print "---cron.php STARTED<br />";
print "started<br />";
$hour = date("G");
print "hour: $hour<br />";

$greetings = array(
	"What have you been up to today?",
	"Stop! and write",
	"Did you forget about writing today?",
	"Wow, how time flies, what did you do today.",
	"Tell us your secret today",
	"We won't say a thing",
	"Yep, go for it",
	"Start of story.",
	"The earth has music for those who listen.",
	"Some people feel the rain. Others just get wet.",
	"I'm going to smile today, don't forget why.",
	"Are you thinking of making some big changes?",
	"Did you get hit by a giant flying beach umbrella, tell us about it.",
	"It's time to dance in the rain",
	"Love thew new profile pic, you have a great smile",
	"You know that place between sleeping and awake, write about it",
	"Did you see a bird eating french fries today?"
	);

# aimee, user_id =15
$greetings_a = array(
	"I L Y & M Y my sweet Aimee",
	"My soul (J) has found yours (A) forever",
	"xoxo +xo",
	"Write about how you will -Come Home- to me",
	"Brooklyn, Brooklyn, take me in",
	"I'm California bound, just like you baby",
	"When was the last time you wrote anything here Aimee?",
	"You're the only exception my sunshine!",
	"Need You Now A.",
	"Write about one more picture perfect memory of You and I (A+J)",
	"Reaching for the phone because I can't stand it anymore - Don't write. Call me Aimee",
	"Aimee, I love you.",
	"You know who wrote this sweetheart, XOXO",
	"Talk to him tonight. Be strong. Have no fear for I am always with you.",
	"Take a walk on The Hill with me and write about what you feel"
	);
				
define('DB_NAME', 'pageonetwentysix');
define('DB_HOST', 'pasteboard.org');
define('DB_USER', 'pasteboard');
define('DB_PSWD', 'kandl3x+en1d');

$DBCON = mysql_connect(DB_HOST, DB_USER, DB_PSWD);	
$DBH = mysql_select_db(DB_NAME, $DBCON);
$sql = "select user_prefs.`pref_value`, user_prefs.`fk_user_id`, user.`first_name`, user.`last_name`, user.`email` FROM user, user_prefs where user_prefs.`pref_value` != '-' AND user.`id` = user_prefs.`fk_user_id` AND user_prefs.`pref_name` = 'email_reminder'";
$sql_result = mysql_query($sql,$DBCON);

if(mysql_num_rows($sql_result) > 0) 
{ 
	while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) 
	{
		
		# get the random subject line
		$oE = rand(0,3);
			
		if ($oE % 2 && $row['fk_user_id'] == '15') { // was 13 (for James)
			#is odd
			$rand_key = array_rand($greetings_a, 1);	
			$greeting_msg = $greetings_a[$rand_key];
			$foundA = TRUE;
			#$even_odd_seed = 'odd';
		} else {
			#is even
			$rand_key = array_rand($greetings, 1);
			$greeting_msg = $greetings[$rand_key];
			#$even_odd_seed = 'even';
		}
		
		#$greeting_msg = $greetings[$rand_key];
		
			# check the current hour == the preferred hour in user_prefs
			if($hour == $row['pref_value'])
			{
				$result .= "mail_at: " . $row['pref_value'] . "<br />";
				$to      = $row['email'];
				$subject = "It's " . date('D, F j, Y', time()) . " - " . $greeting_msg . "\n";
				$message = $row['first_name'] . ", this is your daily reminder to write!\n\nTake just 10 minutes right now and jot down something that's happened, something creative or whatever is on your mind right now!\n\nhttps://pageonetwentysix.com\n\n- - -\nTo stop these reminders, please login to your account and change your Email Reminder setting to 'none'.";
				$headers  = 'From: PageOneTwentySix <noreply@pageonetwentysix.com>' . "\r\n";
				
				mail($to, $subject, $message, $headers);
				$result .= "mailed: $to (" . $row['fk_user_id'] . ") $foundA<br />";
				$result .= "even_odd_seed: $oE<br />";
				$result .= "subject: $subject<br />";
				$result .= "---<br />";
			}
	}
}

mysql_close($DBCON);

print $result;
print "completed<br /><br />";
?>