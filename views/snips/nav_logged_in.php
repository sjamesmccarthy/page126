<?php

/* Figure out my location and build toolbar */

if($_SESSION['_auth'] == 1 && $data['userpage_public'] != "1" && $_GET['func'] != 'logout' ) {

?>

<div id="navbar_links">

<p id="navbar_logout" style="">
<?= $_SESSION['first_name'] ?>, <a href="/logout" onclick="closensave();">logout</a>
</p>

<ul id="navbariconlist">

<?php
    if($_SESSION['pref_profile_public'] == 1) {

        print '<li id="nav-public_profiles" class="navitem"><a class="tipped" data-title="dashboard" href="/dashboard" onclick="closensave();"><img src="/images/v2-public_username.png" alt="publicprofile" /></a></li>';

    }
?>

<li id="nav-settings" class="navitem"><a class="tipped" data-title="update your settings" href="/settings" onclick="closensave();"><img src="/images/v2-toolbar_settings.png" alt="settings" /></a></li>

<li><img src="/images/v2-toolbar_divider.png" /></li>

<li id="nav-entries" class="navitem">
    <a class="tipped" data-title="view all your pages" href="/pages" onclick="closensave();">
        <img src="/images/v2-toolbar_pages.png" alt="pages" />
    </a>
</li>

<li id="nav-journals" class="navitem"><a class="tipped" data-title="view folder list" href="/folders""><img src="/images/v2-toolbar-folders.png" alt="folders"  /></a></li>

<li id="nav-books" class="navitem" style="opacity: .2"><a class="tipped" data-title="view your books" href="/folders""><img src="/images/v2-toolbar-books.png" alt="folders"  /></a></li>

<span id="tools_writing" style="display: none">

<li><img src="/images/v2-toolbar_divider.png" /></li>

<?php
    
    // if($_GET['func'] == 'main') {

    //     print '<li id="nav-main" class="navitem">
    //     <img class="tipped" data-title="API: http://api.page126.com/v1/page/get/' . $data['id'] . '" src="/images/v2-toolbar-api.png" alt="settings" onClick="alert(\'http://api.page126.com/v1/page/get/' . $data['id'] . '\');" />
    //     </li>';

    // }
    
?>

<?php
    if($_SESSION['pref_profile_public'] == 1) {

        if($data['shared'] == 1) {
            $shared_style_override = 'style="opacity: 1.0;"';
            $shared_action_link = "&flip=1";
            $show_api = "<br /><span style='font-size: .6em'>API: http://api.page126.com/v1/page/get/" . $data['id'] . "/</span>";
            $shared_state = "public / make page private again";
            //$add_on_click = "onclick='alert(\"API: http://api.page126.com/v1/page/get/" . $data['id'] . "/\");'";
            $add_on_click=NULL;
        } else {
            $shared_style_override = 'style="opacity: .4;"';
            $shared_action_link = "&flip=0";
            $show_api = '';
            $shared_state = "make this page public";
            $add_on_click = '';
        }

?>

<? } ?>

<li class="navitem">

    <a class="tipped" data-title="add/show tags" id='add_tags_icon_toolbar' href="#">
        <img id="tag_add_icon_toolbar" src="/images/v2-toolbar_tags.png"/>
    </a>

    <?php if($data['tag_count'] > 0) { ?>
    <p class="notification_tags"><?= $data['tag_count'] ?></p>
    <?php } ?>

</li>

<li class="navitem">

    <a class="tipped" data-title="add/view notes" id='notes_icon_toolbar' href="#">
        <img id="tag_add_icon_toolbar" src="/images/v2-toolbar_notes.png"/>
    </a>

    <?php if($data['notes_count'] > 0) {
            $notification_notes_show = "block";
        } else {
           $notification_notes_show = "none";
        }
    ?>

    <p class="notification_notes" style="display: <?= $notification_notes_show ?>"><?= $data['notes_count'] ?></p>

</li>

<?php
    /* insert comments icon if avaiable */
    if($_SESSION['page_comments'] > 0) {

        print '<li class="navitem">';
        print '<div style="position: absolute; right: 4px; top: 16px; color: #FFF; width: 40px;">';
        print '<p style="text-align: center; margin-left: 12px;
margin-top: -6px;
font-size: .4em;">' . $_SESSION['page_comments'] . '</p>';
        print '</div>';
        print '<a id="add_comments_icon_toolbar" target="_new" href="/' . $_SESSION['username'] . '/' . $data['id'] . '?c=true#comments"><img id="tag_comments_icon_toolbar" src="/images/v2-toolbar_comments.png"/></a>';
        print '</li>';

        }
?>

<li class="navitem"><a class="tipped" data-title="email this to yourself" href="/?func=email&id=<?= $data['id']; ?>"><img src="/images/v2-toolbar_email.png" alt="email" /></a></li> 
<li class="navitem"><a class="tipped" data-title="print preview of this page" target="_new" href="/?func=main&id=<?= $data['id'] ?>&v=print"><img src="/images/v2-toolbar_print.png" alt="print" /></a></li>

<!-- <li class="navitem"><a class="tipped export_navicon" data-title="export this page" target="_new" href="#"><img src="/images/v2-toolbar_export.png" alt="export" /></a></li>
 -->

<!-- <li><img src="/images/v2-toolbar_divider.png" /></li> -->

<li class="navitem btn-markdown">
    <p class="btn-markdown-on"><span class="fa fa-check"></span></p>
    <a class="tipped" data-title="preview as markdown" href="#&id=<?= $data['id']; ?>"><img src="/images/v2-toolbar_mdown.png" alt="Markdown" /></a>
</li>

<li><a class="tipped" data-title="<?= $shared_state ?> <?= $show_api ?>" href="/?func=share&id=<?= $data['id'] . $shared_action_link ?>&writing=true"><img <?= $add_on_click ?>  <?= $shared_style_override ?> src="/images/v2-toolbar-share.png" alt="public" /></a></li>

<li><a class="tipped" data-title="move to trash" href="/?func=trash&id=<?= $data['id']; ?>"><img src="/images/v2-toolbar_trash.png" alt="trash" /></a></li>

<li><img src="/images/v2-toolbar_divider.png" /></li>

<!-- removed 1/16/15 onclick="launchFullscreen(document.documentElement);" -->
<li class="navitem btn-fullscreen"><a class="tipped" data-title="hide toolbar" href="#"><img id="fullscreen" src="/images/v2-toolbar_fullscreen.png" alt="hide toolbar" /></a></li>
</span>

<li><img src="/images/v2-toolbar_divider.png" /></li>

<?php
    if($_SESSION['userpage'] == "writing") {
        $link = "/pages";
        $onClickCode = 'onclick="closensave();"';
        $tip = "save&close";
    } else {
        $link = "/logout";    
        $onClickCode = NULL;    
        $tip = "logout";
    }
?>

<li class="navitem-close"><a class="tipped" data-title="<?= $tip ?>" href="<?= $link ?>" <?= $onClickCode ?>><img src="/images/v2-toolbar_close.png" alt="close" /></a></li>

</ul>

<!-- <p class="export_subnav"> -->
    <!-- saving a file in the href -->
    <!-- use ajax to create the file -->
    <!-- the file name will be the user_id + title (with dashes) -->

<!--
    <a href='https://dl.dropboxusercontent.com/s/deroi5nwm6u7gdf/advice.png' class='dropbox-saver'></a>
</p>
-->

</div>


<?php
} ?>