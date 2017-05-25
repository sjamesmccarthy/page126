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

    /* submit form */
    $('#signin_button').click(function(e) {

         e.preventDefault();

        if( $('#username').val() == "" ) {
            $('#username').css('background-color','rgba(210,0,0,.3)');
        }

        else if( $('#password').val() == "" ) {
            $('#password').css('background-color','rgba(210,0,0,.3)');
        }

        else {
            $('#login_form').submit();
        }

    });

 });
</script>

<form id="login_form" action="/?func=auth" method="POST">

<label>Username</label>
<input id="username" type="text" name="login_email" placeholder="username or email" value="<?if(isSet($_COOKIE['login_email'])) { print $_COOKIE['login_email']; } else { print $_SESSION['login_email']; } ?>" />
    <span id="label_x_login_username"><img class="label_x_login" src="/images/v2-icon_x.png" /></span>

<label>Password</label>
<input id="password" type="password" name="login_password" placeholder="password"  autocomplete="off" />
    <span id="label_x_login_password"><img class="label_x_login" src="/images/v2-icon_x.png" /></span>

<p style="margin-top: 20px;">
    <span class="button"><a id="signin_button" href="#">Sign In</a></span>
</p>

<?php if(!isSet($_COOKIE['showlogin'])) { ?>
<p style="margin-top: 30px; float: right; font-weight: 400;">Don't have an account? <a class="signuplink" href="/">sign-up</a></p>
<?php } else { ?>
<p style="margin-top: -30px; float: right; font-weight: 400; font-size: .8em;"><a href="/begin-password-reset">Forgot your password?</a></p>
<?php } ?>

</form>

<br style="clear: both" />

<?
if(isSet($_SESSION['badlogin_msg'])) {
    if(isSet($_SESSION['badlogin_msg_bckg'])) {
        $style_override = 'style="background-color: rgba' . $_SESSION['badlogin_msg_bckg'] . '";';
    }
?>

<div id="login_help" <?= $style_override ?>>
    <?= $_SESSION['badlogin_msg'] ?>
</div>

<? }  ?>
