<?php

/*
 * File Name    : header.php
 * Created On   : 2017-05-28 22:50
 */

require_once './config.php';

?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=EDGE,chrome=1" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0 minimal-ui" />
        
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        
        <meta name="description" content="" />
		<meta name="author" content="" />
		<meta name="keywords" content="" />
        
        <title><?php echo SITE_NAME; ?></title>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_URL; ?>images/favicon.png" />
        
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/style.css" />
        <script type="text/javascript">
            var siteUrl     = '<?php echo SITE_URL; ?>';
            var altServer   = '<?php echo ALT_SERVER; ?>';
            var timerOut    = <?php echo SHOT_TIMEOUT; ?>;
            var idleTimeout = <?php echo IDLE_TIMEOUT; ?>;
            var keybrdClr   = '<?php echo KEYBOARD_CLR; ?>';
            var webLink     = '<?php echo WEB_LINK; ?>';
        </script>
        
    </head>

    <body class="bg-home">
        <header>
            
        </header>
        
        <section>
            <div id="loading-popup" class="dialog">
                <div class="wrapper">
                    <div class="header">
                        <i class="fa fa-info-circle"></i> 
                        <span class="title">Please wait ...</span>
                    </div>

                    <div class="content">
                        <div id="loader" class="status-text success">
                            <i class="fa fa-spinner rotate"></i>
                            <p class="fade">Loading ...</p>
                        </div>
                    </div>

                    <div class="footer">&nbsp;</div>
                </div>
            </div>

            <div id="loader-popup" class="dialog">
                <div class="wrapper">
                    <div class="header">
                        <i class="fa fa-info-circle"></i> 
                        <span class="title">Please wait ...</span>
                    </div>

                    <div class="content">
                        <div id="loader" class="status-text success">
                            <i class="fa fa-spinner rotate"></i>
                            <p class="fade">Sharing your photo ...</p>
                        </div>
                    </div>

                    <div class="footer">&nbsp;</div>
                </div>
            </div>


            <div id="popup" class="dialog">
                <div class="wrapper">
                    <div class="header">
                        <i class="fa fa-info-circle"></i> 
                        <span class="title">Thank you</span>
                        <i class="fa fa-times close-btn"></i> 
                    </div>

                    <div class="content">
                        <div class="status-text success">
                            <i class="fa fa-check-circle fade"></i>
                            <p>Success: The Photo has been shared..</p>
                        </div>
                        <div>    
                            <p>Thank you for using the application.</p>
                        </div>        
                    </div>

                    <div class="footer">&nbsp;</div>
                </div>
            </div>
            
    



