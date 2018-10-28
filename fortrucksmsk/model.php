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
//+7 (963) 975-06-31
$telephoneNumber 	= '8 963 975 06 31';
$telephoneNumber2 	= '+7 (963) 975&nbsp;06&nbsp;31&nbsp;';

//$logoName = mb_convert_encoding('./�������� ������������ For Trucks_files/logo.png','utf-8','windows-1251');
?>