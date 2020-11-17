@extends('public.app')

@section('title', $seo->title)

@section('content')
    <div class="page_static">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> <i class="fa fa-long-arrow-right"></i> Авторизация
            </div>
            <h1>{{ $seo->h1 }}</h1>
        </div>
        <div class="container">

            @include('public._partials.componentError')

            <div class="row bottom-30">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card__content">
                            <div class="card__title bottom-15">Вход в личный кабинет</div>
                            <form method="post" action="{{ route('auth') }}" id="form_auth">
                                <div class="form-group">
                                    <label>E-mail *</label>
                                    <input type="text" class="form-control" name="email">
                                </div>
                                <div class="form-group">
                                    <label>Пароль *</label>
                                    <input type="password" class="form-control" name="pwd">
                                </div>
                            </form>
                            <div class="top-20 bottom-5">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-mobile"
                                    onclick="auth_user()">
                                    Войти
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card__list">
                            <div class="card__listItem">
                                <a class="font-s16" href="/cabinet/reg_form">Зарегистрироваться</a>
                            </div>
                            <div class="card__listItem">
                                <a class="font-s16" href="/cabinet/restore_password">Восстановить пароль</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function auth_user() {
            $('#form_auth').submit();
        }
    </script>
@stop