<?php
	$baseUrl = '/home';
	$baseUrl = str_replace($baseUrl,'',$_SERVER['REQUEST_URI']);
	//echo $baseUrl.'<br>';
	//print_r($_SERVER);
	include 'model.php';
?>
<!DOCTYPE html>
<html lang="ru-ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Грузовые автозапчасти For Trucks </title>
	<link rel="icon" href="img/fa520e0e50d0b5c4d390e9083e300ab7.ico">
	
    <link href="./resource/normalize.min.css" rel="stylesheet" type="text/css">    
    <link rel="stylesheet" href="./resource/font-awesome.min.css">
	
	<script src="js/jquery-3.3.1.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="./resource/style.css">   
	<script type="text/javascript" src="./resource/script.js"></script>

	<script type="text/javascript">
                $(function () {
                    $('body').on('submit', 'form[action="handler.php"]', function (event) {
                        event.preventDefault();
						
						action = $(this).find("[data-action]").data('action');
						
						formData = {};
						for(i=0; i<$(this)[0].length; i++)
						{
							if ($(this)[0][i].type != 'radio' || $(this)[0][i].type == 'radio' && $(this)[0][i].checked)
							{
								formData[$(this)[0][i].name] = $(this)[0][i].value;
								//console.log($(this)[0][i].name+' '+$(this)[0][i].value);
							}
						}
						//$(this)[0].reset();
						
                        $.ajax({
                            type: "POST",
                            url: "handler.php",
							data: formData,
							success:function (data) {
								$('.popup_form').hide();
								if (data==1)
									$('#thanks').show();
								else
									alert('Ошибка при отправке сообщения, попробуйте позже');

							}
                        })

					})
				})
	</script>
	
	
	<script type="text/javascript" src="leaflet/leaflet.js"></script>
	<link type="text/css" rel="stylesheet" href="leaflet/leaflet.css">
	<script type="text/javascript" src="slick/slick.js"></script>
	<link type="text/css" rel="stylesheet" href="slick/slick.css">
	<link type="text/css" rel="stylesheet" href="slick/slick-theme.css">
	<link type="text/css" rel="stylesheet" href="css/style.css">
	<script type="text/javascript" src="js/script.js"></script>

</head>
<body class="editor_false">
<div id="wrapper">
	<div class="hide_line section section103 fixed_top header">
		<div class="section_inner">
		
		<div class='header-elem'>
			<div class="slogan" >
				<div class="text1">
					<p style="text-align:center">
						<span style="font-size:28px">
							<span style="font-family:open sans condensed">
								<strong>Автозапчасти для&nbsp;</strong>
							</span>
						</span>
					</p>
					<p style="text-align:center">
						<span style="font-size:28px">
							<span style="font-family:open sans condensed">
								<strong>Грузовых автомобилей</strong>
							</span>
						</span>
					</p>
				</div>
				<div class="text2">
					<p style="text-align:center">
						<span style="font-size:20px">
							<span style="font-family:open sans condensed">
								Доставка по всей России
							</span>
						</span>
					</p>
				</div>
			</div>
		</div>
		<div class='header-elem'>
			<a href="" class="link_editor_false logo_img"></a>
		</div>
			
		<div class='header-elem'>
			<span class="slogan-anchor" data-slogan='anchor-1'>Бренды</span>
			<span class="slogan-anchor" data-slogan='anchor-2'>Преимущества</span>
			<span class="slogan-anchor" data-slogan='anchor-3'>О нас</span>
			<span class="slogan-anchor" data-slogan='anchor-4'>Спецпредложение</span>
			<span class="slogan-anchor" data-slogan='anchor-5'>Адрес</span>
			<a href="<?=$baseUrl?>shop" class="shop-button"><nobr>Online-Магазин</nobr></a>
		</div>
			
			<div class="phone-and-btn header-elem">
				<span class="phone1 show_phone_icon">
					<span style="font-size:24px;">
						<strong>
							<span style="font-family:lucida sans unicode,lucida grande,sans-serif;"><?=$telephoneNumber?></span>
						</strong>
					</span>
				</span>
				<div class="btn1 show-form surround request-call" data-show-form-type='request-call' data-color="rgb(214, 43, 43)">
					Заказать звонок
				</div>
				<div class="clear"></div>
			</div>
			

			
			<div class="clear"></div>
			<div class="popup_form" id='request-call-form'>
				<div class="popup_form_inner">
					<div class="popup_form_close">X</div>
					<div class="popup_form_title"><p style="text-align:center"><span style="font-size:36px;"><strong><span style="font-family:el messiri;">Заказать звонок</span></strong></span></p></div>
					<form action="handler.php" enctype="multipart/form-data" method="post">
							<div class="form1">
								<input type="hidden" name="Наименование формы" value="Заказать звонок">	
								<div class="field">
									<div class="field_title">Введите имя</div>
									<div class="field_description"></div>
									<div class="field_input">
										<input type="text" required="required" name="Имя" placeholder="Введите имя">
									</div>
								</div>
								<div class="field">
									<div class="field_title">Введите номер телефона</div>
									<div class="field_description"></div>
									<div class="field_input">
										<input type="text" required="required" pattern="[+)( .-]*[0-9]+[0-9+)( .-]*" minlength="6" name="Номер телефона" placeholder="Введите номер телефона">
									</div>
								</div>
								<div class="field">
									<div class="field_input">
										<input type="submit" class="submit_btn surround" data-action="thanks" data-url="" data-amount="0" value="Поехали !" style="background-color:#3F7B16;    border-radius: 30em  5em; ;   ">
									</div>
								</div>
							</div>
					</form>
					<div class="popup_form_text">Мы не передаем Вашу персональную информацию третьим лицам.</div>
				</div>
			</div>	
			

		</div>
	</div>
	
	<div class="section section153">
		<video autoplay="autoplay" loop="loop" muted="muted" class="video_bg" poster='IMG 8665.png'>
			<source src='../video/IMG 8665.mp4' type="video/ogg">
			Ваш браузер не поддерживает тег video.
		</video>
		
		<div class="section_inner big">
			<div class="layer">
				<div class="title">
					<img src='svg/logo_white.svg' alt="">
				<!--
					<h1>
						<span style="font-family:verdana,geneva,sans-serif">
							<span style="color:#B22222">
								For
							</span>
							Trucks
						</span>
					</h1>
				-->
				</div>
				<div class="sub_title"><span style="font-size:36px;">Автозапчасти для тягачей, самосвалов и прицепной техники с доставкой по всей России</span></div>
				<div class="btn1 show-form" data-show-form-type='get-price' style="color:#FFFFFF;  border-radius: 1.5em;    ">Получить прайс</div>
				<div class="btn_descr"></div>
			</div>
									
			<div class="popup_form" id='get-price-form'>
				<div class="popup_form_inner">
					<div class="popup_form_close">X</div>
					<div class="popup_form_title"><h3 style="text-align: center;"><span style="font-size:36px;"><span style="font-family:verdana,geneva,sans-serif;">Получить прайс</span></span><span style="font-family:verdana,geneva,sans-serif;"></span></h3></div>
					<form action="handler.php" enctype="multipart/form-data" method="post">
						<div class="form1">
							<input type="hidden" name="Наименование формы" value="Получить прайс">	
							<div class="field"><div class="field_title">Введите имя</div>
								<div class="field_description"></div>
								<div class="field_input">
									<input type="text" required="required" name="Имя" placeholder="Введите имя">
								</div>
							</div>
							<div class="field">
								<div class="field_description">Введите E-mail</div>
								<div class="field_input">
									<input type="email" required="required" name="E-mail" placeholder="E-mail">
								</div>
							</div>
							<div class="field">
								<div class="field_title">Введите номер телефона</div>
								<div class="field_description"></div>
								<div class="field_input">
									<input type="text" required="required" pattern="[+)( .-]*[0-9]+[0-9+)( .-]*" minlength="6" name="Номер телефона" placeholder="Введите номер телефона">
								</div>
							</div>
							<div class="field">
								<div class="field_input">
									<input type="submit" class="submit_btn surround" data-action="thanks" data-url="" data-amount="0" value="Выслать прайс" style="background-color:#D62B2B;    border-radius: 10em; ;   ">
								</div>
							</div>
						</div>
					</form>
					<div class="popup_form_text">Нажимая на кнопку, Вы принимаете <b>Положение</b> и <b>Согласие</b> на обработку персональных данных.</div>
				</div>
			</div>															
		</div>
	</div>
	<div class="hide_line section section306 dark analog-search-background">
		<div class="section_inner ">
			<div class="form_wrapper">
				<div class="form_title">
					<span style="font-size:18px;">
						<span style="font-family:georgia,serif">
							<strong>Получите специальное предложение !</strong>
						</span>
					</span>
				</div>
				<div class="form1">
					<form action="<?=$baseUrl?>shop/site/search" enctype="multipart/form-data" method="get">
						<div class="field">
							<div class="field_title">Поиск по номеру:</div>
							<div class="field_description"></div>
							<div class="field_input">
								<input type="text" required="required" name="text" placeholder="Поиск по артиклу:">
							</div>
						</div>
						<div class="field">
							<div class="field_input">
								<input type="submit" class="submit_btn surround" data-action="thanks" data-url="" data-amount="0" value="Поиск аналогов" style="background-color:#D62B2B;    border-radius: 1.5em; ;  box-shadow: 0.3em 0.3em 0.3em 0px rgba(0,0,0,0.4); ">
							</div>
						</div>
						<div class="clear"></div>
					</form>
				</div>
				<div class="form_text">
					<span style="font-family:georgia,serif">Поиск осуществляется по всем складам</span>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	

	<div id='anchor-1' data-autoslide="1" class="hide_line section section312 slider-background">
		<div class="section_inner">
			<div class='slick'>
			<?php foreach ($slides as $slideName):?>
				<div class="arr1">
					<div class="slide" style="background-image: url(&quot;img/slide/<?=$slideName?>&quot;);">
						<div class="image1"></div>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
			
			<div class="text_overlay">
				<div class="title1">
					<span style="font-family:verdana,geneva,sans-serif">
						<strong>
							<span style="font-size:26px">
								<span style="color:#FF0000">For </span>
								<span style="color:#000000">Trucks</span> - это автозапчасти для:
							</span>
						</strong>
					</span>
				</div>
				<div class="descr1"></div>
				<div class="btn1 show-form animation " data-show-form-type='leave-application' style="color: rgb(0, 0, 0); border-radius: 10em; background-position: 474px 0px;">Оставить заявку</div>
			</div>
			
			<div class="popup_form" id='leave-application-form'>
				<div class="popup_form_inner">
					<div class="popup_form_close">X</div>
					<div class="popup_form_title">Оставить  заявку</div>
					<form action="handler.php" enctype="multipart/form-data" method="post">
						<div class="form1">
							<input type="hidden" name="Наименование формы" value="Оставить  заявку">	
							<div class="field"><div class="field_title">Введите имя</div>
								<div class="field_description"></div>
								<div class="field_input"><input type="text" required="required" name="Имя" placeholder="Введите имя"></div>
							</div>
							<div class="field">
								<div class="field_title">Введите E-mail</div>
								<div class="field_description"></div>
								<div class="field_input"><input type="email" required="required" name="E-mail" placeholder="Введите E-mail"></div>
							</div>
							<div class="field">
								<div class="field_title">Введите номер телефона</div>
								<div class="field_description"></div>
								<div class="field_input"><input type="text" required="required" pattern="[+)( .-]*[0-9]+[0-9+)( .-]*" minlength="6" name="Номер телефона" placeholder="Введите номер телефона"></div>
							</div>
							<div class="field"><div class="field_input"><input type="submit" class="submit_btn surround" data-action="thanks" data-url="" data-amount="0" value="Заказать" style="background-color:#006699;    border-radius: 10em; ;   "></div>
							</div>
						</div>
					</form>
					<div class="popup_form_text">Нажимая на кнопку, Вы принимаете <b>Положение</b> и <b>Согласие</b> на обработку персональных данных.</div>
				</div>
			</div>
			
			<div class="clear"></div>

		</div>
	</div>

	<div id='anchor-2' class="hide_line  section  section125 advantage-background">
		<div class="section_inner">
			<div class="title  "><p><span style="font-family:georgia,serif;"><em><strong>Преимущества обращения в нашу компанию</strong></em></span></p></div>
			<div class="sub_title  ">автозапчасти по ценам от производителя с доставкой по всей России</div>	  
			<div class="arr1">
				<div class="col_2">
					<div class="image1  radius   s90  border   shadow  ">
						<img src="./resource/b84cac4ea0a321daa4d16d1e741daf21.png" alt="">
					</div>	
					<div class="right s90">
						<div class="title1">
							<strong><em>ШИРОКИЙ АССОРТИМЕНТ</em></strong>
						</div>
						<div class="txt1 ">
							<em>В ассортименте имеются оригинальные и аналоговые автозапчасти для грузовых авто, марок:&nbsp;</em>
							<p style="text-align:center">
								<strong>
									<span style="font-size:14px;">
										<span style="font-family:georgia,serif;">Scania,&nbsp;Volvo, RENAULT, MAN, DUF, &nbsp;IVECO</span>
									</span>
								</strong>
							</p>
							<em>Также, наш менеджер сможет подобрать&nbsp;Б/У запчасти по выгодной цене!</em>
						</div>										
					</div>
					<div class="clear"></div>
				</div>
				<div class="col_2 last">
					<div class="image2  radius   s90  border   shadow  ">
						<img src="./resource/902f5e070930f2310b2dc541989956b3.png" alt="">
					</div>
					<div class="right s90">
						<div class="title2">
							<strong><em>ВЫГОДНЫЕ ЦЕНЫ</em></strong>
						</div>
						<div class="txt2 ">
							<em>Наша компания предлагает самые выгодные цены на рынке автозапчастей. Индивидуальный подход к&nbsp;каждому клиенту!</em>
						</div>												
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>   
			<div class="arr1">
				<div class="col_2">
					<div class="image1  radius   s90  border   shadow  "><img src="./resource/593bc3dee91b5ea3e060e737925a3fd4.png" alt=""></div>
		
					<div class="right s90">
						<div class="title1">
							<strong><em>БЫСТРАЯ ОБРАБОТКА ЗАКАЗОВ</em></strong>
						</div>
						<div class="txt1 ">
							<em>Наши специалисты в кратчайшие сроки помогут подобрать нужную деталь по названию, марке машины или каталогу производителя</em>
						</div>														
					</div>
					<div class="clear"></div>
				</div>
				<div class="col_2 last">
					<div class="image2  radius   s90  border   shadow  ">
						<img src="./resource/3bbe991cb41f3fdbbc83ea9631482c33.png" alt="">
					</div>
					<div class="right s90">
						<div class="title2">
							<strong><em>КРУГЛОСУТОЧНАЯ КОНСУЛЬТАЦИЯ</em></strong>
						</div>
						<div class="txt2 ">
							<em>Вы можете получить грамотную консультацию по подбору автозапчастей по номеру</em>
							<p style="text-align:center">
								<strong><?=$telephoneNumber2?></strong>
							</p>
							<em>в любое удобное для Вас время, несмотря на часовой пояс</em>
							
						</div>												
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div> 		  
			<div class="arr1">
				<div class="col_2">
					<div class="image1  radius   s90  border   shadow  ">
						<img src="./resource/19e3ef3e785d21f2d55959d6c5f2020e.png" alt="">
					</div>
					<div class="right s90">
						<div class="title1">
							<strong><em>УДОБНЫЙ СПОСОБ ОПЛАТЫ</em></strong>
						</div>
						<div class="txt1 ">
							<em>Оплата осуществляется как наличным,&nbsp;так и безналичным расчетом, &nbsp;после высавления счета</em>
						</div>												
					</div>
					<div class="clear"></div>
				</div>
				<div class="col_2 last">
					<div class="image2  radius   s90  border   shadow  ">
						<img src="./resource/9cb2e23984ae6a2339827d7b151074a8.png" alt="">
					</div>
					<div class="right s90">
						<div class="title2">
							<strong><em>УДОБНЫЕ УСЛОВИЯ ДОСТАВКИ</em></strong>
						</div>
						<div class="txt2 ">
							<em>Мы осуществляем доставку заказов по территории России надежными и проверенными транспортными компаниями</em>
						</div>										
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div> 
					
		</div>
	</div>
	
	<div id='anchor-3' class="hide_line  section section117">
		<div class="section_inner">
			  
			<div class="arr1">
				<div class="col_2 image1  border   image_size_470x340 ">
					<img src="./resource/b2903083936679f715cae542675ac5c7.jpg" alt="">
				</div>
				<div class="col_2">
					<div class="descr1">
						<h2 style="text-align:center">
							<span style="font-family:verdana,geneva,sans-serif">
								<span style="color:#FF0000">For</span>
								<span style="color:#000000"> Trucks</span>
							</span>
						</h2>
					</div>
					<div class="txt1">
						<p>
							<span style="font-size:18px">
								<span style="font-family:lucida sans unicode,lucida grande,sans-serif">
									&nbsp;- это огромный склад запчастей и автоаксессуаров в Москве, это удобный сервис и высокое качество продукции. Наша компания нацелена на долгосрочное сотрудничество с покупателями. Для нас одинаково ценны как крупные компании, так и автомобилисты. Поэтому один из наших главных принципов – качество продукции.
								</span>
							</span>
						</p>
					</div>
				</div>
				<div class="clear"></div>
			</div> 
			  
			<div class="arr1">
				<div class="col_2 image1  border   image_size_470x340 ">
					<img src="./resource/9f181f20aba669dec87f4aeba0e9e55c.png" alt="">
				</div>
				<div class="col_2">
					<div class="descr1">
						<h2 style="text-align:center">
							<span style="font-family:verdana,geneva,sans-serif">
								<span style="color:#FF0000">For</span>
								<span style="color:#000000"> Trucks</span>
							</span>
						</h2>
					</div>
					<div class="txt1">
						<p>
							<span style="font-size:20px">
								<span style="font-family:lucida sans unicode,lucida grande,sans-serif"></span>
							</span>
							<span style="font-size:18px;">
								<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">
									У нас Вы всегда сможете купить самые востребованные запчасти для осей полуприцепов.
								</span>
							</span>
						</p>
						<p>
							<span style="font-size:18px;">
								<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">
									Подбор запчастей осуществляем по номеру оси прицепа. У наших менеджеров есть доступ к самым свежим электронным каталогам. Все&nbsp;автозапчасти для полуприцепов, Вы можете приобрести у Нас&nbsp;&nbsp;по лучшим ценам, оптом и в розницу.
								</span>
							</span>
							<br>
						</p>
					</div>
				</div>
				<div class="clear"></div>
			</div> 
			  
			<div class="arr1">
				<div class="col_2 image1  border   image_size_470x340 ">
					<img src="./resource/f4655437192afc7fcb3824924dc8fa47.png" alt="">
				</div>
				<div class="col_2">
					<div class="descr1  ">
						<h2 style="text-align:center">
							<span style="font-family:verdana,geneva,sans-serif">
								<span style="color:#FF0000">For</span><span style="color:#000000"> Trucks</span>
							</span>
						</h2>
					</div>
					<div class="txt1">
						<p>
							<span style="font-size:18px">
								<span style="font-family:lucida sans unicode,lucida grande,sans-serif">
									Наша компания&nbsp;<strong>-</strong> <strong>представляет весь ассортимент продукции Schmitz Cargobull AG на российском рынке</strong>.<br>Концерн Schmitz Cargobull AG - ведущий европейский производитель прицепов и полуприцепов<strong>.</strong>
								</span>
							</span>
							<br>
							<span style="font-size:20px">
								<span style="font-family:lucida sans unicode,lucida grande,sans-serif">
									<strong>
										<span style="color:#FF0000">For </span>Trucks
									</strong>
								</span>
							</span>
							<span style="font-size:18px">
								<span style="font-family:lucida sans unicode,lucida grande,sans-serif">&nbsp;является официальным партнером компании Шмитц Каргобулл Руссланд, дочерней компании немецкого концерна<strong>.</strong>
								</span>
							</span>
						</p>
					</div>
				</div>
				<div class="clear"></div>
			</div> 
			  
			<div class="arr1">
				<div class="col_2 image1  border   image_size_470x340 ">
					<img src="./resource/2b0ee2fa67e4c658ab619bb14520aea1.jpg" alt="">
				</div>
				<div class="col_2">
					<div class="descr1  ">
						<h2 style="text-align:center">
							<span style="font-family:verdana,geneva,sans-serif">
								<span style="color:#FF0000">For</span>
								<span style="color:#000000"> Trucks</span>
							</span>
						</h2>
					</div>
					<div class="txt1  ">
						<span style="font-size:18px">
							<span style="font-family:lucida sans unicode,lucida grande,sans-serif"></span>
						</span>
						<p>
							<span style="font-size:18px;">
								<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">У нас Вы всегда найдете именно ту продукцию, которая нужна в данный момент, в необходимом количестве. За годы работы мы научились предугадывать потребности клиентов: мы регулярно отслеживаем спрос на наши товары, учитываем индивидуальные планы ремонта и обслуживания автомобилей наших клиентов, а также особенности их транспортного парка.
								</span>
							</span>
						</p>
						<p style="text-align: center;">
							<span style="font-size:18px;">
								<span style="font-family:lucida sans unicode,lucida grande,sans-serif;"></span>
							</span>
						</p>
					</div>
				</div>
				<div class="clear"></div>
			</div> 
			
		</div>
	</div>
	
	<div id='anchor-4' class="hide_line section section164 background dark">
		<div class="section_inner ">
			<div class="col pcenter">
				<div class="sub_title  ">
					<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">Обратись за помощью уже сейчас</span>
				</div>
				<div class="btn1 show-form surround  animation" data-show-form-type='get-mark-pice' style="background-color: rgb(143, 66, 0); border-radius: 1.5em; background-position: 474px 0px;">Спецпредложение!</div>
				<div class="btn_descr  ">
					<p>
						<strong>
							<span style="font-size:24px;">
								<span style="font-family:el messiri;">Получить прайс со скидкой 5%</span>
							</span>
						</strong>
					</p>
				</div>
			</div>
			<div class="clear"></div>
																	
			<div class="popup_form" id='get-mark-pice-form'>
				<div class="popup_form_inner">
					<div class="popup_form_close">X</div>
					<div class="popup_form_title">
						<h2 style="text-align:center">
							<span style="font-family:ubuntu;">Получить прайс</span>
						</h2>
					</div>
					<form action="handler.php" enctype="multipart/form-data" method="post">
						<div class="form1">
							<input type="hidden" name="Наименование формы" value="Получить прайс">	
							<div class="field">
								<div class="field_title">Введите имя: </div>
								<div class="field_description"></div>
								<div class="field_input">
									<input type="text" required="required" name="Имя" placeholder="Введите имя:">
								</div>
							</div>
							<div class="field">
								<div class="field_title">Телефон:</div>
								<div class="field_description"></div>
								<div class="field_input">
									<input type="text" required="required" pattern="[+)( .-]*[0-9]+[0-9+)( .-]*" minlength="6" name="Телефон:" placeholder="Телефон:">
								</div>
							</div>
							<div class="field">
								<div class="field_title">Укажите марку автомобиля</div>
								<div class="field_description"></div>
								<div class="field_input">
									<input type="radio" value="Scania " name="Марка автомобиля"> 
									Scania  
									<input type="radio" value="Volvo" name="Марка автомобиля"> 
									Volvo 
									<input type="radio" value="MAN" name="Марка автомобиля"> 
									MAN 
									<input type="radio" value="IVECO" name="Марка автомобиля"> 
									IVECO 
									<input type="radio" value="Другой" name="Марка автомобиля"> 
									Другой
								</div>
							</div>
							<div class="field">
								<div class="field_input">
									<input type="submit" class="submit_btn surround" data-action="thanks" data-url="" data-amount="0" value="Поехали !" style="background-color:#D62B2B;    border-radius: 30em  5em; ;   ">
								</div>
							</div>
						</div>
					</form>
					<div class="popup_form_text">Нажимая на кнопку, Вы принимаете <b>Положение</b> и <b>Согласие</b> на обработку персональных данных.</div>
				</div>
			</div>
					
		</div>
	</div>

	<div class="hide_line section section303">
		<div class="map" id="map" style="z-index:50;">
			<div class="map_inner" id="map_inner"  style="width:100%; height:500px; position:relative; z-index:50;">
			</div>
		</div>

		<div class="text about">
			<p>
				<span style="font-size:18px;">
					<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">
						5 складов по Москве и области
					</span>
				</span>
			</p>
			<p>
				<span style="font-size:18px;">
				<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">
					7 дней в неделю беспрерывной работы&nbsp;
				</span>
				</span>
			</p>
			<p>
				<span style="font-size:18px;">
					<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">
						24 часа в сутки доставка товара
					</span>
				</span>
			</p>
			<p>
				<span style="font-size:18px;">
					<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">123 сотрудника готовых прийти на помощь</span>
				</span>
			</p>
			<p>
				<span style="font-size:18px;">
					<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">
						<br>
					</span>
				</span>
			</p>
			<p>
				<span style="font-size:18px;">
					<span style="font-family:lucida sans unicode,lucida grande,sans-serif;">
						а так же тысячи запчастей, которые вернут Ваш грузовой автомобиль к стабильной работе
					</span>
				</span>​​​​​​​
				<br>
			</p>
		</div> 
		<div class="image about">
			<img src="./resource/a8b9d8725809b598ef5f97b9c997d82e.jpg" alt="">
		</div>

	</div>

	<div id='anchor-5' class="hide_line section section101 address-info">
		<div class="section_inner">										
															
			<a href="" class="link_editor_false logo_img"></a>
															
														
			<div class="text1">
				<p style="text-align:center">
					<span style="color:#FF0000">
						<span style="font-size:24px">
							<span style="font-family:russo one">
								<br>
							</span>
						</span>
					</span>
				</p>
				<p style="text-align:center">
					<span style="color:#FF0000">
						<span style="font-size:24px">
							<span style="font-family:russo one"></span>
						</span>
					</span>
					<span style="color:#FF0000">
						<span style="font-size:24px">
							<span style="font-family:russo one">Your </span>
						</span>
					</span>
					<span style="color:#000000">
						<span style="font-size:24px">
							<span style="font-family:russo one">Trucks </span>
						</span>
					</span>
					<span style="color:#FF0000">
						<span style="font-size:24px">
							<span style="font-family:russo one">Our </span>
						</span>
					</span>
					<span style="color:#000000">
						<span style="font-size:24px">
							<span style="font-family:russo one">Parts</span>
						</span>
					</span>
				</p>
			</div>
			<div class="phone-and-address">
				<span class="phone1 show_phone_icon">
					<span style="font-size:24px;">
						<span style="font-family:lucida sans unicode,lucida grande,sans-serif">
							<strong><?=$telephoneNumber?>&nbsp;​​​​​​​</strong>
						</span>
					</span>
				</span>
				<div class="address1">143409, Московская обл., г. Красногорск, ул. Успенская, д. 4А</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	
	<div class="popup_thanks" id="thanks">
		<div class="popup_thanks_inner">
			<div class="popup_thanks_close">X</div>
			<div class="popup_thanks_title"><p style="text-align: center;"><span style="font-family:el messiri;">Спасибо за заявку!</span></p></div>
			<div class="popup_thanks_text"><h2 style="text-align: center;"><span style="font-family:el messiri;">Заявка отправлена. Наш менеджер свяжется с Вами в ближайшее время.</span></h2></div>
		</div>
	</div>
</div>
            
<script type="text/javascript">

	var map = L.map('map_inner').setView([54.847815044675555, 38.61718750000001], 5);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

	map.on('click',function(e){console.log(e.latlng)});
	
	L.marker([55.828621801187325, 37.297446727752686]).addTo(map);
	L.marker([55.91067040560778, 37.672231793403625]).addTo(map);
	L.marker([48.776802740256336, 44.38213169574737]).addTo(map);
		
</script>
<script type="text/javascript">
$('.slick').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 1000,
  dots: true,
});
</script>
            
        
                                    
     <!-- <link href="./resource/css" rel="stylesheet" type="text/css"> -->

            
    
</body>
</html>