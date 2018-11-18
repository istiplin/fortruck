$(document).ready(function(){
    
    $('.alert .close').click(function(){
        $('.alert').css({'opacity':0},1000);
    });
    
    /*
    $('.request-price-button').click(function(e){
        e.preventDefault();
        $.ajax({
                url: $(this).attr('href'),
                success: function()
                {
                    $(this).html('Цена запрошена');
                    $(this).attr('href', '');
                }.bind(this)
        })
        
    });
    */
    
    //события с корзиной
    $('.add-to-cart').click(function(e){
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
            $('.alert').css({'opacity':0},2000);
            
            $cartCount = $(e.target).parent().find('.cart-count');
            
            //определяем количество товаров
            change_value = $cartCount.val();
            
            //определяем id товара
            change_id = $cartCount.data('id');

            //осуществляем запрос на сервер, изменяя содержимое корзины, и возвращая обработанные данные
            $.ajax({
                url: '/shop/site/add-to-cart',
                data: {id:change_id,count:change_value},
                method: 'get',
                dataType: "json",
                success: function(data)
                {
                    //после успешного изменения корзины, изменяем некоторые данные итерфейса:
                    if (data['status']=='success')
                    {
                        //изменяем количество товаров
                        $('.qty').html(data['qty']);

                        //изменяем стоимость всех товаров
                        $('.moneySumm').html(data['moneySumm']);
                        
                        $('*[data-id="'+change_id+'"]').html(change_value);
                        $('.alert').removeClass('alert-danger');
                        $('.alert').addClass('alert-success');
                    }
                    else if(data['status']=='error')
                    {
                        $('.alert').removeClass('alert-success');
                        $('.alert').addClass('alert-danger');
                    }
                    
                    $('.alert').css({'opacity':1},2000);
                    $('.alert-message').html(data['message']);
                }
            });
        }
    })
})