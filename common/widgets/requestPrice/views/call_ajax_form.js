var widget_id = "<?=$id?>";
ajax_form({
    done:function(data){
            if (data.success==1)
            {
                $('#'+this.widget_id+'-modal').modal('hide');
                
                $('#'+this.widget_id+'-message-after-send-mail').modal('show');

                $('#'+this.widget_id+'-form .form-control').val('');
                $('#'+this.widget_id+'-form').yiiActiveForm('resetForm');
            }
            else if (data.success==0)
            {
                $('#'+this.widget_id+'-form').yiiActiveForm('updateMessages',data.messages);
            }
        }.bind({widget_id:widget_id}),
    selector:'#'+widget_id+'-form'
});

$('#'+widget_id+'-modal').on('show.bs.modal',function(e){
    //$('#requestpriceform-number').val($(e.relatedTarget).data('number'));
    $('#requestpriceform-number').val(e.relatedTarget.dataset.number);
    $('#requestpriceform-brandname').val(e.relatedTarget.dataset.brandName);
})