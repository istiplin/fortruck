ajax_form({
    done:function(data){
        if (data.success==1)
        {
            $('#registration-modal').modal('hide');
            $('#mail-send-message .email').html(data.email);
            $('#mail-send-message').modal('show');

            $('#'+registration_id+' .form-control').val('');
            $('#'+registration_id).yiiActiveForm('resetForm');
        }
        else if (data.success==0)
        {
            $('#'+registration_id).yiiActiveForm('updateMessages',data.messages);
        }
    },
    selector:'#'+registration_id
});