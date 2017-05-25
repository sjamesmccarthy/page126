<?php

// (c) 2010 James McCarthy
// james.mccarthy@gmail.com
// http://www.pasteboard.org

// This app is being written to be ported to the pasteboard project
// which is why some of the code structure may seem strange, like this index file.

session_cache_limiter('nocache');
session_set_cookie_params('10800'); // Based on second = 1 hour inactivity is 3600 (add or session).

session_start();
session_regenerate_id();

include_once('config.php');
include_once('journal_controller.php');

?>