<?php 
if ( ! defined('APP-START')) exit('No direct script access allowed');

/**
 * @FILE		journal_model.php
 * @DESC		stand-alone proto-type app
 * @PACKAGE		PASTEBOARD
 * @VERSION		1.0.0
 * @AUTHOR		James McCarthy
 * @EMAIL		james.mccarthy@gmail.com
 
 trash:entry:theme:show:default_journal
 			
 */

function _auth()
{
	global $data;

	if($_POST['login_email'])
	{
	
		/* This is login attempt */
		_dbopen();
		$sql = "SELECT * from user user where email ='" . $_POST['login_email'] . "' AND password ='" . md5($_POST['login_password']) . "' AND v_code='1'";
		$sql_result = mysql_query($sql,$data['CON']);
		_dbclose();
		
		if(mysql_num_rows($sql_result) > 0) 
		{ 
			while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
				foreach ($row as $key=>$value) 
				{ 
					$data[$key] = $value;
				}
			}
			
			$_SESSION['first_name'] = $data['first_name'];
			$_SESSION['last_name'] = $data['last_name'];
			$_SESSION['login_email'] = $data['email'];
			$_SESSION['user_id'] = $data['id'];
			$_SESSION['SK'] = $data['secret_phrase'];
			$_SESSION['SK_c'] = _encrypt_SK_c($_POST['login_password']);
			$_SESSION['SK_p'] = md5($_POST['login_password']);
			$_SESSION['pro'] = $data['pro_user'];
			$_SESSION['timezone'] = $data['timezone'];
			$_SESSION['_auth'] = 1;
			
			if($_POST['remember_me'] == 1) 
			{
				if(!$_COOKIE['login_email']) 
				{
				setcookie('login_email', $data['email'], time()+2628000); // 1 month
				}
			} else {
				if(isSet($_COOKIE['login_email'])) 
				{
				setcookie('login_email', $data['email'], time()-3600); // expire now
				}
			}
				
			/* separate prefs */
			/* trash:entry:theme:show:journal */
			$prefs = explode(':', $data['prefs']);
			$_SESSION['pref_trash'] = $prefs[0];
			$_SESSION['pref_entry'] = $prefs[1];
			$_SESSION['pref_theme'] = $prefs[2];
			$_SESSION['pref_show'] = $prefs[3];
			$_SESSION['fk_journal_id'] = $prefs[4];
			$_SESSION['df_journal_id'] = $prefs[4];
			
			$_SESSION['theme'] = $prefs[2];
			setcookie("theme", $_SESSION['theme']);
			
			/* log last login */
			$date = date('Y-m-d H:i:s');
			_dbopen();
			$sql = "UPDATE user SET last_login='" . $date . "' WHERE id='" . $_SESSION['user_id'] . "'";
			$sql_result = mysql_query($sql,$data['CON']);
			_dbclose();
	
			return(TRUE);
		} else {
			
			$_SESSION['login_email'] = $_POST['login_email'];
			
			if(_lookuphint() == FALSE) {
				$_SESSION['tmp_pswd'] = $_POST['login_password'];
				$_SESSION['create_new_account'] = 1;
			}
			return(FALSE);
		}
	
	} else {
		/* This is not a login attempt, most like a "create account" link click */
		$_SESSION['create_new_account'] = 1;		
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
	
	$_SESSION['last_viewed'] = $data['id'];
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
	
	$_SESSION['last_viewed'] = $data['id'];
	return($found);
}

function _get_journal_list()
{
	global $data;
	
	_dbopen();
	$sql = "SELECT id, title, description FROM journal WHERE fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY title DESC";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	if(mysql_num_rows($sql_result) > 0)
	{
		$i=0;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) 
		{

			foreach ($row as $key=>$value) 
			{ 
				if($key == 'id')
				{
					// active entries table
					_dbopen();
					$sql = "SELECT COUNT(fk_journal_id) as journal_entries_count FROM entry WHERE fk_journal_id='" . $value . "' AND fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY title DESC";
					$sql_result_j = mysql_query($sql,$data['CON']);
					_dbclose();
					while ($cnt_j = mysql_fetch_array($sql_result_j, MYSQL_ASSOC)) 
					{
						$data['journals_array'][$i]['records'] = $cnt_j['journal_entries_count'];
					}	
					
					// trash table
					_dbopen();
					$sql = "SELECT COUNT(fk_journal_id) as journal_entries_countT FROM entry_trash WHERE fk_journal_id='" . $value . "' AND fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY title DESC";
					$sql_result_jT = mysql_query($sql,$data['CON']);
					_dbclose();
					while ($cnt_jT = mysql_fetch_array($sql_result_jT, MYSQL_ASSOC)) 
					{
						$data['journals_array'][$i]['records_trash'] = $cnt_jT['journal_entries_countT'];
					}	
									
				}
				
				$data['journals_array'][$i][$key] = $value;
			}
			
			$i++;
		}
		
		foreach($data['journals_array'] as $key)
		{
			if($_SESSION['fk_journal_id'] == $key['id']) 
			{ 
				$SELECTED = 'SELECTED';
				$_SESSION['fk_journal_title'] = substr($key['title'], 0, 27);
			} else { 
				$SELECTED = NULL; 
			}
			$data['journal_list'] .= '<option ' . $SELECTED . ' value="' . $key['id'] . '"> ' . substr($key['title'], 0, 27) . '</option>' . "\n";
		}
		
	}
}

function _journals_edit()
{
	global $data;
	
	_dbopen();
	$sql = "UPDATE journal SET ";
	$sql .= "title = '" . $_POST['journal_title'] . "', ";
	$sql .= "description = '" . $_POST['journal_description'] . "' ";
	$sql .= "WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND id='" . $_POST['id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	// update user table to change default journal in prefs field
	// rewrite db_structure to include field: default: boolean
	
	$_SESSION['journal_updated'] = 1;
}

function _journals_new()
{
	global $data;
	
	_dbopen();
	$sql = "INSERT INTO journal (fk_user_id, title, description) VALUES (";
	$sql .= "'" . $_SESSION['user_id'] . "', ";
	$sql .= "'" . $_POST['journal_title'] . "', ";
	$sql .= "'" . $_POST['journal_description'] . "'";
	$sql .= ')';
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	$_SESSION['journal_new'] = 1;
}


function _journals_delete()
{
	global $data;
	
	/* Make sure there is more than 1 journal */
	_dbopen();
	$sql = "SELECT COUNT(id) as journal_count FROM journal WHERE fk_user_id='" . $_SESSION['user_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) 
		{
			$total_J = $row['journal_count'];
		}
		
	if($total_J > 1)
	{
	_dbopen();
	$sql = "DELETE from journal ";
	$sql .= "WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND id='" . $_GET['id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	$_SESSION['journal_deleted'] = 1;
	} else {
	$_SESSION['journal_deleted_cancelled'] = 1;
	}
}

function _get_entries_list($journal_id, $filter=NULL)
{
	// switch
	// ALL, DAY, WEEK, MONTH, SEARCH, RANGE, TRASH(ALL) | DEFAULT=DAY
	// mySQL="SELECT * from EE_info where MONTH(TODAY)=MONTH(BIRTHDATE) and DAY(TODAY)=DAY(BIRTHDATE)";
	
	global $data;
	_get_journal_list();
	
	switch($filter)
	{
		case "TODAY":
		$sql_more = "AND created >= CURDATE()";
		break;
		
		case "YESTERDAY":
		$sql_more = "AND created <= CURDATE() AND created >= DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
		break;
		
		case "WEEK":
		$sql_more = "AND created >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
		break;
		
		case "MONTH":
		$sql_more = "AND created >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
		break;
		
		case "YEAR":
		$sql_more = "AND created >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
		break;
		
		default:
		$sql_more = NULL;
		break;
		
	}
	
	_dbopen();
	$sql = "SELECT id, fk_journal_id, title, created, word_count, content FROM entry WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $journal_id . "' " . $sql_more . " ORDER BY created DESC";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

	if(mysql_num_rows($sql_result) > 0)
	{
		$i=0;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
		
			// alternate row color
			#if($i % 2) { 
			#$bgcolor = "";
			#} else { 
			#$bgcolor = "bgcolor=\"#CCCCCC\"";
			#}
			
			if($row['title'] != NULL) 
			{ 
				$row['title'] = _decrypt($row['title']); 
			} else { 
				$row['title'] = 'Untitled'; 
			}
			
			$results .= "<!-- entry -->";
			$results .= "<tr id=\"entry_row\" $bgcolor align=\"left\">";
			$results .= "<td width=\"250\"><a class=\"entry_row\" href=\"?func=main&id=" . $row['id'] . "\">" . $row['title'] . "</a></td>";
			$results .= "<td width=\"60\" align=\"left\">" . $row['word_count'] . " words</td>";
			$results .= "<td width=\"100\">" . date("m/d/y @ h:i a", strtotime($row['created'])) . "</td>";
			// $results .= "<td width=\"50\">- - -</td>";
			$results .= "</tr>";
			
			#$results .= "<tr $bgcolor>";
			#$results .= "<td colspan=\"3\">" . substr(_decrypt($row['content']), 0, 150) . "</td>";
			#$results .= "</tr>";
			#$results .= "<!-- entry -->";
			
			$i++;
		}
	} else {
		$results .= "<!-- entry -->";
		$results .= "<tr id=\"entry_row\" $bgcolor align=\"left\">";
		$results .= "<td colspan='2'>no entries found, sorry.</td>";
		$results .= "</tr>";
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
	$date = date('Y-m-d H:i:s');
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
	
   	if($w_count == 0)
   	{
   		$wc = str_word_count($content, 0);
   	}
   	
   	/* move this code to the save functino too, or just create a __func */
	$order  = array("\r\n", "\n", "\r");
	$replace = "\n";
	$content = str_replace($order, $replace, $content);

	/* Encrypt the title and content fields */
	$enc_title = _encrypt($title);
	$enc_content = _encrypt($content);
   	
	/* Reformat date strings */
	$created = date('Y-m-d H:i:s', strtotime($created));
	$now = date("Y-m-d H:i:s");  
	
	if(!$journal_id) $journal_id = 99;
	$_SESSION['ajax_array_dump'] = $_POST;
	
    _dbopen();
	$sql = "UPDATE entry SET title='" . $enc_title . "', tags='" . $tags . "', content='" . $enc_content . "', word_count='" . $w_count . "', last_modified='" . $now . "', created='" . $created . "', fk_journal_id='" . $journal_id . "' WHERE id='" . $id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	/* always changes active journal id */
	$_SESSION['fk_journal_id'] = $journal_id;
	
	return($id);
}

function _trash_entry()
{
	global $data;
	_dbopen();
	
	/* Move the record to the trash */
	$sql = "INSERT INTO entry_trash (id,fk_journal_id,fk_user_id,title,content,tags,word_count,created,last_modified, encrypted, shared) SELECT * FROM entry WHERE id='" . $_GET['id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	/* Delete it from the main entry table */
	$sql = "DELETE FROM entry WHERE id='" . $_GET['id'] . "' AND fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	$_SESSION['trash'] = 1;
}

function _trash_restore()
{
	global $data;
	_dbopen();
	
	/* Move the record to the trash */
	$sql = "INSERT INTO entry (fk_journal_id,fk_user_id,title,content,tags,word_count,created,last_modified, encrypted, shared) SELECT * FROM entry_trash WHERE id='" . $_GET['id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	/* Delete it from the main entry table */
	$sql = "DELETE FROM entry_trash WHERE id='" . $_GET['id'] . "' AND fk_user_id='" . $_SESSION['user_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	$_SESSION['restored'] = 1;
}

function _empty_trash()
{
	global $data;
	$i=1;
	_dbopen();
	
	if($_POST['takeitout'] == 1)
	{	
		$sql = "DELETE FROM entry_trash WHERE fk_user_id='" . $_SESSION['user_id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		$_SESSION['trashed'] = 1;
	} else {
		$sql = "SELECT * FROM entry_trash WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		$_SESSION['trash_confirm'] = 1;
		$data['trash_count'] = mysql_num_rows($sql_result);
		
		if($data['trash_count'] > 0) 
		{
			
			while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) 
			{
				if($row['title'] != NULL) 
				{
					$item = _decrypt($row['title']);
				} else {
					$item = 'Untitled';
				}
				
				if($i > 0) $add_comma = ', ';
	  			$items_list .= '<li>';
	  			
	  				if($_SESSION['pro'] == 1)
	  				{ 
	  					//$items_list .= '<a href="/?func=restore&id=' . $row['id'] . '">restore</a> &raquo;  ';
	  				}	
	  					$items_list .= '<a target="_new" href="?func=main&id=' . $row['id'] . '">' . $item . '</a></li>'; 
	  			$i++;
	   		}
	   		
	   		$data['trash_items'] = $items_list;
	   		//$data['trash_items'] = substr_replace($items_list ,"",-2);		
		} else {
			$data['trash_items'] = 'there are no items in the trash';
			$data['trash_count'] = 0;
		}
	}
	
	_dbclose();
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
	/* change account status and v_code */
	_dbopen();
	/* validate user/password/delete_code */
	$sql = "SELECT * from user where id='" . $_SESSION['user_id'] . "' AND password ='" . md5($_POST['login_password']) . "'";
	$sql_result_pswd = mysql_query($sql,$data['CON']);
	
	if(mysql_num_rows($sql_result_pswd) > 0)  
	{
		
		$sql = "UPDATE user SET v_code='" . $d_code . "', locked='1' WHERE id='" . $_SESSION['user_id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);	

		/* send email to person that registered */
		$headers  = 'From: PageOneTwentySix <no-reply@pageonetwentysix.com>' . "\r\n";;
		$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
		
		$to = $_SESSION['login_email'] . "\r\n";
		$subject = "Account DELETION Request" . "\r\n";
		$message  = "We're sorry to see that you want to leave. You are almost done. If you haven't already done it, please log back into your account and BACKUP your data. There is a link in the settings that does this for you.\n\nTo DELETE EVERYTHING visit the URL below and enter the following information.\n\n";
		$message .= "Click this link to DELETE your account: \n\thttp://www.pageonetwentysix.com/?func=delete_account\n\n";
		$message .= "\t username: " . $_SESSION['login_email'] . "\n";
		$message .= "\t password: [you know this]\n";
		$message .= "\t delete authorization code: " . $d_code . "\n\n";
		$message .= "We require this additional step to make sure that you really want to delete your account, because it really will delete everything and can not be undone or restored.\n\n";
		$message .= "If this is a mistake and you wish to cancel this action click this link to simply unlock your account:\n\n\thttp://www.pageonetwentysix.com/index.php?func=unlock&code=" . $d_code . "\n\tUnlock Code: ". $d_code . "\n\n";

		$message .= "We're sorry to see you leave!\n\n";
		
		mail($to, $subject, $message, $headers);
		
		$_SESSION['delete_confirm'] = 'true';
		$view = 'delete_view.php';
	} else {
		$_SESSION['delete_confirm'] = 'false';
		$_SESSION['settings_errors'] = '<h3>error</h3><p class="padtop">We could not delete your account, invalid password was entered. Please try again.</p>';
		$view = 'settings_view.php';
	}
	
	_dbclose();
	return($view);
}

function _sudoseeya()
{
		global $data;
	_dbopen();
	
	// validate user/password/delete_code
	$sql = "SELECT * from user where email ='" . $_POST['login_email'] . "' AND password ='" . md5($_POST['login_password']) . "' AND v_code='" . $_POST['d_code'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	if(mysql_num_rows($sql_result) > 0) 
	{ 
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) 
		{
  			$user_id = $row['id'];
			$email = $row['email'];
  		}
  		$_SESSION['sudo_delete'] = 'true';
  		$_SESSION['sudoseeya'] = 1;

  		/* First, get some stats */
		_get_account_stats($user_id);		
	} else {
		$_SESSION['sudo_delete'] = 'failed';
		_redirect('?func=login');
	}
	
	if($_SESSION['sudoseeya'] == 1) 
	{
	
	_dbopen();	
	// DELETE FROM entry where fk_user_id='{$id}'
	$sql = "DELETE FROM entry WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	// DELETE FROM entry_trash where fk_user_id='{$id}'
	$sql = "DELETE FROM entry_trash WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	// DELETE FROM journal where fk_user_id='{$id}'
	$sql = "DELETE FROM journal WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	// DELETE FROM journal_users where fk_user_id='{$id}'
	$sql = "DELETE FROM journal_users WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	// DELETE FROM user where fk_user_id='{$id}'
	$sql = "DELETE FROM user WHERE id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	_dbclose();
	
	/* EMAIL to login_email that your account is gone; no going back; sorry. */
	$headers  = 'From: PageOneTwentySix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	
	$to = $email . "\r\n";
	$subject = "Account Deleted" . "\r\n";
	$message  = "Your account and all data associated with your email and user ID has been deleted.";
	mail($to, $subject, $message, $headers);
	
	}
	
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
	
	/* This will temporarily put the SK in plain text and md5 hash in SESSION array */
	// $_SESSION['decrypted_KEY'] = $key;
	// $_SESSION['decrypted_KEY_md5'] = md5($key);
	
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);            
	$encrypted = mcrypt_generic($td, $text);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$encrypted = addslashes($encrypted);  
		
    return($encrypted);
}

function _encrypt_SK($plain_key)
{

	$key =  $_POST['password'];
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);            
	$encrypted = mcrypt_generic($td, $plain_key);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$encrypted = addslashes($encrypted);   
	
    return($encrypted);
}

function _encrypt_SK_c($sess_SKc)
{
	$key =  $_SESSION['login_email'];
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);            
	$encrypted_sess_cipher = mcrypt_generic($td, $sess_SKc);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$encrypted_sess_cipher = addslashes($encrypted_sess_cipher);   
	
    return($encrypted_sess_cipher);
}

function _decrypt($encrypted)
{
	
	if($encrypted == NULL) { return FALSE; }
	
	$key = _decrypt_SK($_SESSION['SK']);
	#print "$key <hr />";
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$decrypted = mdecrypt_generic($td, $encrypted);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	$decrypted = stripslashes($decrypted);
	
	return(trim($decrypted));    
}

function _decrypt_SK($encrypted_key)
{	
	$key = _decrypt_SK_c($_SESSION['SK_c']);
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$decrypted_key = mdecrypt_generic($td, $encrypted_key);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td); 

	return(trim($decrypted_key));    
}

function _decrypt_SK_c($sess_cipher)
{	
	
	$key= $_SESSION['login_email'];
	$td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
	$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$decrypted_sess_cipher = mdecrypt_generic($td, $sess_cipher);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td); 
	
	return(trim($decrypted_sess_cipher));    
}

function _create_new_user()
{		
	global $data;
	$date = date('Y-m-d H:i:s');
	$v_code = $_POST['new_user'];
	
	/* Create a md5 hash of the password for database */
	$pswd_md5 = md5($_POST['password']);
	
	/* Encrypt the secret phrase with plain text password for database */
	$secret_key_plain = $_POST['secret_phrase'];
	$_POST['secret_phrase'] = _encrypt_SK($_POST['secret_phrase']);	
	
	_dbopen();
	
	/* Check if username exists */
	$sql = "SELECT * from user where email ='" . $_POST['email'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	if(mysql_num_rows($sql_result) > 0) 
	{
		$_SESSION['email_exists'] = 1;
		return('create_page.php');
	} else {
	
	$sql = "INSERT INTO user (first_name, last_name, email, password, password_hint, secret_phrase_md5, secret_phrase, created, prefs, last_login, pro_user, pro_exp, locked, v_code) VALUES ('" . $_POST['first_name'] . "','" . $_POST['last_name'] . "','" . $_POST['email'] . "','" . $pswd_md5 . "','" . $_POST['password_hint'] . "', '" . md5($secret_key_plain) . "', '" . $_POST['secret_phrase'] . "','" .  $date . "','0:0:blue:0', '" . $date . "', '1', '0000-00-00 00:00:00', '0', '" . $v_code . "')";
	$sql_result = mysql_query($sql,$data['CON']);
	$fk_user_id = mysql_insert_id();
	
	$sql = "INSERT INTO journal (fk_user_id, title) VALUES ('" . $fk_user_id . "', 'Default')";
	$sql_result = mysql_query($sql,$data['CON']);
	$fk_journal_id = mysql_insert_id();
	
	$sql = "INSERT INTO journal_users (fk_journal_id, fk_user_id) VALUES ('" . $fk_journal_id . "','" . $fk_user_id . "')";
	$sql_result = mysql_query($sql,$data['CON']);
	
	$sql = "UPDATE user set prefs='0:0:blue:0:" . $fk_journal_id . "' WHERE id='" . $fk_user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	
	_dbclose();
	
	/* send email to person that registered */
	$headers  = 'From: PageOneTwentySix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	
	$to = $_POST['email'] . "\r\n";
	$subject = "Account Activation" . "\r\n";
	$message  = "Thank you for registering. You're almost done and ready to write.\n\n";
	$message .= "Click this link to activate your account: http://www.pageonetwentysix.com/?func=activate&code=" . $v_code . "\n\n";
	$message .= "\t username: " . $_POST['email'] . "\n";
	$message .= "\t password: " . $_POST['password_chk'] . "\n";
	$message .= "\t password hint: " . $_POST['password_hint'] . "\n";
	$message .= "\t secret phrase: " . $secret_key_plain . "\n\n";
	$message .= "\t activation code: " . $v_code . "\n\n";
	
	$message .= "This is the ONLY copy of your password AND secret phrase in plain text and will be used to encrypt and decrypt your entries. IF YOU LOSE EITHER OF THESE TWO PIECES OF DATA YOU WILL NOT BE ABLE TO DECRYPT YOUR ENTRIES. We are not responsible for your short-term memory, so print the email and lock it in your fire-safe, put it under your mattress or in a shoebox in your closet.\n\n";
	$message .= "Enjoy writing!\n\n";
	
	mail($to, $subject, $message, $headers);
	
	$_SESSION['email'] = $_POST['email'];
	
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
  		
  		$sql = "UPDATE user SET v_code='1' WHERE id='" . $data['id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		_dbclose();
	
  		$_SESSION['unlocked'] = 1;
  		
	} else {
		$_SESSION['unlocked'] = 0;
		$result = FALSE;
	}
	
	return($result);
}

function _lookuphint()
{
	global $data;
	
	if($_SESSION['login_email'])
	{
		$email = $_SESSION['login_email'];
	} else {
		$email = $_POST['login_email'];
	}
	
	 _dbopen();
	$sql = "SELECT password_hint, v_code FROM user WHERE email='" . $email . "' LIMIT 1";
	$sql_result = mysql_query($sql,$data['CON']);
	if(mysql_num_rows($sql_result) > 0) 
	{ 
		$result = TRUE;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		$_SESSION['password_hint'] = $row['password_hint'];
	   		$_SESSION['login_email'] = $email;
	   		$_SESSION['activated'] = $row['v_code'];
	   		
	   		if($row['v_code'] != 1)
	   		{
	   			$_SESSION['activated'] = 0;
	   			$_SESSION['v_code'] = $row['v_code'];
	   		}
  		}
	} else {
		$result = FALSE;
	}
	
	_dbclose();
	return($result);
}

function _emailhint()
{

	/* send email to person that registered */
	$headers  = 'From: PageOneTwentySix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	
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
	$headers  = 'From: PageOneTwentySix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	
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
	global $data;

	/* Create scalar variables from $_POST array */
   	extract($_POST, EXTR_PREFIX_SAME, "dup_");
	$timezone_split = explode(':', $timezone);
	
	if(!$current_password)
	{
		$_SESSION['settings_errors'] = '<h3>error</h3><p class="padtop">Your current password needs to be entered so we didn\'t make any changes.</p>';
		return(false);
	}
	
	#if($current_password != _decrypt_SK_c($_SESSION['SK_c']))
	#{
	#	$_SESSION['settings_errors'] = '<h3>error</h3><p class="padtop">Your current password has been entered incorrectly.</p>';
	#	return(false);
	#}
	
	// only touch the password and secret key if new_password exists
	if($new_password != '') 
	{	
		if($new_password === $new_password_check)
		{
			
			/* Validate current password */
			if($current_password === _decrypt_SK_c($_SESSION['SK_c']))
			{
				/* re-crypt the secret key with new password */
				$new_SK = _rebuild_sk($new_password);
				$more_sql = ", password='" . md5($new_password) . "', secret_phrase='" . $new_SK . "'";
				
				$_SESSION['settings_new_password_ok'] = '<h3>password note:</h3><p class="padtop">Your password has also been changed successfully and your secret key has been re-encrypted. We recommend that you <a href="?fun=logout">logout</a> and log back in to make sure the change worked before trying to read or write anything, because it\'s always better to be safe than sorry!</p>';
				
			} else {
				$_SESSION['settings_errors'] = 'Your current password is invalid so we didn\'t change it. However, any other setting changes were applied okay.';
			}
		 	
		} else {
			$more_sql = NULL;
			$_SESSION['settings_errors'] = 'You had an error in resetting your password, most likely a mismatch in your password so it wasn\'t changed. However, any other setting changes were applied okay.';
		}
	}
	
	/* Set Prefs */
	if($trash_redir == NULL) { $trash_redir = 0; }
	if($login_redir == NULL) { $login_redir = 0; }
	if($show_redir == NULL) { $show_redir = 0; }
	if($default_journal == NULL) { $default_journal = $_SESSION['df_journal_id']; }
	$prefs = $trash_redir . ':' . $login_redir . ':' . $theme_pref . ':' . $show_redir . ':' . $default_journal;
	
	_dbopen();
	$sql = "UPDATE user SET first_name='" . $first_name . "', last_name='" . $last_name . "', email='" . $email . "', timezone='" . $timezone_split[1] . "', prefs='" . $prefs . "', " . "password_hint='" . $password_hint . "'";
	$sql .= $more_sql;
	$sql .= "WHERE id='" . $_SESSION['user_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	$_SESSION['first_name'] = $first_name;
	$_SESSION['last_name'] = $last_name;
	$_SESSION['login_email'] = $email;
	$_SESSION['timezone'] = $timezone_split[1];
	$_SESSION['settings_updated'] = 1;
	$_SESSION['pref_trash'] = $trash_redir;
	$_SESSION['pref_entry'] = $login_redir;
	$_SESSION['pref_show'] = $show_redir;
	$_SESSION['theme'] = $theme_pref;
	$_SESSION['df_journal_id'] = $default_journal;
	
}

function _resetpswd()
{	
	global $data;
	
	/* Generate reset code, using time because it will always be unique */
	$r_code = time();
	
	_dbopen();
	$sql = "UPDATE user set v_code='" . $r_code . "' where email ='" . $_POST['email'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	/* send email to person that registered */
	$headers  = 'From: PageOneTwentySix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	
	$to = $_SESSION['login_email'] . "\r\n";
	$subject = "Password RESET Authorization Code" . "\r\n";
	$message  = "You have requested to reset your password.\n\n";
	$message .= "WARNING! You will also need to reset your secret key or pass phrase too. If it is not EXACTLY as you originally submitted then your entries will be as we say in the tech-world, bricked, or in your case unable to be decrypted again.\n\n";
	$message .= "Click this link to reset your password:\n\thttp://www.pageonetwentysix.com/index.php?func=resetpswd\n\tReset Code: ". $r_code . "\n\n";
	$message .= "If this is a mistake and you know your password click this link to simply unlock your account:\n\n\thttp://www.pageonetwentysix.com/index.php?func=unlock&code=" . $r_code . "\n\tUnlock Code: ". $r_code . "\n\n";
	$message .= "Enjoy writing!\n\n";
	mail($to, $subject, $message, $headers);
	
	$_SESSION['resetpswd'] = 1;

}

function _sudoresetpswd()
{
	global $data;
	
	// 1. Validate email exists
	_dbopen();
	$sql = "SELECT * from user where email ='" . $_POST['email'] . "' AND v_code='" . $_POST['r_code'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	if(mysql_num_rows($sql_result) > 0) 
	{  
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		$md5_SK = $row['secret_phrase_md5'];
		}
	} else {
		$_SESSION['reset_error'] = 1;
		$_SESSION['error_details'] = 'The Deletetion Code and email did not match, so this action has been cancelled. You will need to <a href="?func=contact">contact</a> our support team. We apologize for this inconvenience but keeping your data safe is important to us.';		
		return(FALSE); 
	} 

	#print "post: " . md5($_POST['secret_phrase']) . "==" . "db(" . $md5_SK . ")";
	#exit;
	
	// 2. Validate passwords match
	if($_POST['password'] === $_POST['password_chk']) 
	{
	
	// 2.1 Compare md5 hases of SK
		if(md5($_POST['secret_phrase']) === $md5_SK)
		{
			// 3. encrypt SK w/$_POST[password]
			// $new_SK = _rebuild_sk($password);
			// 3.1 all i need to do is encrypt it using the new_password as the key NOT rebuild it
			$new_SK = _encrypt_SK($_POST['secret_phrase']);
		} else {
			$_SESSION['reset_error'] = 1; 
			$_SESSION['error_details'] = 'The md5 hashes of your secret key or pass phrase did not match and your request to reset your password was cancelled. In order to override ride this you will need to <a href="?func=contact">contact</a> our support team. We apologize for this inconvenience but keeping your data safe is important to us.'; 
			return(FALSE); 
		}
	
	// 4. update db record by email (also UPDATE v_code to 1)
	$sql = "UPDATE user SET password='" . md5($_POST['password']) . "', v_code='1', secret_phrase='" . $new_SK . "' where email ='" . $_POST['email'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	/* send email to person that registered */
	$headers  = 'From: PageOneTwentySix <no-reply@pageonetwentysix.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

	$to = $_POST['email'] . "\r\n";
	$subject = "Password RESET Success" . "\r\n";
	$message  = "You have successfully reset your password and re-encrypted your secret key or pass phrase. \n\n";
	$message .= "Please log back into your account and see if it worked for you. If not, you will need to contact us and we can see if there is anything that we can do, but we make no promises.\n\n";
	
	mail($to, $subject, $message, $headers);
	
	} else {
		$_SESSION['reset_error'] = 1; 
		$_SESSION['error_details'] = 'Your passwords did not match so we didn\'t do anything.'; 
		return(false);
	}
	
	#if(isSet($_SESSION)) { unset($_SESSION); }
	
	_dbclose();
		
}

function _rebuild_sk($np)
{
	global $data;
	
	/* need to force new password into _POST array until _encrypt function are rewritten more efficiently */
	$_POST['password'] = $np;
	
	// 1. decrypt the SK_c (or login password) from SESSION
	$sess_pswd = _decrypt_SK_c($_SESSION['SK_c']);
	
	// 2. decrypt the SK from session
	$sess_SK = _decrypt_SK($_SESSION['SK']);
	
	// 3. encrypt the SK with new_password
	$new_SK = _encrypt_SK($sess_SK);
	
	return($new_SK);
	
}

function profiler() 
{
	if($_SESSION['profiler'] == 1) 
	{
	print '<div style="background-color: #FFFFFF; margin: auto;">';
	print '<pre style="margin-left: 100px; padding: 20px;">'; 
	print_r($_SESSION);
	print "user-agent: " . $_SERVER['HTTP_USER_AGENT'];
	print '</div>';
	}
}

function _sendmail_contact() 
{

	if($_POST['no_captcha'] != 1)
	{
		require_once(DOC_ROOT . '/lib/recaptchalib.php');
		$privatekey = "6LeHJboSAAAAAD2OmwFwlmvRNXwDYPG_l7UGQskd";
		$resp = recaptcha_check_answer ($privatekey,
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["recaptcha_challenge_field"],
	                                $_POST["recaptcha_response_field"]);
	
		if (!$resp->is_valid) {
	  		$_SESSION['error'] = 1;
	  		$_SESSION['error_msg'] = 'reCAPTCHA did not match; Try again';
	  		$_SESSION['msg'] = $_POST['message'];
	  		$_SESSION['email'] = $_POST['email'];
	  		$_SESSION['name'] = $_POST['name'];
	  		return(FALSE);
		}
	}
	
	/* send email to person that registered */
	$headers  = 'From: ' . $_POST['name'] . '<' . $_POST['email'] . ">\r\n";
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

	#$to = 'support-126@pageonetwentysix.com' . "\r\n";
	$to = 'james.mccarthy@gmail.com' . "\r\n";
	$subject = $_POST['subject'] . "\r\n";
	$message  = stripslashes($_POST['message']) . "\r\n";
	
	if($_POST['user_agent']) { $message .= "\n\nuser_agent: " . $_POST['user_agent']; }
	
	mail($to, $subject, $message, $headers);
	$_SESSION['mail_sent'] = 1;
}

function _get_account_stats($user_id)
{
	global $data;
	_dbopen();
	
	/* Count session variables */
	$_SESSION['c_SESSION'] = count($_SESSION);
	
	// will do quick SELECT COUNT(*) on entry, entry_trash, journal, and $_SESSION
	$sql = "SELECT COUNT(id) as c_ENTRY FROM entry WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	$row = mysql_result($sql_result, 0,0);
	$_SESSION['c_ENTRY'] = $row;
	
	$sql = "SELECT COUNT(id) as c_ENTRY_TRASH FROM entry_trash WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	$row = mysql_result($sql_result, 0,0);
	$_SESSION['c_ENTRY_TRASH'] = $row;
	
	_dbclose();
	
}

function _email_entry($id)
{
	global $data;
		
	/* Fetch content */
	_get_view($id);
	
	/* move this code to the save functino too, or just create a __func */
	$order  = array("\r\n", "\n", "\r");
	$replace = "\n";
	$data['content'] = str_replace($order, $replace, $data['content']);
		
	/* send email to person that registered */
	$headers  = 'From: ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '<' . $_SESSION['login_email'] . ">\r\n";
	$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";

	$to = $_SESSION['login_email'] . "\r\n";
	$subject = "Your Entry on " . date("m/d/y, h:i a", strtotime($data['created'])) . "\r\n";
	$message  = $data['title'] . "\nCreated on " . date("m/d/y, h:i a", strtotime($data['created'])) . " and last modified " . date("m/d/y, h:i a", strtotime($data['last_modified']));

	
	if($data['tags'] != '') 
	{
		$message .= "\nTags: " . $data['tags'];
	}
	
	$message .= "\n\n" . $data['content'] . "\n";
	$message = stripslashes($message);

	mail($to, $subject, $message, $headers);
	$_SESSION['emailed_entry'] = 1;
}

function _backup()
{
	global $data;
	
	_dbopen();
	$sql = "SELECT id from entry where fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY id DESC";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
		
	$_SESSION['sql'] = $sql;
	if(mysql_num_rows($sql_result) > 0) 
	{  
		/* format header row */
		$file_data =	'"title","tags","content","created","last_modified"' . "\n";
		
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		_get_view($row['id']);
			
			/* Take it row by row; alread decrypted by _get_view() function*/
			$file_data .=	'"' . addslashes($data['title']) . '","' . addslashes($data['tags']) . '","' . addslashes($data['content']) . '","' . $data['created'] . '","' . $data['last_created'] . '"';
			$file_data .= 	"\n";
		}
		
		/* Output to temp file */			
		$unique_file_ID = time();
		$myFile = $_SERVER["DOCUMENT_ROOT"] . "/files/csv/" . $unique_file_ID . ".csv";
		$fh = fopen($myFile, 'a') or die("can't open file");
		fwrite($fh, $file_data);
		fclose($fh);

		/* send email to person that registered */
		$headers  = 'From: ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '<' . $_SESSION['login_email'] . ">\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
	
		$to = $_SESSION['login_email'] . "\r\n";
		$subject = "Journal Export CSV" . "\r\n";
		$message  = "Thank you for downloading a CSV backup of your journal. We've included a dump of the file in this email.\n\n- - - - -\n\n" . $file_data . "\n\n- - - - -\n";
		
		mail($to, $subject, $message, $headers);
    	
		$_SESSION['backup_success'] = 1;	
		$_SESSION['backup_file'] = $unique_file_ID;
		
		} else {
		$_SESSION['backup_error'] = 1;
		$_SESSION['error_details'] = 'Journal could not be backed up right now.';		
		return(FALSE); 
	} 	
}

function _trash_untitled()
{
	global $data;
	_dbopen();
	/* Delete it from the main entry table */
	$sql = "DELETE FROM entry WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND title IS NULL";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
	
	$_SESSION['trash_untitled'] = 1;
}

/* End of file */
/* Location: /models/journal_model.php */