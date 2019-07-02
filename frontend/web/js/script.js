$(document).ready(function(){
    
    //перемещаем все теги модальных окон в конец тега body
    $('.modal').appendTo('body');
    
    //при вызове модального окна остальные закрываем
    $('[data-toggle=modal]').click(function(){
        $('.modal').modal('hide');
    })
    
    
    $('.alert .close').click(function(){
        $('.alert').css({'opacity':0});
        $('.alert').hide();
    });
    
    /*
    $('body').submit(function(e) {
        if ($(e.target).hasClass('form-order'))
            return confirm('Вы уверены, что хотите оформить заказ?');
    });
    */
    
    //переменная определяющая добавляется ли товар в корзину
    cart_button_click = false;
    
    //события с корзиной
    //$('.add-to-cart')
    $('body')
    .click(function(e){
        //событие при уменьшении количества товаров
        if ($(e.target).hasClass('minus-button'))
        {
            $cartCount = $(e.target).parent().find('.cart-count');
            value = $cartCount.val();
            if (value>0)
                value--;
            $cartCount.val(value);
        }
        
        //событие при увеличении количества товаров
        if ($(e.target).hasClass('plus-button'))
        {
            $cartCount = $(e.target).parent().find('.cart-count');
            value = $cartCount.val();
            value++;
            $cartCount.val(value);
        }
        
        //событие при добавлени товара в корзину
        if ($(e.target).hasClass('cart-button'))
        {
            if (cart_button_click)
                return;
            
            cart_button_click = true;
            
            $(e.target).removeClass('cart-img');
            $(e.target).addClass('cart-loader');
            
            $('.alert').css({'opacity':0},2000);
            
            //cart_button_key = $(e.target).data('cart-button-key');
            $product_data = $(e.target).parents('.product-data');
            norm_number = $product_data.data('norm-number');
            brand = $product_data.data('brand');
            
            $cartCount = $(e.target).parent().find('.cart-count');
            call_cart_list = $(e.target).data('call-cart-list');
            
            //определяем количество товаров
            change_value = $cartCount.val();
            
            //определяем данные по которым будет идентифицироваться товар
            change_product = $cartCount.data('product');

            //осуществляем запрос на сервер, изменяя содержимое корзины, и возвращая обработанные данные
            $.ajax({
                url: BASE_URL+'/site/add-to-cart',
                data: {product:change_product,count:change_value},
                method: 'get',
                dataType: 'json',
                success: function(data)
                {
                    //после успешного изменения корзины, изменяем некоторые данные итерфейса:
                    if (data['status']=='success')
                    {
                        //изменяем количество товаров
                        $('.qty').html(data['qty']);

                        //изменяем стоимость всех товаров
                        $('.moneySumm').html(data['moneySumm']);
                        
                        $('[data-norm-number="'+norm_number+'"][data-brand="'+brand+'"]').find('.cart-count-value').html(change_value);
                        $('[data-norm-number="'+norm_number+'"][data-brand="'+brand+'"]').find('.cart-count').val(change_value);
                        
                        
                        if (call_cart_list)
                        {
                            $.ajax({
                                url: BASE_URL+'/site/cart',
                                method: 'get',
                                dataType: 'html',
                                async: false,
                                success: function(html)
                                {
                                    $('#cart-modal .modal-body').html(html);
                                    $('#cart-modal').modal('show');
                                }
                            })
                        }
                        else
                        {
                            $('.alert').removeClass('alert-danger');
                            $('.alert').addClass('alert-success');
                        
                            $('.alert').show();
                            $('.alert').css({'opacity':1});
                            $('.alert-message').html(data['message']);
                        }
                        
                    }
                    else if(data['status']=='error')
                    {
                        $('.alert').removeClass('alert-success');
                        $('.alert').addClass('alert-danger');
                        
                        $('.alert').show();
                        $('.alert').css({'opacity':1});
                        $('.alert-message').html(data['message']);
                    }
                },
                complete: function(){
                    $(e.target).removeClass('cart-loader');
                    $(e.target).addClass('cart-img');
                    cart_button_click = false;
                }
            });
        }
    })
})