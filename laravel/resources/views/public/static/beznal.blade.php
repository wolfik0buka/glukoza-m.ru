@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('content')
    <div class="beznal">

        <div class="bg-white">
            <div class="container">
                <div class="breadcrumbs top-15">
                    <a href="/">Главная</a> <i class="fa fa-long-arrow-right"></i>
                    {!! $seo->title !!}
                </div>
            </div>
            <div class="container">

                <h1>{!! $seo->title !!}</h1>

                <div class="beznal__lead">

                        <p>Мы открыты к сотрудничеству с юридическими лицами и индивидуальными предпринимателями.</p>
                        <p>Для юридических лиц доступен расширенный ассортимент товаров, не все из которых представлены на сайте. Информацию о наличии интересующего вас товара, условиях оплаты и отгрузки уточняйте в корпоративном отделе:</p>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 ptop-10">
                                <button class="b24-web-form-popup-btn-5 btn btn-lg btn-block btn-primary">Оставить заявку</button>
                            </div>
                            <div class="col-xs-12 col-sm-4 ptop-10 text-center">
                                <div class="font-s14 text-muted">Телефон (Санкт-Петербург)</div>
                                <div class="font-s18 font-w600 text-center">
                                    <a href="tel:+78122444102">+7 (812) 244-41-02</a> <br>
                                    <a href="tel:+78122444192">+7 (812) 244-41-92</a> <br>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 ptop-10 text-center">
                                <div class="font-s14 text-muted">Электронная почта</div>
                                <div class="font-s18 font-w600">
                                    <a
                                        class="color-text-default"
                                        href="mailto:glukoza@glukoza-med.ru">
                                        glukoza@glukoza-med.ru
                                    </a>
                                    <br>
                                    <a
                                        class="color-text-default"
                                        href="mailto:tiv@glukoza-med.ru">
                                        tiv@glukoza-med.ru
                                    </a>
                                    <br>
                                    <a
                                        class="color-text-default"
                                        href="mailto:skv@glukoza-medru">
                                        skv@glukoza-med.ru
                                    </a>
                                    <br>
                                </div>
                            </div>
                        </div>
                </div>

            </div>
        </div>

        <div class="container">
            <div class="row bottom-30">
                <div class="col-xs-12">

                    <h2 class="font-s26 top-30 bottom-20">Вопросы и ответы</h2>

                    <div class="card">
                        <div class="card__list">
                            <div class="card__listItem card__listItem-text">
                                <h3>Оплата</h3>
                                <p>Мы заранее оговариваем с покупателем индивидуальные условия оплаты, которые будут удобны обеим сторонам.</p>
                            </div>
                            <div class="card__listItem card__listItem-text">
                                <h3>Доставка</h3>
                                <p>Доставка товара осуществляется по всей России одним из способов:</p>
                                <ul>
                                    <li>курьерской службой СДЕК  https://cdek.ru до пункта выдачи или двери;</li>
                                    <li>транспортной компанией Деловые Линии https://www.dellin.ru/;</li>
                                    <li>почтой России: в случае, если в населенном пункте нет прочих грузовых терминалов и пунктов выдачи.</li>
                                </ul>
                            </div>
                            <div class="card__listItem card__listItem-text">
                                <h3>Самовывоз</h3>
                                <p>
                                    Вы можете самостоятельно забрать заказ (по предварительной договоренности с менеджером) по адресу:<br/>
                                    Магазин переезжает, скоро будет новый адрес
                                    <!-- Санкт-Петербург, ул. Сикейроса, д. 10, корп. 4, лит. А, ТК «Бульвар», секция 25 (<a target="_blank" href="/kontakty">Карта проезда</a>). -->
                                </p>
                            </div>
                            <div class="card__listItem card__listItem-text">
                                <h3>Какие сопроводительные документы выдаются?</h3>
                                <ul>
                                    <li>товарная накладная (форма ТОРГ-12);</li>
                                    <li>оригинал счета на оплату (по желанию покупателя);</li>
                                    <li>свидетельства о регистрации (по желанию покупателя);</li>
                                    <li>сертификаты соответствия (при условии обязательной сертификации, по желанию покупателя).</li>
                                </ul>
                            </div>
                            <div class="card__listItem card__listItem-text">
                                <h3>Возможно, вам будут полезны эти документы:</h3>
                                <ul>
                                    <li>
                                        <a
                                            target="_blank"
                                            href="{{ $cdn }}/docs/doverennost-tmc.xls">
                                            Бланк доверенности на получение груза (ТМЦ)
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            target="_blank"
                                            href="{{ $cdn }}/docs/dogovor_singer-med.docx">
                                            Типовой договор поставки
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="card">

                    </div>

                </div>
            </div>
        </div>
    </div>
@stop