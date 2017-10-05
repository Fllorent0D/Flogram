<?php
/* Script qui renvoie une miniature */
/* Augmente grandement les chargements de pages et réduit énormément la taille des pages */
$img_url = $_GET['f'];
$ext = substr($img_url, strrpos($img_url, '.')+1); 

switch(strtolower($ext)) {
     case "gif": 
     	$img = imagecreatefromgif($img_url);
     	$content_type="image/gif"; 
     	break;
     case "png": 
     	$img = imagecreatefrompng($img_url);
     	$content_type="image/png"; 
     	break;
     case "tiff":
     	$img = imagecreate($img_url);
     	$content_type="image/tiff";
     	break;
     case "jpeg":
     case "jpg": 
	    $img = imagecreatefromjpeg($img_url);
     	$content_type="image/jpg"; 
     	break;

     default: $content_type="image/png"; 
     	
     	break;

}
header('Content-type: '.$content_type);
$max = $_GET['max'];

$x = imagesx($img);
$y = imagesy($img);

if($x < $max || $y < $max)
	imagejpeg($img);
else
{		
	if($x>$max or $y>$max)
	{
	    if($x>$y)
	    {
	        $nx = $max;
			$ny = $y/($x/$max);
	    }
	    else
	    {
	        $nx = $x/($y/$max);
			$ny = $max;
	    }
	}
}
$nimg = imagecreatetruecolor($nx,$ny);
imagecopyresampled($nimg,$img,0,0,0,0,$nx,$ny,$x,$y);
imagejpeg($nimg);
?>