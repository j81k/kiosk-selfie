<?php

/*
 * File Name    : index.php
 * Created On   : 2017-05-28 22:51
 */

include_once './header.php';
?>
    
    <section id="home-page" class="page">
        <div id="start-btn">
            <div id="start-icon" >
                <i class="fa fa-camera fade"></i>
            </div>
            <div id="start-text">TAP TO START</div>
        </div>
        
    </section>

    <section id="prepare-page" class="page">
        <div>
            <div id="get-ready-msg" class="fade middle">Get Ready ...</div>
        </div>        
    </section>

    <section id="init-page" class="page">
        <div class="frame-wrapper">
            <span id="timer" class=""></span>
            <video id="video-frame" class="frame" autoplay>Video is not supported!</video>
        </div>        
    </section>

    <section id="preview-page" class="page">
        <div class="frame-wrapper">
            <canvas id="preview-frame" class="frame"></canvas>    
            <div id="preview-bottom"></div>
        </div>

        <div id="share-inp-block" class="middle">
            
            <input type="email" class="inp email" placeholder="email@example.com" value="" />
            <input type="text" class="inp contact-no" placeholder="xxxxxxxxxx" maxlength="10" />

            <button id="share-sbmt-btn" class="btn">Share</button>
            
        </div>
        
        <div id="share-pane" class="pop-down">
            <img src="<?php echo SITE_URL; ?>images/retake.png" id="share-retake-btn" class="icon" />
            <img src="<?php echo SITE_URL; ?>images/sms.png" id="share-sms-btn" class="icon" />
            <img src="<?php echo SITE_URL; ?>images/mail.png" id="share-mail-btn" class="icon" />
            <img src="<?php echo SITE_URL; ?>images/home.png" id="share-home-btn" class="icon" />
            <img src="<?php echo SITE_URL; ?>images/facebook.png" id="share-fb-btn" class="icon" />
            <img src="<?php echo SITE_URL; ?>images/twitter.png" id="share-tw-btn" class="icon" />
            <img src="<?php echo SITE_URL; ?>images/print.png" id="share-print-btn" class="icon" />
        </div>



    </section>




<?php

include_once './footer.php';