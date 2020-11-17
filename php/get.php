<?php
session_start();
header("Content-Type: text/xml; charset=utf-8");
include 'mysqlconnect_new.php';
include "query.php";
include "class.php";
function getRegions() {
    global $mysqli;
        $query = 'select tovar.id,
                         tovar.name,
                         tovar.price
                  from tovar
                  where (tovar.del = 0) and
                        (tovar.usluga = 1)
                  order by id';
        $res = $mysqli->query($query);
        $html = '<select name="region" id="region" onChange=addToBasketDostavka(this.value)>
                     <option value="-1" selected="selected">Выберите регион*</option>';
        while ($row = $res->fetch_object()) {
            $selected = ' ';
            foreach ($_SESSION["basket"] as $key => $value) {
                if ($value["idTov"] == $row->id) $selected = ' selected="selected" ';
            }
            $html .= '<option value="'.$row->id.'"'.$selected.'>'.$row->name.'</option>';
        }
        $html .= '</select>
                  <p>Введите адрес:</p>
                  <textarea id="adr"></textarea><div class="clear"></div>
                  <p>Когда вы желаете получить заказ от курьера:</p><input type="text" name="date_of_delivery" id="date_of_delivery" onChange="test_price(this.value)";>';
        if ($value["idTov"] == 71) $html .= '<select name="time_intervals" id="time_intervals">
        	                                     <option value="Доставка до 16:00">Доставка до 16:00</option>
        	                                     <option value="Доставка после 16:00">Доставка после 16:00</option>
        	                                 </select>';
        if (strftime('%H', time()) >= 16) $date_of_delivery = strftime('%d.%m.%Y', (time()+60*60*24*2)); else $date_of_delivery = strftime('%d.%m.%Y', (time()+60*60*24));
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <head date_of_delivery="'.$date_of_delivery.'"><![CDATA['.$html.']]></head>';
        return $xml;
    }
    function getSeo() {
        switch ($_POST["page"]) {
            case ("stat"):
                $query = "select static_pages.tit,
                                 static_pages.keywords,
                                 static_pages.description,                         
                                 static_pages.h1,
                                 static_pages.up_text           
                          from static_pages
                          where (static_pages.id = ".$_POST["idMenu"].")";
            break;
            case ("cat"):
                $query = "select category.tit,
                                 category.keywords,
                                 category.description,                         
                                 category.h1,
                                 category.up_text,
                                 category.down_text
                          from category
                          where (category.id = ".$_POST["idMenu"].")";
            break;        
        }
        $row = ibase_fetch_assoc(ibase_query($query));
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head idMenu=\"".$_POST["idMenu"]."\" page=\"".$_POST["page"]."\">
                    <tit><![CDATA[".getBLOB($row["TIT"])."]]></tit>
                    <keywords><![CDATA[".getBLOB($row["KEYWORDS"])."]]></keywords>
                    <description><![CDATA[".getBLOB($row["DESCRIPTION"])."]]></description>
                    <h1><![CDATA[".getBLOB($row["H1"])."]]></h1>
                    <up_text><![CDATA[".getBLOB($row["UP_TEXT"])."]]></up_text>
                    <down_text><![CDATA[".getBLOB($row["DOWN_TEXT"])."]]></down_text>
                </head>";
        return $xml;        
    }     
    function getComment() {
        global $dbh;
        $query = 'select comments.comment_name,
                         comments.date_comment,
                         comments.comment
                  from comments
                  where (comments.del = 0) and (comments.id = '.$_POST['id'].')';
        $row = ibase_fetch_assoc(ibase_query($dbh, $query));
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <head id="'.$_POST['id'].'"><![CDATA['.getBLOB($row['COMMENT']).']]></head>';
        return $xml;
    }
    function getOrder() {
        global $dbh;
        $query = "select order_shop.id,
                         order_shop.date_order,
                         order_shop.fio,
                         order_shop.phone,
                         order_shop.email,
                         body_order.id_tovar,
                         body_order.price,
                         body_order.amount,
                         tovar.name
                  from order_shop
                  left join body_order on (order_shop.id = body_order.parent)
                  left join tovar on (body_order.id_tovar = tovar.id)
                  where (order_shop.del = 0) and (order_shop.id = ".$_POST["idOrder"].")";
        $data_basket = "<table id=\"data_order\">
                            <tr>
                                <th>№</th>
                                <th>Наименование</th>
                                <th>Кол-во, шт</th>
                                <th>Цена, руб</th>
                                <th>Сумма, руб</th>
                            </tr>";
        $cnt = 1;
        $sum_full = 0;
        $res = ibase_query($dbh, $query);
        while ($row = ibase_fetch_assoc($res)) {
            $data_order = "Заказ № ".$row["ID"]." от ".strftime("%d.%m.%Y (%H:%M)", strtotime($row["DATE_ORDER"]));
            $data_user = "<div style=\"text-align: left\">".$row["FIO"].", тел: ".$row["PHONE"].", e-mail: ".$row["EMAIL"]."</div>";
            $sum_row = $row["AMOUNT"]*$row["PRICE"];
            $sum_full = $sum_full + $sum_row;
            $data_basket .= "<tr>
                                 <td>".$cnt."</td>
                                 <td style=\"text-align: left\">".$row["NAME"]."</td>
                                 <td>".$row["AMOUNT"]."</td>
                                 <td style=\"text-align: right\">".number_format($row["PRICE"],2,"-"," ")."</td>
                                 <td style=\"text-align: right\">".number_format($sum_row,2,"-"," ")."</td>
                             </tr>";
            $cnt++;
        }
        $data_basket .= "<tr><td colspan=\"4\"></td><td style=\"text-align: right\">".number_format($sum_full,2,"-"," ")."</td></tr></table>";
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <box>
                    <order><![CDATA[".$data_order."]]></order>
                    <user><![CDATA[".$data_user."]]></user>
                    <basket><![CDATA[".$data_basket."]]></basket>
                </box>";
        return $xml;
    }
    function getDeliveryContent() {
        global $dbh;
        $query = "select delivery_content.title,
                         delivery_content.content
                  from delivery_content
                  where delivery_content.id = ".$_POST["id"];
                $row = ibase_fetch_assoc(ibase_query($dbh, $query));
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head id=\"".$_POST["id"]."\" title=\"".$row["TITLE"]."\"><![CDATA[".$row["CONTENT"]."]]></head>";
        return $xml;
    }
    function getEvent() {
        global $dbh;
        $query = "select events.tit,
                         events.content,
                         events.date_event,
                         events.parent
                  from events
                  where (events.del = 0) and
                        (events.id = ".$_POST["id"].")";
        $row = ibase_fetch_assoc(ibase_query($dbh, $query));
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <asd>
                <head type=\"".$row["PARENT"]."\" id=\"".$_POST["id"]."\" date_event=\"".strftime("%d.%m.%Y", strtotime($row["DATE_EVENT"]))."\"><![CDATA[".getBLOB($row["CONTENT"])."]]></head>
                <title><![CDATA[".  getBLOB($row["TIT"])."]]></title>
                </asd>";
        return $xml;
    }    
    function getStat() {
        global $dbh;
        $query = "select static_pages.title,
                         static_pages.content
                  from static_pages
                  where static_pages.id = ".$_POST["id"];
        $row = ibase_fetch_assoc(ibase_query($dbh, $query));
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head id=\"".$_POST["id"]."\" title=\"".$row["TITLE"]."\"><![CDATA[".getBLOB($row["CONTENT"])."]]></head>";
        return $xml;
    }    
    function getPropBrands() {
        global $dbh;
        $query = "select brands.id,
                         brands.name,
                         brands.about
                  from brands
                  where brands.id = ".$_POST["id"];
        $row = ibase_fetch_assoc(ibase_query($dbh, $query));
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head id=\"".$_POST["id"]."\" name=\"".$row["NAME"]."\"><![CDATA[".$row["ABOUT"]."]]></head>";
        return $xml;
    }    
    function getNews() {
        $query = "select news.title,
                         news.content
                  from news
                  where (news.del = 0) and
                        (news.id = ".$_POST["id"].")";
        $row = mysql_fetch_assoc(mysql_query($query));
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <asd>
                <head id=\"".$_POST["id"]."\"><![CDATA[".$row["content"]."]]></head>
                <title><![CDATA[".$row["title"]."]]></title>
                </asd>";
        return $xml;
    }    
    function getArticle() {
        $query = "select article.title,
                         article.content
                  from article
                  where (article.del = 0) and
                        (article.id = ".$_POST["id"].")";
        $row = mysql_fetch_assoc(mysql_query($query));
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head id=\"".$_POST["id"]."\" title=\"".$row["title"]."\"><![CDATA[".$row["content"]."]]></head>";
        return $xml;
    }
    function getNameGallery() {
        $query = "select gallery_list.title
                  from gallery_list
                  where gallery_list.id = ".$_POST["id"];
        $row = mysql_fetch_assoc(mysql_query($query));
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head id=\"".$_POST["id"]."\" title=\"".$row["title"]."\"></head>";
        return $xml;        
    }
    function getNameMenu() {
        $query = "select menu.item
                  from menu
                  where menu.id = ".$_POST["id"];
        $row = mysql_fetch_assoc(mysql_query($query));
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head id=\"".$_POST["id"]."\" title=\"".$row["item"]."\"></head>";
        return $xml;        
    }

    function seoInCat() {
        global $dbh;
        $query = "select category.seo_h1,
                         category.seo_up_txt,
                         category.seo_keyword,
                         category.seo_description,
                         category.seo_title
                  from category
                  where (category.id=".$_POST["idCat"].") and
                        (category.del<>1)";
        $res = ibase_query($dbh, $query);
        $content = "<h2></h2>
                    <button onClick=\"saveSeo(".$_POST["idCat"].")\">Сохранить</button>
                    <table class=\"panelTable\">";
        while ($row = ibase_fetch_assoc($res)) {
            $fck = new FCKeditor("content");
            $fck->Height = "400";
            $fck->Value = $row["SEO_UP_TXT"];
            $fck->ToolbarSet = "Auto";
            $seo_up_txt = $fck->CreateHTML();
            $content .= "<tr class=\"gray\">
                             <td style=\"width: 15%\">title</td>
                             <td style=\"width: 85%\"><input style=\"width: 100%\" type=\"text\" id=\"title\" value=\"".$row["SEO_TITLE"]."\"></td>
                         </tr>
                         <tr class=\"white\">
                             <td>keyword</td>
                             <td><input style=\"width: 100%\" type=\"text\" id=\"keyword\" value=\"".$row["SEO_KEYWORD"]."\"></td>
                         </tr>
                         <tr class=\"gray\">
                             <td>description</td>
                             <td><input style=\"width: 100%\" type=\"text\" id=\"description\" value=\"".$row["SEO_DESCRIPTION"]."\"></td>
                         </tr>
                         <tr class=\"white\">
                             <td>h1</td>
                             <td><input style=\"width: 100%\" type=\"text\" id=\"h1\" value=\"".$row["SEO_H1"]."\"></td>
                         </tr>
                         <tr class=\"gray\">
                             <td>Текст</td>
                             <td>".$seo_up_txt."</td>
                         </tr>";
            }
            $content .= "</table>";
            $way = showWay($_POST["idCat"],$dbh);
            $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                    <head idCat=\"".$_POST["idCat"]."\" wayFull=\"".$way["full"]."\" wayCurrent=\"".$way["current"]."\"><![CDATA[".$content."]]></head>";
            return $xml;
    }    
    
    
    function tovarInCat() {
        global $mysqli;
        $query = "select tovar.id,
                         tovar.name,
                         category.name as cat,
                         link_tovar.id as link
                  from tovar
                  left join link_tovar on (tovar.id = link_tovar.id_tovar)
                  left join category on (link_tovar.id_cat = category.id)
                  where (tovar.del = 0) and
                        (link_tovar.del <> 1) and
                        (link_tovar.id_cat=".$_POST["idCat"].")
                  order by tovar.name";
        $res = $mysqli->query($query);
        $content = "<h2 id=\"catName\"></h2>
                    <button onClick=\"seo(".$_POST["idCat"].",'cat')\">SEO</button>
                    <button onClick=\"addTovarToCat(".$_POST["idCat"].")\">Добавить</button>
                    <button onClick=\"getTovarInCat(".$_POST["idCat"].")\">Обновить</button>
                    <button onClick=\"renameCat(".$_POST["idCat"].")\">Переименовать</button>
                    <table class=\"panelTable\">";
            $trBg = "white";
            while ($row = $res->fetch_assoc()) {
                $content .= "<tr class=\"".$trBg."\" id=\"tovarInCatString".$row["id"]."\">
                                 <td style=\"width: 100%\">".$row["name"]."</td>
                                 <!-- <td><button onClick=\"getParamTovar(".$row["id"].")\">?</button></td> -->
                                 <td><button onClick=\"getPropTovar(".$row["id"].")\">Свойства</button></td>
                                 <td><button onClick=\"delTovarInCat(".$row["link"].", ".$row["id"].")\">Удалить</button></td>
                             </tr>";
                switch ($trBg) {
                        case ("gray"): $trBg = "white"; break;
                        case ("white"): $trBg = "gray"; break;
                }
            }
            $content .= "</table>";
            $way = '';
            $way = showWay_new($_POST["idCat"]);
            $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                    <asd>
                    <head idCat=\"".$_POST["idCat"]."\"><![CDATA[".$content."]]></head>
                    <way><![CDATA[".$way["fullAdm"]."]]></way>
                    <wayCurrent><![CDATA[".$way["current"]."]]></wayCurrent>
                    </asd>";
            return $xml;
    }
    function getPropTovar() {
        global $mysqli;
        $query = 'select tovar.name,
                         tovar.art,
                         tovar.description,
                         tovar.desc_full,
                         tovar.price,
                         tovar.balance,
                         tovar.price_old,
                         tovar.seo_tit
                  from tovar
                  where tovar.id = '.$_POST['idTovar'];
        $res = $mysqli->query($query);
        while ($row = $res->fetch_assoc()) {
            $art = $row['art'];
            $name = $row['name'];
            $seo_tit = $row['seo_tit'];
            $imgSrc = '../img/catalog/pic_'.$_POST['idTovar'].'.jpg';
            if(file_exists($imgSrc)) $imgSrc = '../img/catalog/pic_'.$_POST['idTovar'].'.jpg?rnd='.rand(1000, 9999);
            else $imgSrc = '../img/catalog/nophoto.jpg';
            $img = '<div style="text-align: center; float: left; margin: 5px 5px">
                         <img src="'.$imgSrc.'" /><br/>
                    </div>';
            $price = 'Цена: <input type="text" id="price" value="'.$row['price'].'"> руб';
            $price_old = 'Цена старая: <input type="text" id="price_old" value="'.$row['price_old'].'"> руб';
            $desc = $row['description'];
            $desc_full = $row['desc_full'];
            //$balance = 'Остаток: <button onClick="editBalance('.$_POST['idTovar'].')" id="buttonBalance">'.$row['BALANCE'].'</button> шт';
            $balance = '';
        }
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <root>
                <head idTovar="'.$_POST['idTovar'].'" do="edit"><![CDATA['.$img.']]></head>
                <art><![CDATA['.$art.']]></art>
                <name><![CDATA['.$name.']]></name>
                <desc><![CDATA['.$desc.']]></desc>
                <desc_full><![CDATA['.$desc_full.']]></desc_full>
                <price><![CDATA['.$price.']]></price>
                <price_old><![CDATA['.$price_old.']]></price_old>
                <balance><![CDATA['.$balance.']]></balance>
                <seo_tit><![CDATA['.$seo_tit.']]></seo_tit>
                </root>';
        return $xml;
    }
    function getParamTovar() {
        global $dbh;
        $query = "select prop_type.prop_name,
                         prop_value.prop_value,
                         TBL6.FLD25,
                         LINK_PROP.ID
                  from tbl6
                  inner join link_prop on (tbl6.fld24 = LINK_PROP.ID_TOVAR)
                  inner join prop_value on (LINK_PROP.ID_PROP = PROP_VALUE.ID)
                  inner join PROP_TYPE on (PROP_VALUE.PROP_TYPE = PROP_TYPE.ID)
                  where tbl6.fld24=".$_POST["idTov"];
        $res = ibase_query($dbh, $query);
        $content = "<div style=\"text-align: left\"><button id=\"addButton\" onClick=\"addParamDialog(".$_POST["idTov"].")\">Добавить</button></div>
                    <table class=\"panelTable\">";
        $trBg = "white";
        while ($row = ibase_fetch_assoc($res)) {
            $content .= "<tr class=\"".$trBg."\" id=\"paramString".$row["ID"]."\">
                             <td style=\"width: 55%; text-align: left\">".iconv("windows-1251","utf-8",$row["PROP_NAME"])."</td>
                             <td style=\"width: 30%; text-align: left\">".iconv("windows-1251","utf-8",$row["PROP_VALUE"])."</td>
                             <td style=\"width: 15%; text-align: right\"><button onClick=\"delParam(".$row["ID"].")\">Удалить</button></td>
                         </tr>";
            switch ($trBg) {
                case ("gray"): $trBg = "white"; break;
                case ("white"): $trBg = "gray"; break;
            }
        }
        $content .= "</table>
                     <table>
                     <tr>
                         <td id=\"listType\"></td>
                         <td id=\"listVal\"></td>
                         <td id=\"buttonBox\"></td>
                     </tr>
                     </table>";
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head idTovar=\"".$_POST["idTov"]."\"><![CDATA[".$content."]]></head>";
        return $xml;
    }
    function getListType() {
        global $dbh;
        $query = "select id, prop_name from prop_type where (prop_type.del = 0)";
        $res = ibase_query($dbh, $query);
        $content = "<select id=\"selListType\" onChange=\"getListVal(".$_POST["idTov"].", this.value)\">
                    <option value=\"-1\">Выберите тип</option>";
        while ($row = ibase_fetch_assoc($res)) {
            $content .= "<option value=\"".$row["ID"]."\">".iconv("windows-1251", "utf-8", $row["PROP_NAME"])."</option>";
        }
        $content .= "</select>";
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head idTovar=\"".$_POST["idTov"]."\"><![CDATA[".$content."]]></head>";
        return $xml;
    }
    function getListVal() {
        global $dbh;
        $query = "select prop_value.id,
                         prop_value.prop_value
                  from prop_value
                  where (prop_value.prop_type = ".$_POST["idType"].") and
                        (prop_value.del = 0)";
        $res = ibase_query($dbh, $query);
        $content = "<select id=\"selListType\" onChange=\"saveButton(".$_POST["idTov"].", this.value)\">
                    <option value=\"-1\">Выберите значение</option>";
        while ($row = ibase_fetch_assoc($res)) {
            $content .= "<option value=\"".$row["ID"]."\">".iconv("windows-1251", "utf-8", $row["PROP_VALUE"])."</option>";
        }
        $content .= "</select>";
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head idTovar=\"".$_POST["idTov"]."\"><![CDATA[".$content."]]></head>";
        return $xml;
    }    
    function allTovar() {
        global $mysqli;
        $dicQ = new query();
        $res = $mysqli->query($dicQ->allTovar());
        $content = "<table class=\"panelTable\">";
            $trBg = "white";
            $cnt = 1;
            while ($row = $res->fetch_assoc()) {
                $content .= "<tr class=\"".$trBg."\" id=\"tovarString".$row["id"]."\">
                                 <td>".$cnt."</td>
                                 <td style=\"width: 55%; text-align: left\">".$row["name"]."</td>
                                 <td style=\"width: 30%; text-align: left\">".$row["art"]."</td>
                                 <td style=\"width: 15%; text-align: right\"><button onClick=\"tovarLinkCat(".$_POST["idCat"].",".$row["id"].")\">Прикрепить</button></td>
                             </tr>";
                switch ($trBg) {
                        case ("gray"): $trBg = "white"; break;
                        case ("white"): $trBg = "gray"; break;
                }
                $cnt++;
            }
            $content .= "</table>";
            $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                    <head idCat=\"".$_POST["idCat"]."\"><![CDATA[".$content."]]></head>";
            return $xml;
    }
    function getLineSize() {
        global $dbh;
        $query = "select tbl21.fld24 as modelId,
                         p1.fld23 as colorName,
                         p2.fld23 as sizeName,
                         tbl21.fld21_2 as sizeId,
                         tbl21.fld21_1 as colorId,
                         sum(fld70ost)
                  from tbl21
                  left join tbl5 p1 on (p1.fld21=tbl21.fld21_1)
                  left join tbl5 p2 on (p2.fld21=tbl21.fld21_2)
                  left join tbl6 on(tbl6.fld24=tbl21.fld24)
                  where(t21_del=0)and(fld70ost>0)and(tbl21.fld24=".$_POST["idTov"].")and(tbl21.fld21_1=".$_POST["idColor"].")
                  group by tbl21.fld24, p1.fld23, p2.fld23, tbl21.fld21_1, tbl21.fld21_2
                  order by sizeName";
        $res = ibase_query($dbh, $query);
        $content = "<div class=\"propName\">Размер: </div><div class=\"propVal\"><select style=\"width: 137px\" id=\"size\" name=\"size\">";
        while ($row = ibase_fetch_assoc($res)) {
            $content.= "<option value=\"".$row["SIZEID"]."\">".$row["SIZENAME"]."</option>";
        }
        $content .= "</select></div>";
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <head><![CDATA[".$content."]]></head>";
        return $xml;        
    }
    $xml = $_POST["do"]();
    echo $xml;
    $mysqli->close();
?>
