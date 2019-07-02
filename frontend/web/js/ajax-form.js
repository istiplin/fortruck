var ajax_form = function(optionsInput){
    
    var options = {
        selector: 'form',
        before: function(){},
        done: function(){},
        fail: function(){alert('не удалось выполнить запрос к серверу')}
    };

    for (field in optionsInput)
        options[field] = optionsInput[field];

    $(options['selector']).on('beforeSubmit', function () {
        options['before']();
        var $yiiform = $(this);
        // отправляем данные на сервер
        $.ajax({
                type: $yiiform.attr('method'),
                url: $yiiform.attr('action'),
                data: $yiiform.serializeArray()
            }
        )
        .done(function(data) {
            options['done'](data);
        })
        .fail(function () {
            options['fail']();
        })

        return false; // отменяем отправку данных формы
    })

}