<?php 

//print number_format($data['allusers_totalwordcount']);

?>

<!-- Add fancyBox  -->
<link rel="stylesheet" href="/lib/js/jquery/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/lib/js/jquery/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>

<script type="text/javascript">
 $(document).ready(function()
 {

    //console.log('jqery-docready-home-view');

$('.join').on("click", function(e) {
    e.preventDefault();
    $('#section_1').fadeToggle();
});

  $('#returntotop, #returntoptoplink').click(function() {
        $("html, body").animate({
        scrollTop: 0
        }, 900, function() {

            <?php if(!isSet($_COOKIE['showlogin'])) { ?>
                $('#createactformarea').show();
                $('#loginformarea').hide();
            <?php } ?>
        });
    });

    $(".fancybox").fancybox({
    		openEffect	: 'fade',
            closeEffect	: 'fade',
            loop : false,
            fitToView : false,
            autoSize : false,
            maxWidth: '70%',
            helpers : {
            overlay : {
                css : {
                    'background' : 'rgba(0,0,0, .9)'
                }
            }
        }

    });

    $('#takeatour').click(function(e) {
        e.preventDefault();
        $(".fancybox").trigger('click');
    });

 	setTimeout(function() { $('#error_box').fadeOut('10000'); }, 3000);
 	$('#pswd').password_strength();

     <?php if(isSet($_SESSION['badlogin_msg'])) { ?>
            $('#createactformarea').hide();
            $('#loginformarea').show();
     <?php } ?>

     <?php if($_COOKIE['showlogin'] == 1 || $_SESSION['create_new_account'] == 1) { ?>
        $('#createactformarea').hide();
        $('#loginformarea').show();
        $('#section_2, #section_3, #section_4').hide();
     <?php } ?>

     $('.loginlink').click(function(e) {
            e.preventDefault();
            $('#createactformarea').hide();
            $('#loginformarea').show();
     });

     $('.signuplink').click(function(e) {
            e.preventDefault();
            $('#createactformarea').show();
            $('#loginformarea').hide();
            $('#login_help').hide();
     });

 	$("#first_name").keyup(function() {

        if( $("#first_name").val() != "" ) {
            $('#label_x_signup_first_name').fadeIn('slow');
        } else {
            $('#label_x_signup_first_name').hide();
        }
    });

    $('#label_x_signup_first_name').click(function() {
         $('#first_name').val("");
          $('#label_x_signup_first_name').fadeOut('slow');
    });

    $("#first_name").blur(function() {
        $('#label_x_signup_first_name').hide();
    });

    $("#email").keyup(function() {
        if( $("#email").val() != "" ) {
            $('#label_x_signup_email').fadeIn('slow');
        } else {
            $('#label_x_signup_email').hide();
        }
    });

    $('#label_x_signup_email').click(function() {
         $('#email').val("");
          $('#label_x_signup_email').fadeOut('slow');
    });

    $("#email").blur(function() {
        $('#label_x_signup_email').hide();
    });

    $("#pswd").keyup(function() {
        if( $("#pswd").val() != "" ) {
            $('#label_x_signup_password').fadeIn('slow');
        } else {
            $('#label_x_signup_password').hide();
        }
    });

    $('#label_x_signup_password').click(function() {
          $('#pswd').val("");
          $('#label_x_signup_password').fadeOut('slow');
    });

    $("#pswd").blur(function() {
        $('#label_x_signup_password').hide();
    });

    $('#label_x_signup_invite').click(function() {
         $('#invite_code').val("");
          $('#label_x_signup_invite').fadeOut('slow');
    });

    $('#button_create').click(function(e) {

        e.preventDefault();
        var skill = $('#skill').val().toLowerCase();
        var arr = [ "writer", "editor", "publisher" ];
        var a = arr.indexOf(skill);

        //console.log($('#skill').val().toLowerCase());

        if( $('#first_name').val() == "" ) {
            $('#first_name').css('background-color','rgba(210,0,0,.3)');
        }

        else if( $('#email').val() == "" ) {
            $('#email').css('background-color','rgba(210,0,0,.3)');
        }

        else if( $('#pswd').val() == "" ) {
            $('#pswd').css('background-color','rgba(210,0,0,.3)');
        }

        else if( $('#skill').val() == "" ) {

            $('#skill').css('background-color','rgba(210,0,0,.3)');
        }
        else if( a == -1) {
            $('#skill').css('background-color','rgba(210,0,0,.3)');
        }
        else {
            $('#createact').submit();
        }

    });

    // $(":input").focus(function() {
    //    $(":input").addClass('form-whiteback-rgba');
    // });

    //  $(":input").blur(function() {
    //    $(":input").removeClass('form-whiteback-rgba');
    // });

    $(":input").addClass('form-whiteback-rgba');

 });
</script>

<div id="whitebackrgba">

    <div id="writingareawrapper" style="width: 100%">

        <div id="section_1" class="home_section_head section_blue">

            <div class="home_section_head_body">
            <h3 style="margin-bottom: 30px; line-height: 1.0em; width: 100%; text-align: center; font-family: 'Open Sans', sans-serif; font-weight: 300;">so, you have a story to write<br /><span style="font-size: .6em;">network with other writers, find an editor & publish</span></h3>

            <!-- Sign up Form -->
    		<div id="createactformarea" class="home_container">
    		<form id="createact" action="/signup" method="post">
    		<input type="hidden" name="new_user" value="<?= time(); ?>" />
            <input type="hidden" name="invite_code" value="forbiddenlove" />

    		<label>name:</label>
    		<input type="text" id="first_name" name="first_name" value="<?= $_SESSION['first_name'] ?>" placeholder="tell us Your Name" />
    		<span id="label_x_signup_first_name"><img class="label_x_signup_first_name" src="/images/v2-icon_x.png" /></span>

    		<label>email (will be used to login):</label>
    		<input type="text" id="email" name="email" size="45" value="<?= $_SESSION['email'] ?>" placeholder="enter your @email for your login" />
    		<span id="label_x_signup_email"><img class="label_x_signup_email" src="/images/v2-icon_x.png" /></span>

    		<label>password:</label>
    		<input id="pswd" type="password" name="password" size="45" value="<?= $_SESSION['tmp_pswd'] ?>" autocomplete="off" placeholder="type a p*ssword h3re" /><span id="label_x_signup_password"><img class="label_x_signup_password" src="/images/v2-icon_x.png" /></span>

            <label>i am a writer, editor or publisher:</label>
            <input id="skill" type="text" name="skill" size="45" value="<?= $_SESSION['tmp_pswd'] ?>" autocomplete="off" placeholder="i am a writer, editor or publisher" /><span id="label_x_signup_password"><img class="label_x_signup_password" src="/images/v2-icon_x.png" /></span>


            <input type="hidden" id="secret_phrase" name="secret_phrase" value="0123456789012345678901234567" />
            <br style="clear: both;" />

            <p style="margin-top: 10px; float: left;"><!-- onclick="return error_chk();" -->
                <span class="button"><a id="button_create" href="#">create my account</a></span>
            </p>

            <p style="margin-top: 25px; float: right; font-weight: 400;">already have an account? <a class="loginlink" href="#">log in now</a></p>

    		</form>
    		<br style="clear: both" />
    		</div><!-- signup form -->

            <!-- login form -->
            <div id="loginformarea" class="home_container">
            <? insert_snip('login_form.php'); ?>
            </div><!-- /login form -->

            </div>

        </div><!-- /sectionhead -->

        <div id="section_5" class="home_section_head section_blue">

            <div class="home_section_head_body v2-section5">

<h3><?= number_format($data['allusers_totalwordcount']); ?> words written by <?= number_format($data['allusers_totalusers']); ?> writers sharing <?= number_format($data['allusers_totalpages']); ?> pages & publishing 6 stories &mdash; the next could be yours.</h3>

                <ul>

                    <?php

                        $i=0;
                        foreach ($data['storytimeline'] as $key=>$value)
                        {
                        
                            if (0 == $i % 2) { 
                                $profile_img_loc = 'left';
                            } else {
                                $profile_img_loc = 'left';
                            }

                            if($value['profileimage'] == '') {
                                $value['profileimage'] = 'default.png';
                            }

                            $title = substr($value['title'], 0,100);
                            $content = substr($value['content'], 0,200);

                            if($value['comments'] > 0) {
                                $comments = '<p class="v2-home-usercomment-bubble">' . $value['comments'] . '</p>';
                            } else {
                                $comments = NULL;
                            }
                             

//$key . "=>" . $value['id'] . "," . $value['title'] . "," . $value['profileimage'] . "<br />";                   
echo <<<EOF
<li>
<a target="_new" href="/{$value['username']}/"><img style="margin-right: 20px;" src="/images-profiles/{$value['profileimage']}" class="v2-section5-img public_user_profile_image v2-section5-img-{$profile_img_loc}" /></a>

{$comments}

<p style="font-weight: 300;">
<a target="_new" href="/{$value['username']}/{$value['id']}/">{$value['username']} shared a new page<br /><i style="font-weight: 700;">{$title}</a></i>
</p>

<p class="v2-home-shared-listing" style="font-weight: 300;">
{$content} ...
</p>
</li>
EOF;
                            $i++;
                        }
                    ?>

                </ul>
        
            </div>

        </div>

        <div id="section_2" class="home_section_head section_blue" style="clear: both; padding-top: 20px;">

            <div class="home_section_head_body">

                <h3>it's not complicated<br />you write, you network, you publish</h3>

            <ul id="about_list">
                <li><span class="bold">Create quick notes</span> while you're working on a page that you're not quite ready to include in that page but will be later.</li>
                <li>Every page <span class="bold">autosaves your writing</span> while you type so you don't have to worry about clicking a submit button.</li>
                <li><span class="bold">Organize your writing into folders</span>. Maybe have one for a novel you're thinking about writing, one for daily creative challenges, another for poetry and yet another for a daily journal.</li>
                <li><span class="bold">Receive live feedback</span> for any public published page with self-moderated comments.</li>
                <li><span class="bold">Tag your writing using keywords</span> so you can easily find it later.</li>
                <li>Just like the good old fashioned garbage can beside your desk, if you don't like what you wrote, crumple it up and toss it in the <span class="bold">trash, not delete</span>. You can always take it out later or empty it.</li>
                <li><span class="bold">Print out a copy</span> with clean edges and no clutter like headers and footers.</li>
                <li><span class="bold">Keyboard shortcuts</span> for hiding your screen [ctrl-z], saving (if you just can't break that [ctrl-s] habit) and a few others like pressing the ESC key saves and displays all your pages.</li>
                <li><span class="bold">Export your writing</span> and folders to a CSV file.</li>
                <li><span class="bold">Share your writing with a public page</span> or make your profile private.</li>
                <li>Have a <span class="bold">"Don't forget to write!" email reminder</span> to you each morning, afternoon or evening.</li>
                <li><span class="bold">White wash your screen</span> so it's just you and your words. No website graphics or toolbar distractions.</li>
            </ul>

            </div>

        </div>

       <!--   <div id="section_3" class="home_section_head">

            <div class="home_section_head_body">

                <p><a class="fancybox" rel="screenshot" href="/images/v2-screen1.jpg" title="Minimalistic Writing Area with tagging support and print view"><img class="scnshot" src="/images/v2-screens.jpg" style="width: 100%;" /></a></p>

                <p style="display: none"><a class="fancybox" rel="screenshot" href="/images/v2-screen2.jpg" title="Edit, Share, Trash and preview word and comment counts"><img class="scnshot" src="/images/v2-screen2.jpg" style="width: 100%; margin-bottom: 20px" /></a></p>

                <p style="display: none"><a class="fancybox" rel="screenshot" href="/images/v2-screen3.jpg" title="Share your pages on your public profile"><img class="scnshot" src="/images/v2-screen3.jpg" style="width: 100%" /></a></p>

                <p style="display: none"><a class="fancybox" rel="screenshot" href="/images/v2-screen5.jpg" title="Anyone can post a comment and give you instant feedback about your writing - writer moderated"><img class="scnshot" src="/images/v2-screen5.jpg" style="width: 33%; float: left;" /></a></p>

                <p style="display: none"><a class="fancybox" rel="screenshot" href="/images/v2-screen4.jpg" title="HTML5 fullscreen support (supported browsers only) creates a distraction free writing zone"><img class="scnshot" src="/images/v2-screen4.jpg" style="width: 33%; float: left;" /></a></p>

                 <p style="display: none"><a class="fancybox" rel="screenshot" href="/images/v2-screen6.jpg" title="Upload a custom profile image, make your profile private, set your default font-size, location and more"><img class="scnshot" src="/images/v2-screen6.jpg" style="width: 33%; float: left;" /></a></p>


                <p style="margin-top: 10px; text-align: center;">
                <span class="button"><a href="#">sign up & write</a></span>
                </p>
            </div>

        </div> -->

        <div id="section_4" class="home_section_head section_blue">

            <div class="home_section_head_body">

                <h3>turning the next page</h3>

                <p>
                    <img src="/images/v2-roadmap.png" style="width: 100%" />
                </p>


                <?php if(!isSet($_COOKIE['showlogin'])) { ?>
                    <p style="padding-top: 30px; padding-bottom: 30px; text-align: center; font-size: 1.2em;"><a id="returntoptoplink" href="#">sign up</a> and be part of our future</a></p>
                <?php } ?>

                <p id="returntotop" style="display: block; text-align: center">
                    <img src="/images/v2-backtotop.png" />
                </p>

            </div>

        </div>

        <p class="v2-mobile-blurb">
        This is the Page126 mobile discovery site. If you would like to join Page126 and write and share your own writing, please visit page126.com from a tablet, laptop or desktop computer for the full site user experience. 
        </p>

    </div><!-- writingareawrapper -->
</div><!-- /whitebackrgba -->

<?php

unset($_SESSION['email_exists']);
unset($_SESSION['invite_bad']);
unset($_SESSION['email']);
unset($_SESSION['v_code']);
unset($_SESSION['first_name']);
unset($_SESSION['badlogin_msg']);
unset($_SESSION['create_new_account']);

?>

