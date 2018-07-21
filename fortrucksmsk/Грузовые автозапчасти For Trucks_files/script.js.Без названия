$(function () {
    window.SetVideoBG = function (el) {

        var ratio = 16 / 9;

        function resize() {
            var cWidth = Math.floor(el.width());
            var cHeight = Math.floor(el.outerHeight());
            var pWidth = Math.floor($(window).width());
            if (pWidth < cWidth) {
                pWidth = cWidth;
            }
            var pHeight = Math.floor(pWidth / ratio);
            if (pHeight < cHeight) {
                pHeight = cHeight;
                pWidth = Math.floor(pHeight * ratio);
            }
            var position_top = 0,
                    position_left = 0;

            if ((cHeight - pHeight) < 0) {
                position_top = Math.floor((cHeight - pHeight) / 2);
            }
            if ((cWidth - pWidth) < 0) {
                position_left = Math.floor((cWidth - pWidth) / 2);
            }

            el.find('.video_bg').css({
                height: pHeight,
                width: pWidth,
                left: position_left,
                top: position_top,
            });
        }
        resize();
        $(window).resize(function () {
            resize()
        })



    }


    $('#wrapper').find('iframe').each(function () {
        var src = $(this).attr('src');



        if (src.indexOf('https://www.youtube.com/embed/') > -1) {
            var video_id = src.substring(30);
            var yt_quality = 'hqdefault';
            var self = this;
            $.ajax({url:'/system/check_video.php?videoId='+video_id, 
                type:'HEAD',
                success: function(){yt_quality = 'maxresdefault';}, 
                complete: function(){
                    $(self).replaceWith('<div class="video_holder" data-video="'+$(self).attr('src')+'" style="width:'+$(self).width()+'px; height:'+$(self).height()+'px; background-image: url(https://i.ytimg.com/vi/'+video_id+'/'+yt_quality+'.jpg);"></div>')
                }
            });
            
            //$(this).replaceWith('<div class="video_holder" data-video="' + $(this).attr('src') + '" style="width:' + $(this).width() + 'px; height:' + $(this).height() + 'px; background-image: url(https://i.ytimg.com/vi/' + video_id + '/hqdefault.jpg);"></div>')
            
            
            
        }
    })


    $('body').on('click','.video_holder', function () {
        var src = $(this).data('video');
        $(this).replaceWith('<iframe  allowfullscreen src="' + src + '?autoplay=1"></iframe>');
    })


    $('.section318 .accordion .line.caption, .section319 .accordion .line.caption').click(function () {
        $(this).parent().parent().find('.line.option, .line.btn').hide();
        $(this).parent().find('.line.option, .line.btn').show();
    })







    $('body').on('click', '.popup_thanks_close, .popup_form_close', function () {
        $('.popup_thanks').hide();
        $('.popup_form').hide();
    });
    $('body').on('click', '.section1009 .widget_form_close', function () {
        $('.section1009 .form_wrapper').hide();
    });
    $('body').on('click', '.section1010 .widget_form_close', function () {
        $('.section1010 .arr1').hide();
    });
    $('body').on('click', '.choose_btns label', function () {
        $('.choose_btns label').removeClass('current_btn');
        $(this).addClass('current_btn');
    });
    $('body').on('click', '.all_form_close', function () {
        $('.section1009 .all_forms').hide();
    });
    $('body').on('click', '.section1009 .ico1', function () {
        $('.section1009 .social_icons').toggle();
        $('.section1009 .form_wrapper').show();
        $('.section1009 .all_forms').show();
    });
    $('body').on('click', '.section1009 .image2', function () {
        $('.section1009 .social_icons').toggle();
        $('.section1009 .form_wrapper').show();
        $('.section1009 .all_forms').show();
    });

//    $(document).click(function(event) {
//        if ($(event.target).closest(".all_forms").length) return;
//        $(".all_forms").hide("slow");
//        event.stopPropagation();
//    });



    $('body').on('click', '.popup_form', function (event) {
        if ($(event.target).closest(".popup_form_inner").length === 0) {
            $('.popup_form').hide();
        }
    });



    $('body').on('mouseup', '#wrapper', function (event) {
        var container = $(".section1009 .section_inner > .form_wrapper");
        if (container.has(event.target).length === 0) {
            container.hide();

        }
    });
    $('body').on('mouseup', '#wrapper', function (event) {
        var container = $(".section1009 .all_forms");
        if (container.has(event.target).length === 0) {
            container.hide();

        }
    });
    $('body').on('mouseup', '#wrapper', function (event) {
        var container = $(" .section1009 .section_inner > .social_icons");
        if (container.has(event.target).length === 0) {
            container.hide();

        }
    });
    $('body').on('click', '.popup_thanks', function (event) {
        if ($(event.target).closest(".popup_thanks_inner").length === 0) {
            $('.popup_thanks').hide();
        }
    });

    $('body').on('click', '.section119 .policy2', function () {
//        console.log('click');
        $(this).parent().parent().children('.policy2_popup').toggle();
    })


    $('body').on('click', '.product_card', function () {
        if ($(this).parent().parent().children('.extra_info_block_wrapper').eq($(this).data('id')).is(':visible')) {
            $(this).parent().parent().children('.extra_info_block_wrapper').hide();
        } else {
            $(this).parent().parent().children('.extra_info_block_wrapper').hide();
            $(this).parent().parent().children('.extra_info_block_wrapper').eq($(this).data('id')).show();
            $('body').addClass('modal');

        }
    })
//    $('body').on('click', '.img_do', function () {
//        if ($(this).parent().parent().children('.extra_info_block_wrapper').eq($(this).data('id')).is(':visible')) {
//            $(this).parent().parent().children('.extra_info_block_wrapper').hide();
//        } else {
//            $(this).parent().parent().children('.extra_info_block_wrapper').hide();
//            $(this).parent().parent().children('.extra_info_block_wrapper').eq($(this).data('id')).show();
//            $('body').addClass('modal');
//
//        }
//    })
    $('body').on('click', '.extra_info_block .close', function () {
        $(this).parent().parent().hide();
        $('body').removeClass('modal');

    })
    
    
    
        $('body').on('submit', '.section120 form.search', function (e) {
            e.preventDefault();
            
            var $self_block = $(this).closest('.section');
            
            var search_result = '';
            var search_str = $(this).children('input[type="search"]').val();
            
        
            $.ajax({
                    url: '/search.php',
                    dataType: "json",
                    method: "POST",
                    data: {search: search_str},
                    cache: false,
                    success: function (data) {
                        search_result = data;
                        $self_block.find('.find_popup').empty();
                            var html ='';
                        
                        
                        $.each(search_result, function(index,element){
                            html+='<div class="find_item"><a href="'+element.link+'" target="_blank">'+element.title+'</a><p>'+element.description+'</p></div>';
                        });
                        $self_block.find('.find_popup').append(html);
                        if(html==''){
                            
                            $self_block.find('.find_popup').append('Ничего не найдено.');
                            
                        }
                        
                        
                        $self_block.find('.find_popup').fadeIn();
                        $self_block.find('.find_popup').prepend('<div class="result_title">Результаты вашего поиска</div><div class="close"></div>');
                    }
            });

        });
        $('body').on('submit', '.section119 form.search', function (e) {
            e.preventDefault();
            
            var $self_block = $(this).closest('.section');
            
            var search_result = '';
            var search_str = $(this).children('input[type="search"]').val();
            
        
            $.ajax({
                    url: '/search.php',
                    dataType: "json",
                    cache: false,
                    data: {search: search_str},
                    success: function (data) {
                        search_result = data;
                        $self_block.find('.find_popup').empty();
                            var html ='';
                        
                        
                        $.each(search_result, function(index,element){
                            html+='<div class="find_item"><a href="'+element.link+'" target="_blank">'+element.title+'</a><p>'+element.description+'</p></div>';
                        });
                        $self_block.find('.find_popup').append(html);
                        if(html==''){
                            
                            $self_block.find('.find_popup').append('Ничего не найдено.');
                            
                        }
                        
                        
                        $self_block.find('.find_popup').fadeIn();
                        $self_block.find('.find_popup').prepend('<div class="result_title">Результаты вашего поиска</div><div class="close"></div>');
                    }
            });

        });
    
    $('body').on('click', '.find_popup .close', function () {
        $(this).parent().fadeOut();
    });
    
    

//
    if ($('.overlay_image_box').parent().hasClass('img_zoom')) {
        $(window).resize(function () {

            $('#popup_img_wrap img').css('max-height', $(window).height() * 0.98);
        });
        var $all_photo = $('.section .img_zoom');
        var clicked_img_number,
                $preload,
                start,
                $gallery_img_hide,
                $gallery_img;
        var portfolio_img_src = [];
        $all_photo.click(function (e) {
            e.stopPropagation();
            portfolio_img_src.length = 0;

            $(this).parent().children('.img_zoom').each(function () {
                var src = ($(this).children('img').attr('src'));
                var t_arr2 = src.split('/');

                var this_section = $(this).closest('.section');
//                console.log(this_section);
                var image_url = src;
                var t_arr = image_url.split('/');
                var size = '800x600'; //default
                var css_class = ''; //default
                if (this_section.hasClass('img_album')) {
                    size = '800x600';

                }
                if (this_section.hasClass('img_portrait')) {
                    size = '400x560';
                    css_class = 'portret'
                }
                if (this_section.hasClass('img_square')) {
                    size = '600x600';
                    css_class = 'square'
                }

                var new_image_url = '/img/' + size + '/' + t_arr[3];

                portfolio_img_src.push(new_image_url);
            });
            clicked_img_number = $(this).index(); // Определяем порядковый номер изображения, по которому кликнули
            if (clicked_img_number === -1) {
                clicked_img_number = 0;
            }
            start = true;
            // Открываем галлерею
            $('body').append('<div id="popup_img"> \n\
                    <div id="preload"></div>        \n\
                    <img id="popup_img_hide" src=' + portfolio_img_src[clicked_img_number] + '>\n\
                    <div id="popup_img_prev"><i class="fa fa-chevron-left" aria-hidden="true"></i></div> <div id="popup_img_next"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>   \n\
                    <div id="popup_img_wrap"> <img src="' + portfolio_img_src + '" alt=""> \n\
                    <div id="close_popup_img" title="Закрыть"></div> \n\
                </div>\n\
                </div>');
            $('#popup_img_wrap img').css('max-height', $(window).height() * 0.98);
            $('#popup_img').fadeIn(200);
            $preload = $('#preload');
            $gallery_img = $('#popup_img_wrap img');
            $gallery_img_hide = $('#popup_img_hide');
            load_img();
        });

        $('body').on('click', '#popup_img_wrap', function (e) {
            e.stopPropagation();
            GotoRight();
        });
        $('body').on('click', '#popup_img_next', function (e) {
            e.stopPropagation();
            GotoRight();
        });
        $('body').on('click', '#popup_img_prev', function (e) {
            e.stopPropagation();
            GotoLeft();
        });
        $('body').on('click', '#close_popup_img', function (e) {
            e.stopPropagation();
            CloseGallery();
        });

        //$('body').on()(CloseGallery);
        
        $('body').on('click','#popup_img', function(e){
            
            e.stopPropagation();
            CloseGallery();
            
        })
        
        
        $(document).keydown(function (eventObject) {
            if (eventObject.which === 37) {
                GotoLeft();
            }
            if (eventObject.which === 39) {
                GotoRight();
            }
        });
        function GotoRight() {
            ++clicked_img_number;
            if (clicked_img_number + 1 > portfolio_img_src.length) {
                clicked_img_number = 0;
            }
            start = false;
            load_img();
        }
        function GotoLeft() {
            --clicked_img_number;
            if (clicked_img_number < 0) {
                clicked_img_number = (portfolio_img_src.length - 1);
            }
            start = false;
            load_img();
        }
        function CloseGallery() {
            $('#popup_img').fadeOut(100, function () {
                $('#popup_img').remove();
            });
        }
        function load_img() {
            var timer = setTimeout(function () {
                $preload.show();
            }, 400);

            if (!start) {
                $gallery_img_hide.attr('src', portfolio_img_src[(clicked_img_number)]).load(function () {
                    $preload.hide();
                    $gallery_img.fadeOut(100, function () {
                        clearTimeout(timer);
                        $gallery_img.attr('src', portfolio_img_src[(clicked_img_number)]).fadeIn(400);
                    });
                });
            } else {
                $gallery_img.attr('src', portfolio_img_src[(clicked_img_number)]).load(function () {
                    clearTimeout(timer);
                    $preload.hide();
                });
                $gallery_img.attr('src', portfolio_img_src[(clicked_img_number)]);
            }
        }
//Закрытие форм по нажатию клавиши "ESC"
        $(document).keydown(function (eventObject) {
            if (eventObject.which === 27) {
                $('.popup_wrap').fadeOut(400);
                $('#popup_img').fadeOut(100, function () {
                    $('#popup_img').remove();
                });
            }
        });

    }





    $('.btn1, .btn2, .btn3, .btn4,  .btn5, .btn1x, .btn2x, .btn3x, .btn4x,  .btn5x').each(function () {

        if ($(this).css('background-color') == 'rgb(255, 255, 255)') {
//            $(this).css({color: '#3D3D3DC'})
        }


    })


    window.basket = {
        status: 0,

        getData: function () {
            var basket = JSON.parse(localStorage.getItem('basket'));
            if (basket == null) {
                return  [];

            } else {
                return basket;
            }

        },
        saveData: function (data) {
            try {
                localStorage.setItem('basket', JSON.stringify(data));
            } catch (e) {
                if (e == QUOTA_EXCEEDED_ERR) {
                    alert('Ошибка добавления товара в корзину');
                }
            }
        },

        addItem: function (item) {
            var basket = this.getData();
            // смотрим нет ли такой позиции
            var ifExist = false;
            $.each(basket, function (index, element) {
                if (element.title == item.title && element.price == item.price) {
                    // если есть увеличиваем количество
                    ifExist = true;

                    if (typeof (element.quantity) === 'undefined') {
                        element.quantity = 1;


                    } else {
                        element.quantity = (parseInt(element.quantity));
                        element.quantity++;

                    }
                }
            })
            // если нет просто добавляем.
            if (!ifExist) {
                basket.push(item);
            }
            this.saveData(basket);
            this.renderForm();
            this.renderBtn();
            this.showBtn();
        },

        removeItem: function (item) {
            var basket = this.getData();
            // смотрим нет ли такой позиции
            var break_each = false;

            if (basket) {
                $.each(basket, function (index, element) {
                    if (break_each) {
                        return true;
                    }
                    if (element.title == item.title && element.price == item.price) {
                        // если есть удаляем
                        basket.splice(index, 1);
                        break_each = true;
                    }
                })
            }
            this.saveData(basket);
        },
        updateItem: function (item) {
            var basket = this.getData();
            // смотрим нет ли такой позиции
            var break_each = false;

            if (basket) {
                $.each(basket, function (index, element) {
                    if (break_each) {
                        return true;
                    }
                    if (element.title == item.title && element.price == item.price) {
                        // если есть удаляем
                        element.quantity = item.quantity;
                        break_each = true;
                    }
                })
            }
            this.saveData(basket);
        },
        clean: function () {
            this.saveData([]);
        },

        countItems: function () {
            return this.getData().length;
        },

        countSum: function () {
            var sum = 0;

            $.each(this.getData(), function (i, el) {
                sum += parseInt(el.quantity) * parseInt(el.price);

            })


            return sum;

        },

        btnEventListener: function () {
            var self = this;
            $('body').on('click', '#basket_btn', function () {
                self.showForm();
            })
        },
        renderBtn: function () {
            $('#basket_btn').remove();
            if(this.countItems()>0){
                $('body').append('<div id="basket_btn"  style="display:none"> <i class="fa fa-shopping-cart"></i> Корзина (' + this.countItems() + ')</div>');
                
            }
            
            this.init();
        },

        hideBtn: function () {
            $('#basket_btn').hide();
        },
        showBtn: function () {
            $('#basket_btn').show();
        },


        formEventListener: function () {
            var self = this;


            $('body').on('click', '#basket_form_close', function () {
                self.hideForm();
            })
            $('body').on('click', '#basket_left .remove', function () {
                var title = $(this).parent().children('.title').text();
                var price = $(this).parent().children('.price').text();
                var item = {title: title, price: price};
//                console.log(item);
                self.removeItem(item);
                self.renderForm();
                self.showForm();
                self.updateForm();
                
            })
            $('body').on('keyup', '#basket_left .quantity input', function (event) {
                this.value = this.value.replace(/\D/gi, '').replace(/^0+/, '');
                if (this.value == '') {
                    this.value = 0;
                }

                var title = $(this).parent().parent().children('.title').text();
                var price = $(this).parent().parent().children('.price').text();
                var quantity = $(this).val();
                var item = {title: title, price: price, quantity: quantity};
                var sum = parseInt(price) * parseInt(quantity);
                $(this).parent().parent().children('.itogo').text(sum);
                self.updateItem(item);
                $('#basket_make_order textarea[name="order"]').text(JSON.stringify(self.getData()))
                
                $('#basket_form_itogo').html('Итого: <span>' + self.countSum() + '</span>');
                $('#basket_make_order input[name="amount"]').val(self.countSum());  
                
                
                
                
                
                self.updateForm();


            })
            $('body').on('keyup', '#promo_code', function (event) {
                self.updateForm();
            })
            
            
            $('body').on('click', '#basket_continue', function (event) {
                self.hideForm();
            })


        },

        renderForm: function () {
            var self = this;

            $('#basket_form_bg').remove();
            $('body').append('<div id="basket_form_bg" style="display:none">\n\
                    <div id="basket_form_inner">\n\
                        <div id="basket_form_close">X</div>\n\
                        <div id="basket_left"></div>\n\
                        <div id="basket_right"></div>\n\
                        <div class="clear"></div>\n\
                    </div>\n\
                </div>');
            var basket = this.getData();
            $('#basket_left').append('<div class="item caption">\n\
                        <div class="image">Фото</div>\n\
                        <div class="title">Название</div>\n\
                        <div class="quantity">Количество</div>\n\
                        <div class="price">Цена, за ед.</div>\n\
                        <div class="itogo">Цена</div>\n\
                        <div class="clear"></div>\n\
                    </div>');
            $.each(basket, function (i, el) {

                var image = '';
                if (el.image != '') {
                    image = '<img src="' + el.image + '" />';
                }

                $('#basket_left').append('<div class="item" data-id="' + i + '">\n\
                            <div class="image">' + image + '</div>\n\
                            <div class="title">' + el.title + '</div>\n\
                            <div class="quantity"><input type="numper" value="' + el.quantity + '" /></div>\n\
                            <div class="price">' + el.price + '</div>\n\
                            <div class="itogo">' + (el.price * el.quantity) + '</div>\n\
                            <div class="remove"><i class="fa fa-trash"></i></div>\n\
                            <div class="clear"></div>\n\
                        </div>');
            })
            
            this.init();
            this.updateForm();
        },
        hideForm: function () {
            $('#basket_form_bg').hide();
            this.renderBtn();
            this.showBtn()
        },
        showForm: function () {
            $('#basket_form_bg').show();
            this.hideBtn()
        },

        sendForm: function () {




        },
        init: function () {
            
            if (!this.status) {
                this.status = 1;
                this.formEventListener();
                this.btnEventListener();

            }
        }





    }

    $('div.btn1, div.btn2, div.btn3, div.btn4,  div.btn5').click(function () {
        var this_section = $(this).closest('.section');
        var this_button = this;

    })

    $('.btn1, .btn2, .btn3, .btn4,  .btn5, .submit_btn, .btn1x, .btn2x, .btn3x, .btn4x,  .btn5x').hover(function () {
        if ($(this).hasClass('surround')) {
            // обЪемный кнопке
            var color = $(this).css('background-color');
            $(this).attr('data-color', color);
            var color2 = '#fff';
            if (color == 'rgb(255, 255, 255)') {
                color2 = '#3D3D3D';
            }
            $(this).css({
                backgroundColor: color2,
                color: color,
            })

        } else {
            // не объемный кнопке
            var color = $(this).css('color');
            $(this).attr('data-color', color);

            var color2 = '#fff';
            if (color == 'rgb(255, 255, 255)') {
                color2 = '#3D3D3D';
            }

            if (color == 'rgb(255, 255, 255)') {
                color = 'rgb(0, 0, 0,0.5    )';
                color2 = '#FFF';
            }




            $(this).css({
                backgroundColor: color,
                color: color2,
            })
        }
        $(this).addClass('hover');
    }, function () {

        if ($(this).hasClass('surround')) {
            // обЪемный кнопке
            var color = $(this).data('color');
            $(this).css({
                backgroundColor: color,
                color: '#fff',
            })

            if ($(this).css('background-color') == 'rgb(255, 255, 255)') {
//                $(this).css({color: '#3D3D3D'})
            }


        } else {
            //$(this).attr('date-color', $(this).css('color'));
            var color = $(this).data('color');

            $(this).css({
                color: color,
                backgroundColor: 'transparent'
            })
        }

        $(this).removeAttr('date-color');
        $(this).removeClass('hover');
    })



    $(window).scroll(function () {
        $('.fixed_top').css({'left': '-' + $(window).scrollLeft() + 'px'});
    })


    if (window.location.search == '?rk_pay=success') {
        alert('Оплата успешно завершена!');
        window.location.search = '';
    }
    if (window.location.search == '?rk_pay=fail') {
        alert('Оплата не завершена!');
        window.location.search = '';
    }





    $('.section302 .left.style2 ul li a, .section302 .left.style4 ul li a').hover(function () {
        $(this).parent().addClass('hover');
    }, function () {
        $(this).parent().removeClass('hover');
    })


    $('body').on('click', '.section304 .spoiler_toggle', function () {
        if ($(this).parent().hasClass('close')) {
            $(this).text('-')
        }
        if ($(this).parent().hasClass('open')) {
            $(this).text('+')
        }
        $(this).parent().toggleClass('open');
        $(this).parent().toggleClass('close');
    })
    $('body').on('click', '.section305 .spoiler_toggle', function () {
        if ($(this).parent().hasClass('close')) {
            $(this).text('-')
        }
        if ($(this).parent().hasClass('open')) {
            $(this).text('+')
        }
        $(this).parent().toggleClass('open');
        $(this).parent().toggleClass('close');

    })

    $('body').on('mouseenter mouseleave', '.section302 li.level0', function (e) {
        var self = this;
        if (e.type == 'mouseenter') {


            $(this).parent().find('li.level1.visible').hide().removeClass('visible');

            var stop = false;
            var offset = $(self).position();
            var left = offset.left + $(self).outerWidth();
            var top = offset.top;
            var h = $(self).outerHeight();
            var color = $(this).closest('.section').css('background');
            var w = 0;

//                    $(self).nextAll('li').each(function(i,el){
//                        if(!stop){
//                            
//
//                            if($(el).hasClass('level1')){
//                                if($(el).width()>w){
//                                    w = $(el).width();
//                                }
//                            }else{
//                                stop = true;
//                            }
//                        }
//                    })                    
//                    var stop  = false;

            w = 250;
            $(self).nextAll('li').each(function (i, el) {
                if (!stop) {
                    if ($(el).hasClass('level1')) {

                        h = $(el).outerHeight();
                        $(el).show().addClass('visible').css({top: top, left: left, width: w, textAlign: 'left'}).hover(function () {
                            //$(this).css({ backgroundColor:'#ccc'})


                        })
                        top += h - 1;
                    } else {
                        stop = true;
                    }
                }
            })
        }
    })
    $('body').on('mouseenter mouseleave', '.section302 .menu1', function (e) {
        if (e.type == 'mouseleave') {
            $(this).find('li.level1.visible').hide().removeClass('visible');
        }
    })

    $('body').on('mouseenter mouseleave', '.section116 li.level0', function (e) {

        var self = this;
        if (e.type == 'mouseenter') {

            $(this).parent().find('li.level1.visible').hide().removeClass('visible');

            var stop = false;
            var offset = $(self).position();
            var left = offset.left;
            var top = 0;
            var h = $(self).outerHeight();
            var color = $(this).closest('.section').css('backgroundColor');
            var w = 0;

//                    $(self).nextAll('li').each(function(i,el){
//                        if(!stop){
//                            
//
//                            if($(el).hasClass('level1')){
//                                if($(el).width()>w){
//                                    w = $(el).width();
//                                }
//                            }else{
//                                stop = true;
//                            }
//                        }
//                    })                    
//                    var stop  = false;

            w = 250;
            $(self).nextAll('li').each(function (i, el) {
                if (!stop) {
                    if ($(el).hasClass('level1')) {
                        top += $(el).outerHeight() - 1;
                        $(el).show().addClass('visible').css({top: top, left: left, background: color, width: w, textAlign: 'left'})
                    } else {
                        stop = true;
                    }
                }
            })
        }
    })



    //////




    ////



    $('body').on('mouseenter mouseleave', '.section116 .menu1', function (e) {
        if (e.type == 'mouseleave') {
            
            $(this).find('li.level1.visible').hide().removeClass('visible');
        }
    })

    $('body').on('mouseenter mouseleave', '.section107   li.level0', function (e) {
		var self = this;
        if (e.type == 'mouseenter') {


            $(this).parent().find('li.level1.visible').hide().removeClass('visible');

            var stop = false;
            var offset = $(self).position();
            var left = offset.left;
            var h = $(self).outerHeight();
            var top = offset.top + 5;
            var color = $(this).closest('.section').css('backgroundColor');
            var w = 0;

//                    $(self).nextAll('li').each(function(i,el){
//                        if(!stop){
//                            
//
//                            if($(el).hasClass('level1')){
//                                if($(el).width()>w){
//                                    w = $(el).width();
//                                }
//                            }else{
//                                stop = true;
//                            }
//                        }
//                    })                    
//                    var stop  = false;

            w = 200;
            $(self).nextAll('li').each(function (i, el) {
                if (!stop) {
                    if ($(el).hasClass('level1')) {
                        top += $(el).outerHeight() - 1;
                        $(el).show().addClass('visible').css({top: top, left: left, background: color, width: w, textAlign: 'left', paddingLeft: '16px'})
                    } else {
                        stop = true;
                    }
                }
            })
        }
    })
    
    
    
     $('body').on('mouseenter mouseleave', '.section120 li.level0', function (e) {
        var self = this;
        if (e.type == 'mouseenter') {

            $(this).parent().find('li.level1.visible').hide().removeClass('visible');

            var stop = false;
            var offset = $(self).position();
            var left = offset.left;
            var top = 0;
            var h = $(self).outerHeight();
            var color = $(this).closest('.section').css('backgroundColor');
            var w = 0;

//                    $(self).nextAll('li').each(function(i,el){
//                        if(!stop){
//                            
//
//                            if($(el).hasClass('level1')){
//                                if($(el).width()>w){
//                                    w = $(el).width();
//                                }
//                            }else{
//                                stop = true;
//                            }
//                        }
//                    })                    
//                    var stop  = false;

            w = 250;
            $(self).nextAll('li').each(function (i, el) {
                if (!stop) {
                    if ($(el).hasClass('level1')) {
                        top += $(el).outerHeight() - 1;
                        $(el).show().addClass('visible').css({top: top, left: left, background: color, width: w, textAlign: 'left'})
                    } else {
                        stop = true;
                    }
                }
            })
        }
    })



    //////




    ////



    $('body').on('mouseenter mouseleave', '.section120 .menu1', function (e) {
        if (e.type == 'mouseleave') {
            
            $(this).find('li.level1.visible').hide().removeClass('visible');
        }
    })

    $('body').on('mouseenter mouseleave', '.section107   li.level0', function (e) {
        var self = this;
        if (e.type == 'mouseenter') {


            $(this).parent().find('li.level1.visible').hide().removeClass('visible');

            var stop = false;
            var offset = $(self).position();
            var left = offset.left;
            var h = $(self).outerHeight();
            var top = offset.top + 5;
            var color = $(this).closest('.section').css('backgroundColor');
            var w = 0;

//                    $(self).nextAll('li').each(function(i,el){
//                        if(!stop){
//                            
//
//                            if($(el).hasClass('level1')){
//                                if($(el).width()>w){
//                                    w = $(el).width();
//                                }
//                            }else{
//                                stop = true;
//                            }
//                        }
//                    })                    
//                    var stop  = false;

            w = 200;
            $(self).nextAll('li').each(function (i, el) {
                if (!stop) {
                    if ($(el).hasClass('level1')) {
                        top += $(el).outerHeight() - 1;
                        $(el).show().addClass('visible').css({top: top, left: left, background: color, width: w, textAlign: 'left', paddingLeft: '16px'})
                    } else {
                        stop = true;
                    }
                }
            })
        }
    })
    
    
    
    
    $('body').on('mouseenter mouseleave', '.section107 .menu1', function (e) {
        if (e.type == 'mouseleave') {
            $(this).find('li.level1.visible').hide().removeClass('visible');
        }
    })


////

    $('body').on('mouseenter mouseleave', '.section109   li.level0', function (e) {
        var self = this;
        if (e.type == 'mouseenter') {


            $(this).parent().find('li.level1.visible').hide().removeClass('visible');

            var stop = false;
            var offset = $(self).position();
            var left = offset.left;
            var h = $(self).outerHeight();
            var top = offset.top + 5;
            var color = $(this).closest('.section').css('backgroundColor');
            var w = 0;

//                    $(self).nextAll('li').each(function(i,el){
//                        if(!stop){
//                            
//
//                            if($(el).hasClass('level1')){
//                                if($(el).width()>w){
//                                    w = $(el).width();
//                                }
//                            }else{
//                                stop = true;
//                            }
//                        }
//                    })                    
//                    var stop  = false;

            w = 200;
            $(self).nextAll('li').each(function (i, el) {
                if (!stop) {
                    if ($(el).hasClass('level1')) {
                        top += $(el).outerHeight() - 1;
                        $(el).show().addClass('visible').css({top: top, left: left, background: color, width: w, textAlign: 'left', paddingLeft: '5px'})
                    } else {
                        stop = true;
                    }
                }
            })
        }
    })
    $('body').on('mouseenter mouseleave', '.section109 .menu', function (e) {
        if (e.type == 'mouseleave') {
            $(this).find('li.level1.visible').hide().removeClass('visible');
        }
    })





    $('body').on('mouseenter mouseleave', '.section105   li.level0', function (e) {
        var self = this;
        if (e.type == 'mouseenter') {


            $(this).parent().find('li.level1.visible').hide().removeClass('visible');

            var stop = false;
            var offset = $(self).position();
            var left = offset.left;
            var h = $(self).outerHeight();
            var top = offset.top - 2;
            var color = $(this).closest('.section').css('backgroundColor');
            var w = 0;

//                    $(self).nextAll('li').each(function(i,el){
//                        if(!stop){
//                            
//
//                            if($(el).hasClass('level1')){
//                                if($(el).width()>w){
//                                    w = $(el).width();
//                                }
//                            }else{
//                                stop = true;
//                            }
//                        }
//                    })                    
//                    var stop  = false;

            w = 170;
            $(self).nextAll('li').each(function (i, el) {
                if (!stop) {
                    if ($(el).hasClass('level1')) {
                        top += $(el).outerHeight() - 1;
                        $(el).show().addClass('visible').css({top: top, left: left, background: color, width: w, textAlign: 'left', paddingLeft: '16px'})
                    } else {
                        stop = true;
                    }
                }
            })
        }
    })
    $('body').on('mouseenter mouseleave', '.section105 .menu1', function (e) {
        if (e.type == 'mouseleave') {
            $(this).find('li.level1.visible').hide().removeClass('visible');
        }
    })

    $('body').on('mouseenter mouseleave', '.section311   li.level0', function (e) {
        var self = this;
        if (e.type == 'mouseenter') {


            $(this).parent().find('li.level1.visible').hide().removeClass('visible');

            var stop = false;
            var offset = $(self).position();
            var left = offset.left;
            var h = $(self).outerHeight();
            var top = offset.top + 0;
            var color = 'rgba(0,0,0,0.5)';
            var w = 0;

//                    $(self).nextAll('li').each(function(i,el){
//                        if(!stop){
//                            
//
//                            if($(el).hasClass('level1')){
//                                if($(el).width()>w){
//                                    w = $(el).width();
//                                }
//                            }else{
//                                stop = true;
//                            }
//                        }
//                    })                    
//                    var stop  = false;

            w = 170;
            $(self).nextAll('li').each(function (i, el) {
                if (!stop) {
                    if ($(el).hasClass('level1')) {
                        top += $(el).outerHeight() - 1;
                        $(el).show().addClass('visible').css({top: top, left: left, background: color, width: w, textAlign: 'left', paddingLeft: '10px'})
                    } else {
                        stop = true;
                    }
                }
            })
        }
    })

    $('body').on('mouseenter mouseleave', '.section1116   li.level0', function (e) {
        var self = this;
        if (e.type == 'mouseenter') {


            $(this).parent().find('li.level1.visible').hide().removeClass('visible');

            var stop = false;
            var offset = $(self).position();
            var left = offset.left;
            var h = $(self).outerHeight();
            var top = offset.top + 0;
            var color = 'rgba(0,0,0,0)';
            var w = 0;

//                    $(self).nextAll('li').each(function(i,el){
//                        if(!stop){
//                            
//
//                            if($(el).hasClass('level1')){
//                                if($(el).width()>w){
//                                    w = $(el).width();
//                                }
//                            }else{
//                                stop = true;
//                            }
//                        }
//                    })                    
//                    var stop  = false;

            w = 170;
            $(self).nextAll('li').each(function (i, el) {
                if (!stop) {
                    if ($(el).hasClass('level1')) {
                        top += $(el).outerHeight() - 1;
                        $(el).show().addClass('visible').css({top: top, left: left, background: color, width: w, textAlign: 'left', paddingLeft: '15px'})
                    } else {
                        stop = true;
                    }
                }
            })
        }
    })

    $('body').on('mouseenter mouseleave', '.section1116 .menu1', function (e) {
        if (e.type == 'mouseleave') {
            $(this).find('li.level1.visible').hide().removeClass('visible');
        }
    })

    $('body').on('mouseenter mouseleave', '.section311 .menu1', function (e) {
        if (e.type == 'mouseleave') {
            $(this).find('li.level1.visible').hide().removeClass('visible');
        }
    })




    $('body').on('click', '.section126 .img_1, .section126 .img_2 , .section126 .img_3 , .section126 .img_4 ', function () {

        var image = $(this).children('img').data('image');
        $(this).parent().parent().find('.image1').children('img').attr('src', image);
    })
    $('body').on('click', '.section129 .img_1, .section129 .img_2 , .section129 .img_3 , .section129 .img_4 ', function () {

        var image = $(this).children('img').data('image');
        $(this).parent().parent().find('.image1').children('img').attr('src', image);
    })

    $('body').on('click', '.section310 .image_box', function () {
        var image = $(this).children('img').data('image');
        $(this).parent().parent().parent().parent().find('.viewport').css({
            backgroundImage: 'url(' + image + ')'
        });
    })


    $('body').on('click', '.section310 .iamges .go_left', function () {
        silder310($(this).closest('.section'), 'right');
    })


    $('body').on('click', '.section310 .iamges .go_right', function () {
        silder310($(this).closest('.section'), 'left');
    })


    $('body').on('click', '.section116 .menu-toogler', function () {
        $(this).parent().children('.menu1').toggle();
    })
    $('body').on('click', '.section120 .menu-toogler', function () {
        $(this).parent().children('.menu1').toggle();
        $(this).closest('.section').toggleClass('menu_open');
    })





    $(window).resize(function () {
        if (this.resizeTO)
            clearTimeout(this.resizeTO);
        this.resizeTO = setTimeout(function () {
            $(this).trigger('resizeComplete');
        }, 500);
    })
    $(window).bind('resizeComplete', function () {
//        console.log('!');


        if ($(window).width() > 640) {

            $('.section116').find('.menu1').show();
        } else {
            $('.section116').find('.menu1').hide();

        }




    });


    setInterval(function () {
        $({temporary_x: -151, temporary_y: 0}).animate({temporary_x: 500, temporary_y: 0}, {
            duration: 1000,
            step: function () {
                var position = Math.round(this.temporary_x) + "px " + Math.round(this.temporary_y) + "px";
                $('.btn.animation, .btn1.animation, .btn2.animation, .btn3.animation, .btn4.animation, .btn5.animation').css("background-position", position);
            }
        });
    }, 2000)




    $('body').on('click', '.menu_mobile_btn', function (e) {
        $(this).next().children('.menu1 ul').toggle();
    });

    $('body').on('click', '.menu-toogler_widget', function (e) {
        $(this).parent('.section1116').addClass('view_menu');
        $(this).next('.menu-toogler_widget_close').show();
        $(this).hide();
    });

    $('body').on('click', '.menu-toogler_widget_close', function () {
        $(this).parent('.section1116').removeClass('view_menu');
        $(this).prev('.menu-toogler_widget').show();
        $(this).hide();
    });



    $('body').on('click', '.for_view', function () {
        $(this).parent('.section1116').addClass('view_menu');
        $(this).hide();
        $('.type_id_1116 .for_hide').show();
    });

    $('body').on('click', '.for_hide', function () {
        $(this).parent('.section1116').removeClass('view_menu');
        $(this).hide();
        $('.type_id_1116 .for_view').show();
    });




    $(document).on('click', document, function (event) {

        if ($(event.target).closest('.section1116, .menu_config').length)
            return;


        $('#wrapper').find('.section1116.view_menu').find('.menu-toogler_widget_close').click();
        event.stopPropagation();
    });


//     section 159 video play
    var video_block = $('.section159 .video');
    var icon = $('.section159 .play');
    icon.click(function () {
        icon.toggleClass('active');
        video_block.toggleClass('play_active');
        return false;
    });





    $(document).on('click', '.filter_btns button', function () {
        
        $('.filter_btns button').removeClass('current_btn');
        $(this).addClass('current_btn');
        
        var $items = $(this).parent().parent().children('.arr1');
        //Сортировка
        ($(this).data('sort_dir') === 'to_max') ? $items.sort(sort_to_max) : $items.sort(sort_to_min);
        //Вывод 
        $(this).parent().parent().children('.arr1').each(function (i, el) {
            $(el).replaceWith(function () {
                return $items.get(i).outerHTML;
            });
        });

    });

    function sort_to_max(a, b) {
        var a_val = parseInt($(a).find('.price1').text());
        var b_val = parseInt($(b).find('.price1').text());
        return (a_val > b_val ? 1 : b_val > a_val ? -1 : 0);
    }
    function sort_to_min(a, b) {
        var a_val = parseInt($(a).find('.price1').text());
        var b_val = parseInt($(b).find('.price1').text());
        return (a_val > b_val ? -1 : b_val > a_val ? 1 : 0);
    }


});


