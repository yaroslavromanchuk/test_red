<?php
session_start();
 header("Content-type: image/png");
 $string = "";
for ($i = 0; $i < 5; $i++)
	$string .= chr(rand(97, 118)); //или $string .= mt_rand(1, 9); если Вы хотите чтоб были цифры

$_SESSION['rand'] = $string;
$img = imagecreate(50,25);


$black = ImageColorAllocate($img, 255, 0, 26);
$white = ImageColorAllocate($img, 0, 246, 246);
imagefilledrectangle($img,0,0,399,99,$white);
$trans = ImageColorTransparent($img, $white);
//ImageFill($img, 0, 0, $white);
ImageString($img , 5, 0, 0, $string, $black);
ImagePng($img);
ImageDestroy($img);
?>