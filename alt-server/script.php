<?php
	/*
	* Filename 	: common.php (Curl)
	* Created On: 2017-06-19 13:07 
	*/

	define('BASE_DIR', __DIR__ . '/');
	$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
	define('SITE_URL', empty($_SERVER['HTTP_REFERER']) === false ? $_SERVER['HTTP_REFERER'] : $protocol.'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).'/');


	$uploadDir = dirname($_POST['templatePath']);
	makeDir($uploadDir);

	$templateData = base64_decode($_POST['templateData']);
	file_put_contents(BASE_DIR . $_POST['templatePath'], $templateData);

	$return = array();
	switch ($_POST['action']) {

		case 'mail':

			define('MAIL_FROM', $_POST['MAIL_FROM']);
			define('MAIL_SUBJECT', $_POST['MAIL_SUBJECT']);

			$return = sendMail($_POST['mailTo'], $templateData, $_POST['body']);

		break;

		case 'twitter':

			makeDir($_POST['metaPath']);

			$html = '<!DOCTYPE html><html><head>'
				 . "\r\n" .	'<meta charset="utf-8" />'	
				 . "\r\n" .	'<meta name="twitter:card" content="summary_large_image" />'
				 . "\r\n" . '<meta name="twitter:site" content="@'. $_POST['via'] .'" />'
				 . "\r\n" . '<meta name="twitter:creator" content="@'. $_POST['author'] .'" />'
				 . "\r\n" .'<meta name="twitter:title" content="'. $_POST['title'] .'" />'
				 . "\r\n" .	'<meta name="twitter:description" content="'. $_POST['desc'] .'" />'
				 . "\r\n" . '<meta name="twitter:image" content="'. SITE_URL . $_POST['templatePath'] .'" />' 
				 . "\r\n" . '</head><body></body></html>'; 

			file_put_contents(BASE_DIR . $_POST['metaPath'] . 'index.html', $html);

		break;

		case 'facebook':

			$return['templateUrl'] = SITE_URL . $_POST['templatePath'];

		break;

	}
		

	echo json_encode($return);


	# Functions
	# -----------

	function sendMail($to, $photoData, $body, $photoName = 'Photo', $subject = '', $filetype = 'image/png')
	{
		$bound_text = md5(uniqid(rand(), true));
		$bound = "--" . $bound_text . "\r\n";
		$bound_last = "--" . $bound_text . "--\r\n";

		$headers = "From:" . MAIL_FROM . "\r\n"
				 . "MIME-Version: 1.0\r\n"
				 . "Content-Type: multipart/mixed; boundary=\"$bound_text\"";

		$message =	"Sorry, your client doesn't support MIME types.\r\n"
				 . $bound;

		$message .=	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
				 . "Content-Transfer-Encoding: 7bit\r\n\r\n"
				 . $body;
				 

		/*$message .= $bound
				 . "Content-Type: $filetype; name=\"$photoName\"\r\n"
				 . "Content-Transfer-Encoding: base64\r\n"
				 . "Content-disposition: attachment; file=\"$photoName\"\r\n"
				 . "\r\n"
				 . chunk_split(base64_encode($photoData));*/

		$message .= $bound_last;

		$subject = empty($subject) === false ? $subject : MAIL_SUBJECT;		 
		if (mail($to, $subject, $message, $headers)) {
		     return true;
		} 
		else 
		{ 
		    return false;
		}
	}


	function makeDir($dir)
	{

		if( !file_exists($dir) ) :
			mkdir(BASE_DIR . $dir, 777, TRUE);
			//chmod(BASE_DIR . $dir, 777);
		endif;
	}

	function pre($data, $die = true) 
	{
		echo '<pre>Data: '; print_r($data); 
		if ($die) {
			die;
		}
	}