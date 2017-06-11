<?php

/*
 * File Name    : config.php
 * Created On   : 2017-05-28 22:56
 */

//phpinfo(); die;

error_reporting(E_ERROR);
define('SITE_NAME', 'Selfie');
define('SHOT_TIMEOUT', 3); // in Seconds
define('IDLE_TIMEOUT', 30); // Timeout for get back to Home screen, if not clicked on any sharing option in "preview" page.
define('BASE_DIR', __DIR__ . '/');
define('UPLOAD_DIR', 'uploads/');

// Email
define('MAIL_FROM',	'Admin<admin@kiosk.com>');
define('MAIL_SUBJECT', 'Selfie: Photo is attched!');
define('MAIL_TEMPLATE', 0); 


// Twitter
define('TWITTER_AUTHOR', 'Admin');

// ------------------------------------------------------
$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
define( 'SITE_URL', empty($_SERVER['HTTP_REFERER']) === false ? $_SERVER['HTTP_REFERER'] : $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] );

