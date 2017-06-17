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
            var timerOut    = <?php echo SHOT_TIMEOUT; ?>;
            var idleTimeout = <?php echo IDLE_TIMEOUT; ?>;
            var keybrdClr   = '<?php echo KEYBOARD_CLR; ?>';
        </script>
        
    </head>

    <body class="bg-home">
        <header>
            
        </header>
        
        <section>
            
            
    



