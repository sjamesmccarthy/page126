<?php

/**
 * @FILE		journal_controller.php
 * @DESC		stand-alone proto-type app
 * @PACKAGE		PASTEBOARD
 * @VERSION		1.0.0
 * @AUTHOR		James McCarthy
 * @EMAIL		james.mccarthy@gmail.com

 */

__index();
__killapp();

# initialize the controller (app)
function __index()
{

    detectIE();

	global $layout, $no_cache_page;
	$template = 'page_tpl.php';

    /* Add a router */
    $urlparts = parse_url($_SERVER['REQUEST_URI']);
    $path = explode('/', $urlparts['path']);

    if($path[1] != '' && $path[1] != 'index.php') {

        $_SESSION['userpage'] = $path[1];
        $_SESSION['editor'] = 0;

        switch($_SESSION['userpage'])
        {
            case "about":
            $_GET['func'] = 'about';
            break;

            case "signup":
            $_GET['func'] = 'create';
            break;

            case "settings":
            $_GET['func'] = 'settings';
            break;

            case "pages":
            $_GET['func'] = 'entries';
            break;

            case "begin-password-reset":
            $_GET['func'] = 'resetpswd';
            break;

            case "dashboard":
            $_GET['func'] = 'dashboard';
            break;

            case "folders":
            $_GET['func'] = 'journals';
            break;

            case "write":
            $_GET['func'] = 'main';
            $_SESSION['editor'] = 1;
            break;

            case "logout":
            $_GET['func'] = 'logout';
            break;

            case "releasenotes":
            $_GET['func'] = 'releasenotes';
            break;

            default:
            $_GET['func'] = 'user';
            break;

        }

        $page = $path[2];

    }


	//if(in_array($_GET['func'], $no_cache_page))
	//{
    	//header("X-Robots-Tag: noindex, nofollow", true);
    //if(!$path[1]) {
    
 //    if($_GET['func'] != 'user') {

 //    	if (!$_SESSION['_auth']) {
    		
 //    		if(isSet($_GET['func'])) {
 //    			_redirect('/');
 //    		}
		
	// 	}

	// }
	
	//}

	if($_GET['profiler'] == 1) { $_SESSION['profiler'] = 1; }
	if($_GET['profiler'] == 2) { unset($_SESSION['profiler']); }

	/* Sets the default theme as specified in config.php */
	if(!$_COOKIE['theme'])
	{
		#setcookie("theme", DEFAULT_THEME);
		$_SESSION['theme'] = DEFAULT_THEME;
	}


	/* Check to see if it's a mobile version
	if(preg_match("/Macintosh|Windows|Linux/i", $_SERVER['HTTP_USER_AGENT']))
	{
		# do nothing
		# set session var or something
		# maybe do something one day
	} else {
		_redirect('mobile.html');
	}
	*/

	switch($_GET['func'])
	{

		case "auth":
		$view = auth();
		_redirect($view);
		break;

		case "main":
			if($_SESSION['login_email']) // if the user is logged in; kind of weak.
			{

				main();
				if($_GET['v'] == 'print')
				{
					$view = 'print_view.php';
					$template = 'print_tpl.php';
				} else {
					$view = 'main_view.php';
				}

			} else {
				$view = 'login_view.php';
			}
		break;

        case "user":

        /* Just auth the user profile status only */


        if($page) {

                $data = _getPublicView($page);
                $data .= _getPublicProfile($path[1]);

                if($data['page'] == 'FALSE') {
                    _getPublicProfile($path[1]);
                }

        } else {
                _getPublicProfile($path[1]);
                //_get_entries_list_shared();
                _getPublicSharedListings();
        }


		$view = 'user_page.php';
		break;

		case "about":
		$view= 'about_page.php';
		break;

		case "entries":
		if(is_Null($_GET['filter'])) $filter='TODAY';
		$data = entries($filter);
		$view = 'listing_view.php';
		break;

        case "addnote":
            _addnote();
		    // _redirect('/?func=main&id=' . $id);
		break;

         case "deletenote":
            _deletenote();
		    // _redirect('/?func=main&id=' . $id);
		    exit;
		break;

		case "privacy":
		$view = 'privacy_page.php';
		break;

		case "settings":
		_get_journal_list();
		settings();
		$view = 'settings_view.php';
		break;

		case "update_settings":
		update_settings();
		//_redirect('?func=settings');
		_redirect('/settings');
		break;

		case "privacy":
		$view = 'privacy_page.php';
		break;

		case "create":
		$view = create();
		break;

		case "terms":
		$view = 'policy_terms_page.php';
		break;

		case "privacy_policy":
		$view = 'policy_privacy_page.php';
		break;

		case "logout":
		logout();
		$view = 'logout_view.php';
		break;

		case "delete_account":
		$view = delete_account();
		break;

		case "delete_journal":
		$view = delete_journal();
		break;

		case "moretocome":
		$view = 'moretocome_page.php';
		break;

		case "help":
		$view = 'help_page.php';
		break;

		case "save":
		$id = save();
		/* print "<script>alert('" . $id . "');</script>"; */
		_redirect('/?func=main&id=' . $id);
		break;

		case "registered":
		$view = 'registered_page.php';
		break;

		case "activate":
		activate();
		$view = 'registered_page.php';
		break;

		case "dashboard":
			_getStatsAllUsers();
			_getSharedPages();
			_getUserTransactions();
			$view = 'dashboard_view.php';
		break;

		case "journals":
		journals();
			// add in various views for detail(edit)
			if($_GET['rm'] == 1)
			{
			/* _redirect('?func=journals'); */
			_redirect('/folders');
			}
			else {
			$view = 'journals_view.php';
			}
		break;

		case "journal_detail":
		journals();
		$view = 'journals_detail_view.php';
		break;

		case "emailhint":
		emailhint();
		$view = 'login_view.php';
		break;

		case "remove-profile-image":
		    _removeprofileimage();
            _redirect('/settings');
		break;

		case "resendcode":
		resendcode();
		$view = 'login_view.php';
		break;

		case "resetpswd":
            resetpswd();
            $view = 'reset_view.php';
		break;

		case "backup":
		backup();
			if($_GET['prov'] == 'dl')
			{
				_redirect('download.php?get=' . $_SESSION['backup_file']);
			} else {
				if(isSet($_GET['z']))
				{
					_redirect(getenv("HTTP_REFERER"));
				} else {
					/* _redirect('?func=settings'); */
					_redirect('/settings');
				}
			}
		break;

		case "trash_untitled":
		trash_untitled();
		/* _redirect('?func=settings'); */
		_redirect('/settings');
		break;

		case "trash":
		trash_entry();
			if($_SESSION['pref_trash'] == 1)
			{
				new_entry();
				#$view = 'main_view.php';
				_redirect('?func=main');
			} else {
				/* _redirect('?func=entries'); */
				_redirect('/pages');
			}

		break;

        case "comment":

        /* route here for posting */
        if(isSet($_REQUEST['flag'])) {
            _flag_comment();
        } else {
            _add_comment();
        }

        _redirect('/' . $_SESSION['public_username'] . '/' . $_SESSION['public_page_id'] . '#comments');
		break;

        case "share":
        _share_entry($_GET['id']);
            if($_GET['writing'] == 'true') {
                _redirect('?func=main&id=' . $_SESSION['fk_entry_id']);
            } else {
                /* _redirect('?func=entries'); */
                _redirect('/pages');
            }

		break;

		case "releasenotes":
		$view = 'releasenotes_page.php';
		break;

		case "empty_trash":
		empty_trash();
		$view = 'empty_trash_view.php';
		break;

		case "restore":
		restore();
		/* _redirect('?func=entries'); */
		_redirect('/pages');
		break;

		case "new_entry":
		new_entry();
		#$view = 'main_view.php';
		_redirect('/write');
		break;

		case "contact":
		#if($_SESSION['mail_sent']) { unset($_SESSION['mail_sent']); }
		$view = 'contact_page.php';
		break;

		case "blog":
		$view = 'blog_page.php';
		break;

		case "send_mail":
		send_mail();
		_redirect('/releasenotes');
		break;

		case "unlock":
		_activate();
		$view = 'unlock_view.php';
		break;

		case "email":
		email_entry();
		#$view = 'main_view.php';
		_redirect('?func=main&id=' . $_SESSION['fk_entry_id']);
		break;

		case "print":
		//
		// $view = 'print_view.php';
		break;

        case "checkusername":
        print 'available';
        break;

		case "upgrade":
		_redirect('?func=moretocome',301);
		break;

		default:

		if($_SESSION['_auth'])
		{
			 //_redirect('/write');
			
			if($_SESSION['editor'] == 1) { _redirect('/write'); } else { _redirect('/'); }
		} else {
		    $_SESSION['login_attempt'] = $_SESSION['login_attempt'] +1;
			//$view = 'login_view.php';
			
			_getStatsAllUsers();
			_getSharedPages();
			
			$view = 'home_view.php';
		}
		break;
	}

    
	if(!$_SESSION['_auth'] && $_GET['func'] != 'user') {

		/* lockout core app pages from even begin shown as set in config.php */
		if(in_array($_GET['func'], $no_cache_page)) {
			_redirect('/');
		}

	} else {
		
		/* send no-cache header for core app pages as set in config.php */
		if(in_array($_GET['func'], $no_cache_page))
		{
	    	header("X-Robots-Tag: noindex, nofollow", true);
	    }

	}

	/* Finally, render the page */
   	$layout	 = 	array(
	'view' => $view,
	'template' => $template
	);

	_display($layout);
	
}

function __killapp()
{
	exit;
}

function insert_snip($file)
{
	require_once('views/snips/' . $file);
}

function load_model($file)
{
	require_once('models/' . $file);
}

function auth()
{

	/*
		needs to work like this:
			1) if there is not a current entry ?func=new_entry
	*/

	if(_auth() == TRUE)
	{
		if($_SESSION['pref_show_index'] == 1)
		{
			/* $view = '?func=entries'; */
			/* $view = '/pages' changed 1/25/15 */
			// $view = '/folders'; 
			$view = '/dashboard';
		}
		//else if($_SESSION['pref_entry_new'] == 1)
		//{
		// $view = '?func=new_entry';
		//}
		else {
			//$view = '/write';	// send to the ?func=main
			$view = '/';
		}
	} else {
		// create();
		//$view = '?func=create';	// send to the create page & reset password
		$view = '/';
	}

	return($view);
}

function main()
{
	global $data;
	_get_journal_list();

	if(!isSet($_GET['id'])) {
	    _get_last_view();
    } else {
        $data['id'] = $_GET['id'];
    }

	_get_view($data['id']);

    /* only pull an inspirational quote if the pref is set */
    if($_SESSION['pref_show_quote'] == 1) {
        _get_inspiration();
    }

	_main($data['id']);
}

function save()
{
	$id = _save();
	_get_view($id);
	return($id);
}

function create()
{
	global $data;

        if($_POST['new_user'])
    	{
    		$result_location = _create_new_user();
    		/* _redirect('/?func=' . $result_location); */
    		$view = 'home_view.php';

    	} else {
    		if(!$_SESSION['login_email']) { $_SESSION['create_new_account'] = 1; }
    		$view = 'create_page.php';
    	}

        return($view);

}

function settings()
{
	#if($_POST['update'] == 1)
	#{
		#_update_settings();
		#$view = 'settings_view.php';
	#} else {
		//_settings();
		#$view = 'settings_view.php';
	#}
	_lookuphint();
	return($view);
}

function update_settings()
{
	_update_settings();
}

function emailhint()
{
	_emailhint();
}

function resendcode()
{
	_resendcode();
}

function resetpswd()
{
    /*
$_SESSION['code_type'] = "Password Reset";
    $_SESSION['sent_to'] = $_GET['email_reset'];

	if($_GET['again'] == 1)
	{
		unset($_SESSION['reset_error']);
	}
*/

    /* Step 1 send email */
	if($_POST['sudo'] == 1)
	{
		_resetpswd($_POST['sudo']);

			if($_SESSION['reset_error'] == 1)
			{
			    $view= 'reset_view.php';

			} else {
				session_destroy();
				$view= 'reset_view.php';
			}

	} elseif(isSet($_GET['code'])) {
	    /* Step 2 reset the password */
        /* _resetpswd('3',$_GET['code']); */
        $view= 'reset_view.php';

	} elseif($_POST['sudo'] == 3) {
	    _resetpswd('3',$_GET['code']);
		_redirect('/');
	} else {
    	$view= 'reset_view.php';
	}

}

function logout()
{
	setcookie('theme', 'blue'); // expire now
	session_destroy();
}

function _redirect($var,$type=302)
{
	//header("Location: /foo.php",TRUE,301);
	header('location: ' . $var, TRUE, $type);
}

function _dbopen()
{
	global $data;
	$data['CON'] = mysql_pconnect(DB_HOST, DB_USER, DB_PSWD);
	$DBH = mysql_select_db(DB_NAME, $data['CON']);
}

function _dbclose()
{
	global $data;
    mysql_close($data['CON']);
}

function get_view()
{
	global $layout, $data;
	include('views/' . $layout['view']);
}

function entries()
{
	if(!$_REQUEST['journal_id']) {
		$journal_id = $_SESSION['fk_journal_id'];
	} else {
		$journal_id = $_REQUEST['journal_id'];
		$_SESSION['fk_journal_id'] = $journal_id;
	}

	if(isSet($_GET['filter'])) { $filter = $_GET['filter']; }

	$data =_get_entries_list($journal_id, $filter);
}

function journals()
{
	if($_GET['edit'] == 'true')
	{
		_journals_edit();
	} else if($_GET['rm'] == '1')
	{
		_journals_delete();
	} else if($_POST['new'] == '1')
	{
		_journals_new();
	}

	_get_journal_list();
}

function _display($layout)
{
	global $data;
	include('templates/' . $layout['template']);
}

function delete_entry()
{
	// _delete_entry();
}

function delete_account()
{

	if($_POST['confirm'] == 1)
	{
		$view = _delete_account_confirm();
	}
	else if ($_POST['sudoseeya'] == 1)
	{
		_sudoseeya();
			if($_SESSION['sudo_delete'] == 'true')
			{
				_redirect('?func=logout');
			} else {
				_redirect('?func=delete_account');
			}

	} else {
		$view = 'delete_view.php';
	}

	return($view);
}

function delete_journal()
{
	if($_POST['sudoseeyajournal'] == 1)
	{
		$valid = _validate_loggedin_user();
		if($valid == TRUE) {
			_get_account_stats($_SESSION['user_id']);
			_journals_delete();
			/* _redirect('/?func=journals'); */
			_redirect('/folders');
		}
		else {
			_redirect(getenv("HTTP_REFERER"));
		}
	}

	return($view);
}

function trash_entry()
{
	_trash_entry();
}

function activate()
{

	if(_activate() == TRUE)
	{
		//_redirect('?func=registered&activate=true');
		$_SESSION['badlogin_msg_bckg'] = '(138,161,36,.6)';
		$_SESSION['badlogin_msg'] = 'Your <b>account</b> has been activated.';
		_redirect('/');
	} else {
		_redirect('/?func=registered&activate=false');
	}
}

function empty_trash()
{
	_empty_trash();
}

function restore()
{
	_trash_restore();
}

function new_entry()
{
	global $data;
	_create_entry();
	_get_last_view();
	_get_view($data['id']);
}

function send_mail()
{
	_sendmail_contact();
}

function email_entry()
{
	_email_entry($_GET['id']);
}

function backup()
{
	_backup();
}

function trash_untitled()
{
	_trash_untitled();
}

/* End of file */
/* Location: ./pb-modules/pages/controller.php */
