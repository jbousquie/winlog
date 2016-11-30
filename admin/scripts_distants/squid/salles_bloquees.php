<?php
header('Content-type: application/json; charset=utf-8');
$dirname = '/var/www/salles/rep_salles';
$dir = opendir($dirname); 
$salles = array();
while($file = readdir($dir)) {
	if($file != '.' && $file != '..' && !is_dir($dirname.$file))
	{
		$salles[] = $file;
	}
}
echo json_encode($salles);
?>
