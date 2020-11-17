@extends('public.app')

@section('title', $seo->title)
@section('description', $seo->description)
@section('keywords', $seo->keywords)

@section('content')
    <div class="page_PaymentCards">
        <div class="container">
            <div class="breadcrumbs top-15">
                <a href="/">Главная</a> >
                {!! $seo->title !!}
            </div>
        </div>
        <div class="container">
            <div class="row bottom-30">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-block arial font-s15">
                            <h1>{!! $seo->title !!}</h1>
                            <p><strong>Оплата банковскими картами осуществляется после проверки заказа менеджером интернет-магазина.</strong></p>
                            <p>Для выбора оплаты товара с помощью банковской карты на соответствующей странице сайта необходимо нажать кнопку «Оплата банковской картой».</p>
                            <p>Оплата происходит через авторизационный сервер Процессингового центра Банка с использованием Банковских кредитных карт платежных систем VISA International и  MasterCard World Wide</p>

                            <div class="row ptop-30 bottom-30">
                                <div class="col-sm-6">
                                    <img height="60px" src="{{ $cdn }}/cards_payment/visa.png" alt="visa">
                                    <h3>Оплата картами VISA</h3>
                                    <p>К оплате принимаются все виды платежных карточек VISA, за исключением Visa Electron. В большинстве случаев карта Visa Electron не применима для оплаты через интернет.</p>
                                </div>
                                <div class="col-sm-6">
                                    <img height="60px" src="{{ $cdn }}/cards_payment/mastercard.png" alt="mastercard">
                                    <h3>Оплата картами MasterCard</h3>
                                    <p>На сайте к оплате принимаются все виды MasterCard.</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card top-30">
                        <div class="card-block arial font-s15">
                            <h2>Что нужно знать:</h2>
                            <ol>
                                <li>номер вашей кредитной карты; </li>
                                <li>cрок окончания действия вашей кредитной карты, месяц/год; </li>
                                <li>CVV код для карт Visa / CVC код для Master Card: 3 последние цифры на полосе для подписи на обороте карты.</li>
                            </ol>

                            <div class="text-center ptop-15 pbottom-15">
                                <img height="200px" src="{{ $cdn }}/cards_payment/cvv.jpg" alt="CVV-код">
                            </div>

                            <p>Если на вашей карте код CVC / CVV отсутствует, то, возможно, карта не пригодна для CNP транзакций (т.е. таких транзакций, при которых сама карта не присутствует, а используются её реквизиты), и вам следует обратиться в банк для получения подробной информации.</p>
                        </div>
                    </div>


                    <div class="card top-30">
                        <div class="card-block arial font-s15">
                            <h2>Описание процесса передачи данных</h2>
                            <p>Для оплаты покупки Вы будете перенаправлены на платежный шлюз ПАО "Сбербанк России" для ввода реквизитов Вашей карты. Пожалуйста, приготовьте Вашу пластиковую карту заранее. Соединение с платежным шлюзом и передача информации осуществляется в защищенном режиме с использованием протокола шифрования SSL.</p>
                            <p>В случае если Ваш банк поддерживает технологию безопасного проведения интернет-платежей Verified By Visa или MasterCard Secure Code для проведения платежа также может потребоваться ввод специального пароля. Способы и возможность получения паролей для совершения интернет-платежей Вы можете уточнить в банке, выпустившем карту.</p>
                            <p>Настоящий сайт поддерживает 256-битное шифрование. Конфиденциальность сообщаемой персональной информации обеспечивается ПАО "Сбербанк России". Введенная информация не будет предоставлена третьим лицам за исключением случаев, предусмотренных законодательством РФ. Проведение платежей по банковским картам осуществляется в строгом соответствии с требованиями платежных систем Visa Int. и MasterCard Europe Sprl.</p>
                        </div>
                    </div>


                    <div class="card top-30">
                        <div class="card-block arial font-s15">
                            <h2>Описание процессa оплаты</h2>
                            <p>При выборе формы оплаты с помощью пластиковой карты проведение платежа по заказу производится непосредственно после его оформления. После завершения оформления заказа в нашем магазине, Вы должны будете нажать на кнопку «Оплата банковской картой», при этом система переключит Вас на страницу авторизационного сервера, где Вам будет предложено ввести данные пластиковой карты, инициировать ее авторизацию, после чего вернуться в наш магазин кнопкой "Вернуться в магазин". После того, как Вы возвращаетесь в наш магазин, система уведомит Вас о результатах авторизации. В случае подтверждения авторизации Ваш заказ будет автоматически выполняться в соответствии с заданными Вами условиями. В случае отказа в авторизации карты Вы сможете повторить процедуру оплаты.</p>
                            <p>При аннулировании позиций из оплаченного заказа (или при аннулировании заказа целиком) Вы можете заказать другой товар на эту сумму, либо вернуть всю сумму на карту предварительно написав письмо на e-mail.</p>
                        </div>
                    </div>



                    <div class="card top-30">
                        <div class="card-block arial font-s15">
                            <h2>Оплата банковскими картами Сбербанка</h2>
                            <p>По кнопке "Перейти на сайт платежной системы СБЕРБАНК" Вы будете перенаправлены на платежный шлюз ПАО "Сбербанк России", где Вы сможете указать реквизиты Вашей банковской карты*. Соединение с платежным шлюзом и передача параметров Вашей пластиковой карты осуществляется в защищенном режиме с использованием 128-битного протокола шифрования SSL.</p>
                            <p>Если Банк-Эмитент вашей пластиковой карты поддерживает технологию безопасного проведения интернет-платежей Verified By VISA или MasterCard SecureCode, будьте готовы указать специальный пароль, необходимый для успешной оплаты. Способы и возможность получения пароля для совершения интернет-платежа Вы можете уточнить в банке, выпустившем Вашу карту.</p>
                            <p>При выборе формы оплаты с помощью банковской карты проведение платежа по заказу производится непосредственно после подтверждения его менеджером. После подтверждения заказа менеджером, Вы должны будете зайти в личный кабинет вашего заказа (по 20-значному уникальному номеру заказа, полученному при оформлении) и нажать на кнопку «Оплата банковской картой», при этом система переведёт Вас на страницу авторизационного сервера Сбербанка, где Вам будет предложено ввести данные пластиковой карты, инициировать ее авторизацию, после чего вы сможете вернуться в наш магазин кликом по кнопке "Вернуться в магазин". После возвращения в наш магазин, система уведомит Вас о результатах авторизации.</p>
                            <p>До получения успешного подтверждения платежа Ваш заказ будет находиться в режиме ожидания, после пяти дней ожидания получения оплаты заказ будет автоматически аннулирован. После успешного подтверждения платежа Ваш заказ будет переведен в режим доставки по указанному адресу. В случае отказа в авторизации карты Вы сможете повторить процедуру оплаты.</p>
                            <p>На оформление платежа Сбербанком выделяется 20 минут, поэтому, пожалуйста, приготовьте Вашу пластиковую карту заранее. Если Вам не хватит выделенного на оплату времени или в случае отказа в авторизации карты Вы сможете повторить процедуру оплаты.</p>
                            <p class="text-muted">*Конфиденциальность сообщаемой персональной информации обеспечивается ПАО "Сбербанк России". Введенная информация не будет предоставлена третьим лицам за исключением случаев, предусмотренных законодательством РФ. Проведение платежей по банковским картам осуществляется в строгом соответствии с требованиями платежных системVisa Int. и MasterCard Europe Sprl.</p>
                        </div>
                    </div>


                    <div class="card top-30">
                        <div class="card-block arial font-s15">
                            <h2>Доставка и выдача заказа, оплаченного пластиковой картой.</h2>
                            <p>Частные покупатели для получения товара должны предъявить паспорт владельца пластиковой карты, по которой производилась оплата заказа.</p>
                            <p class="bottom-0">Представитель юридического лица должен иметь доверенность с печатью от компании-плательщика или саму печать.</p>
                        </div>
                    </div>


                    <div class="card top-30">
                        <div class="card-block arial font-s15">
                            <h2>Отмена заказа</h2>
                            <p class="bottom-0">При удалении товаров из оплаченного заказа или при аннулировании заказа целиком Вы можете заказать другой товар на такую же сумму, либо полностью вернуть всю сумму на карту с помощью Вашего менеджера.</p>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
@stop