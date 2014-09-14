<?php
/**
* @package phpPhotosMerger
* @author Bruno Trombi <bruno.trombi@gmail.com>
* @copyright 2014 Bruno Trombi
* @license http://www.gnu.org/licenses/gpl-3.0.txt
*
*this is for removing unwanted objects in a photos
*require the php module GD  for pixel maniplultions
**/



/*
this class is for handle rgb color space
*/
class RGB{

	final function colorSet( $colorR ,$colorG ,$colorB)
	{
		return ( $colorR << 16 ) |( $colorG << 8 ) |( $colorB );
	}


	final function getRed( $colorRgb )
	{
		return ($colorRgb >> 16) & 0xFF;
	}


	final function getGreen( $colorRgb )
	{
		return ($colorRgb >> 8) & 0xFF;
	}


	final function getBlue( $colorRgb )
	{
		return $colorRgb & 0xFF;
	}

}

/*
return the average
*/
function avg($array)
{
   if (!is_array($array)) return false;

   return array_sum($array)/count($array);
}


/*
imageFiles is an array of filenames of images to merge, removing the moving objects
return the $gd image.
*/
function hideMovingObjects( $imageFiles )
{
	$xMax= 0;
	$yMax= 0;
	$images= array();
	foreach( $imageFiles as $fn )
	{
		$images []= imagecreatefromjpeg( $fn );

		$info= getimagesize( $fn );

		$infoX= $info[0];
		$infoY= $info[1];
		$infoBits= $info[ 'bits' ];
		$infoChannels= $info[ 'channels' ];

		$xMax= max( $xMax, $infoX );
		$yMax= max( $yMax, $infoY );
	}

	$colorGd = imagecreatetruecolor($xMax, $yMax);

	$imagesnumber= sizeof($images);
	$trimRounds=  ceil( log( $imagesnumber) * log( $imagesnumber)/2 ) ;
	$trimStop= $imagesnumber-$trimRounds-$trimRounds;

	//for all images and all pixel
	//calculate the Trimmed mean of color
	for( $x=0 ;$x <$xMax ;$x++ )
	{
		for( $y=0; $y <$yMax ;$y++ )
		{

			$colorR= array();
			$colorG= array();
			$colorB= array();
			foreach( $images as $img )
			{
				$colorRgb=  imagecolorat( $img, $x, $y );
				$colorR[]= RGB::getRed( $colorRgb );
				$colorG[]= RGB::getGreen( $colorRgb );
				$colorB[]= RGB::getBlue( $colorRgb );
			}

			sort($colorR);
			sort($colorG);
			sort($colorB);
			$colorRed=   avg( array_slice($colorR, $trimRounds, $trimStop) );
			$colorGreen= avg( array_slice($colorG, $trimRounds, $trimStop) );
			$colorBlue=  avg( array_slice($colorB, $trimRounds, $trimStop) );
	
			imagesetpixel( $colorGd, $x, $y, RGB::colorSet( $colorRed, $colorGreen, $colorBlue ) );	
		}
	}

	//done return the merged image!
	//imagepng($colorGd, "last.jpg" );
	return $colorGd;

}


?>
