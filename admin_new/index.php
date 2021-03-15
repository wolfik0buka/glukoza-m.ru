<?php namespace App;

$page = 'nom';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if ($page !== "collections"
    && $page !== "orders"
    && $page !== "order"
    && $page !== "reports"
    && $page !== "responses"
    && $page !== "response"
    && !($page==='nom' && isset($_GET['id']) )
) {
    include '../php/mysqlconnect_new.php';
    include $page . '/editor.php';
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Панель управления сайтом glukoza-med.ru</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <script src="https://yastatic.net/jquery/2.1.4/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/admin_new/assets/jquery-ui-1.11.4.custom/jquery-ui.css">
    <link rel="stylesheet" href="/admin_new/redactor/redactor.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
    <link href="/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/js/vendor/magnific-popup_1.0.0.min.css"/>
    <link rel="stylesheet" href="/js/admin/css/chunk-vendors.css">
    <link rel="stylesheet" href="/js/admin/css/app.css">

    <link href="https://cdn.glukoza-med.ru/fonts/roboto/roboto.css" rel="stylesheet">

    <script>
        (function(e,t){typeof module!="undefined"&&module.exports?module.exports=t():typeof define=="function"&&define.amd?define(t):this[e]=t()})("$script",function(){function p(e,t){for(var n=0,i=e.length;n<i;++n)if(!t(e[n]))return r;return 1}function d(e,t){p(e,function(e){return t(e),1})}function v(e,t,n){function g(e){return e.call?e():u[e]}function y(){if(!--h){u[o]=1,s&&s();for(var e in f)p(e.split("|"),g)&&!d(f[e],g)&&(f[e]=[])}}e=e[i]?e:[e];var r=t&&t.call,s=r?t:n,o=r?e.join(""):t,h=e.length;return setTimeout(function(){d(e,function t(e,n){if(e===null)return y();!n&&!/^https?:\/\//.test(e)&&c&&(e=e.indexOf(".js")===-1?c+e+".js":c+e);if(l[e])return o&&(a[o]=1),l[e]==2?y():setTimeout(function(){t(e,!0)},0);l[e]=1,o&&(a[o]=1),m(e,y)})},0),v}function m(n,r){var i=e.createElement("script"),u;i.onload=i.onerror=i[o]=function(){if(i[s]&&!/^c|loade/.test(i[s])||u)return;i.onload=i[o]=null,u=1,l[n]=2,r()},i.async=1,i.src=h?n+(n.indexOf("?")===-1?"?":"&")+h:n,t.insertBefore(i,t.lastChild)}var e=document,t=e.getElementsByTagName("head")[0],n="string",r=!1,i="push",s="readyState",o="onreadystatechange",u={},a={},f={},l={},c,h;return v.get=m,v.order=function(e,t,n){(function r(i){i=e.shift(),e.length?v(i,r):v(i,t,n)})()},v.path=function(e){c=e},v.urlArgs=function(e){h=e},v.ready=function(e,t,n){e=e[i]?e:[e];var r=[];return!d(e,function(e){u[e]||r[i](e)})&&p(e,function(e){return u[e]})?t():!function(e){f[e]=f[e]||[],f[e][i](t),n&&n(r)}(e.join("|")),v},v.done=function(e){v([null],e)},v})
    </script>

    <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
    <script src="/admin_new/assets/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
    <script src="/admin_new/redactor/redactor.min.js"></script>
    <script src="/admin_new/js/jquery.dataTables.min.js"></script>
    <script src="/js/datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="/js/vendor/magnific-popup_1.0.0.min.js"></script>

    <script src="js/init.js?v=7"></script>
    <script src="js/java.js?v=12"></script>
    <?=
    ($page != 'collections'
        && $page != 'orders'
        && $page != 'reports'
        && $page != 'order'
        && $page != 'responses'
        && $page != 'response')
        ? '<script src="/admin_new/' . $page . '/java.js?ver=304"></script>'
        : '';?>

    <?= ($page === 'order') ? '<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>' : '' ?>
</head>
<body>
<div id="container">
    <div class="fullWidth">
        <div id="header">
            <h1>Панель управления сайтом glukoza-med.ru</h1>
        </div>
    </div>
    <div>
        <div id="wrapper">
                <div id="content">
                    <?php

                    if ($page == 'collections') {
                        echo '<div id="app"><collections-list></collections-list></div>';
                    } elseif ($page == 'orders') {
                        echo '<div id="app"><orders-page></orders-page></div>';
                    } elseif ($page == 'reports') {
                        echo '<div id="app"><reports></reports></div>';
                    } elseif ($page == 'order') {
                        echo '<div id="app"><order :order_id="'.$_GET['id'].'"></order></div>';
                    } elseif ($page == 'nom' && isset($_GET['id'])) {
                        echo '<div id="app"><product :product_id="'.$_GET['id'].'"></product></div>';
                    } elseif ($page == 'nom' && !isset($_GET['id'])) {
                        echo '<div id="app"><products></products></div>';
                    } elseif ($page == 'responses' && !isset($_GET['responses'])) {
                        echo '<div id="app"><responces></responces></div>';
                    } elseif ($page == 'response' && isset($_GET['id'])) {
                        echo '<div id="app"><response :response_id="'.$_GET['id'].'"></response></div>';
                    } else {
                        $x = '\\App\\'.$page;
                        $panel = new $x();
                        $panel->get_html();
                        echo $panel->html;
                        echo '<div id="app"></div>';
                    }

                    ?>
                </div>

        </div>
        <div id="navigation" class="font-roboto">
            <a class="itemnav" href="/admin_new/index.php?page=nom"><span>Товары</span></a>
            <a class="itemnav" href="/admin_new/index.php?page=orders"><span>Заказы</span></a>
            <a class="itemnav" href="/admin_new/index.php?page=users"><span>Клиенты</span></a>
            <a class="itemnav" href="/admin_new/index.php?page=statica"><span>Страницы</span></a>
            <a class="itemnav" href="/admin_new/index.php?page=responses"><span>Отзывы</span></a>
            <a class="itemnav" href="/admin_new/index.php?page=arc"><span>Архив</span></a>
            <a class="itemnav" href="/admin_new/index.php?page=obmen"><span>Выгрузка 1С</span></a>
            <a class="itemnav" href="/admin_new/index.php?page=podzakaz"><span>Под заказ</span></a>
            <a class="itemnav" href="/admin_new/index.php?page=collections"><span>Подборки</span></a>
            <a class="itemnav" href="/admin_new/index.php?page=reports"><span>Отчеты</span></a>
        </div>
        <div id="extra"></div>
    </div>
</div>
<div id="popup"></div>
<div id="dialog_bg"></div>
<input type="hidden" id="currentPage" value="<?php
if (isset($panel->current_page)) {
    echo $panel->current_page;
} ?>">
<?php
if (isset($_GET['str'])) echo '<input type="hidden" id="str" value="' . $_GET['str'] . '">';
if (isset($_GET['sklad'])) echo '<input type="hidden" id="sklad" value="1">'; else echo '<input type="hidden" id="sklad" value="0">';
?>
<iframe id="trash" name="trash" style="width: 0; height: 0; border: 0; display: none"></iframe>
<!-- <script src="/js/admin.js?ver=299"></script> -->
<script src=/js/admin/js/chunk-vendors.js></script>
<script src=/js/admin/js/app.js></script>


</body>
</html>