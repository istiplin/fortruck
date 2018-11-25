var widget_id = "<?=$id?>";
ajax_form({
    done:function(data){
            if (data.success==1)
            {
                document.location.href = data.redirectUrl;
            }
            else if (data.success==0)
            {
                for (id in data.errors)
                {
                    selector = '#'+this.widget_id+'-form [name='+id+']';
                    $(selector).parent().addClass('has-error');
                    $(selector).next('div').html(data.errors[id]);
                }
                $('#'+this.widget_id+'-form [type=password]').val('');
            }
        }.bind({widget_id:widget_id}),
    selector:'#'+widget_id+'-form'
});

$('#'+widget_id+'-modal').on('shown.bs.modal',function(e){
    $('#'+this.widget_id+'-form [name=username]').focus();
}.bind({widget_id:widget_id}))