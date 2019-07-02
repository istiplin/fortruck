<?php ob_start(); ?>

var id = "<?=$id?>";

$('#'+id+'-modal').click(function(e){

    if ($(e.target).hasClass('add-product'))
    {
        productName = $(e.target).parent().find('.number').html()+' ('+$(e.target).parent().data('brand')+')'
        $('[data-target="'+'#'+id+'-modal"]').html(productName);
        $('#orderitem-price').val($(e.target).parent().find('.custPrice').html());
        
        $('[name=normNumber]').val($(e.target).parent().data('norm-number'));
        $('[name=brandName]').val($(e.target).parent().data('brand'));
        
        $(this).modal("hide");
    }

});

<?php return ob_get_clean();?>