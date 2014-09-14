Did a pedestrian or passing car ruin your shots of a landmark? 
The target of this tool is merge photos to remove unwanted people or objects in motion.

To work properly you need to have multiple shots, each one must had the same size,  jpeg 24 bit.
 
This tool create an image with all pixel that are shared in all images (automatically removing the moving objects).

a demo with html upload form is supplied,
you can find it live here:
http://www.scrocconi.it/phpPhotosMerger/formUploadFiles.html


file Contents:

README.md
	this file

LICENSE
	gpl 3.0 license

checkModule.php
	this file check that you had properly installed gd library that is used for pixel manipulations

formUploadFiles.html
	form for Upload Files

formProcess.php
	process the files

libUpload.php
	to filter and validate files uploaded

libImageOjectRemove($imageFiles) 
	this to the jon of merging the images!
	you could call directly this.
