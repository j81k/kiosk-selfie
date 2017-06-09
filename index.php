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
        </div>

        <div id="share-inp-block" class="middle">
            <form>
                <input type="email" class="inp email" placeholder="email@example.com" />
                <input type="text" class="inp contact-no" placeholder="+91xxxxxxxxxx" />

                <button class="btn">Share</button>
            </form>
        </div>
        
        <div id="share-pane" class="pop-down">
            <i id="share-home-btn" class="fa fa-home icon"></i>
            <i id="share-sms-btn" class="fa fa-commenting icon"></i>
            <i id="share-mail-btn" class="fa fa-envelope-o icon"></i>
            <i id="share-retake-btn" class="fa fa-recycle icon"></i>
            <i id="share-fb-btn" class="fa fa-facebook-f icon"></i>
            <i id="share-tw-btn" class="fa fa-twitter icon"></i>
            <i id="share-print-btn" class="fa fa-print icon"></i>
        </div>



    </section>




<?php

include_once './footer.php';