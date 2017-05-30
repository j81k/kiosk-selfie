<?php
	/*
	* Filename 	: common.php (Ajax)
	* Created On: 2017-05-30 15:58 
	*/

	include_once '../config.php';

	$action = $_POST['action'];
	$data   = $_POST['data'];

	$img_data = base64_decode( str_replace(' ', '+', str_replace('data:image/png;base64,' , '', $data['img'] ) ) );

	if( empty( $img_data ) ) 
		die('Image data is empty!');

	if( !file_exists( UPLOAD_DIR ) ) :
		mkdir( UPLOAD_DIR, 777, TRUE );
		chmod( UPLOAD_DIR, 777, TRUE );
	endif;

	/*
	* Write img data as file
	*/
	file_put_contents( UPLOAD_DIR . 'img_'.$_SERVER['REMOTE_ADDR'].'_'.time().'.png', $img_data );


	switch( $action ) :

		case 'mail' :



		break;

	endswitch;


