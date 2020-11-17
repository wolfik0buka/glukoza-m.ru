<?php namespace App;
require_once '../php/Controller.php';
require_once 'query.php';
require_once 'function.php';

class arc
{
    var $id,
        $art,
        $name,
        $desc_full,
        $description,
        $price,
        $price_old,
        $html,
        $dicQ;
    var $doIt;            // Действие
    var $current_page;    // Идентификатор текущей страницы

    function __construct()
    {
        $this->id = -1;
        if (isset($_GET['id'])) {
            $this->id = $_GET['id'];
            if ($this->id == 0) $dop = ' (добавление)';
            if (($this->id != 0) && ($this->id != -1)) $dop = ' (изменение)';
        }
        $this->dicQ = new query();
    }

    function status_list($id)
    {
        global $mysqli;
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_status_list());
        $html = '<select id="status_id" name="status_id" onChange="change_status(' . $this->id . ', this.value)">';
        while ($row = $res->fetch_object()) {
            $selected = '';
            if ($id == $row->id) {
                $selected = ' selected';
            }
            $html .= '<option value="' . $row->id . '"' . $selected . '>' . $row->name . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    public function interval_list($id)
    {
        $html = '<select name="status_id" onChange="change_interval(' . $this->id . ', this.value)">';
        $selected = '';
        if ($id == 'Доставка до 16:00') $selected = ' selected';
        $html .= '<option value="' . urlencode('Доставка до 16:00') . '"' . $selected . '>Доставка до 16:00</option>';
        $selected = '';
        if ($id == 'Доставка после 16:00') $selected = ' selected';
        $html .= '<option value="' . urlencode('Доставка после 16:00') . '"' . $selected . '>Доставка после 16:00</option>';
        $html .= '</select>';
        return $html;
    }

    public function order_list()
    {
        $this->current_page = 'arc';
        $search = '';
        $client_id = '-1';
        if (isset($_GET['search'])) $search = $_GET['search'];
        if (isset($_GET['client_id'])) $client_id = $_GET['client_id'];
        $this->html .= '<h2>Архив заказов</h2>
                        <div class="search_client">
                            Клиент:
                            <input style="height: 22px;" type="text" id="search" value="' . $search . '">
                            <input type="hidden" id="client_id" value="' . $client_id . '">
                            <a class="uni-button" onClick="search_in_order()">Найти</a>
                        </div>
                        <div class="line_dot"></div>
                        <div class="privyazka">
                            <div id="order_table">
                                <table id="order_head" class="panelTableHead order_line">
                                    <tr>
                                        <td>№</td>
                                        <td>Дата</td>
                                        <td>Клиент</td>
                                        <td>Способ<br>получения</td>
                                        <td>Согласовано</td>
                                        <td>Отправка</td>
                                        <td>Сумма, руб</td>
                                        <td>Оплата</td>
                                        <td>Операции</td>
                                        <td>Комментарий</td>
                                    </tr>
                                </table>
                            </div>
                            <div id="order_table_body">
                                <img style="display: block; margin: 50px auto;" src="/img/loading.gif">
                            </div>
                        </div>';
    }

    public function order_single()
    {
        global $mysqli;
        $this->current_page = 'order_single';
        $cnt = 1;
        $client = '';
        if ((isset($_GET['client_id'])) && ($_GET['client_id'] != -1)) $client = '&client_id=' . $_GET['client_id'];
        $t_header = '<table class="panelTable">
                         <thead>
                             <tr>
                                 <th style="width: 50px;">№</th>
                                 <th style="width: 100px;">Артикул</th>
                                 <th>Наименование</th>
                                 <th style="width: 80px;">Кол-во, шт</th>
                                 <th style="width: 110px;">Цена, руб</th>
                                 <th style="width: 90px;">Сумма, руб</th>
                                 <th style="width: 50px;"></th>
                             </tr>
                         </thead>';
        $res = $mysqli->query($this->dicQ->adm_order_single($this->id));
        $sum_all = 0;
        $sum_item = 0;
        $comment = '';
        $dost = '';
        $date_of_delivery = '';
        $time_intervals = '';
        $date_of_cur = '';
        $invoice_status = '';
        $post_id = '';
        while ($row = $res->fetch_object()) {
            $dost_id = $row->delivery_id;
            $post_id = $row->post_id;
            $bonus = $row->bonus;
            if ($row->send_invoice == 1) $invoice_status = 'Отправлен';
            $order_date = strftime('%d.%m.%Y %H:%M', strtotime($row->date_order));
            if ($row->date_of_cur != 0) $date_of_cur = strftime('%d.%m.%Y', $row->date_of_cur);
            if ($row->time_intervals != '') {
                $time_intervals = '<tr>
                                       <td>Временной интервал</td>
                                       <td>' . $this->interval_list($row->time_intervals) . '</td>
                                   </tr>';
            }
            $status = $row->status;
            $comment_my = $row->comment_my;
            $sum_item = $row->amount * $row->price;
            $sum_all = $sum_all + $sum_item;
            $art = '';
            $chk_pay = ' ';
            $pay = 0;
            if ($row->pay == 1) {
                $chk_pay = ' checked ';
                $pay = 1;
            }
            $chk_agreed = ' ';
            $agreed = 0;
            if ($row->agreed == 1) {
                $chk_agreed = ' checked ';
                $agreed = 1;
            }
            if ($row->id_tovar != 71) {
                $art = str_repeat('0', 5 - strlen($row->id_tovar)) . $row->id_tovar;
                $disabled = 'disabled: false';
            } else {
                $disabled = 'disabled: true';
            }
            $t_header .= '<tr>
	                          <td>' . $cnt . '</td>
	                          <td>' . $art . '</td>
	                          <td>' . $row->tovar . '</td>
	                          <td><input type="text" class="spin" id="amount_' . $row->order_item . '" value="' . $row->amount . '"></td>
	                          <td class="td_price" style="text-align: right;">
	                              <span>' . number_format($row->price, 2, ',', ' ') . '</span>&nbsp;&nbsp;
	                              <i class="fa fa-pencil" order_item="' . $row->order_item . '"></i>
	                          </td>
	                          <td style="text-align: right;">' . number_format($sum_item, 2, ',', ' ') . '</td>
	                          <td><i onClick="del_order_item(' . $this->id . ', ' . $row->order_item . ')" class="red fa fa-trash-o"></i></td>
	                      </tr>
	                      <script>
	                          $("input#amount_' . $row->order_item . '").spinner({
                                  min: 1,
                                  stop: function(event, ui) {
                                      change_amount(' . $row->order_item . ', this.value , ' . $this->id . ');
                                  },
                                  ' . $disabled . '
                              });
                          </script>';
            $fio = $row->fio;
            $phone = $row->phone;
            $email = $row->email;
            if ($row->comment != '') $comment = '<td>Комментарий:</td>
                                                 <td id="comment">' . $row->comment . '</td>
                                                 <td id="comment_but"><i class="fa fa-pencil" onClick="edit_fld(\'comment\')"></i></td>';
            $dost = get_delivery($row);
            $number = str_repeat('0', 5 - strlen($row->number)) . $row->number . '\\' . strftime('%y', strtotime($row->date_order));
            $cnt++;
        }
        $t_header .= '<tr>
                          <td colspan="2"></td>
                          <td>
                              <a class="uni-button" onClick="open_add_to_order(' . $_GET['id'] . ')">
                                  <i class="fa fa-plus-circle"></i>&nbsp Добавить товар
                              </a>
                          </td>
                          <td colspan="2" style="text-align: right"><b>Итого:</b></td>
                          <td style="text-align: right;"><b>' . number_format($sum_all, 2, ',', ' ') . '</b></td>
                          <td></td>
                      </tr>
                      <tr>
                          <td colspan="5" style="text-align: right"><b>Использовано бонусов:</b></td>
                          <td style="text-align: right;"><b>' . number_format($bonus, 2, ',', ' ') . '</b></td>
                          <td></td>
                      </tr>
                      <tr>
                          <td colspan="5" style="text-align: right"><b>К оплате:</b></td>
                          <td style="text-align: right;"><b>' . number_format(($sum_all - $bonus), 2, ',', ' ') . '</b></td>
                          <td></td>
                      </tr>
                      <script>
                      function edit_price(obj) {
                          order_item = $(obj).attr("order_item");
                          price = $(obj).parent().children("span").text();
                          price = price.replace(" ","");
                          $(obj).parent().children("span").remove();
                          $(obj).parent().prepend("<input class=\'price_edit\' value=\'" + price + "\'>");
                          $(obj).removeClass("fa-pencil").addClass("fa-floppy-o");
                          $(obj).unbind("click");
                          $(obj).click(function(){
                              price_new = $(obj).parent().children("input").val();
                              order = $("#current_order").val();
                              $.ajax({
                                  type: "POST",
                                   url: "/admin_new/order/db.php",
                                  data: {
                                      doIt: "save_new_price",
                                      price: price_new,
                                      id: order_item
                                  },
                               dataType: "xml",
                                  async: false,
                                  success: function (xml) {

                                         $.ajax({
                                             type: "POST",
                                             url: "/admin_new/order/db.php",
                                             data: {
                                               doIt: "pereschet",
                                               order: order
                                             }
                                        , dataType: "xml"
                                        , async: false
                                       , success: function (xml) {
                                             order = $("head", xml).text();
                                             $(".panelTable tbody").html(order);
                                             $(".panelTable tr td.td_price i").click(function(){
                                                 edit_price(this);
                                            });
                                         }
                                       , error: function (json) {
                                           console.log(json);
                                         }
                                         });

                              },
                                error: function (json) {
                                   console.log(json);
                                }
                            });
                        });
                      }
                      $(".panelTable tr td.td_price i").click(function(){
                          edit_price(this);
                      });
                      </script>';
        $this->html .= '<h2>Заказ №' . $number . ' (' . $_GET['id'] . ') от ' . $order_date . '</h2>' . $t_header . '</table><br>
                        <input type="hidden" id="current_order" value="' . $_GET['id'] . '">
                        <table class="order_info">
                            <tr>
                                <td>Заказчик:</td>
                                <td id="fio">' . $fio . '</td>
                                <td id="fio_but"><i class="fa fa-pencil" onClick="edit_fld(\'fio\')"></i></td>
                            </tr>
                            <tr>
                                <td>Телефон:</td>
                                <td id="phone">' . $phone . '</td>
                                <td id="phone_but"><i class="fa fa-pencil" onClick="edit_fld(\'phone\')"></i></td>
                            </tr>
                            <tr>
                                <td>E-mail:</td>
                                <td id="email">' . $email . '</td>
                                <td id="email_but"><i class="fa fa-pencil" onClick="edit_fld(\'email\')"></i></td>
                            </tr>
                            ' . $dost . '
                            <tr>' . $comment . '</tr>
                        </table>
                        <table class="order_func">
                            ' . $date_of_delivery . $time_intervals . '
                            <tr>
                                <td style="width: 240px;">Cогласовано с покупателем?</td>
                                <td>
                                    <input id="agreed" type="checkbox"' . $chk_agreed . 'onClick="change_flag(\'agreed\',' . $_GET['id'] . ',this.value)" value="' . $agreed . '">
                                </td>
                            </tr>
                            <tr>
                                <td>Выгрузка курьерам</td>
                                <td><i class="fa fa-truck" onClick="in_work()"></i></td>
                            </tr>
                            <tr>
                                <td>Товарный чек</td>
                                <td>
                                    <a target="_blank" href="/admin_new/order/cash-memo.php?order=' . $_GET['id'] . '">
                                        <i class="fa fa-file-text-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Счет на оплату</td>
                                <td>
                                    <a target="_blank" href="/admin_new/order/invoice.php?order=' . $_GET['id'] . '">
                                        <i class="fa fa-file-text-o"></i>
                                    </a>
                                    <a onClick="send_invoice(' . $_GET['id'] . ')">
                                        <i class="fa fa-envelope-o"></i>
                                    </a>
                                    <span id="invoice_status" style="padding-left: 10px">' . $invoice_status . '</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Статус оплаты</td>
                                <td>
                                    <input id="pay" type="checkbox"' . $chk_pay . 'onClick="change_flag(\'pay\',' . $_GET['id'] . ',this.value)" value="' . $pay . '">
                                </td>
                            </tr>
                            <tr>
                                <td>Статус отправки</td>
                                <td>' . $this->status_list($status) . '<span id="date_of_cur">' . $date_of_cur . '</span></td>
                            </tr>';
        if ($dost_id == 4) $this->html .= '<tr>
                                                   <td>Почтовый идентификатор</td>
                                                   <td>
                                                       <input type="text" name="post_id" id="post_id" value="' . $post_id . '">
                                                       <i onclick="add_post_id(' . $_GET['id'] . ')" class="fa fa-envelope-o"></i>
                                                   </td>
                                               </tr>';
        $this->html .= '<tr>
                                <td colspan="2">
                                    <textarea onBlur="save_comment_my(' . $_GET['id'] . ')" id="order_comment_my" style="font-family: Helvetica;" placeholder="Введите комментарий...">' . $comment_my . '</textarea>
                                </td>
                            </tr>
                        </table>
                        <div class="clear20"></div>
                        <a class="uni-button" href="index.php?page=order&str=' . $_GET['id'] . $client . '">« Вернуться к списку заказов</a>';
    }

    function get_html()
    {
        if ($this->id == -1) {
            $this->order_list();
        } else {
            $this->order_single();
        }
    }
}

?>