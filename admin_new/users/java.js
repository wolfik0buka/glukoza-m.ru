function get_orders(id) {
    $.ajax({
        url: '/admin_new/users/db.php',
        type: 'post',
        data: {
            id: id,
            doIt: 'get_orders'
        },
        success: function(xml) {
            alert(xml);
        }
    });
}





function saveTovar(id) {   // Сохранение изменений при редактировании товара
	$.post('nom/db.php', $('#tovarForma').serialize()+'&id='+id, standartAlert, 'xml');
}



/* Удаление товара - начало */
function delTovar(id) {
	$.post('nom/db.php', 'doIt=delTovar&id='+id, requestDelTovar, 'xml');
}
function requestDelTovar(xml) {
	id = $('head', xml).attr('id');
	mess = $('head', xml).text();
	$('tr#str'+id).html('<td colspan="7" style="text-align: center;">'+mess+'</td>');
}
/* Удаление товара - конец */




/* Привязка товара к ветке каталога - начало */
function cat_linker(tovar, cat, doIt) {
    if (tovar == 0) {
        alert('Сначала сохраните данные товара!');
    } else {
        $.post('/admin_new/nom/cat_linker.php', 'tovar=' + tovar + '&cat=' + cat + '&doIt=' + doIt, request_cat_linker, 'xml');
    }
}
function request_cat_linker(xml) {
    id = '#type'+$('head',xml).attr('cat');
    func = $('head',xml).attr('func');
    $(id).attr('onClick',func);
}
/* Привязка товара к ветке каталога - конец */



/* Привязка цвета к товару - начало */
function color_linker(tovar, color, doIt) {
    $.post('/admin/nom/color_linker.php', 'tovar='+tovar+'&color='+color+'&doIt='+doIt, request_color_linker, 'xml');
}
function request_color_linker(xml) {
    id = '#type_color'+$('head',xml).attr('cat');
    func = $('head',xml).attr('func');
    $(id).attr('onClick',func);
}
/* Привязка цвета к товару - конец */




function how_to_wear(box_no, id) {
    $.post('nom/db.php', 'doIt=nom_to_div&id='+id+'&block=how_to_wear'+box_no, request_how_to_wear, 'xml');   
}
function may_like(box_no, id) {
    $.post('nom/db.php', 'doIt=nom_to_div&id='+id+'&block=may_like'+box_no, request_how_to_wear, 'xml');   
}

function request_how_to_wear(xml) {
    html = $('head', xml).text();
    $('#dialog_bg').css('display', 'block');    
    $('#popup').css('display', 'block');
    $('#popup').html(html);
}
function insert_tovar(block, tovar, id) {
    $.post('nom/db.php', 'doIt=insert_tovar&id='+id+'&tovar='+tovar+'&block='+block, request_insert_tovar, 'xml');
}
function request_insert_tovar(xml) {
    close_popup();
    window.location.href=$('go_url',xml).text();
}
function close_popup(){
    $("#dialog_bg").attr({"style": " ", "opacity": "show"}, "fast");
    $("#popup").attr("style"," ");
    $("#popup").html('');
}