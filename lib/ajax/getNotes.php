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
/* $_SESSION['fk_entry_id'] = '39'; */

/* include the config file
define('DB_NAME', 'pageonetwentysix');
define('DB_HOST', 'pasteboard.org');
define('DB_USER', 'pasteboard');
define('DB_PSWD', 'kandl3x+en1d');
*/

$DBCON = mysql_connect(DB_HOST, DB_USER, DB_PSWD);
$DBH = mysql_select_db(DB_NAME, $DBCON);
$sql = "SELECT * from entry_notes WHERE fk_entry_id='" . $_SESSION['fk_entry_id'] . "' AND STATUS='1' ORDER BY created DESC";
$sql_result = mysql_query($sql,$DBCON);
$_SESSION['notes_count'] = mysql_num_rows($sql_result);

if(mysql_num_rows($sql_result) > 0)
{
        while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {

            /*
            $html .= '<li id="deletelink_' . $row['id'] . '" class="deleteitems">';
            $html .= '<p id="note_' . $row['id'] . '" style="float: left; width: 90%;">' . $row['notes'] . '</p>';
            $html .= '<p style="float: right;">';
            $html .= '<a id="deletelink_' . $row['id'] . '" href="/?func=deletenote&i=' . $row['id'] . '">';
            $html .= '<img class="deletenote" src="/images/v2-icon_deletenote.png" />';
            $html .= '</a>';
            $html .= '</p>';
            $html .= '<br style="clear: both" />';
            $html .= '</li>';
            $html .= "\n\n";
            */

                    $html .= '<li id="deletenotenode_' . $row['id'] . '" class="deleteitems">';
                    $html .= '<p class="notetextcopy" id="note_' . $row['id'] . '" style="width: 90%;">' . $row['notes'] . '</p>';

                    $html .= '<p>';

                    $html .= '<a class="notetext" data-title="paste note into page" id="' . $row['id'] . '" href="#">';
                    $html .= '<img class="deletenote" style="width: 13px" src="/images/v2-icon_pasteinto.png" />';
                    $html .= '</a><span class="notes_tools">paste</span>';

                    $html .= '<a class="deletethis" data-title="delete note" id="deletelink_' . $row['id'] . '" href="/?func=deletenote&i=' . $row['id'] . '">';
                    $html .= '<img style="width: 13px" class="deletenote" src="/images/v2-icon_deletenote.png" />';
                    $html .= '</a><span class="notes_tools">delete</span>';

                    $html .= '</p>';
                    $html .= '<br style="clear: both" />';
                    $html .= '</li>';

    }

	/*
    while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC))
	{
		$html .= "<li>" . $row['tag_name'] . "</li>";
	}
    */
}

mysql_close($DBCON);

print '<ul class="notes_ui">';
print $html;
print '</ul>';

?>