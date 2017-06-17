<?php

/*
 * File Name    : config.php
 * Created On   : 2017-05-28 22:56
 */

define('SITE_NAME', 'Selfie');
define('SHOT_TIMEOUT', 3); // in Seconds
define('IDLE_TIMEOUT', 30); // Timeout for get back to Home screen, if not clicked on any sharing option in "preview" page.
define('KEYBOARD_CLR', '#333');
define('UPLOAD_DIR', 'uploads/');

// Email
define('MAIL_FROM',	'Admin<j81k@outlook.com>');
define('MAIL_SUBJECT', 'Selfie: Photo is attached!');
define('MAIL_TEMPLATE', 0); 

// SMS
define('SMS_GATEWAY_URL', 'http://sms.glg.co.in/sendsms?uname=atkochennai&pwd=atkochennai&senderid=ATKOWS&route=T');
define('MOBILE_NO_PREFIX', '+91');


// Twitter
define('TWITTER_AUTHOR', 'Admin');

// ------------------------------------------------------
error_reporting(E_ERROR);
define('BASE_DIR', __DIR__ . '/');

$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
define( 'SITE_URL', empty($_SERVER['HTTP_REFERER']) === false ? $_SERVER['HTTP_REFERER'] : $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] );

