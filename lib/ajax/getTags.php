<?


/*
$host = explode('.', $_SERVER['HTTP_HOST']);
switch ($host[0])
{
	case in_array('dev', $host):
	include('../../config.php');
	break;

	default:
	include(DOC_ROOT . '/config.php');
	break;
}
*/

include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

session_start();

/* include the config file
define('DB_NAME', 'pageonetwentysix');
define('DB_HOST', 'pasteboard.org');
define('DB_USER', 'pasteboard');
define('DB_PSWD', 'kandl3x+en1d');
*/

$DBCON = mysql_connect(DB_HOST, DB_USER, DB_PSWD);
$DBH = mysql_select_db(DB_NAME, $DBCON);
$sql = "SELECT tag_name from entry_tags WHERE fk_entry_id='" . $_SESSION['fk_entry_id'] . "' AND fk_journal_id='" . $_SESSION['fk_journal_id'] ."' AND fk_user_id='" . $_SESSION['user_id'] . "'";
$sql_result = mysql_query($sql,$DBCON);
$_SESSION['tagcount'] = mysql_num_rows($sql_result);

if(mysql_num_rows($sql_result) > 0)
{
	while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
	{
		// build html
		$html .= "<li>" . $row['tag_name'] . "</li>";
	}
}

mysql_close($DBCON);

print '<ul>' . $html;
print '<li class="add_tags_icon"><img src="/images/v2-icon_addtag.png" /></li>';
print '</ul>';
//print '<label class="tag_outer_box_label"><a style="text-decoration: none; font-size: 10pt;" href="">load.tags()</a></label>';

?>