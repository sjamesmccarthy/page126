<?

/* NOTE: THIS SITE REQUIRES PHP 5.x or lower */
/* Uses GLOBALS */

error_reporting(E_ALL);

/* Startup */
define('APP-START','TRUE');
define('VERSION', '2.0.9');
define('YEAR', '2015');

define('DEFAULT_THEME', 'blue');
define('DOC_ROOT', $_SERVER["DOCUMENT_ROOT"]);
define('LOGIN_ERROR', '<p style="padding: 50px;">Whoa! Sorry<br />Your session may have expired.<br /><br /><a href="/">click here to log in again</a></p>');
define('UPGRADE_COST', '$5');

$data = array();
date_default_timezone_set('America/New_York');

define('DATE2', date('l \t\h\e jS'));
define('DATE', date('l'));
define('DATESHORT', date('M jS'));
define('DATEMYSQL', date('Y-m-d H:i:s'));

define('NO_SESSION_ERROR', '<div class="no-direct-access"><h1>What\'s This?</h1><p>No direct script access allowed or your session may have expired. <a href="/">click here to log in again</a></p>');
require_once('models/journal_model.php');

/* Database connection information */
$host = explode('.', $_SERVER['HTTP_HOST']);
$host_db_prefix = "jmgaller_";

switch ($host[0])
{
	case in_array('dev', $host):
	/* Site Database Information */
	define('DB_NAME', 'jamesmcc_pageonetwentysix');
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PSWD', 'jewel4me');
	break;

	default:
	/* Site Database Information */
	define('DB_NAME', $host_db_prefix . 'page126');
	define('DB_HOST', 'localhost');
	define('DB_USER', $host_db_prefix . 'web');
	define('DB_PSWD', 'dontbelik');

	break;
}

/* Timezone Array for settings w/GMT offset */
$tz_array = array(
	'-12'=>'Pacific/Kwajalein',
	'-11'=>'Pacific/Samoa',
	'-10'=>'Pacific/Honolulu',
	'-9'=>'America/Juneau',
	'-8'=>'America/Los_Angeles',
	'-7'=>'America/Denver',
	'-6'=>'America/Mexico_City',
	'-5'=>'America/New_York',
	'-4'=>'America/Caracas',
	'-3.5'=>'America/St_Johns',
	'-3'=>'America/Argentina/Buenos_Aires',
	'-2'=>'Atlantic/Azores', // no cities here so just picking an hour ahead
	'-1'=>'Atlantic/Azores',
	'0'=>'Europe/London',
	'1'=>'Europe/Paris',
	'2'=>'Europe/Helsinki',
	'3'=>'Europe/Moscow',
	'3.5'=>'Asia/Tehran',
	'4'=>'Asia/Baku',
	'4.5'=>'Asia/Kabul',
	'5'=>'Asia/Karachi',
	'5.5'=>'Asia/Calcutta',
	'6'=>'Asia/Colombo',
	'7'=>'Asia/Bangkok',
	'8'=>'Asia/Singapore',
	'9'=>'Asia/Tokyo',
	'9.5'=>'Australia/Darwin',
	'10'=>'Pacific/Guam',
	'11'=>'Asia/Magadan',
    '12'=>'Asia/Kamchatka'
    );

/* Themes */
$theme_array = array(
	'pink'=>'Pink',
	'orange'=>'Orange',
	'blue'=>'Blue Eyes',
	'green'=>'Green',
	'white'=>'White',
	'black'=>'Black',
	'gray'=>'Gray'
	);

/* Default Prefs */
$default_prefs = array(
	'default_theme' => 'blue',
	'trash' => 0,
	'entry_new' => 0,
	'show_index' => 1,
	'default_journal' => '00',
	'hide_entry_pswd' => 0,
	'timezone' => 'America/New_York',
	'email_reminder' => -1,
	'encrypted' => 0,
	'public_profile' => 0,
	'show_quote' => 0,
	'font-size' => '1.2'
	);

/* Pages not to cache */
$no_cache_page = array(
	'main',
	'settings',
	'logout',
	'entries',
	'new_entry',
	'empty_trash',
	'trash_untitled',
	'delete_account',
	'journals',
	'dashboard'
	);

/* Operating Systems */
$desktop_os = array(
	'Mac OS',
	'Windows',
	'Linux'
	);

/* Greetings */
$greetings = array(
	"write",
	"hm",
	"dare to dream",
	"we know your secret",
	"write your story",
	"we keep good secrets",
	"it's like paper",
	"join us",
	"it's all free",
	"padlock your thoughts",
	"oh, yeah",
	"you like who?",
	); #now write about it.

/* list of months */
$months = array(
	'January','February','March','April','May','June','July','August','September','October','November','December'
	);
?>