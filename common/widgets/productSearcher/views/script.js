<?php ob_start(); ?>
var selector = "<?=$selector?>";
var id = "<?=$id?>";
var baseUrl = '<?=\Yii::$app->request->baseUrl?>';
var route = '<?=$route?>';


$('#'+id+'-modal').click(function(e){
    var url = null;
    var data = null;
    var beforeSend = null;
    if ($(e.target).hasClass('offers') || $(e.target).hasClass('lookup'))
    {
        data = {};
        url = baseUrl+'/'+route;

        if ($(e.target).hasClass('lookup'))
        {
            data['number'] = $('[name=number]').val();
        }
        else if ($(e.target).hasClass('offers'))
        {
            data['number'] = $(e.target).parent().data('norm-number');
            data['brandName'] = $(e.target).parent().data('brand');
        }
        
        beforeSend = function(){
            $('#'+id+'-modal .res').html('Идет загрузка данных ждите...');
        };
    }
    else if ($(e.target).data('page')!==undefined)
    {
        e.preventDefault();
        url = $(e.target).attr('href');
    }
    
    if (url)
    {
        $.ajax({
            url: url,
            data: data,
            method: 'get',
            beforeSend: beforeSend,
            success: function(data)
            {
                $('#'+id+'-modal .res').html(data);
            }
        });
    }
});

<?php return ob_get_clean();?>