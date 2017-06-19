<?php

# Functions
# -------------------------

function sendSms($mobileNo, $message = '')
{
	$url = SMS_GATEWAY_URL . '&to='. MOBILE_NO_PREFIX . $mobileNo . '&msg=' . urlencode($message);

	return curl($url);
}


function sendMail($to, $photoData, $body, $photoName = 'Photo', $subject = '', $filetype = 'image/png')
{
	$bound_text = md5(uniqid(rand(), true));;
	$bound = "--" . $bound_text . "\r\n";
	$bound_last = "--" . $bound_text . "--\r\n";

	$headers = "From:" . MAIL_FROM . "\r\n"
			 . "MIME-Version: 1.0\r\n"
			 . "Content-Type: multipart/mixed; boundary=\"$bound_text\"";

	$message =	"Sorry, your client doesn't support MIME types.\r\n"
			 . $bound;

	$message .=	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
			 . "Content-Transfer-Encoding: 7bit\r\n\r\n"
			 . $body
			 . $bound;

	$file =	$photoData;

	$message .=	"Content-Type: $filetype; name=\"$photoName\"\r\n"
			 . "Content-Transfer-Encoding: base64\r\n"
			 . "Content-disposition: attachment; file=\"$photoName\"\r\n"
			 . "\r\n"
			 . chunk_split(base64_encode($file))
			 . $bound_last;

	$subject = empty($subject) === false ? $subject : MAIL_SUBJECT;		 
	if (mail($to, $subject, $message, $headers)) {
	     return true;
	} 
	else 
	{ 
	    return false;
	}
}

function tinyUrl($url)
{

	return curl('http://tinyurl.com/api-create.php?url=' . urlencode($url));
}

function makeDir($dir)
{

	if( !file_exists($dir) ) :
		mkdir( BASE_DIR . $dir, 777, TRUE );
		chmod( BASE_DIR . $dir, 777, TRUE );
	endif;
}

function curl($url, $data = [])
{

	if (empty($url) === false) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if (empty($data) === false) {
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		$return = curl_exec($ch);

		curl_close($ch);

		return $return;	
	}

	return $url;
}

function pre($data) 
{
	echo '<pre>Data: '; print_r($data); die;
}


