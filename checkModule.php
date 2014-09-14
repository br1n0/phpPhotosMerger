<?php

$phpModules= get_loaded_extensions();


if (!in_array('gd', $phpModules))
    die( "you must install php module gd to run correctly" );

else
	echo( "gd is properly installed correctly, you could run the program" );


?>
