<?php
/**
* @package phpPhotosMerger
* @author Bruno Trombi <bruno.trombi@gmail.com>
* @copyright 2014 Bruno Trombi
* @license http://www.gnu.org/licenses/gpl-3.0.txt
*
*manage the upload files, and filter into $accpted and $errors
**/


/*
the $_FILES are splitted on multiple arrays, so arno not simple to handle with a foreach loop, 
this repack the info together, so each row contains all informations of each file,
and filter the empty elemnts of array,
The files with and filter not allowed the types or with files size that exceed the maxSizeKb will be put on $error, and a error message will be added.

$accepted is reference of an array of accpeted filenames
$errors is a refere of an array of filenames with errors
$maxSizeKb maximum filesize in Kb for each files
$allowedExts allowed mime types

return an array of all the input of accepted files;
*/
function uploadedFilesManage( &$accepted, &$errors ,$maxSizeKb, $allowedExts = array("image/jpeg", "image/jpg")  )
{
	//convert maxSizeKb to bytes
	$maxSizeBytes= 1024 * $maxSizeKb;

	//$input= array();
	$n= @sizeof( $_FILES["file"]["name"] );
	for($i=0; $i<$n ;$i++ )
	{


		$error= $_FILES["file"]["error"][$i];

		//skip the empty element
		if( $error == 4) continue; 

		//repack each record from the arrays
		$record= array();
		$record["error"   ]= $error;
		$record["name"    ]= $_FILES["file"]["name"][$i];
		$record["type"    ]= $_FILES["file"]["type"][$i];
		$record["size"    ]= $_FILES["file"]["size"][$i];
		$record["tmp_name"]= $_FILES["file"]["tmp_name"][$i];
		$record["extension"]= pathinfo($record["name"], PATHINFO_EXTENSION);

		$skip= false;
		//when skip is true the record is skipped

		//check that size is less than maxSize, and add a error_msg
		if( $record["size"    ] > $maxSizeBytes )
		{
			$skip= true;
			$record["error_msg"][]= "size too big";
		}


		//check the mime type against the allowed and add a error_msg
		if( !in_array( $record["type"    ], $allowedExts) )
		{
			$skip= true;
			$record["error_msg"][]= "type not accepted";
		}

		//accept or skip files
		if ($error > 0 ||$skip) 
			$errors[$i]= $record;	
		else
			$input[$i]= $record;

	}

	
	foreach( $input as $file )
	{
		$accepted[]= $file["tmp_name"];
	}

	return  $input;

}

?>
