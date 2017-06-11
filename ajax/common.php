<?php
	/*
	* Filename 	: common.php (Ajax)
	* Created On: 2017-05-30 15:58 
	*/

	include_once '../config.php';

	$action = $_POST['action'];
	$data   = $_POST['data'];

	$imgData = base64_decode( str_replace(' ', '+', str_replace('data:image/png;base64,' , '', $data['img'] ) ) );

	if( empty( $imgData ) ) 
		die('Image data is empty!');


	$templatePath = UPLOAD_DIR.'templates/';
	makeDir($templatePath);	

	/*
	* Write img data as file
	*/
	$photoPath = BASE_DIR . $templatePath . 'img_original_'.$_SERVER['REMOTE_ADDR'].'_'.time().'.png'; 
	file_put_contents($photoPath, $imgData );
	$imgData = imagecreatefrompng($photoPath);

	$templateName = empty($data['template']) === false ? $data['template'] : 'template-0';
	$template = imagecreatefrompng('../images/templates/'. $templateName .'.png');

	//imagealphablending($template, false);
	imagesavealpha($template, true);

	switch ($templateName) {

		default:
		case 'template-0' :
			$xD = 188;
			$yD = 36;
		break;
	}

	imagecopyresampled($template, $imgData, $xD, $yD, 0, 0, 610, 434, 610, 434); // 615, 468

	$templatePath = $templatePath . 'img_'.$_SERVER['REMOTE_ADDR'].'_'.time().'.png'; 
	imagepng($template, BASE_DIR . $templatePath);
	unlink($photoPath);


	switch( $action ) :

		case 'mail' :

			$mailTo = $data['emailTo'];
			if (empty($mailTo) === false ) {
				preg_match('/^(.*?)\@/', $mailTo, $mtchs); 
				$name = Ucwords(str_replace('.', ' ', $mtchs[1]));

				$body = file_get_contents(BASE_DIR . 'templates/mail/template-'.MAIL_TEMPLATE.'.php');
				$body = preg_replace('/\{name\}/', '<b>'. $name .'</b>', $body);
				$body = preg_replace('/\{templateImg\}/', '<img src="'.SITE_URL . $templatePath.'" />', $body);

				$return = [
					'success' => sendMail($mailTo, $template, $body)
				];
			}
			else {
				$return = ['success' => false, 'message' => 'Email To Address is empty!'];
			}

			
		break;

		case 'twitter' :

			$returnPath = UPLOAD_DIR . 'twitter/';
			makeDir($returnPath);

			$return = [
				'metaPath'	=> urlencode($returnPath),
				'title'		=> 'Happy Birthday',
				'tags'		=> 'Birthday',
				'via'		=> SITE_NAME,
				'author'	=> TWITTER_AUTHOR,
				'desc'		=> 'Many more happy returns of the day!'
			];

			$html = '<html><head>'
				 . 		'<meta name="twitter:card" content="summary_large_image">'
				 . 		'<meta name="twitter:site" content="@'. $return['via'] .'">'
				 . 		'<meta name="twitter:creator" content="@'. $return['author'] .'">'
				 . 		'<meta name="twitter:title" content="'. $return['title'] .'">'
				 .		'<meta name="twitter:description" content="'. $return['desc'] .'">'
				 .		'<meta name="twitter:image" content="'. SITE_URL . $templatePath .'">' // http://graphics8.nytimes.com/images/2012/02/19/us/19whitney-span/19whitney-span-articleLarge.jpg
				 . '</head><body></body></html>'; 

				 
			file_put_contents(BASE_DIR . $returnPath . 'index.php', $html);	 

		break;

		default :
			$return = $templatePath;
		break;

	endswitch;

	echo json_encode($return);
	


# Functions
# -------------------------
function makeDir($dir)
{

	if( !file_exists($dir) ) :
		mkdir( BASE_DIR . $dir, 777, TRUE );
		chmod( BASE_DIR . $dir, 777, TRUE );
	endif;
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

	$file =	$photoData;//file_get_contents(BASE_DIR . $photoPath);

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

function pre($data) 
{
	echo '<pre>Data: '; print_r($data); die;
}
