<?php if(!defined('APP-START')) { echo 'RESTRICTED-PAGE-LOGIN-REQUIRED'; exit; } ?>

<script>

 $(document).ready(function()
 {

  

 });

</script>

<div id="whitebackrgba" class="padbottom">

    <div id="writingareawrapper" class="section_blue section_blue_padding">

	<h3 style="font-weight: 300;">Welcome, <span style="font-weight: 700;"><a target="_new" class="tipped" data-title="preview your public profile" href="/<?= $_SESSION['username'] ?>/"><?= $_SESSION['username'] ?></a></span></h3>
	<!-- <p class="padtop">You have written 10,903 words in 10 folders and 12 pages since April 12, 2010 which awesome!</p>
 -->

		 <div class="home_section_head_body v2-section5" style="float:left; width: 70%; margin:0; padding: 0; margin-top: 60px; padding-right: 20px; border-right: 1px solid #AAA;">

			<h4 style="text-align: left; padding: 0; margin: 0; font-weight: 700">look who's sharing ...</h4>
<!-- <h3>Over <?= number_format($data['allusers_totalwordcount']); ?> words written<br />by <?= number_format($data['allusers_totalusers']); ?> writers who have shared more than <?= number_format($data['allusers_totalpages']); ?> pages</h3>
 -->
                <ul style="margin-top: 15px; padding-bottom: 40px;">

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

                            $title = substr($value['title'], 0,15);
                            $content = substr($value['content'], 0,150);

                            if($value['comments'] > 0) {
                                $comments = '<p class="v2-home-usercomment-bubble">' . $value['comments'] . '</p>';
                            } else {
                                $comments = NULL;
                            }


//$key . "=>" . $value['id'] . "," . $value['title'] . "," . $value['profileimage'] . "<br />";                   
echo <<<EOF
<li style="border-bottom: none;">
<a target="_new" href="/{$value['username']}/"><img style="margin-right: 20px;" src="/images-profiles/{$value['profileimage']}" class="v2-section5-img public_user_profile_image v2-section5-img-{$profile_img_loc}" /></a>


{$comments}

<p style="font-weight: 700;">
<a target="_new" href="/{$value['username']}/{$value['id']}/">{$value['username']} shared a new page, <i>{$title}</a></i>
</p>

<p style="font-weight: 300; color: #FFF;">
{$content} ...
</p>
</li>
EOF;
                            $i++;
                        }
                    ?>

                </ul>
        
        	</div>

        	<div style="float: left; width: 25%;  margin-top: 65px; margin-left: 20px;">
        		<h4 style="font-weight: 700; padding-bottom: 20px">your newsfeed</h4>

                <!-- look at the user_transactions table to populate -->
        		<!-- <p>nothing to share as of yet, but keep your eyes open.</p> -->

                 <?php

                        $i=0;
                        foreach ($data['user_transactions'] as $key=>$value)
                        {
                            print '<p style="line-height: 1.2em; padding-bottom: 10px; font-size: .8em">';
                            print $value['action'];
                            print '</p>';
                            $i++;
                        }

                ?>

        		<p style="padding-top: 10px;">
        			Find Out What's New<br />
                    <a href="/releasenotes">&raquo; Read the Release Notes</a>
        		</p>

        	</div>
	</div>
</div>

<p style="clear: both; padding-bottom: 40px;"><!--stupid place holder--></p>


