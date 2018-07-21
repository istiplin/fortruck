<?php
function getSlides()
{
	$newSlides = array();

	$slides = scandir('img/slide');
	$slideCount = count($slides);
	for ($i=0; $i<$slideCount; $i++)
	{
		if ($slides[$i]!='.' AND $slides[$i]!='..')
			$newSlides[] = $slides[$i];
	}
	return $newSlides;
}
$slides = getSlides();

$telephoneNumber 	= '8 926 277 77 61';
$telephoneNumber2 	= '+7 (926) 277&nbsp;77&nbsp;61&nbsp;';

//$logoName = mb_convert_encoding('./Грузовые автозапчасти For Trucks_files/logo.png','utf-8','windows-1251');
?>