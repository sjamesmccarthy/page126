<?php

// (c) 2010 James McCarthy
// james.mccarthy@gmail.com
// http://www.pasteboard.org

// This app is being written to be ported to the pasteboard project
// which is why some of the code structure may seem strange, like this index file.

	$dlFile = $_SERVER["DOCUMENT_ROOT"] . "/files/csv/" . $_GET['get'];
	// . ".csv";

	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $dlFile);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($dlFile));
    #ob_clean();
    #flush();
    #readfile($dlFile);
    $file = file_get_contents($dlFile, true);
    unlink($dlFile);
    print $file;
    exit;

?>