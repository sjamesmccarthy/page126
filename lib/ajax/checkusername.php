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
$sql = "SELECT username from user WHERE username='" . $_REQUEST['name'] . "'";
$data = $sql . "/";

$sql_result = mysql_query($sql,$DBCON);
$data .= mysql_num_rows($sql_result);

if(mysql_num_rows($sql_result) == 0) {
    $data = 'available';
} else {
    $data = 'not-available';
    }

/*
if(mysql_num_rows($sql_result) > 0)
{
	while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
	{
		// build html
		$html .= "<li><a style=\"text-decoration: none; font-size: 10pt;\" href=\"\">" . $row['tag_name'] . "</a>";
	}
}
*/

mysql_close($DBCON);

print $data;
//print '<label class="tag_outer_box_label"><a style="text-decoration: none; font-size: 10pt;" href="">load.tags()</a></label>';

?>