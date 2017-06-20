<?php
	/*
	* Filename 	: common.php (Ajax)
	* Created On: 2017-05-30 15:58 
	*/

	$action = $_POST['action'];
	$data   = $_POST['data'];
	
	$imgData = base64_decode(str_replace(' ', '+', str_replace('data:image/png;base64,' , '', $data['img'])));

	if (empty($imgData)) 
		die('Image data is empty!');

	include_once '../config.php';
	include_once '../functions.php';

	$isAltServer = empty(ALT_SERVER) === false ? true : false;

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

	$templateUrl = $isAltServer ? (dirname(ALT_SERVER) . '/' : SITE_URL) . $templatePath;
	
	switch( $action ) :

		case 'sms' :

			$mobileNo = $data['contactNo'];
			$message = 'Happy Birthday ...' . tinyurl($templateUrl);

			if (empty($mobileNo) === false) {
				$output = sendSms($mobileNo, $message);
				if (!is_numeric($output)) {
					// Error	
					$return = ['success' => false, 'message' => 'SMS is not sent!' . "\r\n" . (empty($output) === false ? $output : 'Oops: something went wrong.')];

				} else if ($isAltServer) {
					
					/*
					* Upload Image to Alternative Server
					*/

					$data = [
						'action'	=> $action,
						'templateData'	=> '@'.BASE_DIR . $templatePath,
						'templatePath' => $templatePath,

					];

					curl(ALT_SERVER, $data);	
				}

			}else {
				$return = ['success' => false, 'message' => 'Mobile Number is empty!'];
			}
			
		break;

		case 'mail' :

			$mailTo = $data['emailTo'];
			if (empty($mailTo) === false ) {
				preg_match('/^(.*?)\@/', $mailTo, $mtchs); 
				$name = ucwords(str_replace('.', ' ', $mtchs[1]));

				$body = file_get_contents(BASE_DIR . 'templates/mail/template-' . MAIL_TEMPLATE . '.php');
				$body = preg_replace('/\{name\}/', '<b>'. $name .'</b>', $body);
				$body = preg_replace('/\{templateImg\}/', '<img src="'. $templateUrl .'" />', $body);

				if ($isAltServer) {
					$data = [
						'action'	=> $action,
						'mailTo'	=> $mailTo,
						'body'		=> $body,
						'templateData'	=> '@'.BASE_DIR . $templatePath,
						'templatePath' => $templatePath,

						'MAIL_FROM'	=> MAIL_FROM,
						'MAIL_SUBJECT' => MAIL_SUBJECT,
					];

					$return = curl(ALT_SERVER, $data);	
				} else {
					// Sent from Local	
					$return = sendMail($mailTo, $template, $body);
				}
				
				$return = [
					'success' => $return
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

	unlink($photoPath);
	if ($isAltServer) {
		unlink(BASE_DIR . $templatePath);
	}

	echo json_encode($return);
	