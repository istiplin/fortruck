$(document).ready(function() {
	$('.show-form').click(function(e){
		showFormType = $(e.target).data('show-form-type');
		$('#'+showFormType+'-form').show();
	})
	
	$('.popup_form_close').click(function(){
		$('.popup_form').hide();
	})
	$('.popup_form').click(function(e){
		if($(e.target).hasClass('popup_form'))
			$('.popup_form').hide();
	})
	
	$('.popup_thanks_close').click(function(){
		$('.popup_thanks').hide();
	})
	$('.popup_thanks').click(function(e){
		if($(e.target).hasClass('popup_thanks'))
			$('.popup_thanks').hide();
	})
	
	var fixed_top_height;
	$(window).resize(function() {
		fixed_top_height = $('.fixed_top').height();

		$('.video_bg').css({'top':$('.fixed_top').height()+'px','width':$(window).width()+'px'});
		
		video_height = $('.video_bg').height();
		$('.section153 .section_inner').css({'margin-top':fixed_top_height+'px'});
		$('.section153 .section_inner').css({'min-height':video_height+'px'});

		section153_inner_height = $('.section153 .section_inner').height();
		padding_vertical = (section153_inner_height-$('.section153 .section_inner .layer').height())/2;
		$('.section153 .section_inner .layer').css({'padding-top':padding_vertical+'px'});
	});
	
	$('.slogan-anchor').click(function(e){
		anchor_id = $(e.target).data('slogan');
		offset = $('#'+anchor_id).offset();
		$('html, body').animate({scrollTop: offset.top-fixed_top_height+'px'},500);
	})
	
	$(window).resize();
});