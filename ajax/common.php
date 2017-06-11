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

function pre($data) 
{
	echo '<pre>Data: '; print_r($data); die;
}
