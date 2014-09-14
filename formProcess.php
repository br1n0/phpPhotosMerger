<?php
/**
* @package phpPhotosMerger
* @author Bruno Trombi <bruno.trombi@gmail.com>
* @copyright 2014 Bruno Trombi
* @license http://www.gnu.org/licenses/gpl-3.0.txt
*
*process the uploaded files
**/


/*
* process the files uploaded in the form 
* in case of error, return a minimal feedback to user 
* otherwise call hideMovingObjects that create an imaged 
* and return the image to the user.
*/

require('libUpload.php');
require('libImageMovingObjectsRemove.php');

	$accepted= array();
	$errors= array();
	$r=uploadedFilesManage( $accepted, $errors ,300  );
	if( empty( $errors ) )
	{	

		//merge the image, removing pixel that are not common on most of images;
		$gd=hideMovingObjects( $accepted );

		//set the header properly
		header('Content-Type: image/jpeg');
		//imagejpeg($gd,'last.jpg');
		imagejpeg($gd);

	}
	
	else 		//give a minum of feedback on files skipped
	{
		echo "<html><body>";
		foreach( $errors as $errorFile )
		{
			echo "<br>" .$errorFile["name"] ."  <br>   errors: " ;
			foreach(  $errorFile["error_msg"] as $msg )
				echo "<br>$msg";
			echo "<hr>";
		}
		echo "</body></html>";
	}
?>
