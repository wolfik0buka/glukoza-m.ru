@extends('public.app')

@section('title', $seo->title)

@section('content')
    <div class="page_static">

        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> > Профиль
            </div>
            <h1>{{ $seo->h1 }}</h1>
        </div>

        <div class="container">

            @include('public._partials.componentError')

            <div class="row bottom-30">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card__content">
                            <div class="card__title bottom-30">Мой профиль</div>
                            <form action="/cabinet/profile/update" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label>ФИО</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control" value="{{ $user->email }}">
                                </div>
                                <div class="form-group">
                                    <label>Телефон</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                </div>
                                <div class="form-group">
                                    <label>Город</label>
                                    <input type="text" name="city" class="form-control" value="{{ $user->city }}">
                                </div>
                                <div class="form-group">
                                    <label>Адрес доставки</label>
                                    <input type="text" name="adr" class="form-control" value="{{ $user->adr }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </form>
                        </div>
                    </div>

                    <div class="card top-30">
                        <div class="card__content">
                            <div class="card__title bottom-10">Изменить пароль</div>
                            <form action="/cabinet/profile/change_password" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label>Введите текущий пароль</label>
                                    <input type="text" name="current_pwd" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label>Новый пароль</label>
                                    <input type="text" name="new_pwd" class="form-control" value="">
                                </div>
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-sm-4">
                    @include('public.cabinet.cabinetSidebar')
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                $('input[name="phone"]').mask("+7(999) 999-9999")
            });
        </script>

    </div>
@stop