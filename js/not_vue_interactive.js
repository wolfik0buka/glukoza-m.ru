$( function() {
    $('.do_toggle_city').click(function () {
        var value = $(this).text();
        $('.do_set_city_value').text(value);
        $.cookie('city', value, { expires: 7, path: '/' });
    });
    $('#feedback__form').submit(function (e) {
        e.preventDefault();
        var result =  $(this).serializeArray();
        var right = true;
        var pers = false;
        var pot = false;
        var data = {};
        var errors =[];
        result.forEach(function (field) {
            if (field.name === 'pot' && field.value !== ""){
                pot = true;
            } else{
                switch (field.name) {
                    case 'phone':
                        if(!field.value){
                             errors.push('Телефон');
                        }else{
                            data.phone = field.value;
                        }
                        break;
                    case 'fio':
                        if(!field.value){
                            errors.push('ФИО');
                        }else{
                            data.fio = field.value;
                        }
                        break;
                    case 'question':
                        if(!field.value){
                            errors.push('Вопрос');
                        }else{
                            data.question = field.value;
                        }
                        break;
                    case 'pers_data':
                        pers = true;
                }
            }

        });
        if(errors.length){
            $('#feedback__errors').text('Пожалйста заполните следующие поля: ' + errors.join(', '));
            $('#feedback__success').text();
        } else if(!pers){
            $('#feedback__errors').text('Нам требуется ваше согласие на обработку персональных данных');
            $('#feedback__success').text();
        } else {
            if(pot){
                $('#feedback__success').text('Ваше сообщение отправлено!');
                $(this).find('.form-control').val('');
                $('#feedback__errors').text();
            } else {
                console.log(data);
                $.ajax({
                    url: '/feedback-handler',
                    dataType: 'json',
                    method: 'post',
                    data: data,
                    success: function (response) {
                        if(response.status === "Ok"){
                            $('#feedback__success').text('Ваше сообщение отправлено!');
                            $('.form-control').val('');
                            $('#feedback__errors').text('');
                        } else {
                            $('#feedback__errors').text(response.error);
                            $('#feedback__success').text('');
                        }
                    }
                });
            }

        }

    });
    $( "#product__tabs" ).tabs();
    $('.responseForm__form').on('submit', function(e){
        e.preventDefault();
        var response =  $(this).serializeArray();
        var errors = [];
        var data = [];
        var pers_data = false;
        response.forEach(function (field) {
            switch (field.name) {
                case 'fio':
                    if(!field.value.trim()){
                        errors.push('Пожалуйста, заполните имя');
                    }else{
                        data.fio = field.value.trim();
                    }
                    break;
                case 'response':
                    if(!field.value.trim()){
                        errors.push('Пожалуйста, напишите отзыв');
                    }else{
                        data.response = field.value.trim();
                    }
                    break;
                case 'tovar_id':
                    if(!field.value.trim()){
                        errors.push('Произошла ошибка при отправке формы. Пожалуйста, попробуйте позднее.');
                    }else{
                        data.tovar_id = field.value.trim();
                    }
                    break;
                case 'user_id':
                    if(!field.value.trim()){
                        errors.push('Произошла ошибка при отправке формы. Пожалуйста, попробуйте позднее.');
                    }else{
                        data.user_id = field.value.trim();
                    }
                    break;
                case 'pers_data':
                    pers_data = true;
                    break;
            }
        });
        if (!pers_data){
            errors.push('Мы не сможем обработать ваш отзыв без вашего согласия.');
        }
        if (errors.length) {
            var errorText = errors.join('<br>');
            $(this).find('.responseForm__errors').html(errorText).css("display", "block");
        } else {
            $(this).find('.responseForm__errors').html('').css("display", "none");
            $.ajax({
                url: '/responses/add',
                dataType: 'json',
                method: 'post',
                data: data,
                success: function (response) {
                    if(response.status === "added"){
                        $('.responseForm__form').hide();
                        $('.responseForm__succsess').
                            text('Спасибо! Ваш отзыв будет опубликован после прохождения модерации.')
                            .css("display", "block");
                    } else {
                        $('.responseForm__errors')
                            .html('Извините, произошла ошибка при отправке')
                            .css("display", "block");
                    }
                }
            });
        }
        console.log(response);
    })
});
