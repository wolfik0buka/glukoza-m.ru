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
});
