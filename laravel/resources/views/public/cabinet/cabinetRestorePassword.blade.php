@extends('public.app')

@section('title', 'Восстановление пароля')

@section('content')
    <div class="page_static">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> <i class="fa fa-long-arrow-right"></i> Авторизация
            </div>
            <h1>Восстановление пароля</h1>
        </div>
        <div class="container">

            @include('public._partials.componentError')

            <div class="row bottom-30">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card__content">
                            <p>Укажите ваш Email, мы отправим на него новый пароль. Позже в личном кабинете вы сможете изменить пароль.</p>
                            <form method="post" action="/cabinet/restore_password/reset_password" id="form_auth">

                                <input
                                    type="hidden"
                                    name="_token"
                                    value="{{ csrf_token() }}">

                                <div class="form-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="email"
                                        placeholder="Укажите ваш Email">
                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-mobile">
                                    Выслать временный пароль
                                </button>

                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card__list">
                            <div class="card__listItem">
                                <a class="font-s16" href="/cabinet/reg_form">Регистрация</a>
                            </div>
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