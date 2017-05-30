<?php

/*
 * File Name    : index.php
 * Created On   : 2017-05-28 22:51
 */

include_once './header.php';
?>
    
    <section id="home-page" class="page">
        <div id="start-btn" class="fade middle">
            <i id="start-icon" class="fa fa-play"></i>
            <span>Start</span>
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
        
        <div id="share-pane" class="pop-down">
            <i id="share-home-btn" class="fa fa-home icon"></i>
            <i class="fa fa-mail-reply icon"></i>
            <i id="share-retake-btn" class="fa fa-recycle icon"></i>
            <i class="fa fa-facebook-f icon"></i>
            <i class="fa fa-twitter icon"></i>
        </div>
    </section>


<?php

include_once './footer.php';