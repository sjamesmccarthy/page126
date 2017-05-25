<?php 
if ( ! defined('APP-START')) exit('No direct script access allowed');

/**
 * @FILE		journal_model.php
 * @DESC		stand-alone proto-type app
 * @PACKAGE		PASTEBOARD
 * @VERSION		1.0.0
 * @AUTHOR		James McCarthy
 * @EMAIL		james.mccarthy@gmail.com
 
 */

function _auth()
{
	global $data;
	
	#$_SESSION['login_email'] = $_POST['login_email'];
	#$_SESSION['login_password'] = $_POST['login_password'];
	
	_dbopen();
	$sql = "SELECT * from user user where email ='" . $_POST['login_email'] . "' AND password ='" . md5($_POST['login_password']) . "' AND v_code='1'";
	$sql_result = mysql_query($sql,$data['CON']);
	$data['COUNT_ROWS'] = mysql_num_rows($sql_result);
	_dbclose();
	
	if($data['COUNT_ROWS'] > 0) 
	{ 
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		foreach ($row as $key=>$value) 
			{ 
				$data[$key] = $value;
				#print "$key => $value <br />";
			}
  		}
		
		$_SESSION['first_name'] = $data['first_name'];
		$_SESSION['last_name'] = $data['last_name'];
		$_SESSION['login_email'] = $data['email'];
		$_SESSION['fk_journal_id'] = 1;
		$_SESSION['user_id'] = $data['id'];
		$_SESSION['SK'] = $data['secret_phrase'];
		#$_SESSION['SK_c'] = _encrypt_SK($_POST['login_password'], $_POST['login_email']);
		$_SESSION['SK_c'] = $_POST['login_password'];
		$_SESSION['pro'] = $data['pro_user'];
		$_SESSION['tz'] = $data['timezone'];
		
		#print "<pre>";
		#print_r($_SESSION);
/*
Array
(
    [first_name] => James
    [last_name] => McCarthy
    [login_email] => james.mccarthy@gmail.com
    [fk_journal_id] => 1
    [user_id] => 38
    [SK] => 5œ+€¥1U–»z?XïÙŸ«¯ähIï}í¤FŸ?j
    [SK_c] => my4girl$
    [pro] => 0
    [tz] => America/Los_Angeles
)
*/
		
  		return(TRUE);
	} else {
		_lookuphint();
		return(FALSE);
	}
}

function _main($id=NULL)
{
	global $data;
	
	if(is_null($id)) 
	{
		if(_get_last_view() == TRUE)
		{
			_get_view($data['id']);
		} else {
			_create_entry();
			_get_last_view();
			_get_view($data['id']);
		}	
	} else {
		_get_view($id);
	}
}

function _get_view($id)
{
	global $data;
	
	_dbopen();
	$sql = 'SELECT * FROM entry WHERE id = ' . $id . ' LIMIT 1';
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	if(mysql_num_rows($sql_result) > 0)
	{
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) 
		{
			foreach ($row as $key=>$value) 
			{ 
				$data[$key] = $value;
			}
		}			
			if(!empty($data['title'])) $data['title'] = _decrypt($data['title']);
   			if(!empty($data['content'])) $data['content'] = _decrypt($data['content']);
	}	
	
	return($id);
}

function _get_last_view()
{
	global $data;

	_dbopen();
	$sql = "SELECT * FROM entry WHERE created >= CURRENT_DATE() AND fk_user_id ='" . $_SESSION['user_id'] . "' ORDER BY created DESC LIMIT 1";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

	if(mysql_num_rows($sql_result) > 0)
	{
		$found = TRUE;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) 
		{
			$data['id'] = $row['id'];
		}
	} else {
		$found = FALSE;
	}
	
	$_SESSION['c_id'] = $data['id'];
	return($found);
}

function _get_entries_list($filter='TODAY')
{
	// switch
	// ALL, DAY, WEEK, MONTH, SEARCH, RANGE, TRASH(ALL) | DEFAULT=DAY
	
	global $data;
	
	_dbopen();
	$sql = "SELECT id, title, last_modified, word_count FROM entry WHERE fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY last_modified DESC";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	if(mysql_num_rows($sql_result) > 0)
	{
		$i=0;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
		
			#if($i % 2) { 
			#$bgcolor = "bgcolor=\"#333333\"";
			#} else { 
			#$bgcolor = "bgcolor=\"#CCCCCC\"";
			#}
	
			$results .= "<!-- entry -->";
			$results .= "<tr id=\"entry_row\" $bgcolor align=\"left\">";
			$results .= "<td width=\"250\"><a class=\"entry_row\" href=\"?func=main&id=" . $row['id'] . "\">" . _decrypt($row['title']) . "</a></td>";
			$results .= "<td width=\"60\" align=\"left\">" . $row['word_count'] . " words</td>";
			$results .= "<td width=\"100\">" . date("m/d/y @ g:i a", strtotime($row['last_modified'])) . "</td>";
			// $results .= "<td width=\"50\">- - -</td>";
			$results .= "</tr>";
			$results .= "<!-- entry -->";
			
			$i++;
		}
	}
		$data['results'] = $results;
		
	return($data);
	
	// SELECT * FROM entries WHERE fk_user_id=$_SESSION['user_id']
	// select * from dt_tb where `dt` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) | -1 (yesterday) 
	// select COUNT(*) AS rows from Orders WHERE YEARweek(ODate) = YEARweek(CURRENT_DATE - INTERVAL 7 DAY)  (last week)
	// WHERE YEAR(date) = 2008 AND month(date) = 12  (last month)
	// WHERE YEAR(date) = 2010  (last year)
	
	// SEARCH BY TAG -> WHERE tags LIKE '%{$tag}%'
	// SEARCH BY DATE -> just enter the date, ex. 2-23-10
	
	// return $data
}

function _create_entry()
{
	global $data;
	$date = date('Y-m-d H:m:s');
	_dbopen();
	$sql = "INSERT INTO entry (fk_journal_id, fk_user_id, created, last_modified) VALUES ('" . $_SESSION['fk_journal_id'] . "', '" . $_SESSION['user_id'] . "','$date','$date')";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
}

function _save()
{   
    global $data;
	
	/* Create scalar variables from $_POST array */
   	extract($_POST, EXTR_PREFIX_SAME, "dup_");
   	
	/* Encrypt the title and content fields */
	$enc_title = _enrypt($title);
   	$enc_content = _enrypt($content);
   	
	/* Reformat date string */
	$last_modified = date('Y-m-d H:m:s', strtotime($last_modified));
	
    _dbopen();
	$sql = "UPDATE entry SET title='" . $enc_title . "', tags='" . $tags . "', content='" . $enc_content . "', word_count='" . $w_count . "', last_modified='" . $last_modified . "' WHERE id='" . $id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	return($id);
}

function _trash_entry()
{
	global $data;
	_dbopen();
	
	/* Move the record to the trash */
	$sql = "INSERT INTO entry_trash (id,fk_journal_id,fk_user_id,title,content,tags,word_count,created,last_modified) SELECT * FROM entry WHERE id='" . $_GET['id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	/* Delete it from the main entry table */
	$sql = "DELETE FROM entry WHERE id='" . $_GET['id'] . "' AND fk_user_id='" . $_SESSION['user_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
}

function _empty_trash()
{
	global $data;
	
	if($_POST['takeitout'] == 1)
	{	
		_dbopen();
		$sql = "DELETE FROM entry_trash WHERE fk_user_id='" . $_SESSION['user_id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		$_SESSION['trashed'] = 1;
	} else {
		$_SESSION['trash_confirm'] = 1;
	}
}

function _delete_from_trash()
{
	// this will permanently remove trash
	// DELETE FROM entry_trash WHERE id='{$id}'
}

function _delete_account_confirm()
{
	global $data;
	$d_code = time();
	
	/* validate user/password/delete_code */
	$sql = "SELECT * from user where password ='" . md5($_POST['login_password']) . "' AND v_code='" . $_POST['d_code'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	if(mysql_num_rows($sql_result) > 0)  
	{
		/* change account status and v_code */
		_dbopen();
		$sql = "UPDATE user SET v_code='" . $d_code . "', locked='1' WHERE id='" . $_SESSION['user_id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		_dbclose();
		
		/* send email to person that registered */
		$headers  = 'From: editorial@pageonetwentysix <no-reply@pageonetwentysix.com>' . "\r\n";;
		$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
		$headers .= 'Mime-Version: 1.0' . "\r\n";
		
		$to = $_SESSION['login_email'] . "\r\n";
		$subject = "Account DELETION Request" . "\r\n";
		$message  = "We're sorry to see that you want to leave. You are almost done. If you haven't already done it, please log back into your account and BACKUP your data. There is a link in the settings that does this for you.\n\nTo DELETE EVERYTHING visit the URL below and enter the following information.\n\n";
		$message .= "Click this link to DELETE your account: \n\thttp://www.pageonetwentysix.com/?func=sudoseeya\n\n";
		$message .= "\t username: " . $_SESSION['login_email'] . "\n";
		$message .= "\t password: [you know this]\n";
		$message .= "\t delete code: " . $d_code . "\n\n";
		$message .= "We require this additional step to make sure that you really want to delete your account, because it really will delete everything and can not be undone or restored.\n\n";
		$message .= "We're sorry to see you leave!\n\n";
		
		mail($to, $subject, $message, $headers);
		
		$_SESSION['delete_confirm'] = 'true';
		$view = 'delete_view.php';
	} else {
		$_SESSION['delete_confirm'] = 'failed';
		$view = 'logout_view.php';
	}
	
}

function _sudoseeya()
{
	global $data;
	_dbopen();
	
	// validate user/password/delete_code
	$sql = "SELECT * from user where email ='" . $_POST['login_email'] . "' AND password ='" . md5($_POST['login_password']) . "' AND v_code='" . $_POST['d_code'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	if(mysql_num_rows($sql_result) > 0) 
	{ 
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) 
		{
  			$id = $row['id'];
			$email = $row['email'];
  		}
	} else {
		$_SESSION['sudo_delete'] = 'failed';
		_redirect('?func=login');
	}
	
	// DELETE FROM entry where fk_user_id='{$id}'
	$sql = "DELETE FROM entry WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	// DELETE FROM entry_trash where fk_user_id='{$id}'
	$sql = "DELETE FROM entry_trash WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	// DELETE FROM journal where fk_user_id='{$id}'
	$sql = "DELETE FROM journal WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	// DELETE FROM user where fk_user_id='{$id}'
	$sql = "DELETE FROM user WHERE id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	// div blocks that are orange with red X next to description as show above
	// SUCCESS - you have been deleted -> return to logout w/notice (sorry to see you leave)
	
	_dbclose();
	$_SESSION['sudoseeya'] = 1;
	
	/* EMAIL to login_email that your account is gone; no going back; sorry. */
	$headers  = 'From: editorial@pageonetwentysix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	$headers .= 'Mime-Version: 1.0' . "\r\n";
	
	$to = $email . "\r\n";
	$subject = "Account Deleted" . "\r\n";
	$message  = "Your account and all data associated with your email and user ID has been deleted.";
	mail($to, $subject, $message, $headers);
}

function _get_user()
{
	_dbopen();
	$sql = "SELECT * from user where email ='" . $_POST['login_email'] . "' AND password ='" . md5($_POST['login_password']) . "' AND v_code=1";
	$sql_result = mysql_query($sql,$data['CON']);
	$data['COUNT_ROWS'] = mysql_num_rows($sql_result);
	_dbclose();
		
	if($data['COUNT_ROWS'] > 0) 
	{ 
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) 
		{
  			$_SESSION['user_id'] = $row['id'];
  		}
  		return(TRUE);
	} else {
		return(FALSE);
	}
}

function _encrypt($text)
{

	
	$key = _decrypt_SK($_SESSION['SK']);
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);            
	$encrypted = mcrypt_generic($td, $text);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$encrypted = addslashes($encrypted);  
		
    return($encrypted);
}

function _encrypt_SK($plain_key, $alt_key=NULL)
{
	
	if($alt_key == NULL)
	{
		$key =  $_POST['password'];
	} else {
		$key =  $_POST['login_email'];
	}
	
	#$key =  $_POST['password'];
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);            
	$encrypted = mcrypt_generic($td, $plain_key);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$encrypted = addslashes($encrypted);   
	
    return($encrypted);
}

function _decrypt($encrypted, $alt_key=NULL)
{
	
	$key = _decrypt_SK($_SESSION['SK']);
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$decrypted = mdecrypt_generic($td, $encrypted);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);

	return($decrypted);    
}

function _decrypt_SK($encrypted_key, $alt_key=NULL)
{	
	
	$key= $_SESSION['SK_c'];
	$encrypted = $_SESSION['SK'];
	
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$decrypted_key = mdecrypt_generic($td, $encrypted_key);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td); 
	
	return($decrypted_key);    
}

function _create_new_user()
{		
	global $data;
	$date = date('Y-m-d H:m:s');
	$v_code = $_POST['new_user'];
	
	/* Create a md5 hash of the password for database */
	$pswd_md5 = md5($_POST['password']);
	
	/* Encrypt the secret phrase with plain text password for database */
	$secret_key_plain = $_POST['secret_phrase'];
	$_POST['secret_phrase'] = _enrypt_SK($_POST['secret_phrase']);	
	
	_dbopen();
	
	/* Check if username exists */
	$sql = "SELECT * from user where email ='" . $_POST['email'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	if(mysql_num_rows($sql_result) > 0) 
	{
		$_SESSION['email_exists'] = 1;
		return('create_page.php');
	} else {
	
	$sql = "INSERT INTO user (first_name, last_name, email, password, password_hint, secret_phrase, created, last_login, pro_user, pro_exp, act_lock, v_code) VALUES ('" . $_POST['first_name'] . "','" . $_POST['last_name'] . "','" . $_POST['email'] . "','" . $pswd_md5 . "','" . $_POST['password_hint'] . "','" . $_POST['secret_phrase'] . "','" .  $date . "','" . $date . "', '0', '0000-00-00 00:00:00', '0', '" . $v_code . "')";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	/* send email to person that registered */
	$headers  = 'From: editorial@pageonetwentysix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	$headers .= 'Mime-Version: 1.0' . "\r\n";
	
	$to = $_POST['email'] . "\r\n";
	$subject = "Account Activation" . "\r\n";
	$message  = "Thank you for registering. You're almost done and ready to write.\n\n";
	$message .= "Click this link to activate your account: http://www.pageonetwentysix.com/?func=activate&code=" . $v_code . "\n\n";
	$message .= "\t username: " . $_POST['email'] . "\n";
	$message .= "\t password: " . $_POST['password_chk'] . "\n";
	$message .= "\t password hint: " . $_POST['password_hint'] . "\n";
	$message .= "\t secret phrase: " . $secret_key_plain . "\n\n";
	$message .= "This is the ONLY copy of your password AND secret phrase in plain text and is used to encrypt and decrypt your entries. IF YOU LOSE EITHER OF THESE TWO PIECES OF DATA YOU WILL NOT BE ABLE TO DECRYPT YOUR ENTRIES. We are not responsible for your short-term memory, so print the email and lock it in your fire-safe, put it under your mattress or in a shoebox in your closet.\n\n";
	$message .= "Enjoy writing!\n\n";
	
	mail($to, $subject, $message, $headers);
	
	$_SESSION['email'] = $_POST['email'];
	//$_SESSION['code'] = $v_code;
	}
}

function _activate()
{
	global $data;
	
	 _dbopen();
	$sql = "SELECT * FROM user WHERE v_code='" . $_GET['code'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	if(mysql_num_rows($sql_result) > 0) 
	{ 
		$result = TRUE;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		foreach ($row as $key=>$value) 
			{ 
				$data[$key] = $value; 
			}
  		}
	} else {
		$result = FALSE;
	}

	$sql = "UPDATE user SET v_code='1' WHERE id='" . $data['id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	return($result);
}

function _lookuphint()
{
	global $data;
	
	 _dbopen();
	$sql = "SELECT password_hint, v_code FROM user WHERE email='" . $_POST['login_email'] . "' LIMIT 1";
	$sql_result = mysql_query($sql,$data['CON']);
	if(mysql_num_rows($sql_result) > 0) 
	{ 
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		$_SESSION['password_hint'] = $row['password_hint'];
	   		$_SESSION['login_email'] = $_POST['login_email'];
	   		$_SESSION['activated'] = $row['v_code'];
	   		
	   		if($row['v_code'] != 1)
	   		{
	   			$_SESSION['activated'] = 0;
	   			$_SESSION['v_code'] = $row['v_code'];
	   		}
  		}
	}
	_dbclose();
}

function _emailhint()
{
	/* send email to person that registered */
	$headers  = 'From: editorial@pageonetwentysix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	$headers .= 'Mime-Version: 1.0' . "\r\n";
	
	$to = $_SESSION['login_email'] . "\r\n";
	$subject = "Password Hint" . "\r\n";
	$message  = "Thank you for requesting your password hint.\n\n";
	$message .= "\t hint: " . $_SESSION['password_hint'] . "\n\n";
	$message .= "If you still can't remember your password you can have it reset, but please try to remember it. We emailed it to you when you first registered and I bet it's in your email's trash, archives or somewhere on your under your mattress where we told you to put it!\n\n";
	$message .= "Enjoy writing!\n\n";
	
	mail($to, $subject, $message, $headers);
	
	$_SESSION['hint_sent'] = 1;
}

function _resendcode()
{
/* send email to person that registered */
	$headers  = 'From: editorial@pageonetwentysix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	$headers .= 'Mime-Version: 1.0' . "\r\n";
	
	$to = $_SESSION['login_email'] . "\r\n";
	$subject = "Resending Activation Code" . "\r\n";
	$message  = "Thank you for registering. You're almost done and ready to write.\n\n";
	$message .= "Click this link to activate your account: http://www.pageonetwentysix.com/?func=activate&code=" . $_SESSION['v_code'] . "\n\n";
	$message .= "Enjoy writing!\n\n";
	
	mail($to, $subject, $message, $headers);
	$_SESSION['resendcode'] = 1;
}

function _update_settings()
{

	// check to see if password needs resetting
	// if TRUE ($_POST['new_paswd']) _resetpswd();
	
	print "update.settings()<br />";
	exit;
}

function _resetpswd()
{	
	global $data;
	$r_code = time();
	
	// if(_POST['resetpswd'] == 1 _make_random_pswd();
	// UPDATE user set r_code = [new_pswd] | disable act
	// SEND EMAIL TO login_email
}

function _rebuild_sk()
{
	// decrypt 
}

/* End of file */
/* Location: /models/journal_model.php */