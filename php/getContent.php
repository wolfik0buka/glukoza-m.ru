<?php namespace App;
require_once 'Controller.php';
require_once 'query.php';
function ucfirst_utf8($stri)
{
    if ($stri{0} >= "\xc3")
        return (($stri{1} >= "\xa0") ?
            ($stri{0} . chr(ord($stri{1}) - 32)) :
            ($stri{0} . $stri{1})) . substr($stri, 2);
    else return ucfirst($stri);
}

class product
{
    function __construct()
    {
    }

    function getHtml()
    {
        global $mysqli;
        $dicQ = new query();
        $query = $dicQ->product($_GET['idTov'], $_GET['cat']);
        $res = $mysqli->query($query);
        $disabled = ' ';
        $old = '';
        $circle = 'green';
        $cnt = 1;
        $comments = '';
        while ($row = $res->fetch_assoc()) {
            if ($cnt == 1) {
                $name = $row["name"];
                $description_full = $row["desc_full"];
                $description = $row["description"];
                if ($row["pres"] == 0) {
                    $disabled = ' disabled ';
                    $circle = 'red';
                }
                if ($row["podzakaz"] == 1) {
                    $disabled = ' ';
                    $circle = 'green';
                }
                $imgSrc = "img/catalog/pic_" . $_GET["idTov"] . ".jpg";
                if (!file_exists($imgSrc)) $imgSrc = "img/catalog/nophoto.jpg";
                $price = number_format($row["price_1c"], 2, "-", " ");
                $data_price = $row["price_1c"];
                if ($row["price_1c"] == 0) $price = "call";
//                if ($row["comment"] != '') $comments = '<td colspan="2" style="font-size: 12px; padding: 20px;"><div class="tit">Отзывы</div>';
                if ($row['price_old'] != 0) $old = '<div class="price_old"><span>' . number_format($row['price_old'], 0, '-', ' ') . '<span> руб</div>';
            }
//            if ($row["comment"] != '') $comments .= '<div style="font-size: 12px; font-weight: bold; margin-top: 5px;">'.date("d.m.Y", strtotime($row['date_comment'])).' - '.$row["comment_name"].'</div>
//                                                     <div style="font-size: 12px; margin-bottom: 5px;">'.$row['comment'].'</div>';
            $cnt++;
        }

        if ($circle == "green") {
            $instock = "В наличии";
            $to_cart = '<button class="addtocart" onClick="addToBasket(' . $_GET["idTov"] . ',' . $data_price . ')">В корзину</button>';
        } elseif ($circle == "red") {
            $instock = "Временно отсутствует";
            $to_cart = '';

        }
        $html = '<div class="tovaritem">
                    <h1 id="tovarName' . $_GET['idTov'] . '">' . $name . '</h1>
                    <div class="instock" style="background-color: ' . $circle . '">' . $instock . '</div>
                    <table class="singleProduct">
                        <tr id="row1">
                            <td class="tdImg"><img src="' . $imgSrc . '" alt="' . str_replace('\"', '', $name) . '"></td>
                            <td class="tdDesc">
                                <div class="art">Артикул: ' . str_repeat('0', 5 - strlen($_GET["idTov"])) . $_GET["idTov"] . '</div>
                                <div class="clear"></div>
                                <div class="price">Цена: <span>' . $price . '</span> руб</div>' . $old . '
                                ' . $to_cart . '
                                <div class="desc">' . $description . $description_full . '</div>
                                <div style="width: 100%; clear: both;"></div>
                                <div class="clear10"></div>
                                <button class="uni-button" onClick="addCommentForm(' . $_GET["idTov"] . ')">Оставить отзыв</button>
                            </td>
                        </tr>
                        <tr>' . $comments . '</tr>
                    </table>
                 </div>';
        return $html;
    }
}

class content extends Controller
{
    var $massCat;

    function getChild($idCat)
    {
        global $mysqli;
        $html = '';
        $dicQ = new query();
        $res = $mysqli->query($dicQ->getChild($idCat));
        while ($row = $res->fetch_object()) {
            $html .= $this->tovarInCat($row->id);
            $html .= $this->getChild($row->id);
        }
        return $html;
    }

    function getChildSearch($idCat)
    {
        global $mysqli;
        $html = '';
        $dicQ = new query();
        $res = $mysqli->query($dicQ->getChild($idCat));
        while ($row = $res->fetch_object()) {
            $html .= $this->tovarInCatSearch($row->id);
            $html .= $this->getChildSearch($row->id);
        }
        return $html;
    }

    function searchMyResult()
    {
        $result = '';
        $result .= $this->tovarInCatSearch(1);
        $result .= $this->getChildSearch(1);
        $html = '<div class="groupName">Результаты поиска по запросу: «' . $_GET['search_word'] . '»</div>
                  <div class="box static search_result_container">' . $result . '</div>';
        return $html;
    }

    function basket()
    {
        $count = 0;
        $buttonsUp = '';
        $buttonsDown = '';
        $userData = '';
//        if (isset($_SESSION["basket"])) {
            $count = count($_SESSION["basket"]);
            $dostavka = array();
            for ($i = 1; $i <= 5; $i++) {
                $dostavka[$i] = "";
                if ($i == $_SESSION["dostavka"]) $dostavka[$i] = "checked";
            }
            $buttonsUp = '<button class="uni-button" id="clearBasket" onClick="clearBasket()">Очистить корзину</button>';
            $buttonsDown = '<button id="basketToOrder">Оформить заказ</button>';
            $last_name = '';
            $first_name = '';
            $email = '';
            $phone = '';
            $pref = '';
            if (isset($_SESSION['user'])) {
                $mass_fio = explode(' ', $_SESSION['user']['name']);
                $last_name = $mass_fio[0];
                $first_name = $mass_fio[1];
                $email = $_SESSION['user']['email'];
                $mass_phone = explode(') ', $_SESSION['user']['phone']);
                $phone = $mass_phone[1];
                $mass_pref = explode(' (', $mass_phone[0]);
                $pref = $mass_pref[1];
            }

            $userData = '';

//            $userData = '<div class="clear10"></div>
//                         <div class="basketblocktitle">Контактная информация:</div>
//                         <div class="basketcontacttable">
//                             <table>
//                                <tr>
//                                    <td class="titPole">Фамилия:*</td>
//                                    <td class="valPole"><input type="text" id="fam" value="' . $last_name . '"></td>
//                                </tr>
//                                <tr>
//                                    <td class="titPole">Имя:*</td>
//                                    <td class="valPole"><input type="text" id="name" value="' . $first_name . '"></td>
//                                </tr>
//                                <tr>
//                                    <td class="titPole">Телефон:*</td>
//                                    <td class="valPole poleTel">+7&nbsp;(<input type="text" id="pref" onkeyup="testJump(this)" size="3" maxlength="3" value="' . $pref . '"/>)&nbsp;<input type="text" id="phone" maxlength="7" value="' . $phone . '"/></td>
//                                </tr>
//                                <tr>
//                                    <td class="titPole">E-mail:*</td>
//                                    <td class="valPole"><input type="text" id="email" value="' . $email . '"></td>
//                                </tr>
//                                <tr>
//                                    <td class="titPole">Комментарий к заказу:</td>
//                                    <td class="comment"><textarea id="comment"></textarea></td>
//                                </tr>';
//            if (!isset($_SESSION['user'])) {
//                $userData .= '<tr>
//                                  <td colspan="2">
//                                      <input onclick="hide_pers_data()" id="pers_data" name="pers_data" type="checkbox">&nbsp;Согласен на <a class="uni-link" onclick="pers_data()">обработку персональных данных</a>
//                                      <div class="line"><div class="error" id="er_pers_data">Подтвердите соглашение на обработку персональных данных</div></div>
//                                  </td>
//                              </tr>';
//            } else {
//                $userData .= '<tr style="display: none;">
//                                  <td colspan="2">
//                                      <input onclick="hide_pers_data()" id="pers_data" name="pers_data" type="checkbox" checked="checked">&nbsp;Согласен на <a class="uni-link" onclick="pers_data()">обработку персональных данных</a>
//                                      <div class="line"><div class="error" id="er_pers_data">Подтвердите соглашение на обработку персональных данных</div></div>
//                                  </td>
//                              </tr>';
//            }

//            $userData .= '</table>
//                         </div>
//                         <div class="attention">
//                         <p class="title">ВНИМАНИЕ!</p>
//                         <p>При самовывозе со склада или из магазина Ваш заказ действителен в течение 3-х рабочих дней.</p>
//                         <p>Если Вы (или Ваш представитель) не забрали заказ своевременно - он аннулируется.</p>
//                         </div>
//                         <div class="clear10"></div>';

            $userData .= '<div class="basketblocktitle">Способ получения:</div>
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Самовывоз</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Доставка</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="home">
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
              <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingOne">
                      <h4 class="panel-title">
                          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                             Из магазинов «Глюкоза»
                          </a>
                      </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                      <div class="panel-body">
                          <ul>
                              <li><input name="d" type="radio" checked> СПб - Сампсониевский</li>
                              <li><input name="d" type="radio"> СПб - Озерки</li>
                              <li><input name="d" type="radio"> Вологда</li>
                              <li><input name="d" type="radio"> Новгород</li>
                          </ul>
                      </div>
                  </div>
             </div>
             <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingTwo">
                      <h4 class="panel-title">
                          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              Из пункта выдачи в СПб
                          </a>
                      </h4>
                  </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
        <script type="text/javascript">
function callback(){
    $("#createButton").removeAttr("disabled");
}
proMap.loading(callback, {
    onShowPrice: function(val) {
        return val + \' Ваши данные (например доп сбор 150р.)\'
    }
});


$(proMap)
    .bind(\'selected\',function(e,info){
        console.log(info);
        alert("Выбрана точка:"
               +"\ninfo.city_id:       "+info.city_id
               +"\ninfo.id_obl:        "+info.id_obl
               +"\ninfo.city_name:     "+info.city_name
               +"\ninfo.point.id:      "+info.point.id
               +"\nindo.point.address: "+info.point.address
               +"\ninfo.point.name:    "+info.point.name
               +"\ninfo.point.phone:   "+info.point.phone
               +"\ninfo.point.time:    "+info.point.time
               +"\ninfo.point.work:    "+info.point.work
               +"\ninfo.point.weight:  "+info.point.weight
             )
          $("#test").html(info.point.name);
        }
    )
    .bind(\'canceled\',function(e){
        alert (\'Выбор отменен!\')
    })
proMap.show(0);
</script>
<input type="button" id="createButton1" onclick="proMap.show(0)" value="Выбрать пункт выдачи"/>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Из пункта выдачи в РФ
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">

<input type="button" id="createButton2" onclick="proMap.show(0)" value="Выбрать пункт выдачи"/>
      </div>
    </div>
  </div>
</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
<br>
                              <ul>
                              <li><input name="d" type="radio" checked> Курьер СПб</li>
                              <li><input name="d" type="radio"> Курьер РФ</li>
                              <li><input name="d" type="radio"> Почта РФ</li>
                          </ul>

</div>

  </div>

</div>';
//            $userData .= '<div class="basketdelivery">
//                         <table width="100%">
//                             <tr>
//                                 <td width="50%">
//                                     <input id="getTovar1" type="radio" name="getTovar" value="1" onChange="addDostavka(1)" ' . $dostavka[1] . '>Самовывоз из магазина Б. Сампсониевский пр. 62, помещение 202 пн-пт 9:30-18:00
//                                     <div class="clear10"></div>
//                                     <input id="getTovar5" type="radio" name="getTovar" value="5" onChange="addDostavka(5)" ' . $dostavka[5] . '>Самовывоз из магазина ул. Сикейроса, д.10/4 лит.А <br>ТК «Бульвар» помещение 4/2 <br/>пн-пт 11:00-19:00 (обед 15-16), сб 11:00-17:00 (без обеда)<br/>
//                                     <div class="clear10"></div>
//                                     <input id="getTovar3" type="radio" name="getTovar" value="3" onChange="addDostavka(3)" ' . $dostavka[3] . '>Доставка курьером по Санкт-Петербургу<br/>
//                                     <div class="clear10"></div>
//                                     <input id="getTovar2" type="radio" name="getTovar" value="2" onChange="addDostavka(2)" ' . $dostavka[2] . '>Доставка курьером по РФ<br/>
//                                     <div class="clear10"></div>
//                                     <input id="getTovar4" type="radio" name="getTovar" value="4" onChange="addDostavka(4)" ' . $dostavka[4] . '>Доставка почтой по РФ<br/>
//                                 </td>
//                                 <td id="dopFld"></td>
//                             </tr>
//                         </table>
//                         </div>';
//        }
        $html = "<div class=\"groupName\">Корзина: наименований <span id=\"qtBig\">(" . $count . ")</span></div>";
        if (!isset($_SESSION['user'])) $html .= "<div class=\"no_auth_txt\">Чтобы воспользоваться всеми преимуществами постоянного покупателя - <a href=\"/index.php?page=cabinet\">войдите</a> или <a href=\"/index.php?page=reg\">зарегистрируйтесь</a></div>";
        $html .= "<div class=\"box basket\">
                 <div id=\"basketBox\"></div>
                 " . $buttonsUp . $userData . $buttonsDown . "
                 <script type=\"text/javascript\">getBasket()</script>
                 <div class=\"supportphone\"></div>
                 </div>";
        return $html;
    }


    function search_list($idCat)
    {
        global $htmlSeo, $mysqli;
        $html = '';
        $dicQ = new query();
        $query = $dicQ->tovarInCat_search($idCat, $_GET['search_word']);
        $res = $mysqli->query($query);
        $groupName = '';
        while ($row = $res->fetch_assoc()) {
            $hitClass = '';
            $disabled = ' ';
            $circle = 'green';
            if ($row["podzakaz"] == 1) {
                $disabled = ' disabled ';
                $circle = 'red';
            }
            $imgSrc = 'img/catalog/pic_' . $row['id'] . '.jpg';
            if (!file_exists($imgSrc)) $imgSrc = 'img/catalog/nophoto.jpg';
            $old = '';
            $price = '<span>' . number_format($row['price_1c'], 2, '-', ' ') . '</span>';
            if ($row['sale'] == 1) $price = '<span style="color: red">' . number_format($row['price_1c'], 2, '-', ' ') . '</span>';
            if ($row['hit'] == 1) $hitClass = '<img style="position: absolute; top: 0; left: 0" src="img/hit.png">';
            if ($row['price_1c'] == 0) $price = 'call';
            if ($row['price_old'] != 0) {
                $old = '<div style="float: left;" class="price_old"><span>' . number_format($row['price_old'], 0, '-', ' ') . '<span> руб</div>';
            }
            $html .= '<div class="search_result_item">
                          <div class="pic"><img src="/img/resize/resize.php?src=/' . $imgSrc . '&h=80&q=100" alt="' . str_replace('"', '', $row['name']) . '"></div>
                          <a id="tovarName' . $row['id'] . '" class="productName" href="index.php?page=cat&amp;cat=' . $row['idCat'] . '&amp;idTov=' . $row['id'] . '">' . $row['name'] . '</a>
                          <div style="background-color: ' . $circle . '" class="circle"></div>
                          <div class="price" style="padding-top: 15px;">Цена: <span>' . $price . '</span> руб</div>
                      </div>';
        }
        return $html;
    }

    function tovarInCatSearch($idCat)
    {
        global $htmlSeo, $mysqli;
        $html = '';
//        if($_GET["search_word"] == ''){
//            $html = 'Пустой запрос';
//        }
//        else{
        $html .= $this->search_list($idCat);
//        }
        return $html;
    }


    function tovarInCat($idCat)
    {
        global $htmlSeo, $mysqli;
        $html = '';
        $dicQ = new query();
        $query = $dicQ->tovarInCat_new($idCat);
        $res = $mysqli->query($query);
        $groupName = '';
        while ($row = $res->fetch_object()) {
            $circle = 'green';
            if ($groupName != $row->cat) {
                $groupName = $row->cat;
                $html .= '<h1>' . $row->cat . '</h1>' . $htmlSeo['up_text'];
            }
            if (($row->podzakaz == 0) && ($row->pres == 0)) $circle = 'red';
            if ($row->sale == 1) $price = '<span style="color: red">' . number_format($row->price_1с, 2, '-', ' ') . '</span>';
            if ($row->hit == 1) $hitClass = '<img style="position: absolute; top: 0; left: 0" src="img/hit.png">';
            $data = [
                'circle' => $circle,
                'result' => $row
            ];
            $html .= $this->blade->render('public.tovarincat', $data);
        }
        return $html . $htmlSeo['down_text'];
    }

    function cat()
    {
        $way = showWay_new($_GET['cat']);
        $valCat = '';
        foreach ($way['massCat'] as $key => $value) {
            if ($valCat == '') $valCat .= $value; else $valCat .= ',' . $value;
        }
        $html = '<div class="way">' . $way['full'] . '</div>
                 <input type="hidden" id="currentCat" value="' . $valCat . '" />';
        if (!isset($_GET['idTov'])) {
            $html .= $this->tovarInCat($_GET['cat']);
            $html .= $this->getChild($_GET['cat']);
            $html .= $this->get_list_articles($_GET['cat']);
        } else {
            $product = new product();
            $html .= $product->getHtml();
        }
        return $html;
    }

    // список статей для раздела (внизу под товарами)
    function get_list_articles($id_cat)
    {
        global $mysqli;
        $check = '';
        $html = '';
        $list = '
            <div class="articles_list">
                <div class="name">Полезные публикации:</div>
            ';
        $query = "select * from articles
                  where id_cat = '" . $id_cat . "'";
        $res = $mysqli->query($query);
        while ($row = $res->fetch_assoc()) {
            $list .= '<a href="/index.php?page=article&id=' . $row['id'] . '">' . $row['name'] . '</a>';
            $check = $row['name'];

        }
        $list .= '</div>';
        if (strlen($check) > 2) {
            $html = $list;
        }
        return $html;
    }


    // страница статьи
    function article()
    {
        global $mysqli;
        $html = '';
        $query = "select * from articles where id = '" . $_GET['id'] . "'";
        $res = $mysqli->query($query);
        while ($row = $res->fetch_assoc()) {
            $html .= '
                <div class="articles__single">
                    <h1>' . $row['name'] . '</h1>
                    <div class="articles__single-content">' . $row['text'] . '</div>
                </div>
            ';
        }
        return $html;
    }

    function stat()
    {   // Статические
        global $alias, $mysqli;
        $html = "";
        $query = "select static_pages.title,
                         static_pages.content
                  from static_pages
                  where static_pages.alias = '" . $alias . "'";
        $res = $mysqli->query($query);
        while ($row = $res->fetch_assoc()) {
            $html .= '<div class="groupName">' . $row['title'] . '</div>
                      <div class="box static">' . $row['content'] . '</div>';
        }
        return $html;
    }

    function podbor()
    { // Подбор глюкометра
        global $dbh;
        $html = '<div class="groupName">Подбор глюкометров</div>
                 <div class="box static podbor">
                 <form id="podbor">';
        $query = 'select prop_type.name as question,
                         prop_type.id as type,
                         prop_value.id, 
                         prop_value.name,
                         prop_value.def
                  from prop_value
                  inner join prop_type on (prop_type.id = prop_value.type)
                  where (prop_value.del = 0)
                  order by prop_type.id,
                           prop_value.def desc,
                           prop_value.name';
        $type = '';
        $cnt = 1;
        $res = ibase_query($dbh, $query);
        while ($row = ibase_fetch_assoc($res)) {
            if ($type != $row['TYPE']) {
                if ($cnt != 1) $html .= '</div>';
                $html .= '<div class="question"><span>' . $row['QUESTION'] . '</span>
            	<div class="question-line"><input onClick="podobrat()" checked type="radio" name="prop_' . $row['TYPE'] . '" value="' . $row['ID'] . '">' . $row['NAME'] . '</div>';
                $type = $row['TYPE'];
            } else {
                $html .= '<div class="question-line"><input onClick="podobrat()" type="radio" id="prop_' . $row['TYPE'] . '" name="prop_' . $row['TYPE'] . '" value="' . $row['ID'] . '">' . $row['NAME'] . '</div>';
            }
            $cnt++;
        }
        return $html . '</div></form><div style="clear: both; width: 100%;"></div></div>
                                   <a class="podborsbros" href="/index.php?page=podbor">Сбросить фильтр</a>
                                   <div class="podbortitleresult">Результаты выборки:</div>
                                   <div id="gluki"></div>
                      <script>
                          function podobrat() {
                              param = $("form#podbor").serialize();
                              $.post("/php/get_podbor.php", param, requestPodbor, "xml");                          	
                          }
                          podobrat();
                      </script>';
    }

    function events()
    {
        global $mysqli;
        $html = "";
        if (!isset($_GET["idEvent"])) {
            $query = "select events.id,
                             events.tit,
                             events.content,
                             events.date_event,
                             events.parent
                      from events
                      where (events.del = 0) and
                            (events.parent = " . $_GET["type"] . ")
                      order by events.date_event desc";
            $res = $mysqli->query($query);
            while ($row = $res->fetch_assoc()) {
                $preview = "";
                if ($_GET["type"] != 1)
                    $zagolovok = $row["tit"];
                else
                    $zagolovok = date("d.m.Y", strtotime($row["date_event"])) . " - " . $row["tit"];
                $body = explode(" ", strip_tags($row["content"]));
                for ($i = 0; $i < 80; $i++) $preview .= " " . $body[$i];
                $html .= "<div class=\"eventBlock\">
                              <div class=\"eventHead\">" . $zagolovok . "</div>
                              <div class=\"box events\"><p>" . $preview . "</p><a class=\"button\" href=\"index.php?page=events&amp;idEvent=" . $row["id"] . "\">Подробнее</a>
                              <div style=\"width: 100%; clear: both\"></div>    
                              </div>
                          </div>";
            }
        } else {
            $query = "select events.tit,
                             events.content,
                             events.date_event,
                             events.parent
                      from events
                      where (events.del = 0) and
                            (events.id = " . $_GET["idEvent"] . ")";
            $res = $mysqli->query($query);
            while ($row = $res->fetch_assoc()) {
                if ($row["parent"] != 1)
                    $zagolovok = $row["tit"];
                else
                    $zagolovok = date("d.m.Y", strtotime($row["date_event"])) . "-" . $row["tit"];
                $html .= "<div class=\"groupName\">" . $zagolovok . "</div>
                              <div class=\"box events\">" . $row["content"] . "</div>";
            }
        }
        echo $html;
    }

    public function my_info()
    {
        global $mysqli;
        $html = '<div class="cabinet_block my_info">
                     <div class="title">Мои данные</div>';
        $query = 'select * from users where (id = ' . $_SESSION['user']['id'] . ')';
        $res = $mysqli->query($query);
        while ($row = $res->fetch_object()) {
            $fio = explode(' ', $row->name);
            $mas = explode('(', $row->phone);
            $phone = explode(')', $mas[1]);
            $html .= '<form method="post" action="/index.php?page=cabinet" id="form_my_info">
                        <div class="line">
                            <span>Фамилия</span>
                            <input name="fam" value="' . $fio[0] . '" type="text" placeholder="Ваша фамилия?">
                        </div>
                        <div class="line">
                            <span>Имя</span>
                            <input name="name" value="' . $fio[1] . '" type="text" placeholder="Ваше имя?">
                        </div>
                        <div class="line">
                            <span>Телефон</span>+7&nbsp;(
                            <input type="text" value="' . trim($phone[0]) . '" id="pref" name="pref" onkeyup="testJump(this)" size="3" maxlength="3" />)&nbsp;
                            <input type="text" value="' . trim($phone[1]) . '" id="phone" name="phone" maxlength="7"/>
                        </div>
                        <div class="line">
                            <span>Адрес</span>
                            <input name="adr" value="' . $row->adr . '" type="text" placeholder="Адрес доставки">
                        </div>
                        <input type="hidden" name="doIt" value="save_my_info">
                        <div class="clear20"></div>
                        <a class="uni-button" onclick="save_my_info()">Сохранить</a>
                    </form>';
        }
        $html .= '</div>';
        return $html;
    }

    public function my_bonus()
    {
        global $mysqli;
        $html = '<div class="cabinet_block my_info">
                     <div class="title">Бонусы</div>';
        $query = 'select * from users where (id = ' . $_SESSION['user']['id'] . ')';
        $res = $mysqli->query($query);
        while ($row = $res->fetch_object()) {
            $html .= '<div class="line bonus">
                          <span>Остаток бонусов:</span>
                          <strong>' . $row->bonus . '</strong>
                      </div>
                      <div class="line bonus">
                          <span>Потрачено бонусов:</span>
                          <strong>0</strong>
                      </div>';
        }
        $html .= '</div>';
        return $html;
    }

    public function my_orders()
    {
        global $mysqli;
        $html = '';
        if (!isset($_GET['id'])) {
            $query = 'select order_shop.id,
                         order_shop.date_order,
                         order_shop.status_agreed,
                         order_shop.status_pay,
                         order_shop.status as status_id,
                         delivery.name_full as delivery_name,
                         sum(body_order.price * body_order.amount) as order_sum
                  from order_shop
                  inner join status on (status.id = order_shop.status)
                  inner join body_order on (body_order.parent = order_shop.id)
                  inner join delivery on (delivery.id = order_shop.delivery)
                  where (order_shop.del = 0) && (order_shop.user_id = ' . $_SESSION['user']['id'] . ')
                  group by order_shop.id,
                         order_shop.date_order,
                         order_shop.status_agreed,
                         order_shop.status_pay,
                         order_shop.status,
                         delivery.name
                  order by order_shop.date_order desc';
            $res = $mysqli->query($query);
            $html = '<div class="cabinet_block">
                         <div class="title">Мои заказы</div>';
            while ($row = $res->fetch_object()) {
                switch ($row->status_pay) {
                    case 1:
                        $payment = 'оплачен';
                        $bonus = '<div class="orl"><span>Начислено бонусов:</span>' . floor($row->order_sum / 100) . '</div>';
                        break;
                    case 0:
                        $payment = 'не оплачен';
                        $bonus = '';
                        break;
                }
                $html .= '<div class="my_order_item">
                          <div class="order_tit"><a href="/index.php?page=cabinet&part=my_orders&id=' . $row->id . '">Заказ №' . $row->id . '</a> от ' . strftime('%d.%m.%Y', strtotime($row->date_order)) . '</div>
                          <div class="orl"><span>Сумма:</span>' . number_format($row->order_sum, 2, ',', ' ') . '</div>';
                $html .= $bonus;
                $html .= '<div class="orl"><span>Способ получения:</span>' . $row->delivery_name . '</div>
                          <div class="payment_status"><span>' . $payment . '</span></div>
                      </div>';
            }
            $html .= '<div class="clear30"></div>
                  </div>';
        } else {
            $query = 'select order_shop.id,
                       order_shop.date_order,
                       order_shop.fio,
                       order_shop.phone,
                       order_shop.email,
                       order_shop.comment,
                       order_shop.comment_my,
                       order_shop.adr,
                       order_shop.date_of_delivery,
                       order_shop.date_of_cur,
                       order_shop.time_intervals,
                       order_shop.status,
                       order_shop.status_pay as pay,
                       order_shop.status_agreed as agreed,
                       order_shop.dop_fld,
                       order_shop.send_invoice,
                       order_shop.post_id,
                       order_shop.bonus,
                       body_order.id as order_item,
                       body_order.id_tovar,
                       body_order.price,
                       body_order.amount,
                       tovar.name as tovar,
                       tovar.art,
                       delivery.name as delivery_name,
                       delivery.id as delivery_id
                  from order_shop
                  left join body_order on (order_shop.id = body_order.parent)
                  left join tovar on (body_order.id_tovar = tovar.id)
                  inner join delivery on (delivery.id = order_shop.delivery)
                  where (order_shop.del = 0) &&
                        (order_shop.id = ' . $_GET['id'] . ') &&
                        (order_shop.user_id = ' . $_SESSION['user']['id'] . ')
                  order by tovar.usluga,
                           tovar.name';
            $res = $mysqli->query($query);
            $cnt = 1;
            $sum_all = 0;
            while ($row = $res->fetch_object()) {
                if ($cnt == 1) {
                    $html .= '<div class="cabinet_block">
                              <div class="title">Мои заказы (Заказ №' . $_GET['id'] . ' от ' . strftime('%d.%m.%Y', strtotime($row->date_order)) . ')</div>
                              <table class="my_order">
                                  <tr>
                                      <th>№</th>
                                      <th>Артикул</th>
                                      <th>Наименование</th>
                                      <th>Кол-во, шт</th>
                                      <th>Цена, руб</th>
                                      <th>Сумма, руб</th>
                                  </tr>';
                }
                $sum_item = $row->amount * $row->price;
                $sum_all = $sum_all + $sum_item;
                $html .= '<tr>
                              <td>' . $cnt . '</td>
                              <td>' . str_repeat('0', 5 - strlen($row->id_tovar)) . $row->id_tovar . '</td>
                              <td>' . $row->tovar . '</td>
                              <td style="text-align:center;">' . $row->amount . '</td>
                              <td style="text-align:right;">' . number_format($row->price, 2, ',', ' ') . '</td>
                              <td style="text-align:right;">' . number_format($sum_item, 2, ',', ' ') . '</td>
                          </tr>';
                $bonus = $row->bonus;
                $cnt++;
            }
            $html .= '<tr>
                          <td colspan="5" style="text-align: right">Итого:</td>
                          <td style="text-align:right;">' . number_format($sum_all, 2, ',', ' ') . '</td>
                      </tr>
                      <tr>
                          <td colspan="5" style="text-align: right">Использовано бонусов:</td>
                          <td style="text-align:right;">' . number_format($bonus, 2, ',', ' ') . '</td>
                      </tr>
                      <tr>
                          <td colspan="5" style="text-align: right; font-weight: bold; font-size: 16px;">К оплате:</td>
                          <td style="text-align:right; font-weight: bold; font-size: 16px;">' . number_format(($sum_all - $bonus), 2, ',', ' ') . '</td>
                      </tr>
                      </table>
                      </div>';

        }
        return $html;
    }

    public function reg()
    {
        return '<div class="reg_block" style="display: block">
                    <div class="title">Регистрация</div>
                    <form method="post" action="/index.php?page=cabinet" id="form_reg">
                        <div class="descr">
                            Зарегистрированные пользователи не вводят свои контактные данные при каждом заказе, могут просмотреть информацию о своих заказах, получают информацию о новинках и акциях, накапливают скидки.
                        </div>
                        <div class="line"><span>Фамилия</span><input name="fam" type="text" placeholder="Ваша фамилия?"></div>
                        <div class="line"><span>Имя</span><input name="name" type="text" placeholder="Ваше имя?"></div>
                        <div class="line">
                            <span>E-mail</span><input name="email" type="email" placeholder="Ваш e-mail">
                            <div class="error" id="er_email">Пользователь с таким E-mail уже зарегистрирован!</div>
                        </div>
                        <div class="line"><span>Телефон</span>+7&nbsp;(<input type="text" id="pref" name="pref" onkeyup="testJump(this)" size="3" maxlength="3" />)&nbsp;<input type="text" id="phone" name="phone" maxlength="7"/></div>
                        <div class="line">
                            <span>Регион</span><input type="text" name="city" id="city" value="Санкт-Петербург">
                            <input type="hidden" name="city_id" id="city_id" value="137">
                        </div>
                        <div class="line"><span>Адрес</span><input name="adr" type="text" placeholder="Адрес доставки"></div>
                        <div class="line"><span>Пароль</span><input name="pwd1" type="password" placeholder="Введите пароль"></div>
                        <div class="line">
                            <span>Пароль</span><input name="pwd2" type="password" placeholder="Пароль еще раз">
                            <div class="error" id="er_no_swp">Введеные пароли не совпадают!</div>
                        </div>
                        <div style="padding: 8px 0;"><input onclick="hide_pers_data()" id="pers_data" name="pers_data" type="checkbox"> Согласен на <a target="_blank" class="uni-link" href="/confirm">обработку персональных данных</a></div>
                        <div class="line"><div class="error" id="er_pers_data">Подтвердите соглашение на обработку персональных данных</div></div>
                        <input type="hidden" name="doIt" value="reg">
                        <div class="clear20"></div>
                        <a class="uni-button" onclick="reg_user()">Зарегистрироваться</a>
                    </form>
                    <div class="clear20"></div>
                    <a class="uni-link" href="/index.php?page=cabinet">Я уже зарегистрирован</a>&nbsp;
                    <a class="uni-link" href="/index.php?page=cat&cat=16">Продолжить без регистрации</a>
                </div>
                <script>
                    $("#city").autocomplete({
                        source: "transfer_reg.php",
                        minLength: 1,
                        select: function(event, ui) {
                            $("#city_id").val(ui.item.id);
                        }
                    });
                </script>';
    }


    public function recovery()
    {
        return '<div class="reg_block" style="display: block">
                    <div class="title">Восстановление пароля</div>
                    <form method="post" action="/index.php?page=cabinet" id="form_recovery">
                        <div class="descr">
                            Введите ваш E-mail. Мы отправим на него письмо с новым паролем.
                        </div>
                        <div class="line">
                            <span>E-mail</span>
                            <input name="email" type="email" placeholder="Ваш e-mail">
                            <div class="error" id="er_no_mail">Такой E-mail не зарегистрирован!</div>
                        </div>
                        <input type="hidden" name="doIt" value="recovery">
                        <div class="clear20"></div>
                        <a class="uni-button" onclick="recovery_user()">Выслать пароль</a>
                    </form>
                </div>';
    }


    public function logout()
    {
        unset($_SESSION['user']);
        echo '<script>window.location.href = "/";</script>';
    }

    public function cabinet()
    {
        global $mysqli;
        $html = '';
        $doIt = '';
        if (isset($_POST['doIt'])) $doIt = $_POST['doIt'];
        switch ($doIt) {
            case 'auth':
                $query = 'select * from users
                          where (email="' . $_POST['email'] . '") &&
                                (pwd="' . md5($_POST['pwd']) . '")';
                $res = $mysqli->query($query);
                while ($row = $res->fetch_object()) {
                    foreach ($row as $key => $value) $_SESSION['user'][$key] = $value;
                    $html .= '<script>window.location.href = "/index.php?page=cat&cat=16"</script>';
                }
                break;
            case 'logout':
                unset($_SESSION['user']);
                $html .= '<script>
                              $("ul#left_menu li.last").html("<a href=\'index.php?page=cabinet\'>Вход</a><a style=\'margin-left: 10px;\' href=\'index.php?page=reg\'>Регистрация</a>");
                          </script>';
                break;
        }
        if (isset($_SESSION['user'])) {
            $part = 'my_orders';
            if (isset($_GET['part'])) $part = $_GET['part'];
            $html .= $this->$part();
        } else {
            $html .= '<div class="auth_block">
                          <div class="title">Авторизация</div>
                          <form method="post" action="/index.php?page=cabinet">
                              <div class="line">
                                  <span>E-mail</span><input name="email" type="email" placeholder="Введите e-mail">
                              </div>
                              <div class="line"><span>Пароль</span><input name="pwd" type="password" placeholder="Введите пароль"></div>
                              <input type="hidden" name="doIt" value="auth">
                              <div class="clear20"></div>
                              <button class="uni-button">Войти</button>
                          </form>
                          <div class="clear20"></div>
                          <a href="/index.php?page=recovery" class="uni-link">Забыли пароль?</a>
                          <a href="/index.php?page=reg" class="uni-link">Регистрация</a>
                      </div>
                      <div class="rec_block">
                          <div class="title">Восстановление пароля</div>
                          <form method="post" action="/index.php?page=cabinet">
                              <div class="line"><span>E-mail</span><input name="email" type="email" placeholder="Введите e-mail"></div>
                              <input type="hidden" name="doIt" value="rec">
                              <div class="clear20"></div>
                              <button class="uni-button">Выслать пароль</button>
                          </form>
                          <div class="clear20"></div>
                          <button class="uni-link" onclick="view_auth()">Авторизация</button>
                          <button class="uni-link" onclick="view_reg()">Регистрация</button>
                      </div>';
        }
        echo $html;
    }
}

?>
