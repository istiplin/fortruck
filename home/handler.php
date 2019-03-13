<?php
	$message = "С сайта fortrucksmsk была отправлена заявка<br><br>";

	foreach($_POST as $key=>$name)
	{
	    $key=mb_convert_encoding($key,'windows-1251','utf-8');
	    $name=mb_convert_encoding($name,'windows-1251','utf-8');
		$message.="$key: $name<br>";
	}
	$mailheaders = "Content-type:text/html;charset=windows-1251"; 

    
	if (mail('for.truck@mail.ru','ForTrucks',$message,$mailheaders))
	{
		mail('istiplin@gmail.com','ForTrucks',$message,$mailheaders);
		echo 1;
	}
?>