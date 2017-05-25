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

function _validate_loggedin_user()
{
	global $data;

	// validate a password for a logged in user
	_dbopen();
	$sql = "SELECT * from user where email ='" . $_SESSION['login_email'] . "' AND password ='" . sha1($_POST['login_password']) . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

	if(mysql_num_rows($sql_result) > 0)
	{
  		$_SESSION['sudo_delete_journal'] = 'true';
  		$_SESSION['sudoseeya_journal'] = 1;
  		return(true);
	} else {
		$_SESSION['sudo_delete_journal'] = 1;
	}
}

function _auth()
{
	global $data;

	if($_POST['login_email'])
	{

		/* This is login attempt */
		_dbopen();
		$sql = "SELECT * from user where (username='" . $_POST['login_email'] . "' OR email ='" . $_POST['login_email'] . "') AND password ='" . sha1($_POST['login_password']) . "'";
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

            #print '<pre>';
            #print_r($data);

			if($data['locked'] == 1) {
				$_SESSION['badlogin_msg'] = '<h3>Shucks.</h3><p style="margin-top: 15px;">Your account is locked. Maybe you requested to reset your password? This will temporarily lock your account until you follow the reset password link in the email. Please contact <a href="support@page126.com">support</a>.</p>';
				return(FALSE);
			}

			$_SESSION['first_name'] = $data['first_name'];
			$_SESSION['last_name'] = $data['last_name'];
			$_SESSION['login_email'] = $data['email'];
			$_SESSION['username'] = $data['username'];

                /* Should be stored in the session, but it's how the settings work for now Monday, May 19, 2014 */
			    $_SESSION['location'] = $data['location'];
                $_SESSION['website'] = $data['website'];
                $_SESSION['bio'] = $data['bio'];
                $_SESSION['profileimage'] = $data['profileimage'];

			$_SESSION['user_id'] = $data['id'];

			/* Set timezone */
			$_SESSION['timezone'] = $data['timezone'];
            date_default_timezone_set($_SESSION['timezone']);

			/*
            $_SESSION['SK'] = $data['secret_phrase'];
			$_SESSION['SK_c'] = _encrypt_SK_c($_POST['login_password']);
			$_SESSION['SK_p'] = md5($_POST['login_password']);
			$_SESSION['pro'] = $data['pro_user'];
            */
			$_SESSION['timezone'] = $data['timezone'];
			$_SESSION['_auth'] = 1;
			$_SESSION['locked'] = $data['locked'];

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

			/* get preferences */
			_get_preferences($data['id']);
			_set_preferences_session();

            /* set time zone preference */

			/* log last login */
			$date = date('Y-m-d H:i:s');
			_dbopen();
			$sql = "UPDATE user SET last_login='" . $date . "' WHERE id='" . $_SESSION['user_id'] . "'";
			$sql_result = mysql_query($sql,$data['CON']);
			_dbclose();

            setcookie("showlogin", '1', time()+31536000); // 1 year from setting

			return(TRUE);
		} else {

			$_SESSION['login_email'] = $_POST['login_email'];

			if(_lookuphint() == FALSE) {
				$_SESSION['tmp_pswd'] = $_POST['login_password'];
				$_SESSION['create_new_account'] = 1;
			}

 	        $_SESSION['badlogin_msg'] = '<h3>Bummer.</h3><p style="margin-top: 15px;">We think you have the wrong password or username. Did you forget? Try using the email you created the account with or <a href="/begin-password-reset">reset your password.</a></p>';
			return(FALSE);
		}

	} else {
		/* This is not a login attempt, most like a "create account" link click */
		$_SESSION['badlogin_msg'] = '<h3>Request An Invite</h3><p class="padtop">Are you trying to create a new account? Request an invite via Twitter by tweeting us <a href="http://twitter.com/nomoreblacktea" target="_new">"@nomoreblacktea we want an invite for #Page126"</a>.</p>';
		$_SESSION['create_new_account'] = 1;
		return(FALSE);
	}
}

function _main($id=NULL)
{
    // print "_main(" . $id . ")<br />";
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

        /* make version history */

        if($_GET['m'] == 'open') {
        _dbopen();
    	$sql = "INSERT INTO entry_versions (id,fk_journal_id,fk_book_id,fk_user_id,title,content,tags,word_count,created,last_modified, encrypted, shared, favorite) SELECT * FROM entry WHERE id='" . $_GET['id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
    	$sql_result = mysql_query($sql,$data['CON']);
        _dbclose();

        $log_array = array(
        'action' => 'version saved for page ID ' . $_GET['id'],
        'table' => 'entry',
        'file' => 'journal_model.php',
        'public' => '0'
        );

        __log($log_array);

        }

       // echo "editing"; exit;
        $_SESSION['userpage'] = 'writing';
		//_get_view($id);
	}
}

function _get_view($id)
{
    #print '_get_view(' . $id . ')<br />';

	global $data;

	/* Fetch the content record */
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

			/* Fetch the comments record */
        	_dbopen();
        	$sql_comments = "SELECT id FROM comments WHERE fk_entry_id = '" . $row['id'] . "' AND status='1'" ;
        	$sql_result_comments = mysql_query($sql_comments,$data['CON']);
        	$_SESSION['page_comments'] = mysql_numrows($sql_result_comments);
        	_dbclose();

		}

		// added code to check if entry is encrypted
		if(!empty($data['title']))
		{
			if($data['encrypted'] == 1) { $data['title'] = _decrypt($data['title']); }
   		}

   		if(!empty($data['content']))
   		{
	   		if($data['encrypted'] == 1) { $data['content'] = _decrypt($data['content']); }
		}
	}

	/* Also get the tags associated */
	_dbopen();
	$sql_T = 'SELECT tag_name FROM entry_tags WHERE fk_entry_id = \'' . $id . '\' AND tag_type=\'PRIVATE\' and fk_user_id=\'' . $_SESSION['user_id'] . '\'';
	$sql_result_T = mysql_query($sql_T,$data['CON']);
	$data['tag_count'] = $count = mysql_numrows($sql_result_T);
	_dbclose();

	if(!mysql_errno() && @mysql_num_rows($sql_result_T) > 0)
	{
		$i=0;
		while ($row_T = mysql_fetch_array($sql_result_T, MYSQL_ASSOC))
		{
			foreach ($row_T as $key_T => $value_T)
			{
				$data['tagTBL'][$i] = $value_T;
				$i++;
			}
		}
	}

    /* Also get the entry notes associated */
	_dbopen();
	$sql_N = 'SELECT * FROM entry_notes WHERE fk_entry_id = \'' . $id . '\' AND status=\'1\' AND fk_user_id=\'' . $_SESSION['user_id'] . '\' ORDER BY created DESC';
	$sql_result_N = mysql_query($sql_N,$data['CON']);
	$data['notes_count'] = $count = mysql_numrows($sql_result_N);
	_dbclose();

	if(!mysql_errno() && @mysql_num_rows($sql_result_N) > 0)
	{
		$i=0;
		while ($row_N = mysql_fetch_array($sql_result_N, MYSQL_ASSOC))
		{
			foreach ($row_N as $key_N => $value_N)
			{
				$data['notesTBL'][$i][$key_N] = $value_N;
			}
			$i++;
		}
	}

	//$_SESSION['last_viewed'] = $data['id'];
	$_SESSION['fk_entry_id'] = $data['id'];
	$_SESSION['encrypted'] = $data['encrypted'];
	return($id);
}

function _get_inspiration()
{

	global $data;

	/* Fetch the content record */
	_dbopen();
	$sql = 'SELECT DISTINCT fk_user_id, content, credit FROM rollandwrite ORDER BY RAND( ) LIMIT 1';
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

	if(mysql_num_rows($sql_result) > 0)
	{
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
		{
			foreach ($row as $key=>$value)
			{
				$data['inspiration'][$key] = $value;
			}
		}
    }
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

	//$_SESSION['last_viewed'] = $data['id'];
	$_SESSION['fk_entry_id'] = $data['id'];
	//print $found;
	return($found);
}

function _get_journal_list($shared=false)
{
	global $data;

	_dbopen();
	$sql = "SELECT * FROM journal WHERE fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY title ASC";
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
					$sql = "SELECT COUNT(fk_journal_id) as journal_entries_countT FROM entry WHERE fk_journal_id='" . $value . "' AND fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY title DESC";
					$sql_result_j = mysql_query($sql,$data['CON']);
					_dbclose();
					while ($cnt_j = mysql_fetch_array($sql_result_j, MYSQL_ASSOC))
					{
						$data['journals_array'][$i]['records_entries'] = $cnt_j['journal_entries_countT'];
					}

					// get total words written
					// SELECT SUM(word_count) from entry where fk_journal_id = 2
					_dbopen();
					$sql = "SELECT SUM(word_count) as twc from entry where fk_journal_id = " . $value;
					$sql_result_jWrds = mysql_query($sql,$data['CON']);
					_dbclose();
					while ($cnt_jWrds = mysql_fetch_array($sql_result_jWrds, MYSQL_ASSOC))
					{
						$data['journals_array'][$i]['total_words'] = $cnt_jWrds['twc'];
						//$data['total_words'] = $cnt_jWrds['twc'];
					}

					// trash table
					_dbopen();
					$sql = "SELECT COUNT(fk_journal_id) as journal_entries_countT FROM entry_trash WHERE fk_journal_id='" . $value . "' AND fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY title DESC";
					$sql_result_jT = mysql_query($sql,$data['CON']);
					_dbclose();
					while ($cnt_jT = mysql_fetch_array($sql_result_jT, MYSQL_ASSOC))
					{
						$data['journals_array'][$i]['records_trash'] = $cnt_jT['journal_entries_countT'];
						// set SESSION var for records_trashed
						//$_SESSION['records_trashed'] = $cnt_jT['journal_entries_countT'];
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
				$_SESSION['fk_journal_title'] = substr($key['title'], 0, 50);
			} else {
				$SELECTED = NULL;
			}
			$data['journal_list'] .= '<option ' . $SELECTED . ' value="' . $key['id'] . '"> ' . substr($key['title'], 0, 27) . '</option>' . "\n";
		}
	}
}

function _get_preferences($user)
{

	global $data;
	$data['preferences'] = array();

	_dbopen();
	$sql = "SELECT * from user_prefs where fk_user_id='" . $data['id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	$num = mysql_num_rows($sql_result);
	_dbclose();

	$i=0;
	while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
		{

			foreach ($row as $key => $value)
			{
				$data['preferences'][$i][$key] = $value;
			}
			$i++;
		}

	#print "<pre>";
	#print_r($data);
	#exit;
}

function _set_preferences_session()
{
	global $data;
	$data['prefs_v'] = array();
	$pro_ext = '';

    #rint_r($data['preferences']);

	/* Find the number of preferences returned */
	$pref_count = count($data['preferences']);

	/* Loop through the array segment */
	$i=0;
	foreach($data['preferences'] as $key => $value)
	{
		#if($data['preferences'][$i]['pref_pro'] == 1) { $pro_ext = '_pro'; } else { $pro_ext = NULL; }

		foreach($data['preferences'][$i] as $pref_key => $pref_value)
		{
			if($pref_key == 'pref_name')
			{
				$pref_name_key = $pref_value;
				$data['prefs_v'][$pref_value . $pro_ext] = $pref_value;
				$_SESSION['pref_' . $pref_value];
			}

			if($pref_key == 'pref_value')
			{
				$pref_name_value = $pref_value;
				$data['prefs_v'][$pref_name_key . $pro_ext] = $pref_value;
				$_SESSION['pref_' . $pref_name_key] = $pref_value;
			}
		}
		$i++;
	}

	$_SESSION['fk_journal_id'] = $data['prefs_v']['default_journal'];
	setcookie("theme", $data['pref_default_theme']);

	unset($data['preferences']);
}

function _set_preferences_on_update()
{
	global $data;

	_dbopen();
	$i=0;
	foreach($data['preferences'] as $key => $value)
	{
		if(is_null($value)) { $value = 0; }

		$sql = "SELECT pref_name FROM user_prefs WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND pref_name='" . $key . "'; \n";
		$sql_result = mysql_query($sql,$data['CON']);

		if(mysql_num_rows($sql_result) > 0)
		{
			$sql = "UPDATE user_prefs set pref_value='" . $value . "' WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND pref_name='" . $key . "'; \n";
			$sql_result = mysql_query($sql,$data['CON']);
		} else {
			$sql = "INSERT INTO user_prefs (fk_user_id, pref_name, pref_value, pref_state, pref_pro) VALUES ('" . $_SESSION['user_id'] . "','$key','$value','1','0'); ";
			$sql_result = mysql_query($sql,$data['CON']);
		}

		$i++;
		$_SESSION['pref_' . $key] = $value;
	}

	_dbclose();
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
	$sql = "SELECT id, COUNT(id) as journal_count FROM journal WHERE fk_user_id='" . $_SESSION['user_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
		{
			$total_J = $row['journal_count'];
			$new_Jid = $row['id'];
			$new_Jtitle = $row['title'];
		}

	if($total_J > 1)
	{
	_dbopen();

		// DELETE FROM entry where fk_user_id='{$id}'
		$sql = "DELETE FROM entry WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_POST['j'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		#print $sql . "<br />";

		// DELETE FROM entry_trash where fk_user_id='{$id}'
		$sql = "DELETE FROM entry_trash WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_POST['j'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		#print $sql . "<br />";

		// DELETE FROM tags where fk_user_id='{$id}'
		$sql = "DELETE FROM entry_tags WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_POST['j'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		#print $sql . "<br />";

		// DELETE FROM tags_trash where fk_user_id='{$id}'
		$sql = "DELETE FROM entry_tags_trash WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_POST['j'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		#print $sql . "<br />";

		// DELETE FROM journal where fk_user_id='{$id}'
		$sql = "DELETE FROM journal WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND id='" . $_POST['j'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		#print $sql . "<br />";

		// DELETE FROM journal_users where fk_user_id='{$id}'
		$sql = "DELETE FROM journal_users WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_POST['j'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);
		#print $sql . "<br />";

	_dbclose();

	$_SESSION['journal_deleted'] = 1;
	$_SESSION['fk_journal_id'] = $new_Jid;
	$_SESSION['pref_default_journal'] = $new_Jid;
	$_SESSION['fk_journal_title'] = $new_Jtitle;

	// update prefs for defautl journal
  	_dbopen();
	$sql = "UPDATE user_prefs SET pref_value='" . $new_Jid . "' WHERE pref_name='default_journal' AND fk_user_id='" . $_SESSION['user_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

	} else {
	$_SESSION['journal_deleted_cancelled'] = 1;
	}
}

function _get_entries_list_shared() {

    global $data;

    _dbopen();
	$sql = "SELECT id, title, content FROM entry WHERE shared='1' AND fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY created DESC";
	$sql_result = mysql_query($sql,$data['CON']);
	$count = mysql_numrows($sql_result);
    _dbclose();

    if(mysql_num_rows($sql_result) > 0)
	{
        $i=0;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
		{
			foreach ($row as $key=>$value)
			{
				$data['entries'][$i][$key] = $value;
			}

            _dbopen();
            $sql_comments_count = "SELECT id FROM comments WHERE status='1' AND fk_entry_id='" . $row['id'] . "'";
            $sql_result_comments_count = mysql_query($sql_comments_count,$data['CON']);
            $comments_count = mysql_numrows($sql_result_comments_count);
            $data['entries'][$i]['comments_count'] = $comments_count;
            _dbclose();

            $i++;

		}
    } else {
        $data[0] = 'No Shared Entries Found for ' . $_SESSION['userpage'];
    }

    return($data);
}

function _get_entries_list($journal_id, $filter=NULL)
{
	// switch
	// ALL, DAY, WEEK, MONTH, SEARCH, RANGE, TRASH(ALL) | DEFAULT=DAY
	// mySQL="SELECT * from EE_info where MONTH(TODAY)=MONTH(BIRTHDATE) and DAY(TODAY)=DAY(BIRTHDATE)";

	if($_COOKIE['tmp_journal_id'])
	{
		$_SESSION['fk_journal_id'] = $_COOKIE['tmp_journal_id'];
		$journal_id = $_COOKIE['tmp_journal_id'];
		$_SESSION['fk_journal_title'] = $_COOKIE['tmp_journal_title'];
		setcookie ("tmp_journal_id", "", time() - 3600);
		setcookie ("tmp_journal_title", "", time() - 3600);
	}

	global $data;
	_get_journal_list();

	// Resets fk_journal_id session VAR
	// This cookie is currently set from journal_view.php when clicking a journal title

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

        /*
        Saturday, May 17, 2014
        Add a filter for showing just the trash items

        case "TRASH":
		$sql_more = "AND created >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
		break;
        */

		default:
		$sql_more = NULL;
		break;

	}

    /* Saturday, May 17, 2014 */
    /* Add Flag to include trash. They will not be linked and be struck out and not have the trash icon */

	_dbopen();
	# removed content field 7/1//10; seemed unnecessary to pull
	$sql = "SELECT id, fk_journal_id, title, created, encrypted, shared, word_count FROM entry WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $journal_id . "' " . $sql_more . " ORDER BY created DESC";

	#print $sql;
	#exit;
	

	/*
	SQL for selecting all the tags along with all the entries by a specifi user. 
	Need to loop through and just add tags to array while id is the same

SELECT entry.id, entry.fk_user_id, entry.fk_journal_id, entry.title, entry.created, entry.encrypted, entry.shared, entry.word_count, entry_tags.tag_name 
FROM entry 
LEFT JOIN entry_tags  
ON entry.fk_user_id='1' AND entry.fk_user_id='1' 
AND entry.id = entry_tags.fk_entry_id
ORDER BY entry.created DESC
	*/

	/*
	 For a specific entry
	
	SELECT DISTINCT entry.id, entry.fk_user_id, entry.fk_journal_id, entry.title, entry.created, entry.encrypted, entry.shared, entry.word_count, entry_tags.tag_name 
FROM entry 
INNER JOIN entry_tags  
ON entry.fk_user_id='1' AND entry.fk_user_id='1' 
WHERE entry.id = '132'
ORDER BY entry.created DESC
	 */


	$sql_result = mysql_query($sql,$data['CON']);
	$count = mysql_numrows($sql_result);

	$sql = "SELECT SUM(word_count) as twc from entry where fk_journal_id = " . $journal_id;
	$sql_result_jWrds = mysql_query($sql,$data['CON']);
	while ($cnt_jWrds = mysql_fetch_array($sql_result_jWrds, MYSQL_ASSOC))
	{
		$data['total_words'] = $cnt_jWrds['twc'];
	}

	$sql = "SELECT COUNT(id) as ttc from entry_trash where fk_journal_id = " . $journal_id;
	$sql_result_trash = mysql_query($sql,$data['CON']);
	while ($trashed = mysql_fetch_array($sql_result_trash, MYSQL_ASSOC))
	{
		$data['total_trashed'] = $trashed['ttc'];
	}


	if(mysql_num_rows($sql_result) > 0)
	{
		$i=0;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {


            /* Select DB for comments */
            $sql_comments = "SELECT * from comments where fk_entry_id = '" . $row['id'] . "' AND status='1'";
            $sql_result_comments = mysql_query($sql_comments,$data['CON']);
            $count_comments = mysql_numrows($sql_result_comments);

            if($row['title'] != NULL)
			{
				if($row['encrypted'] == 1) { $row['title'] = _decrypt($row['title']); }
			} else {
				$row['title'] = 'Untitled';
			}


            /* Column for shared */
			if($row['shared'] == 1)
			{
				/* $enc_img = 'icon_padlock_on.png'; */
				$enc_img = 'v2-listings_public.png';
				$shared_style_override = 'style="opacity: 1.0;"';
				$shared_action_link = "&flip=1";
				$count_comments_data = $count_comments . ' ';
				$share_tip = "make private again";
			} else {
				/* $enc_img = 'v2-toolbar_private.png'; */
				$enc_img = 'v2-listings_public.png';
				$shared_style_override = 'style="opacity: .4;"';
				$shared_action_link = "&flip=0";
				$share_tip = "share with public";
			}


            /* Column for comments */
            if($count_comments > 0) {
                $count_comments_data = $count_comments . ' c';
            } else {
                $count_comments_data = NULL;
            }

			$results .= "<!-- entry -->";
			$results .= "<tr id=\"entry_row\" $bgcolor>";
			// add a column for favorites
			// $results .= "<td width=\"10\" align=\"left\"><img src=\"/images/icon_fav.png\" height=\"10\" /></td>";
			// add a column for padlock changed 250 to 240

			// $results .= "<td width=\"10\" align=\"left\"><img src=\"/images/icon_share.png\" height=\"12\" /></td>";
			$results .= "<td width=\"73%\"><a class=\"entry_row_link\" href=\"/write?id=" . $row['id'] . "&m=open\">" . $row['title'] . "</a></td>";
			$results .= "<td width=\"13%\" align=\"right\">" . $row['word_count'] . " w</td>";

			$results .= "<td width=\"10%\" align=\"right\"> " . $count_comments_data . "</td>";

			$results .= "<td width=\"12%\">" . date("m/d/y", strtotime($row['created'])) . "</td>";

			if($_SESSION['pref_profile_public'] == 1) {
			$results .= "<td class=\"trash_listings\" width=\"1%\" align=\"center\"><a class=\"tipped\"	 data-title=\"" . $share_tip . "\" href=\"/?func=share&id=" . $row['id'] . $shared_action_link . "\"><img class=\"shared_listing\" " . $shared_style_override . " src=\"/images/" . $enc_img . "\" height=\"14\" /></a></td>";
			}

            $results .= "<td width=\"1%\">
                <a href=\"/?func=trash&id=" . $row['id'] . "\"><img class=\"trash_listings\" src=\"/images/v2-toolbar_trash-listings.png\" alt=\"trash\" /></a></td>";
			/* <a href="?func=trash&id=<?= $data['id']; ?>"><img src="/images/v2-toolbar_trash.png" alt="trash" /></a> */

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

    _dbclose();

		$data['results'] = $results;
		$data['results_count'] = $count;

	return($data);
}

function _create_entry()
{

	global $data, $default_prefs;
	$date = date('Y-m-d H:i:s');
	$PREF_ENCRYPTED = $default_prefs['encrypted'];

	_dbopen();
	$sql = "INSERT INTO entry (fk_journal_id, fk_user_id, encrypted, created, last_modified) VALUES ('" . $_SESSION['fk_journal_id'] . "', '" . $_SESSION['user_id'] . "', '" . $PREF_ENCRYPTED . "', '$date','$date')";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

		$log_array = array(
        'action' => 'You created a new page in the ' . $_SESSION['fk_journal_title'] . ' journal',
        'table' => 'entry',
        'file' => 'journal_model.php _create_entry()',
        'public' => '1'
    	);

    	__log($log_array);

}

function _addnote()
{

	global $data;
	$date = date('Y-m-d H:i:s');

	_dbopen();

	$content_area = mysql_real_escape_string($_POST['notearea']);
	$sql = "INSERT INTO entry_notes (fk_entry_id, fk_user_id, fk_journal_id, type, notes, created, status) VALUES ('" . $_POST['id'] . "', '" . $_SESSION['user_id'] . "', '" . $_SESSION['fk_journal_id'] . "', '1', '" . $content_area . "', '" . $date ."', '1')";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

}

function _deletenote() {

    global $data;
	$date = date('Y-m-d H:i:s');

	_dbopen();
	$sql = "DELETE FROM entry_notes WHERE id='" . $_REQUEST['i'] . "' AND fk_user_id='" . $_SESSION['user_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

}

function _save()
{
    global $data;

	/* Create scalar variables from $_POST array */
   	extract($_POST, EXTR_PREFIX_SAME, "dup_");

	/* Check encryption status */
	if($encrypt_toggle == 1)
	{
		$_SESSION['encrypted'] = 1;
	} else {
		$_SESSION['encrypted'] = 0;
	}

   	if($w_count == 0)
   	{
   		$wc = str_word_count($content, 0);
   	}

   	/* move this code to the save functino too, or just create a __func */
	$order  = array("\r\n", "\n", "\r");
	$replace = "\n";
	$content = str_replace($order, $replace, $content);

	/* Encrypt the title and content fields if $_SESSION['encrypted'] == 1 */
	if($_SESSION['encrypted'] == 1)
	{
		$title = _encrypt($title);
		$content = _encrypt($content);
   	}

	/* Reformat date strings */
	$created = date('Y-m-d H:i:s', strtotime($created));


    /* set timezone */
	date_default_timezone_set($_SESSION['timezone']);

	$now = date("Y-m-d H:i:s");
    /* Need to add timezone support here. now should change based on when set_default_timezone is changed. */

	if(!$journal_id) $journal_id = 99;
	/* $_SESSION['ajax_array_dump'] = $_POST; */

    _dbopen();
    $content = mysql_real_escape_string($content);

	$sql = "UPDATE entry SET title='" . $title . "', tags='" . $tags . "', content='" . $content . "', word_count='" . $w_count . "', last_modified='" . $now . "', created='" . $created . "', fk_journal_id='" . $journal_id . "', encrypted='" . $_SESSION['encrypted'] . "' WHERE id='" . $id . "'";
    //$sql = "UPDATE entry SET title='" . $title . "', tags='" . $tags . "', content='" . $content . "', word_count='" . $w_count . "', created='" . $created . "', fk_journal_id='" . $journal_id . "', encrypted='" . $_SESSION['encrypted'] . "' WHERE id='" . $id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

	$_SESSION['sql'] = $sql;

	if($tags)
	{
		$tag_str = __sanitize($tags);
		$tag_array = explode(' ', $tag_str);

	    _dbopen();
		$sql = "DELETE FROM entry_tags where fk_entry_id='" . $id . "' AND fk_user_id='" . $_SESSION['user_id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);

		foreach($tag_array as $value)
		{
			// notes on ENCRYPTED. later this can be determined by entry type: SHARED or ENCRYPTED
			if($value != '')
			{
			$sql = "INSERT INTO entry_tags (fk_entry_id, fk_journal_id, fk_user_id, tag_name, tag_type) VALUES ('" . $id . "', '" . $journal_id . "', '" . $_SESSION['user_id'] . "', '" . $value . "', 'PRIVATE')";
			$sql_result = mysql_query($sql,$data['CON']);
			}
		}

		_dbclose();
	}

	/* always changes active journal id */
	$_SESSION['fk_journal_id'] = $journal_id;

	// update active journal last update
	 _dbopen();
	$sql = "UPDATE journal SET updated='" . $now . "' WHERE id='" . $journal_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

	return($id);
}

function _share_entry($id)
{
	global $data;

	/* two states */
	if($_GET['flip'] == '1') {
	    $share_state = '0';
	} else {
    	$share_state = '1';
	}

	_dbopen();
    $sql = "UPDATE entry SET shared='" . $share_state . "' WHERE id='" . $_GET['id'] . "'";
    $sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

	$_SESSION['shared'] = 1;
}

function _trash_entry()
{
	global $data;
	_dbopen();

	/* Move the record to the trash */
	$sql = "INSERT INTO entry_trash (id,fk_journal_id,fk_user_id,title,content,tags,word_count,created,last_modified, encrypted, shared, favorite) SELECT * FROM entry WHERE id='" . $_GET['id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	/* Delete it from the main entry table */
	$sql = "DELETE FROM entry WHERE id='" . $_GET['id'] . "' AND fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	/* Move the tags records to the trash table */
	$sql = "INSERT INTO entry_tags_trash (fk_entry_id, fk_journal_id, fk_user_id, tag_name, tag_type) SELECT * FROM entry_tags WHERE fk_entry_id='" . $_GET['id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	/* Delete tag records from the tags table */
	$sql = "DELETE FROM entry_tags WHERE fk_entry_id='" . $_GET['id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	_dbclose();
	$_SESSION['trash'] = 1;

        $log_array = array(
        'action' => 'You moved a page to the trash',
        'table' => 'entry',
        'file' => 'journal_model.php _trash_entry()',
        'public' => '1'
    	);

    	__log($log_array);

}

function _trash_restore()
{
	global $data;
	_dbopen();

	/* Move the record to the trash */
	$sql = "INSERT INTO entry (id,fk_journal_id, fk_book_id, fk_user_id,title,content,tags,word_count,created,last_modified, encrypted, shared, favorite) SELECT * FROM entry_trash WHERE id='" . $_GET['id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	/* Delete it from the main entry table */
	$sql = "DELETE FROM entry_trash WHERE id='" . $_GET['id'] . "' AND fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	/* Move the tags records to the trash table */
	$sql = "INSERT INTO entry_tags (fk_entry_id, fk_journal_id, fk_user_id, tag_name, tag_type) SELECT * FROM entry_tags_trash WHERE fk_entry_id='" . $_GET['id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	/* Delete tag records from the tags table */
	$sql = "DELETE FROM entry_tags_trash WHERE fk_entry_id='" . $_GET['id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	_dbclose();
	$_SESSION['restored'] = 1;

	    $log_array = array(
        'action' => 'You restored ' . $_GET['id'] . ' from the trash',
        'table' => 'entry',
        'file' => 'journal_model.php _trash_restore()',
        'public' => '1'
    	);

    	__log($log_array);

}

function _empty_trash()
{
	global $data;
	$i=1;

    if($_COOKIE['tmp_journal_id'])
	{
		$_SESSION['fk_journal_id'] = $_COOKIE['tmp_journal_id'];
		//$journal_id = $_COOKIE['tmp_journal_id'];
		$_SESSION['fk_journal_title'] = $_COOKIE['tmp_journal_title'];
		setcookie ("tmp_journal_id", "", time() - 3600);
		setcookie ("tmp_journal_title", "", time() - 3600);
	}

	_dbopen();

	if($_POST['takeitout'] == 1)
	{
		$sql = "DELETE FROM entry_trash WHERE fk_user_id='" . $_SESSION['user_id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);

		//$sql = "DELETE FROM entry_tags WHERE fk_user_id='" . $_SESSION['user_id'] . "' AND ";
		// we need the ID of each entry to do this sucessfully or a entry_tags_trash table
		//$sql_result = mysql_query($sql,$data['CON']);

		/* Delete tag records from the tags table */
		$sql = "DELETE FROM entry_tags_trash  WHERE fk_user_id='" . $_SESSION['user_id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);

		$_SESSION['trashed'] = 1;
	} else {
	// Just gets a listing of trashed items by user
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
					if($row['encrypted'] == 1) { $item = _decrypt($row['title']); }
					else { $item = $row['title']; }

				} else {
					$item = 'Untitled';
				}

				if($i > 0) $add_comma = ', ';
	  			$items_list .= '<li>';

	  				// note: change this so that only an array of data is returned and is formatted in view.
	  				if($_SESSION['pro'] == 1)
	  				{
	  					//$items_list .= '<a href="/?func=restore&id=' . $row['id'] . '">restore</a> &raquo;  ';
	  				}

	  					$items_list .= '<b><!-- <a target="_new" href="/?func=main&id=' . $row['id'] . '"> -->' . $item . '</b> (created on ' . $row['created'] . ')<!-- </a> --> | <a target="_new" href="/?func=restore&id=' . $row['id'] . '">restore</a>';
	  		  			$i++;
	   		}

	   		$data['trash_items'] = $items_list;
	   		//$data['trash_items'] = substr_replace($items_list ,"",-2);
		} else {
			$data['trash_items'] = '<p style="font-weight: 700; font-size: 1.3me;">There are no items in the trash.</p>';
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
	$sql = "SELECT * from user where id='" . $_SESSION['user_id'] . "' AND password ='" . sha1($_POST['login_password']) . "'";

	$sql_result_pswd = mysql_query($sql,$data['CON']);

	if(mysql_num_rows($sql_result_pswd) > 0)
	{

		$sql = "UPDATE user SET v_code='" . $d_code . "', locked='1' WHERE id='" . $_SESSION['user_id'] . "'";
		$sql_result = mysql_query($sql,$data['CON']);

		/* send email to person that registered */
		$headers  = 'From: Page126 <no-reply@page126.com>' . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

		$to = $_SESSION['login_email'] . "\r\n";
		$subject = "Account DELETION Request" . "\r\n";
		$message  = "We're sorry to see that you want to leave. You are almost done. If you haven't already done it, please log back into your account and BACKUP your data. There is a link in the settings that does this for you.\n\nTo DELETE EVERYTHING visit the URL below and enter the following information.\n\n";
		$message .= "Click this link to DELETE your account: \n\thttp://www.page126.com/?func=delete_account\n\n";
		$message .= "\t username: " . $_SESSION['login_email'] . "\n";
		$message .= "\t password: [you know this]\n";
		$message .= "\t delete authorization code: " . $d_code . "\n\n";
		$message .= "We require this additional step to make sure that you really want to delete your account, because it really will delete everything and can not be undone or restored.\n\n";
		$message .= "If this is a mistake and you wish to cancel this action click this link to simply unlock your account:\n\n\thttp://www.page126.com/index.php?func=unlock&code=" . $d_code . "\n\tUnlock Code: ". $d_code . "\n\n";

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
	$sql = "SELECT * from user where email ='" . $_SESSION['login_email'] . "' AND password ='" . sha1($_POST['login_password']) . "'";
	#$sql = "SELECT * from user where email ='" . $_POST['login_email'] . "' AND password ='" . md5($_POST['login_password']) . "' AND v_code='" . $_POST['d_code'] . "'";
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
		_redirect('/?func=login');
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

	// DELETE FROM entry_tags where fk_user_id='{$id}'
	$sql = "DELETE FROM entry_tags WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	// DELETE FROM entry_tags_trash where fk_user_id='{$id}'
	$sql = "DELETE FROM entry_tags_trash WHERE fk_user_id='" . $user_id . "'";
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

	// DELETE FROM user_prefs where fk_user_id='{$id}'
	$sql = "DELETE FROM user_prefs WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);

    // DELETE FROM user_prefs where fk_user_id='{$id}'
	$sql = "DELETE FROM entry_notes WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);


	_dbclose();

	/* EMAIL to login_email that your account is gone; no going back; sorry. */
	$headers  = 'From: Page126 <no-reply@page126.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

	$to = $email . "\r\n";
	$subject = "Account Deleted" . "\r\n";
	$message  = "Your account and all data associated with your email and user ID has been deleted.";
	mail($to, $subject, $message, $headers);

	}

}


function _getPublicSharedListings() {

    global $data;

    _dbopen();
	$sql = "SELECT id, title, content FROM entry WHERE shared='1' AND fk_user_id='" . $data['public_id'] . "' ORDER BY created DESC";
	

	$sql_result = mysql_query($sql,$data['CON']);
	$count = mysql_numrows($sql_result);
    _dbclose();

    if(mysql_num_rows($sql_result) > 0)
	{
        $i=0;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
		{
			foreach ($row as $key=>$value)
			{
				$data['entries'][$i][$key] = $value;
			}

            _dbopen();
            $sql_comments_count = "SELECT id FROM comments WHERE status='1' AND fk_entry_id='" . $row['id'] . "'";
            $sql_result_comments_count = mysql_query($sql_comments_count,$data['CON']);
            $comments_count = mysql_numrows($sql_result_comments_count);
            $data['entries'][$i]['comments_count'] = $comments_count;
            _dbclose();

            $i++;

		}
    } else {
        $data[0] = 'No Shared Entries Found for ' . $_SESSION['userpage'];
    }

    return($data);
}

function _getPublicView($page_id)
{
    global $data;

	_dbopen();
	$sql = "SELECT * from entry where id ='" . $page_id . "' AND shared='1'";
	$sql_result = mysql_query($sql,$data['CON']);
	$count = mysql_numrows($sql_result);
	_dbclose();

	if($count > 0)
	{
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
		{
            foreach ($row as $key=>$value)
            {
				$data['entry'][$key] = $value;
            }
        }

         //$data = _get_entries_list_shared();
         $data['page'] = 'TRUE';
         $data['userpage_public'] = '1';

         /* look up comments */
        _dbopen();
    	$sql_comments = "SELECT * from comments where fk_entry_id ='" . $page_id . "' AND status='1'";
    	$sql_result_comments = mysql_query($sql_comments,$data['CON']);
    	$count_comments = mysql_numrows($sql_result_comments);
    	_dbclose();

        if($count_comments > 0)
    	{
    	    $data['entry']['comments_found'] = 'TRUE';
            $data['entry']['comments_found_count'] = $count_comments;

    	    $i=0;
    		while ($row_comments = mysql_fetch_array($sql_result_comments, MYSQL_ASSOC))
    		{

                /* if user_id_commentor is anything other than 0, they are a member get some info */
                if( $row_comments['user_id_commentor'] != '0' ) {

                    _dbopen();
                	$sql_comments_member = "SELECT id, first_name, username, profileimage from user where id ='" . $row_comments['user_id_commentor'] . "' AND v_code='1'";
                	$sql_result_comments_member = mysql_query($sql_comments_member,$data['CON']);
                	$count_comments_member = mysql_numrows($sql_result_comments_member);
                	_dbclose();

                	while ($row_comments_members = mysql_fetch_array($sql_result_comments_member, MYSQL_ASSOC))
                    {
                    	/*
                        foreach ($row_comments as $key_comments=>$value_comments)
                        {
        				    $data['entry']['comments'][$i][$key_comments] = $value_comments;
                        }
                        */

                        $data['entry']['comments'][$i]['user_id_commentor_name'] =  $row_comments_members['first_name'];
                        $data['entry']['comments'][$i]['user_id_commentor_username'] =  $row_comments_members['username'];
                        $data['entry']['comments'][$i]['user_id_commentor_profileimage'] =  $row_comments_members['profileimage'];
                	}

                } else {
                    $data['entry']['comments'][$i]['user_id_commentor_name'] =  $row_comments['name'];
                }

                foreach ($row_comments as $key_comments=>$value_comments)
                {
    				$data['entry']['comments'][$i][$key_comments] = $value_comments;
                }
                $i++;
            }

        }

        //exit;
  		return($data);

	} else {
        $data['page'] = 'FALSE';
        $data['userpage_public'] = '1';

		return($data);
	}
}

function _getPublicProfile($username)
{

    global $data;

	_dbopen();
	//$sql = "SELECT id,first_name,last_name,email,username,location,website,bio,profileimage,v_code from user where username ='" . $username . "' AND v_code=1";
	$sql = "SELECT id,first_name,last_name,email,username,location,website,bio,profileimage,v_code,locked from user where username ='" . $username . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	$count = mysql_numrows($sql_result);

	$_SESSION['debug']['sql'] = $sql;

	_dbclose();

	if($count > 0)
	{
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
		{
            foreach ($row as $key=>$value)
            {
				$data['public_' . $key] = $value;
            }
        }

			$_SESSION['public_first_name'] = $data['public_first_name'];
			$_SESSION['public_last_name'] = $data['public_last_name'];
			$_SESSION['public_login_email'] = $data['public_email'];
			$_SESSION['public_username'] = $data['public_username'];
			$_SESSION['public_user_id'] = $data['public_id'];

            /* Should be stored in the session, but it's how the settings work for now Monday, May 19, 2014 */
		    $_SESSION['public_location'] = $data['public_location'];
            $_SESSION['public_website'] = $data['public_website'];
            $_SESSION['public_bio'] = $data['public_bio'];
            $_SESSION['public_profileimage'] = $data['public_profileimage'];

            if($data['public_locked'] == '1') {
            	$_SESSION['public_account_locked'] = true;
            	return(false);
            }

  		  /* get preferences */
          // _get_preferences($data['id']);
          // _set_preferences_session();

          $data['public_userpage_public'] = '1';
          //$data = _get_entries_list_shared();

  		return($data);

	} else {
        $data['userpage'] = 'not-found';
        return($data);

	}
}

function _get_user()
{
	_dbopen();
	$sql = "SELECT * from user where email ='" . $_POST['login_email'] . "' AND password ='" . sha1($_POST['login_password']) . "' AND v_code=1";
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
	//$_SESSION['decrypted_KEY'] = $key;
	//$_SESSION['decrypted_KEY_md5'] = md5($key);

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

	$_SESSION['first_name'] = $_POST['first_name'];
	$_SESSION['email'] = $_POST['email'];

	$date = date('Y-m-d H:i:s');

	/* for now Wednesday, June 18, 2014 we will just activate all accounts */
	/* $v_code = $_POST['new_user']; */
    $v_code =1;

    /* check invite code */
	$invite_codes = array('happybirthday9', 'blueeyes', 'santacruz', 'forbiddenlove','princess', 'shortblonde');
	if(!in_array($_POST['invite_code'], $invite_codes)) {
        // invite code was wrong
        $_SESSION['invite_bad'] = 1;
		return('create');
	}

	/* Create a md5 hash of the password for database */
	$pswd_md5 = sha1($_POST['password']);

	/* Encrypt the secret phrase with plain text password for database */
	$secret_key_plain = $_POST['secret_phrase'];
	$_POST['secret_phrase'] = _encrypt_SK($_POST['secret_phrase']);

	/* split apart name on space; not always accurate */
	$name = explode(' ', $_POST['first_name']);
	$_POST['first_name'] = $name[0];
	$_POST['last_name'] = $name[1];

	_dbopen();

	/* Check if username exists */
	$sql = "SELECT * from user where email ='" . $_POST['email'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	if(mysql_num_rows($sql_result) > 0)
	{
		$_SESSION['email_exists'] = 1;
		//return('create_page.php');
		return('create');
	} else {

	$sql = "INSERT INTO user (first_name, last_name, username, email, password, password_hint, secret_phrase_md5, secret_phrase, skill, created, prefs, last_login, pro_user, pro_exp, locked, v_code) VALUES ('" . $_POST['first_name'] . "','" . $_POST['last_name'] . "','" . $_POST['username'] . "','" . $_POST['email'] . "','" . $pswd_md5 . "','" . $_POST['password_hint'] . "', '" . md5($secret_key_plain) . "', '" . $_POST['secret_phrase'] . "','" . strtolower($_POST['skill']) . "','" . $date . "','SEE_user_prefs_TABLE', '" . $date . "', '1', '0000-00-00 00:00:00', '0', '" . $v_code . "')";

	$sql_result = mysql_query($sql,$data['CON']);
	$fk_user_id = mysql_insert_id();

	$sql = "INSERT INTO journal (fk_user_id, title) VALUES ('" . $fk_user_id . "', 'Default')";
	$sql_result = mysql_query($sql,$data['CON']);
	$fk_journal_id = mysql_insert_id();

	$sql = "INSERT INTO journal_users (fk_journal_id, fk_user_id) VALUES ('" . $fk_journal_id . "','" . $fk_user_id . "')";
	$sql_result = mysql_query($sql,$data['CON']);

	global $default_prefs;
	$default_prefs['default_journal'] = $fk_journal_id;

	foreach ($default_prefs as $key => $value)
	{
		$sql = "INSERT INTO user_prefs (fk_user_id, pref_name, pref_value, pref_state, pref_pro) VALUES ('$fk_user_id','$key','$value','1','0'); ";
		$sql_result = mysql_query($sql,$data['CON']);
	}

	#$sql = "UPDATE user set prefs='0:0:blue:0:" . $fk_journal_id . "' WHERE id='" . $fk_user_id . "'";
	#$sql_result = mysql_query($sql,$data['CON']);

	_dbclose();

	/* send email to person that registered */
	$headers  = 'From: Page126 <no-reply@page126.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

	$to = $_POST['email'] . "\r\n";
	$subject = "Welcome To Page126!" . "\r\n";
	$message  = "Welcome To Page126, " . $_POST['first_name'] . "!" . "\n\n";
	$message .= "Now that you have created your account, you can start creating new pages, sharing pages with others and getting the most out of Page126.\n\n";
	// $message .= "It's easy and fast! Just follow this link\nhttp://www.page126.com/?func=activate&code=" . $v_code . "\n\n";
	// $message .= "and enter activation code: " . $v_code . "\n\n";
	$message .= "If you didn't register for a Page126 account, feel free to ignore this email. Someone may have entered your email address by accident.";

	$message .= "Enjoy writing!\n\n";

	mail($to, $subject, $message, $headers);

    $_SESSION['create_new_account'] = 1;
	$_SESSION['email'] = $_POST['email'];
    $_SESSION['login_email'] = $_POST['email'];

	$_SESSION['badlogin_msg_bckg'] = '(138,161,36,.3)';
	$_SESSION['badlogin_msg'] = '<h3>Congratulations!</h3><p style="margin-top: 15px;">Your account has been created and you can log in right now and start writing, sharing and exploring Page126.';
	setcookie("showlogin", '1', time()+31536000); // 1 year from setting

    /* return('registered'); */
    return('home');

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
	$sql = "SELECT password_hint, v_code, locked FROM user WHERE email='" . $email . "' LIMIT 1";
	$sql_result = mysql_query($sql,$data['CON']);
	if(mysql_num_rows($sql_result) > 0)
	{
		$result = TRUE;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		$_SESSION['password_hint'] = $row['password_hint'];
	   		$_SESSION['login_email'] = $email;
	   		$_SESSION['activated'] = $row['v_code'];
	   		$_SESSION['locked'] = $row['locked'];

	   		if($row['v_code'] != 1)
	   		{
	   			$_SESSION['activated'] = 0;
	   			$_SESSION['v_code'] = $row['v_code'];
	   			$_SESSION['account_locked'] = true;
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
	$headers  = 'From: Page126 <no-reply@page126.com>' . "\r\n";
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
	$headers  = 'From: Page126 <no-reply@page126.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

	$to = $_SESSION['login_email'] . "\r\n";
	$subject = "Resending Activation Code" . "\r\n";
	$message  = "Thank you for registering. You're almost done and ready to write.\n\n";
	$message .= "Click this link to activate your account: http://www.page126.com/?func=activate&code=" . $_SESSION['v_code'] . "\n\n";
	$message .= "Enjoy writing!\n\n";

	mail($to, $subject, $message, $headers);
	$_SESSION['resendcode'] = 1;
}

function _update_settings()
{

	global $data;


if(isset($_FILES['file'])) {
    if($_FILES['file']['size'] > 153600) {

    $_SESSION['error_details'] = 'File is too big. 150k limit.';
    $_SESSION['settings_updated'] = 3;
    return(false);
    } else {
        _uploadProfileImage();
    }
}

	/* Create scalar variables from $_POST array */
   	extract($_POST, EXTR_PREFIX_SAME, "dup_");
	$timezone_split = explode(':', $pref_timezone);

    if($pref_login_redir == "createnew") {
        $pref_login_redir = 1;
        $pref_show_index_redir = 0;
    } else {
        $pref_login_redir = 0;
        $pref_show_index_redir = 1;
    }

    if($pref_login_redir == "openlast") {
        $pref_show_index_redir = 1;
        $pref_login_redir = 0;
    } else {
        $pref_show_index_redir = 0;
        $pref_login_redir = 1;
    }

    /* how preferences are stored in the database */
	$data['preferences'] = array(
		'default_theme' => $theme_pref,
		'trash' => $pref_trash_redir,
		'entry_new' => $pref_login_redir,
		'show_index' => $pref_show_index_redir,
		'default_journal' => $pref_default_journal,
		'hide_entry_pswd' => $pref_hide_entry_pswd,
		'timezone' => $timezone_split[1],
		'email_reminder' => $pref_email_reminder_time,
		'zipcode' => $pref_zipcode,
		'profile_public' => $pref_profile_public,
		'show_quote' => $pref_show_quote,
		'font_size' => $pref_font_size
		);

        // print_r($data['preferences']);
        // exit;

    /* set timezone */
	date_default_timezone_set($timezone_split[1]);

    /* This function does the DB update */
	_set_preferences_on_update();

	//if($trash_redir == NULL) { $trash_redir = 0; }
	//if($login_redir == NULL) { $login_redir = 0; }
	//if($show_redir == NULL) { $show_redir = 0; }
    // df_journal_id

	if($default_journal == NULL) { $default_journal = $_SESSION['pref_default_journal']; }
	#$prefs = $trash_redir . ':' . $login_redir . ':' . $theme_pref . ':' . $show_redir . ':' . $default_journal;
	$prefs = 'SEE_user_prefs_TABLE';

	_dbopen();

	/* $tz_sql = "SET time_zone = '+6:00'";
	mysql_query($tz_sql, $data['CON']);
    */

	$sql = "UPDATE user SET "
	    . "first_name='" . $first_name
	    . "', last_name='" . $last_name
	    . "', username='" . $username
	    . "', email='" . $email
	    . "', timezone='" . $timezone_split[1]
	    . "', prefs='" . $prefs
	    . "', password_hint='" . $password_hint
	    . "', location='" . $location
	    . "', website='" . $website
	    . "', bio='" . $bio
	    . "'";

	$sql .= $more_sql;
	$sql .= "WHERE id='" . $_SESSION['user_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	_dbclose();

	/* update SESSION variables with new preference settings */
	/* some of these are broken from initial login settings, Sunday, May 18, 2014 */

	$_SESSION['first_name'] = $first_name;
	$_SESSION['last_name'] = $last_name;
	$_SESSION['login_email'] = $email;
	$_SESSION['username'] = $username;
    $_SESSION['location'] = $location;
    $_SESSION['website'] = $website;
    $_SESSION['bio'] = $bio;
	$_SESSION['timezone'] = $timezone_split[1];
	$_SESSION['pref_trash'] = $pref_trash_redir;
	$_SESSION['pref_entry'] = $pref_login_redir;
	$_SESSION['pref_show'] = $pref_show_index_redir;
	$_SESSION['theme'] = $theme_pref;
	$_SESSION['zipcode'] = $pref_zipcode;
	$_SESSION['pref_font_size'] = $pref_font_size;
	$_SESSION['profile_public'] = $pref_profile_public;
	$_SESSION['show_quote'] = $pref_show_quote;
	$_SESSION['df_journal_id'] = $default_journal; /* what the hell is this for, cleanup with PREF clean task */
	$_SESSION['fk_journal_id'] = $default_journal;
	/* **************************************** */

	$_SESSION['settings_updated'] = 1;

	/* check to see if password needs to be updated */

	if($new_password != '' && $new_password === $new_password_check)
    {
    	_dbopen();
    	$sql = "UPDATE user SET password='" . sha1($new_password) . "', v_code='1' where email ='" . $_SESSION['login_email'] . "'";
    	$sql_result = mysql_query($sql,$data['CON']);
    	_dbclose();

    	$_SESSION['settings_new_password_ok'] = "<p>Your password has been successfully updated.</p>";

    	/* shoot off an email here */
    	$to      = $email;
        $subject = "Password RESET" . "\r\n";
        $message .= "You have recently changed your password. If this is incorrect, please click this link to lock your account and reset your password:\n\thttp://www.page126.com/index.php/?func=resetpswd&email_reset=" . $_SESSION['login_email'] . "\n\n";
	    $message .= "If you did change delete this message.";
	    $message .= "Enjoy writing!\n\n";

        $headers = 'From: Page126 <no-reply@page126.com>' . "\r\n" .
            'Reply-To: no-reply@page126.com' . "\r\n" .
            'X-Priority: 3' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);

        $log_array = array(
        'action' => 'You changed your password',
        'table' => 'user',
        'file' => 'journal_model.php _update_settings()',
        'public' => '1'
    	);

    	__log($log_array);

    }

}

function _resetpswd($step,$code=NULL)
{
	global $data;

    if($step == '1') {

        /* Generate reset code, using time because it will always be unique */
        $r_code = time();

        /* check for a user with that email */
        _dbopen();
    	$sql = "SELECT id, email, v_code, locked from user where email ='" . $_POST['email'] . "'";
    	$sql_result = mysql_query($sql,$data['CON']);
    	if(mysql_num_rows($sql_result) > 0)
    	{
    		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {

            	_dbopen();
            	$sql = "UPDATE user set v_code='" . $r_code . "-" . $row['id'] . "', locked='1' where email ='" . $_REQUEST['email'] . "'";
            	$sql_result = mysql_query($sql,$data['CON']);
            	_dbclose();

                $to      = $_REQUEST['email'];
                $subject = "Password Reset Request" . "\r\n";
                // $message .= "You have requested to have your password reset. ";
                $message .= "Copy & paste this URL into a web browser to reset your password:\n\thttp://www.page126.com/?func=resetpswd&code=". $r_code . "-" . $row['id'] . "\n\n";
        	    // $message .= "If this is a mistake and you know your password click this link to simply unlock your account:\n\n\thttp://www.page126.com/index.php?func=unlock&code=" . $r_code . "\n\tUnlock Code: ". $r_code . "\n\n";
        	    $message .= "Enjoy writing!\nThe Page126 Team";

                $headers = 'From: Page126 <no-reply@page126.com>' . "\r\n" .
                    'Reply-To: no-reply@page126.com' . "\r\n" .
                    'X-Priority: 3' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);

                $_SESSION['reset_error'] = 2;
                $_SESSION['error_details'] = 'We have sent email with a reset link to you.';
    		}

    	} else {
    		$_SESSION['reset_error'] = 1;
    		$_SESSION['error_details'] = 'We could not find a matching email.';
    		return(FALSE);
    	}

    } elseif ($_REQUEST['sudo'] == '3') {

        /* check code in hidden field against code in database */
        /* update password for that code and id combination */

        $data = explode('-', $_REQUEST['code']);

        _dbopen();
        // 4. update db record by email (also UPDATE v_code to 1)
        $sql = "UPDATE user SET password='" . sha1($_REQUEST['password']) . "', v_code='1', locked='0' where id ='" . $data[1] . "' AND v_code='" . $data[0] . "-" . $data[1] . "'";
        $sql_result = mysql_query($sql,$data['CON']);
        _dbclose();

        /* verify it's success */
        $_SESSION['pswd_change'] = 1;
        $_SESSION['badlogin_msg_bckg'] = '(138,161,36,.3)';
        $_SESSION['badlogin_msg'] = '<h3>password changed</h3><p style="margin-top: 15px;">your password has been changed. please log in above.';

        $log_array = array(
        'action' => 'You changed your password',
        'table' => 'user',
        'file' => 'journal_model.php _resetpswd()',
        'public' => '1'
    	);

    	__log($log_array);

       return(true);

    }

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
		/* $_SESSION['error_details'] = 'The Deletion Code and email did not match, so this action has been cancelled. You will need to <a href="?func=contact">contact</a> our support team. We apologize for this inconvenience but keeping your data safe is important to us.'; */
		$_SESSION['error_details'] = 'The ' . $_SESSION['code_type'] . ' code and email did not match, so this action has been cancelled. You will need to <a href="/?func=contact">contact</a> our support team. We apologize for this inconvenience but keeping your data safe is important to us.';

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
			$_SESSION['error_details'] = 'The md5 hashes of your secret key or pass phrase did not match and your request to reset your password was cancelled. In order to override ride this you will need to <a href="/?func=contact">contact</a> our support team. We apologize for this inconvenience but keeping your data safe is important to us.';
			return(FALSE);
		}

	// 4. update db record by email (also UPDATE v_code to 1)
	$sql = "UPDATE user SET password='" . md5($_POST['password']) . "', v_code='1', secret_phrase='" . $new_SK . "' where email ='" . $_POST['email'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);

	/* send email to person that registered */
	$headers  = 'From: Page126 <no-reply@page126.com>' . "\r\n";;
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

	$to = $_POST['email'] . "\r\n";
	$subject = "Password RESET Success" . "\r\n";
	$message  = "You have successfully reset your password and re-encrypted your secret key or pass phrase. \n\n";
	$message .= "Please log back into your account and see if it worked for you. If not, you will need to contact us and we can see if there is anything that we can do, but we make no promises.\n\n";

	mail($to, $subject, $message, $headers);

	$log_array = array(
        'action' => 'You reset your password',
        'table' => 'user',
        'file' => 'journal_model.php _sudoresetpswd()',
        'public' => '1'
    );

    __log($log_array);

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
	global $data;

	if($_SESSION['profiler'] == 1)
	{
	print '<div style="background-color: #FFFFFF; margin: auto;">';
	print '<pre style="margin-left: 100px; padding: 20px;">';

	print "<fieldset style='margin-bottom: 20px; padding: 10px; border: 1px solid #CCCCCC;'>";
	print "<legend>_SESSION Array </legend>";
	print_r($_SESSION);
	print "</fieldset>";

	print "<fieldset style='margin-bottom: 20px; padding: 10px; border: 1px solid #CCCCCC;'>";
	print "<legend>DATA Array </legend>";
	print_r($data);
	print '</fieldset>';

	print "<fieldset style='margin-bottom: 20px; padding: 10px; border: 1px solid #CCCCCC;'>";
	print "<legend>_COOKIE Array </legend>";
	print_r($_COOKIE);
	print '</fieldset>';
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
	//$headers  = 'From: ' . $_POST['name'] . '<' . $_POST['email'] . ">\r\n";
	$headers = "From: no-reply@nomoreblacktea.com <NMBT BUG REPORTER>";
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

	#$to = 'support-126@page126.com' . "\r\n";
	$to = 'james.mccarthy@gmail.com' . "\r\n";
	$subject = $_POST['subject'] . "\r\n";
	$message  = stripslashes($_POST['message']);

	if($_POST['user_agent']) { $message .= "\n\nuser_agent: " . $_POST['user_agent']; }
    if($_SESSION['_auth'] == 1) { $message .= "\n\n " . $_POST['user_id'] . "/" . $_POST['login_email']; }

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

    $sql = "SELECT COUNT(id) as c_ENTRY_NOTES FROM entry_notes WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	$row = mysql_result($sql_result, 0,0);
	$_SESSION['c_ENTRY_NOTES'] = $row;

    $sql = "SELECT COUNT(id) as c_ENTRY_COMMENTS FROM comments WHERE fk_user_id='" . $user_id . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	$row = mysql_result($sql_result, 0,0);
	$_SESSION['c_ENTRY_COMMENTS'] = $row;

	// we will add here for journal clicked on.

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
	$subject = "Page126 - " . $data['title'] . "\r\n";
	// date("m/d/y, h:i a", strtotime($data['created'])) . "\r\n";
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

function _export($id, $type=NULL)
{
	global $data;

	/* Fetch content */
	_get_view($id);

	/* */

}

function _backup()
{
	global $data;

	_dbopen();
	if($_GET['j']) {
	$sql = "SELECT id from entry where fk_user_id='" . $_SESSION['user_id'] . "' AND fk_journal_id='" . $_GET['j'] . "' ORDER BY id DESC";
	$sql_result = mysql_query($sql,$data['CON']);

	$sql_jTitle = "SELECT title from journal where id='" . $_GET['j'] . "' LIMIT 1";
	$sql_result_jTitle = mysql_query($sql_jTitle,$data['CON']);
		while ($row_jTitle = mysql_fetch_array($sql_result_jTitle, MYSQL_ASSOC)) {

			foreach ($row_jTitle as $key=>$value)
			{
				$journal_title_bu = $value;
			}
		}
	} else {
		$sql = "SELECT id from entry where fk_user_id='" . $_SESSION['user_id'] . "' ORDER BY id DESC";
		$sql_result = mysql_query($sql,$data['CON']);
		$journal_title_bu = 'all-journals-';
	}
	_dbclose();

	$_SESSION['sql'] = $sql;
	if(mysql_num_rows($sql_result) > 0)
	{

		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {

			/* Take it row by row; alread decrypted by _get_view() function*/
			_get_view($row['id']);

			/* format the data */
			$file_data .=	'"' . $data['title'] . '","' . $data['tags'] . '","' . str_replace('"', '""', $data['content']) . '","' . $data['created'] . '","' . $data['last_modified'] . '"';
			$file_data .= 	"\n";
		}

		/* Create temp file name roll-and-write-1297825606.csv */
		$search = array(' ','&','?','/','\\','@');
		$replace = array('-');
		$journal_file_name = str_replace($search, $replace, $journal_title_bu);
		$journal_file_name = strtolower($journal_file_name);
		$journal_file_name = substr($journal_file_name, 0, 20);

		$unique_file_ID = time();
		$file_name = $journal_file_name . '-' . $unique_file_ID . ".csv";

		/* Output to temp file */
		$myFile = $_SERVER["DOCUMENT_ROOT"] . "/files/csv/" . $file_name;
		$fh = fopen($myFile, 'a') or die("can't open file");
		fwrite($fh, $file_data);
		fclose($fh);

		/* send email to person that registered */
		$headers  = 'From: ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '<' . $_SESSION['login_email'] . ">\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

		//$file_data_esc = stripslashes($file_data);
		$to = $_SESSION['login_email'] . "\r\n";
		$subject = "Journal Export CSV" . "\r\n";
		$message  = "Thank you for downloading a CSV backup of your journal. We've included a dump of the file in this email.\n\n- - - - -\n\n" . $file_data . "\n\n- - - - -\n";

		mail($to, $subject, $message, $headers);

		$_SESSION['backup_success'] = 1;
		$_SESSION['backup_file'] = $file_name;

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

function _share_create_code()
{
	/*
	$allowed_characters = 5;
	$random_string = NULL;
	$last_char = NULL;

		// generate random code of alpha/numeric limit length: 6
		$length = 6;
		$valid_chars = 'BCDFGHJKLMNPQRSTVWXYZbcdfghjklmnpqrstvwxyz1234567890'; #removed vowels to prevent bad words
		$num_valid_chars = strlen($valid_chars);

		for ($i = 1; $i < $length; $i++)
		{
			// pick a random number from 1 up to the number of valid chars
			$random_pick = mt_rand(1, $num_valid_chars);

			// take the random character out of the string of valid chars
			// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
			$random_char = $valid_chars[$random_pick-1];

			/ * do not use same character next to each other regardless of case; as to prevent Aa or MM
			if (strtolower($last_char) != strtolower($random_pick))
			{
				$random_string .= $random_char;
				$last_char = $random_char;
			} else {
				$i = $i-1;
			}
			* /

			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}

	return($random_string);
	*/

}

function _share_save_code()
{

}

function _share_delete_code()
{

}

function _share_fetch_entry()
{

}

function __sanitize($str)
{
	$bad_chars = array('$','\'','"','!','%','^','[',']','{','}','(',')',':',';','/','@','#','&','*','<','>',',','.');
	trim($str);
	$str = str_replace($bad_chars, '', $str);
	#$str = str_replace(' ', '-', $str);
	return($str);
}

function _uploadProfileImage() {

    global $data;

    $salt = time();

/*
set file extensions based on type
*/

if ($_FILES["file"]["type"] == "image/gif") {
    $ext = '.gif';

} elseif ($_FILES["file"]["type"] == "image/jpeg") {
    $ext = '.jpg';

} elseif ($_FILES["file"]["type"] == "image/jpg") {
    $ext = '.jpg';

} elseif ($_FILES["file"]["type"] == "image/x-png") {
    $ext = '.png';

} elseif ($_FILES["file"]["type"] == "image/png") {
    $ext = '.png';

} else {
    $invalid_type = true;
    $_SESSION['error_details'] = 'Incorrect file type. Must be a GIF, JPG or PNG file.';
    return(false);
}

    if($invalid_type == false) {

    // create a directory
    $target_path = DOC_ROOT . '/images-profiles';
    $profileimagefilename =
        "profile-" . $_SESSION['user_id'] . $ext;

        //print "real-name: " . $_FILES["file"]["name"] . "<br />";
        //print "name: " . $profileimagefilename . "<br />";

    // if (file_exists($target_path . "/" . $_FILES["file"]["name"])) {
   // if (file_exists($target_path . "/" . $profileimagefilename)) {
     //     echo $profileimagefilename . " already exists. ";
    //} else {
          move_uploaded_file($_FILES["file"]["tmp_name"],
          $target_path . "/" . $profileimagefilename);
          // echo "Stored in: " . $target_path . "/" . $_FILES["file"]["name"];
    //}

    // update databse
    _dbopen();
    $sql = "UPDATE user SET profileimage='" . $profileimagefilename . "' WHERE id='" . $_SESSION['user_id'] . "'";
    // print $sql; exit;

	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

	$_SESSION['settings_updated_profile_image'] = 2;
	$_SESSION['profileimage'] = $profileimagefilename;
    }

}

function _removeprofileimage() {
    //print 'removing profile image for userid: ' . $_SESSION['user_id'];

    $target_path = DOC_ROOT . '/images-profiles';
    $filename = $target_path . '/' . $_SESSION['profileimage'];

    if($_SESSION['profileimage'] != 'default.png') {

        if(file_exists($filename)) {
            unlink($file_name);
        }
    }

    $_SESSION['settings_updated_profile_image'] = 3;
    $_SESSION['profileimage'] = 'default.png';
}

function _add_comment() {

	global $data;

    $comments = mysql_escape_string($_POST['comments']);

	_dbopen();
	$sql = "INSERT INTO comments (fk_entry_id, fk_user_id, user_id_commentor, email, name, comment, public, date, status) VALUES (";
	$sql .= "'" . $_POST['fk_entry_id'] . "', ";
	$sql .= "'" . $_POST['fk_user_id'] . "', ";
	$sql .= "'" . $_POST['user_id_commentor'] . "',";
	$sql .= "'" . $_POST['email'] . "', ";
	$sql .= "'" . $_POST['name'] . "', ";
	$sql .= "'" . $comments . "', ";
	$sql .= "'1', ";
    $sql .= "'" . DATEMYSQL . "', ";
    $sql .= "'1' ";
	$sql .= ')';

	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

    /* send email to person that registered */
    $headers  = 'From: Page126 <no-reply@page126.com>' . "\r\n";
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

	$to = $_SESSION['login_email'] . "\r\n";
	$subject = "Comment Posted For " . $_POST['title'] . "\r\n";
	$message  = "Hey there! A comment has just been left on your page, " . $_POST['title'] . ". The IP address is: " . $_SERVER['REMOTE_ADDR'] . "\n\nYou can view following the link below.";
	$message .= "\n\n";
	$message .= "http://page126.com/" . $_SESSION['username'] . "/" . $_POST['page_id'];
	$message .= "\n\n";
	$message .= "Thank you, the Page126 Team\n\nThis is your page. Write.";

	mail($to, $subject, $message, $headers);

    $log_array = array(
        'action' => $_POST['name'] . ' posted a comment for ' . $_POST['title'],
        'table' => 'comments',
        'file' => 'journal_model.php',
        'public' => '1'
    );

    __log($log_array);
}

function _flag_comment() {

    global $data;

    /* check to see if user is logged in */
    if($_SESSION['_auth'] == 1) {

        /* check to see if the logged in owns the page */
        /* not the best solution; should check the DB; this is quick */

        if($_SESSION['public_page_owner'] == $_SESSION['user_id']) {

        _dbopen();
        $sql = "UPDATE comments SET status='2' WHERE id='" . $_REQUEST['flag'] . "'";
    	$sql_result = mysql_query($sql,$data['CON']);
    	_dbclose();

    	$log_array = array(
        'action' => 'You flagged a comment [ID ' . $_REQUEST['flag'] . '] for ' . $_SESSION['public_page_title'],
        'table' => 'comments',
        'file' => 'journal_model.php',
        'public' => '1'
        );

        __log($log_array);

        }

    } else {
       // log invalid transaction
    }
}

function __log($log_array) {

	global $data;

	_dbopen();
	$sql = "INSERT INTO user_transactions (fk_user_id, action, table_name, file, public) VALUES (";
	$sql .= "'" . $_SESSION['user_id'] . "', ";
	$sql .= "'" . mysql_escape_string($log_array['action']) . "', ";
	$sql .= "'" . $log_array['table'] . "',";
	$sql .= "'" . $log_array['file'] . "', ";
    $sql .= "'" . $log_array['public'] . "'";
	$sql .= ')';

	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();

}

function _delete_profile_image() {

/*
     _dbopen();
    $sql = "UPDATE user SET profileimage='" . $profileimagefilename . "' WHERE id='" . $_SESSION['user_id'] . "'";
	$sql_result = mysql_query($sql,$data['CON']);
	_dbclose();
*/

}

function detectIE() {

    global $data;

    preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);

    if(count($matches)<2){
        preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
        }

    /* $matches = array('v','8'); */

    if (count($matches)>1){
      //Then we're using IE
      $version = $matches[1];;

          switch(true){
            case ($version<=8):

             /*  die('<div style="width: 960px; background-color: #C5435C; margin: 20px auto; padding: 20px; border: 1px solid #FF0000; color: #FFF;"><p style="text-align: center">UPDATE REQUIRED!<br />Internet Explorer 6, 7 and 8 does not view this site adequately and is not supported.<br /> Consider <a style="color: #000" href="http://whatbrowser.org/">upgrading your browser</a> to a newer version of Internet Explorer or another web browser.</p></div>'); */
              header('location:/unsupported.html');

              break;

            case ($version==9 || $version==10):
              //IE9 & IE10!
              break;

            case ($version==11):
              //Version 11!
              break;

            default:
              //You get the idea
          }

    } else {
        return(false);
    }

}

function _getStatsAllUsers() {

	global $data;
	
	_dbopen();
	
	// for: words select SUM(word_count) from entry	
	$sql = "SELECT SUM(word_count) as totalwords from entry";
	$sql_result = mysql_query($sql,$data['CON']);

		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		$data['allusers_totalwordcount'] = $row['totalwords'];
		}

	// for: page select COUNT(id) from entry
	$sql = "SELECT COUNT(id) as totalpages from entry where shared = 1";
	$sql_result = mysql_query($sql,$data['CON']);

		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		$data['allusers_totalpages'] = $row['totalpages'];
		}

	// for: users select COUNT(id) from user
	$sql = "SELECT COUNT(id) as totalusers from user";
	$sql_result = mysql_query($sql,$data['CON']);

		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		$data['allusers_totalusers'] = $row['totalusers'];
		}

	_dbclose();
}

function _getSharedPages() {

	global $data;

	// SELECT id, title FROM `entry` WHERE shared = 1 ORDER BY RAND() LIMIT 0,2;
	_dbopen();
	
	// for: words select SUM(word_count) from entry	
	/* SELECT entry.id, entry.fk_user_id, entry.title, entry.content, entry.last_modified, user.profileimage
		from entry 
		INNER JOIN user ON user.id = entry.fk_user_id
		where entry.shared = 1 ORDER BY entry.last_modified ASC LIMIT 4
		*/

	$sql = "SELECT entry.id, entry.fk_user_id, entry.title, entry.content, entry.created, user.profileimage, user.username
		from entry 
		INNER JOIN user ON user.id = entry.fk_user_id
		where entry.shared = 1 ORDER BY entry.created DESC LIMIT 5";

	$sql_result = mysql_query($sql,$data['CON']);

	//$row = mysql_fetch_array($sql_result, MYSQL_ASSOC);

	// print "<pre>";
	// print_r($row);
	// exit;

		$i=0;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
	   		
	   		foreach ($row as $key=>$value)
			{

				$data['storytimeline'][$i][$key] .= $value;

				if($key =='id') {	
					$sql_C = "SELECT COUNT(id) as comment_C from comments where fk_entry_id = " . $row['id'];
					$sql_result_C = mysql_query($sql_C,$data['CON']);
					$row_C = mysql_fetch_array($sql_result_C, MYSQL_ASSOC);
					$data['storytimeline'][$i]['comments'] .= $row_C['comment_C'];
				}

			}

			$i++;

		}
	_dbclose();

}

function _getUserTransactions() {

	global $data;
	
	_dbopen();
	
	// for: words select SUM(word_count) from entry	
	$sql = "SELECT DISTINCT action, date from user_transactions WHERE public = 1 AND fk_user_id = " . $_SESSION['user_id'] . " GROUP BY action ORDER BY date DESC LIMIT 25";
	$sql_result = mysql_query($sql,$data['CON']);

		$i=0;
		while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
			foreach ($row as $key=>$value)
			{
	   			$data['user_transactions'][$i][$key] = $value;
	   		}
	   		$i++;
		}

	_dbclose();
}


/* End of file */
/* Location: /models/journal_model.php */