<script type="text/javascript">
 $(document).ready(function()
 {

    $("#username").keyup(function() {
        if( $("#username").val() != "" ) {
            $('#label_x_login_username').fadeIn('slow');
        } else {
            $('#label_x_login_username').hide();
        }
    });

    $('#label_x_login_username').click(function() {
         $('#username').val("");
          $('#label_x_login_username').fadeOut('slow');
    });

    $('#username').blur(function() {
          $('#label_x_login_username').fadeOut('slow');
    });

    $("#password").keyup(function() {
        if( $("#password").val() != "" ) {
            $('#label_x_login_password').fadeIn('slow');
        } else {
            $('#label_x_login_password').hide();
        }
    });

    $('#label_x_login_password').click(function() {
         $('#password').val("");
          $('#label_x_login_password').fadeOut('slow');
    });

    $('#password').blur(function() {
          $('#label_x_login_password').fadeOut('slow');
    });

    $('#getinvite').click(function(e) {
        e.preventDefault();
        $('#login_form').fadeToggle('slow', function() {
            $('#mailchimp-signupbeta').fadeToggle('slow');
        });

    });

    $('#getinvite_cancel').click(function(e) {
        e.preventDefault();
        $('#mailchimp-signupbeta').fadeToggle('slow', function() {
            $('#login_form').fadeToggle('slow');
        });

    });

 });
</script>

<form id="login_form" action="index.php?func=auth" method="POST">

<label>Username</label>
<input id="username" type="text" name="login_email" placeholder="username" value="<?if(isSet($_COOKIE['login_email'])) { print $_COOKIE['login_email']; } else { print $_SESSION['login_email']; } ?>" />
    <span id="label_x_login_username"><img class="label_x_login" src="/images/v2-icon_x.png" /></span>

<label>Password</label>
<input id="password" type="password" name="login_password" placeholder="password"  autocomplete="off" />
    <span id="label_x_login_password"><img class="label_x_login" src="/images/v2-icon_x.png" /></span>

<p style="margin-top: 30px;"><span class="button"><a id="button_login" href="#">sign in & write</a></span></p>

<!-- getinvite -->
<p style="margin-top: 20px; float: right;"><a href="/about">about</a> | <a id="create" href="/signup">sign-up</a></p>

</form>

<br style="clear: both;" />

<div id="mailchimp-signupbeta">
    <!--
<form action="http://pageonetwentysix.us8.list-manage.com/subscribe/post?u=fe32bbab51d9aa19f527a0c18&amp;id=af31646d24" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

    	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="your email">

        <input type="text" name="b_fe32bbab51d9aa19f527a0c18_af31646d24" tabindex="-1" value="" style="display: none;">
        <input type="submit" value="request invite" name="subscribe" id="mc-embedded-subscribe" class="button" style="border: 0; cursor: pointer; font-size: 1.2em">
    </form>
-->
<p>Request an invite via Twitter by tweeting us<br /><a href="http://twitter.com/nomoreblacktea" target="_new">"@nomoreblacktea #amwriting send me an invite for #pageonetwentysix"</a>.</p>

<p style="padding-top: 20px;">
<a href="https://twitter.com/intent/tweet?button_hashtag=pageonetwentysix&text=%40nomoreblacktea%20%23amwriting%20send%20me%20an%20invite%20for" class="twitter-hashtag-button" data-size="large" data-related="page126,nomoreblacktea" data-url="http://pageonetwentysix.com">Tweet #pageonetwentysix</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</p>

    <p id="hr"></p>
    <p><a id="getinvite_cancel-no" href="/">back</a></p>
</div>
