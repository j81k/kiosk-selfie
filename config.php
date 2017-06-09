<?php

/*
 * File Name    : config.php
 * Created On   : 2017-05-28 22:56
 */

define( 'SITE_NAME', 'Selfie | Kiosk' );
define( 'SHOT_TIMEOUT', 3 ); // in Seconds
define( 'IDLE_TIMEOUT', 3000 ); // Timeout for get back to Home screen, if not clicked on any sharing option in "preview" page.
define( 'UPLOAD_DIR',	__DIR__ . '/uploads/' );

// Email
define( 'MAIL_FROM',	'Admin<admin@kiosk.com>' );

// ------------------------------------------------------
$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
define( 'SITE_URL',  $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] );

