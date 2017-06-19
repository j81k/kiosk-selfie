<?php
	/*
	* Filename 	: common.php (Curl)
	* Created On: 2017-06-19 13:07 
	*/

	$return = [];
	switch ($_POST['action']) {

		case 'mail':

			define('MAIL_FROM', $_POST['MAIL_FROM']);
			define('MAIL_SUBJECT', $_POST['MAIL_SUBJECT']);

			$return = sendMail($_POST['mailTo'], $_POST['template'], $_POST['body']);

		break;

	}

	echo json_encode($return);


	# Functions
	# -----------

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
				 . chunk_split($file)
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
