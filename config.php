<?php

/*
 * File Name    : config.php
 * Created On   : 2017-05-28 22:56
 */

define('SITE_NAME', 'Kiosk-Selfie');
define('SHOT_TIMEOUT', 3); // in Seconds
define('IDLE_TIMEOUT', 300); // Timeout for get back to Home screen, if not clicked on any sharing option in "preview" page.
define('KEYBOARD_CLR', '#333');
define('UPLOAD_DIR', 'uploads/');

// Email
define('MAIL_FROM',	'Admin<j81k@outlook.com>');
define('MAIL_SUBJECT', 'Selfie: Photo is attached!');
define('MAIL_TEMPLATE', 0); 

// SMS
define('SMS_GATEWAY_URL', 'http://sms.glg.co.in/sendsms?uname=selfie&pwd=selfie&senderid=SELFIE&route=T');
define('MOBILE_NO_PREFIX', '+91');


// Twitter
define('TWITTER_USER', 'Selfie');
define('TWITTER_AUTHOR', 'Admin');

// Website Link
define('WEB_LINK', 'http://naturals.in/');

// Live Server
define('ALT_SERVER', 'http://www.touchscreenkiosk.co.in/selfie/script.php'); //http://atkoscales.com/kiosk/alt-server/script.php'); 

// ------------------------------------------------------
error_reporting(E_ERROR);
define('BASE_DIR', __DIR__ . '/');

$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
define( 'SITE_URL', empty($_SERVER['HTTP_REFERER']) === false ? $_SERVER['HTTP_REFERER'] : $protocol.'://'.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);


