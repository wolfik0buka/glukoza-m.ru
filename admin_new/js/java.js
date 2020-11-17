function toPdf() {
    $.post("../admin_new/getPdf.php", "", requestToPdf, "xml");
}

$(function () {
    $(window).scroll(function () {
        var top = $(document).scrollTop();
        if (top > 105) {
            $('#order_head').addClass('order_head_fix');
            $('#order_table_body').addClass('mt50');
            twidth = $('table.order_list').outerWidth();
            $('table#order_head').outerWidth(twidth);
        } else {
            $('#order_head').removeClass('order_head_fix');
            $('#order_table_body').removeClass('mt50');
        }
    });
    $(window).resize(function () {
        $('table#order_head').width($('table.order_list').width());
    });
});

function send_invoice(id) {
    $.post("../../php/send_invoice.php", 'id=' + id, request_send_invoice, "xml");
}

function send_post_id(id) {
    $.post("../../php/send_post_id.php", 'id=' + id, request_send_post_id, "xml");
}

function request_send_post_id(xml) {
    alert('Номер почтового идентификатора записан и отправлен получателю');
}

function request_send_invoice(xml) {
    if ($('head', xml).text() == 'ok') $('#invoice_status').text('Отправлен');
}

function requestToPdf(xml) {
    window.open('https://glukoza-med.ru/pdf/export.pdf');
}

function urlencode(str) {
    str = (str + '').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

/* Удаление фотографии - начало */
function delPhoto(id, photo) {
    $.post('editPhoto.php', 'do=del&id=' + id + '&photo=' + photo, requestDelPhoto, "xml");
}
function requestDelPhoto(xml) {
    idPhoto = $('head', xml).attr('idPhoto');
    $('div#photo' + idPhoto).html('<div style="display: block; width: 120px; height: 120px; text-align: center; line-height: 120px;">Удалено</div>');
}
/* Удаление фотографии - конец */

/* Установка основной фотографии в коллекциях - начало */
function setBasicPhotoCol(photo, tovar) {
    $.post('setBasicPhotoCol.php', 'photo=' + photo + '&tovar=' + tovar, requestBasicPhotoCol, 'xml');
}
function requestBasicPhotoCol(xml) {
    id = '#photo_in' + $('head', xml).attr('idPhoto');
    $('img.basic_photo').attr('class', 'no_basic_photo');
    $(id).attr('class', 'basic_photo');
}
/* Установка основной фотографии в коллекциях - конец */

/* Удаление фотографии в коллекциях - начало */
function delPhotoCol(id, photo) {
    $.post('editPhoto.php', 'do=delCol&id=' + id + '&photo=' + photo, requestDelPhotoCol, "xml");
}
function requestDelPhotoCol(xml) {
    idPhoto = $('head', xml).attr('idPhoto');
    $('div#photo' + idPhoto).html('<div style="display: block; width: 120px; height: 120px; text-align: center; line-height: 120px;">Удалено</div>');
}
/* Удаление фотографии в коллекциях - конец */

/* Привязываем св-во к товару - начало */
function prop_linker(tovar, prop, doIt) {
    $.post('prop_linker.php', 'tovar=' + tovar + '&prop=' + prop + '&doIt=' + doIt, request_prop_linker, 'xml');
}
function request_prop_linker(xml) {
    id = '#type' + $('head', xml).attr('id_val');
    func = $('head', xml).attr('func');
    $(id).attr('onClick', func);
}
/* Привязываем св-во к товару - конец */

function get_fb() {
    $.post('dermo_from_fb.php', 'do=sklad', request_get_fb, 'xml');
}
function request_get_fb(xml) {
    $('#dermo_from_fb').html($('head', xml).text());
}
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
function stripslashes(str) {
    return str.replace('/\0/g', '0').replace('/\(.)/g', '$1');
}

///// Новости начало //////
function addNewsDialog() {
    fck = new FCKeditor("FCKeditor1");
    fck.InstanceName = "news";
    fck.ToolbarSet = "Auto";
    fck.Height = 350;
    editor = fck.CreateHtml();
    $("#popUp").html("<div id=\"editNewsDialog\" title=\"Добавление новости\">\n\
                          <div style=\"text-align: left; font-weight: bold\">Название:<br />\n\
                              <input style=\"width: 100%\" type=\"text\" name=\"title\" id=\"title\">\n\
                          </div>\n\
                          <div style=\"text-align: left; font-weight: bold\">Дата:<br />\n\
                              <input style=\"width: 100%\" type=\"text\" name=\"date_news\" id=\"date_news\">\n\
                          </div>\n\
                          <div style=\"text-align: left; font-weight: bold\">Содержание:<br />" + editor + "</div>\n\
                      </div>");
    $("#editNewsDialog").dialog({
        autoOpen: true,
        modal: true,
        width: 750,
        buttons: {
            "Сохранить": function () {
                fckNew = FCKeditorAPI.GetInstance("news");
                content = encodeURIComponent(fckNew.GetXHTML());
                title = encodeURIComponent($("input#title").val());
                date_news = $("input#date_news").val()
                $.post("editNews.php", "date_news=" + date_news + "&content=" + content + "&do=add&title=" + title, requestEditNews, "xml");
            }
        },
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("div#editNewsDialog").remove();
            $("div#popUp").empty();
            window.location.reload();
        }
    });
    $("input#date_news").datepicker();
}
function requestEditNews(xml) {
    $("#errorUp").html("<div id=\"errorWin\" title=\"Внимание!\">" + $("head", xml).text() + "</div>");
    $("#errorWin").dialog({
        autoOpen: true,
        modal: true,
        width: 250,
        height: 120,
        buttons: {
            "Ок": function () {
                $(this).dialog("close");
            }
        },
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("div#errorWin").remove();
            $("div#errorUp").empty();
            window.location.reload();
        }
    });
}
function editNews(id) {
    $.post("get.php", "do=getNews&id=" + id, editNewsDialog, "xml");
}
function editNewsDialog(xml) {
    fck = new FCKeditor("FCKeditor1");
    fck.InstanceName = "news";
    fck.ToolbarSet = "Auto";
    fck.Height = 350;
    fck.Value = $("head", xml).text();
    editor = fck.CreateHtml();
    title = htmlEntities($("title", xml).text());
    $("#popUp").html("<div id=\"editNewsDialog\" title=\"Новость\">\n\
                          <div style=\"text-align: left; font-weight: bold\">Название:<br />\n\
                              <input style=\"width: 100%\" type=\"text\" name=\"title\" id=\"title\" value=\"" + title + "\">\n\
                          </div>\n\
                          <div style=\"text-align: left; font-weight: bold\">Дата:<br />\n\
                              <input style=\"width: 100%\" type=\"text\" name=\"date_news\" id=\"date_news\" value=\"" + $("asd", xml).attr("date_news") + "\">\n\
                          </div>\n\
                          <div style=\"text-align: left; font-weight: bold\">Содержание:<br />" + editor + "</div>\n\
                      </div>");
    $("#editNewsDialog").dialog({
        autoOpen: true,
        modal: true,
        width: 750,
        buttons: {
            "Сохранить": function () {
                fckNew = FCKeditorAPI.GetInstance("news");
                content = encodeURIComponent(fckNew.GetXHTML());
                title = $("input#title").val();
                date_news = $("input#date_news").val();
                id = $("head", xml).attr("id");
                $.post("editNews.php", "date_news=" + date_news + "&content=" + content + "&do=edit&id=" + id + "&title=" + title, requestEditNews, "xml");
            }
        },
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("div#editNewsDialog").remove();
            $("div#popUp").empty();
            window.location.reload();
        }
    });
    $("input#date_news").datepicker();
}

/*


 function delPhoto(id) {
 $.post('editPhoto.php', 'do=del&id='+id, requestDelPhoto, "xml");
 }
 function requestDelPhoto(xml) {
 idPhoto = $('head',xml).attr('idPhoto');
 $('div#photo'+idPhoto).html('<div style="display: block; width: 120px; height: 120px; text-align: center; line-height: 120px;">Удалено</div>');
 }
 */

///// Новости конец //////


///// Статические страницы ////
function editStatDialog(xml) {
    fck = new FCKeditor();
    fck.InstanceName = "event";
    fck.ToolbarSet = "Auto";
    fck.Height = 300;
    fck.Value = $("head", xml).text();
    editor = fck.CreateHtml();
    name = htmlEntities($("name", xml).text());
    seo_title = htmlEntities($("seo_title", xml).text());
    seo_desc = htmlEntities($("seo_description", xml).text());
    seo_keywords = htmlEntities($("seo_keywords", xml).text());
    $("#popUp").html('<div id="editNewsDialog">\n\
                          <table style="width: 100%;">\n\
                          <tr><th>Название:</th><td style="width: 90%;"><input style="width: 100%" type="text" name="name" id="name" value="' + name + '"></td></tr>\n\
                          <tr><th>Title:</th><td style="width: 90%;"><input style="width: 100%" type="text" name="seo_title" id="seo_title" value="' + seo_title + '"></td></tr>\n\
                          <tr><th>Description:</th><td style="width: 90%;"><input style="width: 100%" type="text" name="seo_desc" id="seo_desc" value="' + seo_desc + '"></td></tr>\n\
                          <tr><th>Keywords:</th><td style="width: 90%;"><input style="width: 100%" type="text" name="seo_keywords" id="seo_keywords" value="' + seo_keywords + '"></td></tr>\n\
                          <tr><td colspan="2"><div style="text-align: left; font-weight: bold">Содержание:<br />' + editor + '</div></td></tr>\n\
                          </table>\n\
                      </div>');
    $("#editNewsDialog").dialog({
        autoOpen: true,
        modal: true,
        width: 950,
        buttons: {
            "Сохранить": function () {
                fckNew = FCKeditorAPI.GetInstance("event");
                content = encodeURIComponent(fckNew.GetXHTML());
                name = encodeURIComponent($("input#name").val());
                seo_title = encodeURIComponent($("input#seo_title").val());
                seo_desc = encodeURIComponent($("input#seo_desc").val());
                seo_keywords = encodeURIComponent($("input#seo_keywords").val());
                id = $("head", xml).attr("id");
                $.post("editStat.php", "content=" + content + "&id=" + id + "&name=" + name + "&seo_keywords=" + seo_keywords + "&seo_desc=" + seo_desc + "&seo_title=" + seo_title + "&do=edit", requestEditNews, "xml");
            }
        },
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("div#editNewsDialog").remove();
            $("div#popUp").empty();
        }
    });
}
function editStat(id) {
    $.post("get.php", "do=getStat&id=" + id, editStatDialog, "xml");
}
function requestEditNews(xml) {
    $('#errorUp').html('<div id="errorWin" title="Внимание!">' + $('head', xml).text() + '</div>');
    $('#errorWin').dialog({
        autoOpen: true,
        modal: true,
        width: 250,
        height: 140,
        buttons: {
            "Ок": function () {
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).dialog('destroy');
            $('div#errorWin').remove();
            $('div#errorUp').empty();
            //window.location.reload();
        }
    });
}
///// Конец статические страницы /////

///// Св-ва товара /////

function getPropTovar(idTov) {
    $.post("get.php", "idTov=" + idTov + "&do=getPropTovar", requestPropTovar, "xml");
}

function requestPropTovar(xml) {
    fckDescFull = new FCKeditor();
    fckDescFull.InstanceName = "descFull";
    fckDescFull.ToolbarSet = "Auto";
    fckDescFull.Height = 200;
    fckDescFull.Value = stripslashes($("desc_full", xml).text());
    editorFull = fckDescFull.CreateHtml();
    price = $("price", xml).text();
    seo_title = $('seo_title', xml).text();
    $('#popUp').html('<div id="propTovarDialog" title="' + $('name', xml).text() + '">\n\
                          <div style="text-align: left; float: left; width: 70%">\n\
                              <div class="propDiv">Наименование: <input style="margin-right: 5px; float: right; width: 550px" type="text" id="nameTovar" value="' + htmlEntities($('name', xml).text()) + '"></div>\n\
                              <div class="propDiv">Артикул: <input style="margin-right: 5px; float: right; width: 550px" type="text" id="artTovar" value="' + htmlEntities($('art', xml).text()) + '"></div>\n\
                              <div class="propDiv">Описание товара:' + editorFull + '</div>\n\
                              <div class="propDiv">' + price + '</div>\n\
                          </div>\n\
                          <div style="text-align: left; float: left; width: 30%">\n\
                              <form id="photoForm" enctype="multipart/form-data" action="addPhotoGallery.php?idTov=' + $("head", xml).attr("idTov") + '&idPic=' + $("head", xml).attr("idPic") + '" method="POST" name="addPhoto" target="trash">\n\
    	                          Новое фото:&nbsp;<input id="filePath" type="file" name="x" size=25>\n\
                                  <button onClick="addPhoto.submit()">Отправить</button>\n\
                              </form><div id="imgBox">' + $('head', xml).text() + '</div><div style="width: 100%; clear: both;"></div>\n\
                              <div class="propDiv">Title страницы товара: <input style="width: 100%;" type="text" id="seoTitle" value="' + htmlEntities($('seo_title', xml).text()) + '"></div>\n\
                          </div>\n\
                      </div>');
    $("#propTovarDialog").dialog({
        autoOpen: true,
        modal: true,
        width: 1120,
        buttons: {
            "Сохранить": function () {
                fckDescFullNew = FCKeditorAPI.GetInstance("descFull");
                descFull = encodeURIComponent(fckDescFullNew.GetXHTML());
                id = $("head", xml).attr("idTov");
                price = $("input#price").val();
                doIt = $("head", xml).attr("do");
                art = $("input#artTovar").val();
                name = encodeURIComponent($("input#nameTovar").val());
                seo_title = encodeURIComponent($("input#seoTitle").val());
                $.post("editTovar.php", "do=" + doIt + "&id=" + id + "&descFull=" + descFull + "&price=" + price + "&art=" + art + "&name=" + name + "&seo_title=" + seo_title, standartAlert, "xml");
            }
        },
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("div#propTovarDialog").remove();
            $("div#popUp").empty();
            $("iframe").not("#trash").remove();
        }
    });
    $("button").button();
}

function getTovarInCat(idCat, orderFld) { //Отправляем запрос на список товара в ветке каталога
    $.post('/admin/get.php', 'idCat=' + idCat + '&orderFld=' + orderFld + '&do=getTovarInCat', requestTovarInCat, 'xml');
}
function requestTovarInCat(xml) { //Получаем список товара в ветке каталога и выводим на экран
    $("td#tdTovar div").html($("head", xml).text());
    $("td#tdTovar div h2").html($("wayCurrent", xml).text());
    //$("div#way").html($("way",xml).text());
    $("button").button();
}
function standartAlert(xml) {
    alert($('head', xml).text());
    if ($('go_url', xml).text() != '') window.location.href = $('go_url', xml).text();
}
///// Конец св-ва товара /////

function addCatToCat(idCat) { // Добавляем категорию в категорию
    $('#popUp').html('<div id="newCatDialog" title="Новая категория">\n\
                          <div style="text-align: left; font-weight: bold">Наименование:<br />\n\
                              <input style="width: 100%" type="text" id="name">\n\
                          </div>\n\
                      </div>');
    $("#newCatDialog").dialog({
        autoOpen: true,
        modal: true,
        width: 550,
        buttons: {
            "Сохранить и закрыть": function () {
                name = $("input#name").val();
                $.post("editCat.php", "name=" + name + "&parent=" + idCat + "&do=add", standartAlert, "xml");
                $(this).dialog("close");
            }
        },
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("div#newCatDialog").remove();
            $("div#popUp").empty();
        }
    });
}
function delCat(idCat) { // Удаляем текущую категорию
    $.post('editCat.php', 'do=delCat&idCat=' + idCat, standartAlert, 'xml');
}

//////// Прикрепляем товар в категорию начало ///////
function addTovarToCat(idCat) {
    $.post('/admin/get.php', 'idCat=' + idCat + '&do=getAllTovar', requestAllTovar, 'xml');
}
function requestAllTovar(xml) {
    $("#popUp").html("<div id=\"allTovarDialog\" title=\"Список товаров\">" + $("head", xml).text() + "</div>");
    $("#allTovarDialog").dialog({
        autoOpen: true,
        modal: true,
        width: 950,
        height: 500,
        buttons: {
            "Закрыть": function () {
                $(this).dialog("close");
            }
        },
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("div#allTovarDialog").remove();
            $("div#popUp").empty();
            getTovarInCat($("head", xml).attr("idCat"));
        }
    });
    $("button").button();
}
function tovarLinkCat(idCat, idTov) {
    $.post("editCat.php", "idCat=" + idCat + "&idTov=" + idTov + "&do=link", requestTovarLinkCat, "xml");
}
function requestTovarLinkCat(xml) {
    idTov = $('head', xml).attr('idTov');
    $('tr#tovarString' + idTov).html('<td colspan=4 style="text-align: center">ГОТОВО</td>');
}
//////// Прикрепляем товар в категорию конец ///////

//////// Открепляем товар от ветки каталога начало ///////
function delTovarInCat(idLink) {
    $.post("editCat.php", "idLink=" + idLink + "&do=del", requestDelTovarInCat, "xml");
}
function requestDelTovarInCat(xml) {
    idLink = $('head', xml).attr('idLink');
    $('tr#tovarInCatString' + idLink).html('<td colspan="5" style="text-align: center">УДАЛЕНО</td>');
}
//////// Открепляем товар от ветки каталога конец ///////


//////// Свойства категории начало ///////////
function propCat(idCat, order, title) {
    $('#popUp').html('<div id="editCat" title="Свойства категории">\n\
                          <b>Название:</b> <input style="width: 96%" type="text" id="name" value="' + htmlEntities($('h2#catName').text()) + '">\n\
                          <b>Порядок:</b> <input style="width: 96%" type="text" id="order" value="' + order + '">\n\
                          <b>Title:</b> <input style="width: 96%" type="text" id="title" value="' + title + '">\n\
                      </div>');
    $('#editCat').dialog({
        autoOpen: true,
        modal: true,
        width: 500,
        height: 290,
        buttons: {
            'Сохранить и закрыть': function () {
                param = 'name=' + $('input#name').val() + '&order=' + $('input#order').val() + '&title=' + $('input#title').val() + '&idCat=' + idCat + '&do=edit';
                $.post('editCat.php', param, requestEditCat, 'xml');
                $(this).dialog('close');
            }
        },
        close: function (event, ui) {
            $(this).dialog('destroy');
            $('div#editCat').remove();
            $('div#popUp').empty();
        }
    });
}
function requestEditCat(xml) {
    alert($('head', xml).text());
    getTovarInCat($('head', xml).attr('idCat'), $('head', xml).attr('order'));
}
//////// Свойства категории конец ///////////


/// Удаление заказа НАЧАЛО - NEW
function delOrder(id) {
    $.post("/admin_new/order/db.php", "doIt=del_order&id=" + id, requestDelOrder, "xml");
}

function requestDelOrder(xml) {
    id = $('head', xml).attr('id');
    txt = '<td colspan="5" style="text-align: center">' + $('head', xml).text() + '</td>';
    $('tr#str' + id).html(txt);
}
/// Удаление заказа КОНЕЦ - NEW

/// Меняем статус заказа - НАЧАЛО - NEW
function change_status(order, status) {
    $.post("/admin_new/order/db.php", "doIt=change_status&order=" + order + "&status=" + status, request_change_status, 'xml');
}
function request_change_status(xml) {
    date_of_cur = '';
    if ($('head', xml).attr('val') != '0') date_of_cur = $('head', xml).attr('val');
    $('#date_of_cur').html(date_of_cur);
    if ($('head', xml).attr('status') == '3') {
        change_flag('pay', $('head', xml).attr('id'), 0);
        $('#pay').attr('checked', 'checked');
    }
    if ($('head', xml).attr('status') == '2') {
        if ($('head', xml).attr('type_delivery') == '3') send_sms_client($('head', xml).attr('id'));
    }
}
/// Меняем статус заказа - КОНЕЦ - NEW


/**  Меняем способ получения заказа в админке  **/
function change_delivery(order, status) {
    $.ajax({
        type: 'post',
        url: '/admin_new/order/db.php',
        data: {
            doIt: 'change_delivery',
            order: order,
            delivery: status
        },
        success: function(xml) {
            window.location.href = "/admin_new/index.php?page=order&id=" + $('head', xml).attr('id');
        }
    });
}

/// Меняем временной интервал доставки - НАЧАЛО - NEW
function change_interval(order, status) {
    $.post("/admin_new/order/db.php", "doIt=change_interval&order=" + order + "&status=" + status);
}
/// Меняем временной интервал доставки - КОНЕЦ - NEW


/// Сохраняем внутренний комментарий - НАЧАЛО - NEW
function save_comment_my(order) {
    order_comment_my = $('#order_comment_my').val();
    $.post("/admin_new/order/db.php", "doIt=save_comment_my&order=" + order + "&comment_my=" + order_comment_my);
}
/// Сохраняем внутренний комментарий - КОНЕЦ - NEW

/// Меняем значение флага - НАЧАЛО - NEW
function change_flag(fld, order, c_val) {
    $.post("/admin_new/order/db.php", "doIt=change_flag&fld=" + fld + "&c_val=" + c_val + "&order=" + order, request_change_flag, "xml");
}
function request_change_flag(xml) {
    $('#' + $('head', xml).attr('id')).val($('head', xml).attr('val'));
}
/// Меняем значение флага - КОНЕЦ - NEW

/// Удаляем позицию из заказа - НАЧАЛО - NEW
function del_order_item(order, id) {
    $.post("/admin_new/order/db.php", "doIt=del_order_item&order=" + order + "&id=" + id, request_del_order_item, 'xml');
}
function request_del_order_item(xml) {
    id = $('head', xml).attr('id');
    window.location.href = '/admin_new/index.php?page=order&id=' + id;
}

/// Удаляем позицию из заказа - КОНЕЦ - NEW


function change_amount(item, amount, order) {
    $.post("/admin_new/order/db.php", "doIt=change_amount&item=" + item + "&amount=" + amount + "&order=" + order, request_change_amount, 'xml');
}

function request_change_amount(xml) {
    order = $('head', xml).text();
    $('.panelTable tbody').html(order);
    $(".panelTable tr td.td_price i").click(function () {
        edit_price(this);
    });
    //$('#order_table').html("<span class='red'>Hello <b>Again</b></span>");
}


function save_date_of_delivery(date_of_delivery, id) {
    $.post("/admin_new/order/db.php", "doIt=save_date_of_delivery&date_of_delivery=" + date_of_delivery + "&id=" + id, request_save_date_of_delivery, 'xml');
}

function request_save_date_of_delivery(xml) {

}

function edit_fld(fld) {
    fld_val = '<textarea>' + $('#' + fld).text() + '</textarea>';
    if (fld == 'date_of_delivery') fld_val = '<input type="text" value="' + $('#' + fld).text() + '">';
    $('#' + fld + '_but i.fa').removeClass('fa-pencil')
        .addClass('fa-floppy-o')
        .attr('onClick', 'save_fld(\'' + fld + '\')');
    $('#' + fld).html(fld_val);
    if (fld == 'date_of_delivery') $('td#date_of_delivery input').datepicker({
        autoClose: true,
        minDate: new Date(),
        position: 'top right'
    });
}

function save_fld(fld) {
    val = urlencode($('#' + fld + ' textarea').val());
    if (fld == 'date_of_delivery') val = $('#' + fld + ' input').val();
    id = $('#current_order').val();
    $.post("/admin_new/order/db.php", "doIt=save_fld&fld=" + fld + "&val=" + val + "&id=" + id, request_save_fld, 'xml');
}

function edit_price_in_list(obj) {
    //alert ($(obj).text());
    fld_val = '<input type="text" value="' + $(obj).text() + '">';
    //$('#' + fld + '_but i.fa').removeClass('fa-pencil')
    //    .addClass('fa-floppy-o')
    //    .attr('onClick', 'save_fld(\'' + fld + '\')');
    $(obj).html(fld_val);
}

function request_save_fld(xml) {
    if ($('head', xml).text() != 'Ошибка') {
        fld = $('head', xml).attr('id');
        if (fld == 'date_of_delivery') {
            $('#' + fld + ' input').replaceWith($('head', xml).text());
        } else {
            $('#' + fld + ' textarea').replaceWith($('head', xml).text());
        }
        $('#' + fld + '_but i.fa').removeClass('fa-floppy-o')
            .addClass('fa-pencil')
            .attr('onClick', 'edit_fld(\'' + fld + '\')');
    }
}


function open_add_to_order(order) {
    $("#popup").html('<div onclick="close_add_to_order()" class="close">Закрыть</div>' +
        '<h1>Добавить товар</h1>' +
        '<div><input placeholder="Начните вводить наименование товара" onkeyup="go_search(this.value, ' + order + ')" type="text" id="search_tovar"></div>' +
        '<div class="search_result"></div>');
    $("#dialog_bg").animate({"display": "=block", "opacity": "show"}, "fast");
    $("#popup").animate({"display": "=block", "opacity": "show"}, "fast");
}
function close_add_to_order() {
    $("#dialog_bg").attr({"style": " ", "opacity": "show"}, "fast");
    $("#popup").attr("style", " ");
}
function go_search(word, order) {
    if (word.length >= 3) {
        $.post("/admin_new/order/db.php", "doIt=get_tovar_list&word=" + word + "&order=" + order, request_go_search, 'xml');
    } else {
        $('div.search_result').empty();
    }
}
function request_go_search(xml) {
    result = $('head', xml).text();
    $('div.search_result').html(result);
}
function add_to_order(order, id) {
    $.post("/admin_new/order/db.php", "doIt=add_to_order&id=" + id + "&order=" + order, request_add_to_order, 'xml');
}
function request_add_to_order(xml) {
    if ($('go_url', xml).text() != '') window.location.href = $('go_url', xml).text();
}

///


//////// Добавление нового товара начало //////

function newTovarDialog() {
    $("#popUp").html("<div id=\"newTovarDialog\" title=\"Новый товар\">\n\
                          <div style=\"text-align: left; font-weight: bold\">Наименование:<br />\n\
                              <input style=\"width: 100%\" type=\"text\" id=\"name\">\n\
                          </div>\n\
                          <div style=\"text-align: left; font-weight: bold\">Артикул:<br />\n\
                              <input style=\"width: 100%\" type=\"text\" id=\"art\">\n\
                          </div>\n\
                      </div>");
    $("#newTovarDialog").dialog({
        autoOpen: true,
        modal: true,
        width: 750,
        buttons: {
            "Сохранить и закрыть": function () {
                name = $("input#name").val();
                art = $("input#art").val();
                $.post("editTovar.php", "art=" + art + "&name=" + name + "&do=add", standartAlert, "xml");
            }
        },
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("div#newTovarDialog").remove();
            $("div#popUp").empty();
        }
    });
}

//////// Добавление нового товара конец //////


function in_work() {
    alert('На стадии разработки!');
}