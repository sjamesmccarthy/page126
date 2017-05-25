<?php

// print "<pre>";
// print_r($data);

?>


<script type="text/javascript">

function count(){
  var val   = $.trim($('textarea').val()),
      words = val.replace(/\s+/gi, ' ').split(' ').length,
      chars = val.length;
  if(!chars)words=0;

  $('#counter').html(words+' words and '+ chars +' characters');

  if(words >= 40) {
      $('#counter').css('background-color','rgba(255,255,102,.8)');
  } else {
      $('#counter').css('background-color','transparent');
  }

  if(words >= 60) {
      $('#counter').css('background-color','rgba(255,173,24,.8)');
  } else if (words >= 40) {
      $('#counter').css('background-color','rgba(255,255,102,.8)');
  } else {
      $('#counter').css('background-color','transparent');
  }

  if(words == 81) {
      $('#counter').css('background-color','rgba(210,0,0,.8)');
      $("#comments_area").attr("disabled", "true");
      alert('Congrats! You\'ve reached 80 words!');
  }

}
count();

$(document).ready(function()
{

    <?php
         if($data['page'] == 'TRUE') {

    ?>

    $('#whitebackrgba').css('background-color','rgba(255,255,255,1)');
    $('#makewhite').css('border','1px solid #CCC');;
    $('#makewhite').hide()
    $('#makeopaque').show();
    $('body, #comment_list >p, #comment_list li >p a, #write_comment, #name-block >p, #humancheck_field, .public-location-website-block a').css('color', '#000');

    <?php } ?>

    function scrollToAnchor(aid){
        var aTag = $("a[name='"+ aid +"']");
        $('html,body').animate({scrollTop: aTag.offset().top},'slow');
    }

    //console.log('jqry.docready.user_page');

    <?php if($_REQUEST['c'] == 'true') { ?>
        $('#comment_list').show();
        $('#comment_count_bubble').hide();
        scrollToAnchor('comments');
    <? } ?>

    $('textarea').on('input', count);

    $('textarea').focus(function() {
        $('#humancheck_field').fadeIn();
    });

    $('#returntotop').click(function() {
        $("html, body").animate({
        scrollTop: 0
        }, 900, function() {
            //$('#returntotop').hide();
            $('#comment_count_bubble').show();
        });
    });

    $('#comment_count_bubble').click(function(e) {
        e.preventDefault();
        $('#comment_list').fadeToggle();
        scrollToAnchor('comments');
        // $('#comment_count_bubble').hide();
    });

    $('#write_comment').click(function(e) {
        e.preventDefault();
        $('#comment_box').fadeToggle();
    });

    <?php if ($_SESSION['_auth'] != 1) { ?>

    $('#email').focus(function() {
       $('#email').css('background-color','#FFF');
    });

    $('#name').focus(function() {
       $('#name').css('background-color','#FFF');
    });

    <?php } ?>

    $('#comments_area').focus(function() {
       $('#comments_area').css('background-color','#FFF');
    });

    $('#humancheck').focus(function() {
       $('#humancheck').css('background-color','#FFF');
    });

    $('#button_comment').click(function(e) {
        e.preventDefault();

        <?php if ($_SESSION['_auth'] != 1) { ?>

            if( $('#email').val() == "" ) {
                $('#email').css('background-color','rgba(210,0,0,.3)');
            }

            if( $('#name').val() == "" ) {
                $('#name').css('background-color','rgba(210,0,0,.3)');
            }

        <?php } ?>

        if( $('#comments_area').val() != "" ) {

            if( $('#humancheck').val() == '126' ) {
                $('#comments').submit();
            } else {
                $('#humancheck').css('background-color','rgba(210,0,0,.3)');
            }

            //$('#comments').submit();
        } else {
            $('#comments_area').css('background-color','rgba(210,0,0,.3)');
        }

    });

    /* THESE CODE BLOCKS NEED TO BE UPDATED 1/27/15 */
    /* $('#makewhite').click(function() {
            $('#whitebackrgba').css('background-color','rgba(255,255,255,1)');
            $('#makewhite').css('border','1px solid #CCC');
            $('body').css('color','#000');
            $('#makewhite').hide()
            $('#makeopaque').show();
            $('#name-block >p').css('color', '#000');
    });

        $('#makeopaque').click(function() {
            $('#whitebackrgba').css('background-color','transparent');
            $('body').css('color','#FFF');
            $('#makewhite').css('border','0');
            $('#makewhite').show()
            $('#makeopaque').hide();
            $('#name-block >p').css('color', '#FFF');
    }); */

 $(":input, textarea").focus(function() {
       $(":input, textarea").addClass('form-whiteback-rgba');
    });

     $(":input, textarea").blur(function() {
       $(":input, textarea").removeClass('form-whiteback-rgba');
    });

 });
</script>


<div id="whitebackrgba" class="padtopextra section_blue section_blue_padding public-page-top-adjustment">

<?php
    if($data['public_userpage_public'] == 1) {

        if($_SESSION['public_profileimage'] == '') { $_SESSION['public_profileimage'] = 'default.png'; }
        if($_SESSION['public_website'] != '') { $website = '&mdash; ' . '<a target="_new" href="http://' 
            . $_SESSION['public_website'] . '">' . $_SESSION['public_website'] . '</a>'; }

        /* if($data['page'] == 'TRUE') {
            $makewhite = '
            <p id="makewhite" class="tipped" data-title="make background solid white"></p>
            <p id="makeopaque" class="tipped" data-title="make background opaque"></p>';
        } */
?>
    <div id="name-block">

        <?= $makewhite ?>

        <p style="text-align: center">
        <!-- <img style="vertical-align: middle; margin-top: 5px; background-color: #FFF; border-radius: 10px; margin-bottom: 10px;" src="/images/v2-public_profile.png" alt="public profile" /> -->
        <a href="/<?= $_SESSION['userpage'] ?>">
        <img src="/images-profiles/<?= $_SESSION['public_profileimage'] ?>" alt=""
            class="public_user_profile_image" style="z-index: 100000;" /></a>
        </p>
    
    <p class="public-username-block"><?= $_SESSION['public_first_name'] . " " . $_SESSION['public_last_name'] ?><br />
        <!-- <span style="font-size: .7em; font-weight: 400"><?= $_SESSION['userpage'] ?></span> -->
    </p>
    <p class="public-bio-block"><?= substr($_SESSION['public_bio'], 0, 40); ?></p>
    <p class="public-location-website-block"><?= $_SESSION['public_location'] ?> <?= $website ?></p>
    </div>

<!-- Display and individual page -->
    <?php

    if($data['page'] == 'TRUE') {

        /* Am I logged in */
        if($_SESSION['_auth'] == 1) {
            $log_array = array(
            'action' => 'You viewed ' . $data['entry']['title'] . ' by' . $_SESSION['public_username'],
            'table' => 'entry',
            'file' => 'user_page.php',
            'public' => '1'
            );

            __log($log_array);
        }

        $_SESSION['public_page_id'] = $data['entry']['id'];
        $_SESSION['public_page_title'] = $data['entry']['title'];
        $_SESSION['public_page_owner'] = $data['entry']['fk_user_id'];

        /* SINGLE PAGE VIEW */

        if($data['entry']['comments_found_count'] > 0) {

        print '<div id="comment_count_bubble">';
        //print '<img src="/images/v2-toolbar_comments.png" />';
        print '<div style="position: absolute; right: 5px; top: 0px; color: #FFF; width: 40px; background-color: black;  height: 14px; 
    width: 14px; 
    padding: 15px; border-radius: 50px;">';
        print '<a class="tipped" data-title="read the comments"href="#"><p style="text-align: center; font-size: .8em">' . $data['entry']['comments_found_count'] . '</p></a>';
        print '</div>';
        print '</div>';

        }

        print '<div id="publicpageview">';

        print '<h4 style="padding-top: 40px; padding-bottom: 40px; font-weight: 700; text-align: center">' . $data['entry']['title'] . '</h4>';
       
        /* 
        need to switch this out with mardown parser
        http://parsedown.org/

        $Parsedown = new Parsedown();
        echo $Parsedown->text('Hello _Parsedown_!'); # prints: <p>Hello <em>Parsedown</em>!</p>
        */
        
        //error_reporting(-1);
        include('lib/Parsedown.php');
        include('lib/ParsedownExtra.php');
        $Parsedown = new Parsedown();
        

        $bodycontent= nl2br($data['entry']['content']);
        echo $Parsedown->text($bodycontent);
       
        print '<p id="hr" style="margin-top: 20px;"></p>';
        print '<p id="backarrow-bottom"><a href="/' . $_SESSION['userpage'] . '"><img src="/images/v2-icon_left_arrow.png" /></a> <a style="margin-left: 30px;" href="#" id="write_comment">write a comment</a>';

        // if($data['entry']['comments_found_count'] > 0) {

        // print '<div id="comment_count_bubble">';
        // print '<img src="/images/v2-toolbar_comments.png" />';
        // print '<div style="position: absolute; right: 5px; top: 20px; color: #FFF; width: 40px;">';
        // print '<p style="text-align: center; font-size: .8em">' . $data['entry']['comments_found_count'] . '</p>';
        // print '</div>';
        // print '</div>';

        // }

    ?>

<a name="comments"></a>
<div id="comment_box">

<!-- <h2>write a comment</h2> -->

<form id="comments" action="/?func=comment&action=post" method="POST">
<input type="hidden" name="fk_entry_id" value="<?= $data['entry']['id'] ?>" />
<input type="hidden" name="fk_user_id" value="<?= $data ['entry']['fk_user_id'] ?>" />
<input type="hidden" name="title" value="<?= $data ['entry']['title'] ?>" />
<input type="hidden" name="page_id" value="<?= $data ['entry']['id'] ?>" />

<?php if( $_SESSION['_auth'] == 1 ) { ?>
    <input type="hidden" name="email" value="<?= $_SESSION['login_email'] ?>" />
    <input type="hidden" name="user_id_commentor" value="<?= $_SESSION['user_id'] ?>" />
    <input type="hidden" name="name" value="<?= $_SESSION['username'] ?>" />
<?php } else { ?>

<input type="hidden" name="user_id_commentor" value="0" />

<label>email</label>
<input id="email" type="text" name="email" placeholder="enter your email" />
<span id="label_x_login_username"><img class="label_x_login" src="/images/v2-icon_x.png" /></span>

<input id="name" type="text" name="name" placeholder="enter your name or use your @twitter handle" />
<span id="label_x_login_username"><img class="label_x_login" src="/images/v2-icon_x.png" /></span>

<?php } ?>

<label>comment</label>
<textarea id="comments_area" name="comments" placeholder="type your comment"></textarea>
<p id="counter" style="position: relative; float: right; font-size: .8em; right: 20px; padding: 5px;">80 word limit</p>


<p id="humancheck_field">
prove you're human.<br /> 
type the answer to what <span style="color: blue">(twenty six + 100) equals</span> below:<br />
<label>captcha</label>
<input type="text" name="humancheck" id="humancheck" />
</p>

<p><span class="button"><a id="button_comment" href="#">post comment</a></span></p>

</form>
</div>

<ul id="comment_list" style="width: 100%; margin-top: 40px;">

<?php

    if($data['entry']['comments_found'] == 'TRUE') {

        $i=0;
        foreach ($data['entry']['comments'] as $key=>$value)
        {
            if($value['user_id_commentor'] != '0') {
                /* this is an exsiting member, check for profile pic */
                if(file_exists(DOC_ROOT . '/images-profiles/profile-' . $value['user_id_commentor'] . '.jpg'))
                {
                    $profile_image = 'profile-' . $value['user_id_commentor'] . '.jpg';
                } else {
                    $profile_image = 'default.png';
                }
            } else {
                $profile_image = 'default.png';
            }

            print '<li style="min-height: 100px;">';
            print '<img src="/images-profiles/' . $profile_image . '" alt=""
            class="public_user_profile_image" style="margin-top: -5px; margin-right: 20px; float: left; box-shadow: none;"/>
            <p>' . $value['comment'] . '</p>';

            /* check to see if a username was found; if not use first name */
            if ($value['user_id_commentor_username'] != "" ) {
                // $commented_by = $value['user_id_commentor_username'];
                $commented_by = '<a href="/' . $value['user_id_commentor_username'] . '">' . $value['user_id_commentor_username'] . '</a>';
            } else {
                $commented_by = $value['user_id_commentor_name'];

                if( preg_match("/\@/i", $value['user_id_commentor_name']) )
                {
                    $commented_by = '<a target="_twitter" href="http://twitter.com/' . ltrim($value['user_id_commentor_name'],'\@') . '">' . $value['user_id_commentor_name'] . '</a>';
                }
            }

            print '<p style="font-size: .8em;">comment written by ' . $commented_by . " on " . date('l, F j, Y, h:i a', strtotime($value['date']));

            if($_SESSION['_auth'] == 1) {
                print ' <br /><!-- <img src="/images/v2-icon_x.png" style="vertical-align: middle; opacity: .2;"/>--><span style="padding-bottom: 5px; font-size: .7em; font-weight: 400;"> <a href="/?func=comment&flag=' . $value['id'] . '">remove this comment</a></span>';
            }

            print '</p>';
            print '</li>';

            print '<p id="hr" style="clear: both; padding-bottom: 10px;"></p>';

        }
        $i++;

    }

?>

</ul>

    <div id="returntotop" style="display: block">
    <img src="/images/v2-backtotop.png" />
    </div>

<!--</div> comment box -->

    <?php

        print '</div>';

    } else {

        /* 
        USER PAGE LISTINGS */

        print '<ul class="public_entries">';

        $cnt_entries = count($data['entries']);

        if($cnt_entries > 0) {
            foreach ($data['entries'] as $key=>$value)
            {

                if($value['comments_count'] == 0) {
                    $comments = 'no comments yet';
                } elseif($value['comments_count'] > 1) {
                    $comments = $value['comments_count'] . ' comments';
                } else {
                    $comments = $value['comments_count'] . ' comment';
                }

                print '<li class="public_entrybox">
                        <a href="/' . $_SESSION['userpage'] . '/' . $value['id'] . '">
                        <p style="font-weight: 700; text-align: left; margin-left: 10px; margin-top: 10px;">' .  substr($value['title'], 0, 220) . '</p>
                        <p style="padding: 10px 10px 10px 10px; text-align: left;">' .  substr($value['content'], 0, 340) . '...</p>
                ';

                if($value['comments_count'] > 0) {
                    print '
                        <!-- comment bubble -->
                        <a class="tipped" data-title="read the comments" href="/' . $_SESSION['userpage'] . '/' . $value['id'] . '?c=true#comments">
                        <p style="position: absolute; right: 10px; top: 10px; color: #FFF; width: 40px; background-color: black;  height: 23px; 
    width: 23px; 
    padding: 5px; border-radius: 50px; font-size: .7em;">' . $value['comments_count'] . '</p></a>
                        </a>';
                }

                print '</li>';
                //position: absolute; width: 100%; text-align: left; bottom: 10px;

        	}
        } else {
            print '<li style="font-weight: 700;">Sorry, but we couldn\'t locate any public pages.</li>';
        }

    	print '</ul>';
    }

    ?>

<?php
} elseif ($data['userpage'] == 'not-found') { ?>

    <p style="margin-top: 40px; text-align: center; font-weight: 800;">No profile found</p>

<?php
} else { ?>

    <p style="margin-top: 40px; text-align: center; font-weight: 800;">This profile is private</p>

<?php } ?>

 <?php
    if($_SESSION['account_locked'] == 1) {
?>
         <p style="margin-top: 10px; text-align: center; font-weight: 400;">This account may be disabled.</p>
<?php
    }
?>

<!-- everything that is public -->
<!-- <p style="margin: 40px 0 0 40px;">entries found:</p> -->

</div>

    <br style="clear: both" />
  <!--   <p style="text-align: center; color: #FFF; padding: 20px 0 0 0;">sometimes, kismet happens. this is your page &mdash; explore, write, share</p>
 -->
