<?php
function getSlides()
{
	$newSlides = array();

	//$slides = scandir('/start/img/slide');
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

$config = include '../common/config/main-local.php';
$db = new PDO($config['components']['db']['dsn'],$config['components']['db']['username'],$config['components']['db']['password']);
$sth = $db->prepare("select value from config where alias='site_phone'");
$sth->execute();
$telephoneNumber = $telephoneNumber2 = $sth->fetchColumn();

//+7 (963) 975-06-31
//$telephoneNumber 	= '8 963 975 06 31';
//$telephoneNumber2 	= '+7 (963) 975&nbsp;06&nbsp;31&nbsp;';

//$logoName = mb_convert_encoding('./�������� ������������ For Trucks_files/logo.png','utf-8','windows-1251');
?>