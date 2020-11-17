@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('content')
    <div class="page_PaymentCards">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> <i class="fa fa-long-arrow-right"></i>
                {!! $seo->title !!}
            </div>
        </div>
        <div class="container">
            <div class="row bottom-30">
                <div class="col-xs-12">


                    <div class="card">
                        <div class="card-block arial font-s15">
                            <h1>{!! $seo->title !!}</h1>

                            <p><strong>Я даю согласие на обработку, в том числе на сбор, систематизацию, накопление, хранение (уточнение, обновление, изменение), использование, передачу третьим лицам, обезличивание, блокирование и уничтожение моих персональных данных ООО «Сингер-Мед»</strong>, юр. адрес: 197082, г. Санкт-Петербург, Камышовая улица, дом 38, корпус 1, пом. 54, фактический адрес: г. Санкт-Петербург, ул. Стародеревенская, дом 34к1, ТК «Мир», 2 этаж, секция 2-15. (далее – «Магазин») с целью предоставления мне товаров и услуг (продуктов) и повышения удобства их получения, включая, но не ограничиваясь: информирования о деталях моих заказов, обеспечения процедуры начисления, учета и использования персональной скидки, идентификацией участника в программе лояльности, осуществление доставки, приема платежей, предоставление сервисных услуг, распространения информационных и рекламных сообщений (любыми средствам связи, включая SMS, Viber, WhatsApp, электронную почту, телефон, программы для ЭВМ и мобильные устройства), получения обратной связи.</p>
                            <p>Я также даю свое согласие на трансграничную передачу моих персональных данных, в том числе на территории иностранных государств, не включенных в перечень, утвержденный Приказом Роскомнадзора от 15.03.2013 N 274 (ред. от 29.10.2014) "Об утверждении перечня иностранных государств, не являющихся сторонами Конвенции Совета Европы о защите физических лиц при автоматизированной обработке персональных данных и обеспечивающих адекватную защиту прав субъектов персональных данных", для выполнения вышеуказанных целей обработки персональных данных.</p>
                            <p>Я предоставил свои персональные данные (фамилия, имя, отчество, адрес регистрации или пребывания, номер контактного телефона, адрес электронной почты, имя пользователей в социальных сетях) Магазину добровольно путем внесения их при регистрации на сайте glukoza-med.ru или его поддоменах, или при оформлении заказа или услуги по телефонам, указанным на сайте. Я подтверждаю, что предоставленные персональные данные являются достоверными. Я извещен о том, что в случае недостоверности предоставленных персональных сведений Магазин оставляет за собой право прекратить обслуживание посредством сайта glukoza-med.ru и его поддоменов.</p>
                            <p>Я согласен, что мои персональные данные будут обрабатываться без ограничения срока любыми законными способами, соответствующими целям обработки персональных данных (в том числе в информационных системах персональных данных с использованием средств автоматизации или без использования таких средств).</p>
                            <p>Я согласен с тем, что не является нарушением передача моих персональных данных в соответствии с обоснованными и применимыми требованиями законодательства Российской Федерации. Я извещен, что Магазин использует технологию "cookies", собирает информацию об ip-адресе, браузере (или иной программе), устройстве дате и моих действиях во время моих посещений сайта glukoza-med.ru. Данная информация не является персональными данными.</p>
                            <p>Настоящее согласие может быть отозвано мной в любой момент путем направления письменного требования в адрес Продавца (на адрес электронной почты Продавца glukoza@glukoza-med.ru, что автоматически прекращает мое участие в скидочных программах и программах лояльности, а также освобождает Продавца от выполнения обязательств по предоставлению мне товаров и услуг.</p>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
@stop