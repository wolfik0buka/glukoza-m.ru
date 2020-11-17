@extends('public.app')

@section('title', $seo->title)

@section('content')
    <div class="page_static">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> > Регистрация
            </div>
            <h1>{{ $seo->h1 }}</h1>
        </div>
        <div class="container">
            <div class="row bottom-30">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card__content">
                            <form method="post" action="{{ route('reg') }}" id="form_reg">
                                <p>Зарегистрированные пользователи не вводят свои контактные данные при каждом заказе, могут просмотреть информацию о своих заказах, получают информацию о новинках и акциях, накапливают скидки.
                                </p>
                                <div class="row">
                                    <div class="top-30">
                                        <div class="form-group col-sm-6">
                                            <label>Фамилия, Имя *</label>
                                            <input type="text" class="form-control" name="fio">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Телефон *</label>
                                            <input type="text" class="form-control" name="phone">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>E-mail *</label>
                                            <input type="text" class="form-control" name="email">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Адрес</label>
                                            <input type="text" class="form-control" name="adr">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Пароль *</label>
                                            <input type="password" class="form-control" name="pwd1">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Пароль (еще раз) *</label>
                                            <input type="password" class="form-control" name="pwd2">
                                        </div>
                                        <div class="checkbox col-xs-12">
                                            <label>
                                                <input type="checkbox" id="pers_data">
                                                Согласен на <a class="uni-link" target="_blank" href="/confirm">обработку
                                                    персональных данных</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="top-15 bottom-15">
                                <button type="button" class="btn btn-primary btn-mobile" onclick="reg_user()">
                                    Зарегистрироваться
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card__list">
                            <div class="card__listItem">
                                <a class="font-s16" href="/cabinet/auth_form">Уже зарегистрированы?</a>
                            </div>
                            <div class="card__listItem">
                                <a class="font-s16" href="/cabinet/restore_password">Забыли пароль?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function reg_user() {
            if ($('input#pers_data').prop('checked')) {
                if (
                    ($('input[name="fio"]').val() == '') ||
                    ($('input[name="phone"]').val() == '') ||
                    ($('input[name="email"]').val() == '') ||
                    ($('input[name="pwd1"]').val() == '') ||
                    ($('input[name="pwd2"]').val() == '')
                ) {
                    $.magnificPopup.open({
                        items: {
                            src: '<div class="white-popup">' +
                            '<div class="white-popup__title marmelad">Внимание!</div>' +
                            '<p>Вы заполнили не все обязательные поля.</p>' +
                            '<div class="white-popup__buttons">' +
                            '<button class="btn btn-success" onclick="$.magnificPopup.close()">Ок</button></div>' +
                            '</div>',
                            type: 'inline'
                        }
                    });
                } else {
                    if ($('input[name="pwd1"]').val() != $('input[name="pwd2"]').val()) {
                        $.magnificPopup.open({
                            items: {
                                src: '<div class="white-popup">' +
                                '<div class="white-popup__title marmelad">Внимание!</div>' +
                                '<p>Введеные пароли - не совпадают.</p>' +
                                '<div class="white-popup__buttons">' +
                                '<button class="btn btn-success" onclick="$.magnificPopup.close()">Ок</button></div>' +
                                '</div>',
                                type: 'inline'
                            }
                        });
                    } else {
                        $("#form_reg").submit();
                    }
                }
            } else {
                $.magnificPopup.open({
                    items: {
                        src: '<div class="white-popup">' +
                        '<div class="white-popup__title marmelad">Внимание!</div>' +
                        '<p>Вам необходимо подтвердить соглашение<br>на обработку персональных данных.</p>' +
                        '<div class="white-popup__buttons">' +
                        '<button class="btn btn-success" onclick="$.magnificPopup.close()">Ок</button></div>' +
                        '</div>',
                        type: 'inline'
                    }
                });
            }
        }
        /**
         *  Соглашение об обработке персональных данных
         */
        function pers_data() {
            $.magnificPopup.open({
                items: {
                    src: '<div class="white-popup">' +
                    '<div class="white-popup__title marmelad">ВНИМАНИЕ!</div>' +
                    '<p style="text-align: center;font-weight: 600;font-size: 18px;">Обработка персональных данных</p>' +
                    '<p style="font-size: 13px;color:#222;">Администрация сайта www.glukoza-med.ru (далее Оператор) обязуется соблюдать правила обработки персональных данных, предусмотренные Федеральным законом «О персональных данных» и иными законами, подзаконными нормативными актами. Указанные персональные данные обрабатываются оператором путем их сбора, записи, систематизации, накопления, хранения, уточнения, использования, уничтожения и иных действий исключительно для целей ведения статистического учёта пользователей сайта. Оператор обязуется соблюдать конфиденциальность персональных данных и обеспечивать безопасность персональных данных при их обработке в соответствии с требованиями к защите обрабатываемых персональных данных, установленными в статье 19 Федерального закона «О персональных данных».</p>' +
                    '<div class="white-popup__buttons">' +
                    '<a class="btn btn-success" onclick="pers_data_yes()">Согласен</a>' +
                    '<a class="btn btn-danger" onclick="pers_data_no()">Не согласен</a></div>' +
                    '</div>',
                    type: 'inline'
                },
                closeBtnInside: false,
                closeOnBgClick: false,
                showCloseBtn: false
            });
        }
        function pers_data_yes() {
            $.magnificPopup.close();
            $('input#pers_data').prop('checked', true);
        }
        function pers_data_no() {
            $.magnificPopup.close();
            $('input#pers_data').prop('checked', false);
        }
    </script>
@stop