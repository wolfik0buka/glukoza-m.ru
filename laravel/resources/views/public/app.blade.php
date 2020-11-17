<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name=viewport content="width=device-width, initial-scale=1">
        <meta name="yandex-verification" content="1c0682a1978c2dbf"/>

        <title>@yield('title')</title>
        <meta name="description" content="@yield('description')"/>
        <meta name="keywords" content="@yield('keywords')"/>

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        
        @if(isset($canonical) && $canonical)
            <link rel="canonical" href="https://glukoza-med.ru{!! $canonical !!}"/>@endif
        @yield('opengraph')

        <script>(function(e, t) {
                typeof module != "undefined" && module.exports ? module.exports = t() : typeof define == "function" && define.amd ? define(
                    t) : this[e] = t();
            })("$script", function() {
                function p(e, t) {
                    for (var n = 0, i = e.length; n < i; ++n) if (!t(e[n])) return r;
                    return 1;
                }

                function d(e, t) {
                    p(e, function(e) {
                        return t(e), 1;
                    });
                }

                function v(e, t, n) {
                    function g(e) {
                        return e.call ? e() : u[e];
                    }

                    function y() {
                        if (!--h) {
                            u[o] = 1, s && s();
                            for (var e in f) p(e.split("|"), g) && !d(f[e], g) && (f[e] = []);
                        }
                    }

                    e = e[i] ? e : [e];
                    var r = t && t.call, s = r ? t : n, o = r ? e.join("") : t, h = e.length;
                    return setTimeout(function() {
                        d(e, function t(e, n) {
                            if (e === null) return y();
                            !n && !/^https?:\/\//.test(e) && c && (e = e.indexOf(".js") === -1 ? c + e + ".js" : c + e);
                            if (l[e]) return o && (a[o] = 1), l[e] == 2 ? y() : setTimeout(function() {
                                t(e, !0);
                            }, 0);
                            l[e] = 1, o && (a[o] = 1), m(e, y);
                        });
                    }, 0), v;
                }

                function m(n, r) {
                    var i = e.createElement("script"), u;
                    i.onload = i.onerror = i[o] = function() {
                        if (i[s] && !/^c|loade/.test(i[s]) || u) return;
                        i.onload = i[o] = null, u = 1, l[n] = 2, r();
                    }, i.async = 1, i.src = h ? n + (n.indexOf("?") === -1 ? "?" : "&") + h : n, t.insertBefore(i, t.lastChild);
                }

                var e = document, t = e.getElementsByTagName("head")[0], n = "string", r = !1, i = "push", s = "readyState",
                    o = "onreadystatechange", u = {}, a = {}, f = {}, l = {}, c, h;
                return v.get = m, v.order = function(e, t, n) {
                    (function r(i) {
                        i = e.shift(), e.length ? v(i, r) : v(i, t, n);
                    })();
                }, v.path = function(e) {
                    c = e;
                }, v.urlArgs = function(e) {
                    h = e;
                }, v.ready = function(e, t, n) {
                    e = e[i] ? e : [e];
                    var r = [];
                    return !d(e, function(e) {
                        u[e] || r[i](e);
                    }) && p(e, function(e) {
                        return u[e];
                    }) ? t() : !function(e) {
                        f[e] = f[e] || [], f[e][i](t), n && n(r);
                    }(e.join("|")), v;
                }, v.done = function(e) {
                    v([null], e);
                }, v;
            });
        </script>


        {{--Google Tag Manager--}}
        <script>(function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({"gtm.start": new Date().getTime(), event: "gtm.js"});
                var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != "dataLayer" ? "&l=" + l : "";
                j.async = true;
                j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, "script", "dataLayer", "GTM-WNGSK62");</script>

        {{--Universal Analytics--}}
        <script>
            (function(i, s, o, g, r, a, m) {
                i["GoogleAnalyticsObject"] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments);
                }, i[r].l = 1 * new Date();
                a = s.createElement(o), m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m);
            })(window, document, "script", "https://www.google-analytics.com/analytics.js", "ga");
            ga("create", "UA-38466301-1", "auto");
            ga("send", "pageview");
        </script>

        {{--CSS--}}
        <link rel="stylesheet" href="/js/public/css/chunk-vendors.css">
        <link rel="stylesheet" href="/css/datepicker.min.css">
        <link rel="stylesheet" href="/js/vendor/magnific-popup_1.0.0.min.css">
        <link rel="stylesheet" href="/js/public/css/app.css">
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Ubuntu:400,400i,500,700&amp;subset=cyrillic">

        {{--JS--}}
        <script src="https://yastatic.net/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="/js/datepicker.min.js"></script>
        <script>$.ajaxSetup({headers: {"X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content")}});</script>

        @if(Request::is('checkout') || Request::is('kontakty') || (Request::has('alias') && Request::get('alias')=='contacts'))
            <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
            <script src="/js/ymaps_arrows.js"></script>
        @endif

        @yield('java_box')
    </head>

    <body>
        <noscript>
            <iframe
                src="https://www.googletagmanager.com/ns.html?id=GTM-WNGSK62" height="0" width="0"
                style="display:none;visibility:hidden"></iframe>
        </noscript>

        <div id="app">
            @if (getSettings('noticeWorkTime__active'))
                <?php
                $data = [
                    'isOneStringMode' => getSettings('noticeWorkTime__isOneStringMode'),
                    'title' => getSettings('noticeWorkTime__title'),
                    'content' => getSettings('noticeWorkTime__content'),
                ];
                ?>
                <notice-worktime :data='{{ json_encode($data) }}'></notice-worktime>
            @endif

            <div class="header">

                @include('public._partials.headerTopLine')

                <div class="container">

                    <div class="header__wrapper">

                        <div class="logo">
                            <a href="/">
                                <svg viewBox="0 0 140 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g fill="#2f86d6">
                                        <path
                                            d="M61.0922 11.1053C59.7491 11.1053 58.6863 11.568 57.8893 12.5079C57.0922 13.4478 56.6937 14.6335 56.6937 16.0795C56.6937 17.5255 57.0922 18.7113 57.904 19.6367C58.7011 20.5621 59.7638 21.0249 61.0922 21.0249C62.4649 21.0249 63.5424 20.5621 64.3247 19.6367C65.1218 18.7113 65.5203 17.5255 65.5203 16.0795C65.5203 14.6335 65.1218 13.4478 64.3247 12.5079C63.5424 11.5825 62.4649 11.1053 61.0922 11.1053ZM77.3136 8.32897V0H16.4133C11.8819 0 7.7786 1.76412 4.81181 4.67059C1.84502 7.57705 0 11.5535 0 15.9928C0 24.8712 7.35056 32 16.3985 32H77.2841V23.8735H73.3579V8.32897H77.3136ZM23.6605 11.4234H16.4871V23.859H12.5166V8.32897H23.6458V11.4234H23.6605ZM41.2694 23.859H37.2989V11.4234H32.0738V14.7203C32.0738 17.916 31.6015 20.2729 30.6568 21.7768C29.7122 23.2806 28.2214 24.047 26.1993 24.047C25.1365 24.047 24.3542 23.9602 23.8229 23.7867L24.1181 20.62C24.4428 20.7212 24.797 20.779 25.1808 20.779C26.1993 20.779 26.9668 20.2729 27.4981 19.2607C28.0295 18.2485 28.2952 16.441 28.2952 13.8527V8.32897H41.2841V23.859H41.2694ZM67.1439 22.0515C65.535 23.5843 63.5277 24.3507 61.0922 24.3507C58.9373 24.3507 57.0922 23.7 55.5572 22.413C54.0221 21.1116 53.1218 19.4198 52.8266 17.2942H49.9926V23.859H46.0221V8.32897H49.9926V14.2865H52.9004C53.2989 12.3633 54.2435 10.8161 55.7491 9.64483C57.2398 8.47357 59.0258 7.8807 61.107 7.8807C63.5277 7.8807 65.5498 8.64708 67.1587 10.1798C68.7675 11.7126 69.5646 13.6936 69.5646 16.1084C69.5498 18.5233 68.7528 20.5188 67.1439 22.0515Z"></path>
                                        <path
                                            d="M89.5499 16.0217C89.5499 13.6213 90.3764 11.6692 92.0296 10.1509C93.6827 8.63264 95.7344 7.86626 98.1698 7.86626C100.62 7.86626 102.657 8.63264 104.31 10.1509C105.963 11.6692 106.79 13.6358 106.79 16.0217C106.79 18.4365 105.963 20.4176 104.295 21.9792C102.627 23.5265 100.59 24.3073 98.1698 24.3073C95.7491 24.3073 93.6975 23.5265 92.0443 21.9792C90.3764 20.432 89.5499 18.4365 89.5499 16.0217ZM94.7602 12.5368C93.9631 13.5346 93.5646 14.6914 93.5646 16.0362C93.5646 17.3809 93.9631 18.5522 94.7602 19.5644C95.5572 20.5766 96.6938 21.0827 98.1698 21.0827C99.6458 21.0827 100.782 20.5766 101.579 19.5644C102.376 18.5522 102.775 17.3809 102.775 16.0362C102.775 14.6914 102.376 13.5346 101.579 12.5368C100.782 11.5391 99.6458 11.0475 98.1698 11.0475C96.6938 11.0475 95.5572 11.5391 94.7602 12.5368Z"></path>
                                        <path
                                            d="M115.675 24.3796C112.709 24.3796 110.391 23.4397 108.738 21.5599L111.114 19.2897C111.572 19.897 112.192 20.3886 112.945 20.779C113.697 21.1695 114.509 21.3574 115.38 21.3574C117.476 21.3574 118.524 20.6923 118.524 19.3764C118.524 18.5811 118.199 18.0316 117.55 17.728C116.9 17.4243 116 17.2797 114.864 17.2797H113.609V14.4889H114.864C117.048 14.4889 118.14 13.8382 118.14 12.5079C118.14 11.3656 117.255 10.7872 115.469 10.7872C113.86 10.7872 112.59 11.38 111.675 12.5658L109.461 10.4546C110.864 8.70494 113.033 7.83734 115.941 7.83734C117.816 7.83734 119.321 8.2133 120.458 8.96522C121.594 9.71714 122.155 10.7583 122.155 12.0741C122.155 12.9562 121.86 13.737 121.269 14.3877C120.679 15.0529 119.911 15.4722 118.996 15.6602V15.718C120.074 15.906 120.945 16.3398 121.609 17.0194C122.273 17.6846 122.598 18.5233 122.598 19.5066C122.598 21.0104 121.948 22.1961 120.635 23.0493C119.277 23.9458 117.638 24.3796 115.675 24.3796Z"></path>
                                        <path
                                            d="M136.325 23.859V21.878H136.221C135.823 22.5721 135.188 23.136 134.288 23.5843C133.387 24.0325 132.399 24.2494 131.292 24.2494C129.771 24.2494 128.399 23.859 127.173 23.0637C125.948 22.2829 125.343 21.0682 125.343 19.4198C125.343 18.5377 125.565 17.7569 126.022 17.1062C126.48 16.4555 127.041 15.9349 127.72 15.5734C128.399 15.2119 129.255 14.9083 130.273 14.6769C131.292 14.4456 132.266 14.301 133.181 14.2431C134.096 14.1853 135.114 14.1419 136.236 14.1419V13.7515C136.236 12.8405 135.897 12.1464 135.218 11.6548C134.539 11.1631 133.668 10.9173 132.605 10.9173C130.937 10.9173 129.491 11.5102 128.28 12.6959L126.229 10.3389C128.03 8.70494 130.273 7.88072 132.93 7.88072C134.303 7.88072 135.454 8.0687 136.413 8.44466C137.373 8.82062 138.111 9.34118 138.613 9.99188C139.114 10.657 139.483 11.3367 139.69 12.0597C139.897 12.7827 140 13.5635 140 14.4022V23.859H136.325ZM136.266 17.3231V16.7736H135.38C131.351 16.7736 129.328 17.6412 129.328 19.362C129.328 20.056 129.594 20.5477 130.14 20.8513C130.686 21.155 131.336 21.2996 132.103 21.2996C133.447 21.2996 134.48 20.9526 135.188 20.244C135.897 19.55 136.266 18.5667 136.266 17.3231Z"></path>
                                        <path
                                            d="M77.2694 15.4144L77.4465 15.2264L83.5572 8.32898H88.5757L81.6089 15.6457L89.107 23.8735H84.0148L77.4465 16.4989L77.2694 16.2964"></path>
                                    </g>
                                </svg>
                            </a>
                            <div class="logo__slogan">Медицинская техника и&nbsp;товары для&nbsp;контроля&nbsp;диабета</div>
                        </div>

                        <div class="headerSocial hidden-xs hidden-sm">
                            <div class="headerSocial__link" onclick="App.openInNewTab('https://vk.com/glukozamed')">
                                <i class="fa fa-vk"></i>
                            </div>
							<div class="headerSocial__link" onclick="App.openInNewTab('https://instagram.com/glukoza_med?igshid=1ptlogabzzkbp')">
                                <i class="fa fa-instagram"></i>
                            </div>
                        </div>

                        <div class="headerContacts">
                            <div class="headerContacts__phone">
                                <a
                                    type="tel"
                                    href="tel:{{ str_replace([" ", "-", "(", ")"], "", getSettings('phone')) }}">
                                    {{ getSettings('phone') }}
                                </a>
                            </div>
                            <div class="headerContacts__worktime">
                                {{ getSettings('headerWorkTime') }}
                            </div>
                            <div class="headerContacts__contactLink">
                                <a href="/kontakty#map2">Санкт-Петербург</a>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="headerNavContainer">
                    <header-nav></header-nav>
                </div>

            </div>

            {{--<div class="bg-white text-center hidden-xs hidden-sm hidden-md ptop-15 pbottom-15 sale__horizontal">--}}
            {{--<img width="973px" src="{{ $cdn }}/sales/ny_2018_discountx2.jpg" alt="Скидка в 2 раза больше">--}}
            {{--</div>--}}

            @yield('content')

        </div>

        <div class="footer">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="footer__copyright">
                            <p class="arial font-s16 font-w600 text-white">2012-{{ date('Y') }} © Глюкоза</p>
                            <p class="arial font-s16 font-w600 text-white">Медтехника и товары для контроля диабета</p>
                            <p class="text-white">Магазин медицинской техники в Санкт-Петербурге, доставка по всей России.</p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="footer__title">Покупателям</div>
                        <div class="footer__links">
                            <a href="/beznal">Для юридических лиц</a>
                            <a href="/dostavka">Доставка и самовывоз</a>
                            <a href="/oplata">Способы оплаты</a>
                            <a href="/kontakty">Контакты и адреса магазинов</a>
                            <a href="/vozvrat">Условия возврата</a>
                            <a href="/card-payment">Оплата банковскими картами</a>
                        </div>
                        <div class="footer__title">Сервисы</div>
                        <div class="footer__links">
                            <a href="/cabinet/auth_form">Личный кабинет</a>
                            <a href="/track-orders">Отслеживание заказов</a>
                            <a href="/bonusnaya-programma">Бонусная программа</a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="footer__title">Информация</div>
                        <div class="footer__links">
                            <a href="/requisites">Реквизиты</a>
                            <a href="/oferta">Публичная оферта</a>
                            <a href="/politika-konfidencialnosti">Политика конфиденциальности</a>
                            <a class="font-lh110 top-3" href="/soglasie-na-obrabotku-pd">Пользовательское соглашение</a>
                        </div>
                    </div>
                </div>

                <div class="footer__cards text-center ptop-15">
                    <img src="{{ $cdn }}/cards_payment/footer-payment-types-bw.png" alt="Способы оплаты">
                </div>

                <div class="top-15 font-s13">
                    <p>Обращаем ваше внимание на то, что данный интернет-сайт носит исключительно информационный характер и ни при каких условиях не является публичной офертой, определяемой положениями Статьи 437 Гражданского кодекса РФ.</p>
                    
                </div>
            </div>

        </div>
        <div id="popUp"></div>
        <div id="errorUp"></div>
        <div id="dialog_bg"></div>
        <div class="alerting"></div>




        {{-- Yandex.Metrika counter --}}
        <script type="text/javascript"> (function(d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter23274694 = new Ya.Metrika2({
                            id: 23274694,
                            clickmap: true,
                            trackLinks: true,
                            accurateTrackBounce: true,
                            webvisor: true,
                            ecommerce: "dataLayer"
                        });
                    } catch (e) {
                    }
                });
                var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function() {
                    n.parentNode.insertBefore(s, n);
                };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/tag.js";
                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(document, window, "yandex_metrika_callbacks2"); </script>
        <noscript>
            <div><img src="https://mc.yandex.ru/watch/23274694" style="position:absolute; left:-9999px;" alt=""/></div>
        </noscript>
        {{-- /Yandex.Metrika counter --}}

        <script>
            window.LaravelToken = "{{ csrf_token() }}";
            window.state = {
                isMobile: window.matchMedia('all and (max-width: 767px)').matches
            };
            state.catalog = JSON.parse('{!! json_encode($cats) !!}');
            state.catUrl = '{!! $cat_url !!}';
            state.catLevelOneActive = false;
            state.isExpandMenuOpened = false;
            state.user_id = {!! session()->has('user') ? session()->get('user.id') : 'false' !!};
            window.initialOrder = {!! session()->has('order') ? json_encode(session()->get('order')) : 'false' !!};
            window.user_id = {!! session()->has('user') ? session()->get('user.id') : 'false' !!};
        </script>

        <script src="/js/vendor/magnific-popup_1.0.0.min.js"></script>
        <script src="/js/public/js/chunk-vendors.js"></script>
        <script src="/js/public/js/app.js"></script>
    </body>
</html>