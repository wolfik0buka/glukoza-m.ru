@extends('public.app')

@section('title', 'Авторизация пользователя')

@section('content')
    <div class="page_static">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> > Авторизация
            </div>
            <h1>Проверьте почту</h1>
        </div>
        <div class="container">
            <div class="row bottom-30">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card__content">
                            <p class="font-s16">Мы отправили СМС и письмо с новым паролем на указанные в вашем профиле контактные данные.</p>
                            <a href="/cabinet/auth_form" class="btn btn-primary">Войти с новым паролем</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card__list">
                            <div class="card__listItem">
                                <a class="font-s16" href="/cabinet/auth_form">Вход в личный кабинет</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop