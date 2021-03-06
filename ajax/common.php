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

	$remoteAddr = str_replace(':', '', $_SERVER['REMOTE_ADDR']);
	$isAltServer = empty(ALT_SERVER) === false ? true : false;

	$templatePath = UPLOAD_DIR.'templates/';
	makeDir($templatePath);	

	/*
	* Write img data as file
	*/
	$photoPath = BASE_DIR . $templatePath . 'img_original_'. $remoteAddr .'_'.time().'.png'; 
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
	$templatePath = $templatePath . 'img_'. $remoteAddr .'_'.time().'.png'; 
	imagepng($template, BASE_DIR . $templatePath);

	$templateUrl = ($isAltServer ? dirname(ALT_SERVER) . '/' : SITE_URL) . $templatePath;
	$templateData = base64_encode(file_get_contents(BASE_DIR . $templatePath));
	
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
						'templateData'	=> $templateData,
						'templatePath' => $templatePath,

					];

					$return = curl(ALT_SERVER, $data);	
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
						'templateData'	=> $templateData, //'@'. BASE_DIR . $templatePath,
						'templatePath' => $templatePath,

						'mailTo'	=> $mailTo,
						'body'		=> $body,
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
			//makeDir($returnPath);

			$return = [
				'action'		=> $action,
				'templateData'	=> $templateData, 
				'templatePath' 	=> $templatePath,

				'metaPath'	=> $returnPath,
				'title'		=> 'Happy Birthday',
				'tags'		=> 'Birthday',
				'via'		=> TWITTER_USER,
				'author'	=> TWITTER_AUTHOR,
				'desc'		=> 'Many more happy returns of the day!'
			];

			curl(ALT_SERVER, $return);
			unset($return['templateData']);
			$return['tinyPath'] = tinyUrl($_POST['appUrl'] . $return['metaPath']);

		break;

		case 'facebook' :

			$return = [
				'action'		=> $action,
				'templatePath' 	=> $templatePath,
				'templateData'	=> $templateData,

				'title'		   => 'Happy Birthday'
			];

			curl(ALT_SERVER, $return);
			unset($return['templateData']);
			$return['tinyPath'] = tinyUrl($_POST['appUrl'] . $return['templatePath']);

		break;

		default :
			$return = [
				'templatePath'	=> $templatePath
			];
			
		break;

	endswitch;

	unlink($photoPath);
	if ($isAltServer) {
		unlink(BASE_DIR . $templatePath);
	}

	echo json_encode($return);
	