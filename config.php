<?php

/*
 * File Name    : config.php
 * Created On   : 2017-05-28 22:56
 */

define( 'SITE_NAME', 'Kiosk' );
define( 'SHOT_TIMEOUT', 3); // in Seconds
define( 'IDLE_TIMEOUT', 30 ); // Timeout for get back to Home screen, if not clicked on any sharing option in "preview" page.

// ------------------------------------------------------
$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
define( 'SITE_URL',  $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] );

//echo '<pre>'; print_r( SITE_URL ); die;

