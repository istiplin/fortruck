ajax_form({
    done:function(data){
        if (data.success==1)
        {
            $('#restore-password-modal').modal('hide');
            $('#restore-password-mail-send-message .email').html(data.email);
            $('#restore-password-mail-send-message').modal('show');

            $('#'+restore_password_id+' .form-control').val('');
            $('#'+restore_password_id).yiiActiveForm('resetForm');
        }
        else if (data.success==0)
        {
            $('#'+restore_password_id).yiiActiveForm('updateMessages',data.messages);
        }
    },
    selector:'#'+restore_password_id
});