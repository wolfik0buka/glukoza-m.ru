<div class="topLine clearfix">
    <div class="container">
        <div class="topLine_element dropdown">
            <a href="javascript:void(0);"
               class="dropdown-toggle hidden-md hidden-lg"
               data-toggle="dropdown"
               role="button">
                Покупателям <i class="fa left-5 fa-chevron-down"></i>
            </a>
            <div style="width:320px;" class="dropdown-menu">
                <a href="/kontakty">Контакты</a>
                <a href="/dostavka">Доставка и самовывоз</a>
                <a href="/oplata">Оплата</a>
                <a href="/beznal">Юр. лицам</a>
                <a href="/bonusnaya-programma">Бонусная программа</a>
                <a href="/track-orders" title="Отслеживание заказов">Отслеживание</a>
                <div class="text-muted top-10 font-s13 font-lh140">Напишите нам</div>
                <a href="mailto:glukoza@glukoza-med.ru">glukoza@glukoza-med.ru</a>
            </div>
        </div>

        <div class="topLine_element dropdown left--15 hidden-xs hidden-sm">
            <a href="javascript:void(0);"
                class="dropdown-toggle"
                data-toggle="dropdown"
                role="button">
                <i class="fa fa-envelope font-s12"></i>
                <span class="hidden-xs">Напишите нам</span>
            </a>
            <div class="dropdown-menu">
                <div class="text-muted font-s13 font-lh140">Контактный email:</div>
                <a href="mailto:glukoza@glukoza-med.ru">glukoza@glukoza-med.ru</a>
                {{--<div class="divider"></div>--}}
                {{--<div class="text-muted font-s13 font-lh140">Или отправьте сообщение прямо здесь:</div>--}}
                {{--<form action="#">--}}
                {{--<input type="text" class="form-control top-10" placeholder="Тема">--}}
                {{--<textarea name=""--}}
                {{--class="form-control top-10 bottom-5"--}}
                {{--rows="6"--}}
                {{--placeholder="Сообщение"></textarea>--}}
                {{--<button type="button" class="btn btn-primary">--}}
                {{--<i class="right-5 fa fa-paper-plane-o"></i>--}}
                {{--Отправить--}}
                {{--</button>--}}
                {{--</form>--}}
            </div>
        </div>
        <div class="topLine_element hidden-xs hidden-sm">
            <a href="/dostavka">Доставка и самовывоз</a>
        </div>
        <div class="topLine_element hidden-xs hidden-sm">
            <a href="/oplata">Оплата</a>
        </div>
        <div class="topLine_element hidden-xs hidden-sm">
            <a href="/beznal">Юр. лицам</a>
        </div>
        <div class="topLine_element hidden-xs hidden-sm">
            <a href="/track-orders" title="Отслеживание заказов">Отслеживание заказа</a>
        </div>
        <div class="topLine_element hidden-xs hidden-sm">
            <a href="/kontakty" title="Контакты">Контакты</a>
        </div>


        @if(Session::has('user'))
            <div class="topLine_element pull-right dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                    Личный кабинет <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/cabinet/profile">Профиль</a></li>
                    <li><a href="/cabinet/profile/history">Мои заказы</a></li>
                    {{--<li><a href="index.php?page=cabinet&part=my_info">Мои данные</a></li>--}}
                    {{--<li><a href="index.php?page=cabinet&part=my_bonus">Мои бонусы</a></li>--}}
                    <li><a href="/index.php?page=stat&alias=bonus">Бонусы - что это?</a></li>
                    <li><a href="{{ route('logout') }}">Выход</a></li>
                </ul>
            </div>
        @else
            <div class="topLine_element pull-right dropdown">
                <a  class="dropdown-toggle" data-toggle="dropdown" role="button">
                    <i class="right-5 fa fa-sign-in"></i>Личный кабинет
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/cabinet/auth_form">Вход</a></li>
                    <li><a href="/cabinet/reg_form">Зарегистрироваться</a></li>
                </ul>
            </div>
        @endif


    </div>
</div>