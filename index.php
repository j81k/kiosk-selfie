<?php

/*
 * File Name    : index.php
 * Created On   : 2017-05-28 22:51
 */

include_once './header.php';
?>
    
    <section id="home-page" class="page">
        <div id="web-start-btn">
            <div id="web-start-icon" >
                <img class="icon" src="./images/makeup-icon.png" />
            </div>
            <div id="web-start-text">VISIT OUR PRODUCT</div>
        </div>

        <div id="start-btn">
            <div id="start-icon" >
                <i class="icon fa fa-camera fade"></i>
            </div>
            <div id="start-text">TAKE SELFIE</div>
        </div>
        
    </section>

    <section id="website-page" class="page">
            <div class="header">
                <i class="fa fa-home"></i>
            </div>

            <div class="content"></div>

    </section>

    <section id="prepare-page" class="page">
        <div>
            <div id="get-ready-msg" class="fade middle">Get Ready ...</div>
        </div>        
    </section>

    <section id="init-page" class="page">
        <div class="frame-wrapper frame-full">
            <span id="timer" class=""></span>
            <video id="video-frame" class="frame" autoplay>Video is not supported!</video>
        </div>        
    </section>

    <section id="preview-page" class="page">
        <div class="frame-wrapper">
            <canvas id="preview-frame" class="frame"></canvas>    
        </div>

        <div id="preview-ctrls"> 
            <div id="share-retake-btn">
                <img src="<?php echo SITE_URL; ?>images/retake.png" class="icon" />
            </div>

            <div id="share-home-btn">
                <img src="<?php echo SITE_URL; ?>images/home.png" class="icon" />
            </div>
        </div>

        <div id="share-inp-block" class="middle">
            <i id="inp-clr-btn" class="fa fa-times-circle"></i>
            <input type="text" class="inp email" placeholder="email@example.com" value="" />
            <input type="text" class="inp contact-no" placeholder="xxxxxxxxxx" maxlength="10" />

            <button id="share-sbmt-btn" class="btn">Share</button>
            
        </div>

        <div id="dock-container" class="easy-up">
            <div id="dock">
                <ul>
                    <li>
                        <img src="<?php echo SITE_URL; ?>images/sms.png" id="share-sms-btn" class="icon" />
                        <span class="icon-label">SMS</span>
                    </li>
                    <li>
                        <img src="<?php echo SITE_URL; ?>images/mail.png" id="share-mail-btn" class="icon" />
                        <span class="icon-label">E-Mail</span>
                    </li>
                    <li>
                        <img src="<?php echo SITE_URL; ?>images/facebook.png" id="share-fb-btn" class="icon" />
                        <span class="icon-label">Facebook</span>
                    </li>
                    <li>
                        <img src="<?php echo SITE_URL; ?>images/twitter.png" id="share-tw-btn" class="icon" />
                        <span class="icon-label">Twitter</span>    
                    </li> 
                    <li>
                        <img src="<?php echo SITE_URL; ?>images/print.png" id="share-print-btn" class="icon" />
                        <span class="icon-label">Print</span>
                    </li>
                </ul>
                <div id="share-icon-msg"><i class="fa fa-hand-o-up"></i> Touch the above icon(s) to receive your photo!</div>
                <div class="base"></div>
            </div>
        </div>
        
    </section>




<?php

include_once './footer.php';