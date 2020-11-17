<?php namespace App;
require_once '../php/Controller.php';

class obmen extends Controller
{
    public $html;

    function obmen_list()
    {
        global $mysqli;

        $time_res = $mysqli->query('select max(updated_at) as last_time from 1c_amount');
        while($time_row = $time_res->fetch_object()) $last_time = strftime('%d.%m.%Y (%H:%M)', strtotime($time_row->last_time));


        $this->current_page = 'order_list';
        $table_head = '<table class="panelTableHead c1_tovar">
                            <thead>
                            <tr>
                                <td style="width: 11%">Арт. в 1С</td>
                                <td style="width: 33%">Товар в 1С</td>
                                <td style="width: 8%">Цена в 1С</td>
                                <td style="width: 11%">Арт. в Интернет-магазине</td>
                                <td style="width: 33%">Товар в Интернет-магазине</td>
                                <td style="width: 4%"></td>
                            </tr>
                            </thead>';
        $this->html .= '<h2>Связка с 1С</h2>
                        <div id="app">
                            <page-sklad lastupdate="'.$last_time.'"></page-sklad>
                        </div>
                        <h3>Непривязанные товары (отсортированы по названию)</h3>
                        '.$table_head;
        $cnt = 1;
        $sklad = '';
        $flag = '-1';
        $res = $mysqli->query('select 1c_tovar.id,
                                      1c_tovar.name,
                                      1c_tovar.price,
                                      1c_tovar.id_tovar,
                                      tovar.name as magaz,
                                      1c_amount.amount,
                                      1с_sklad_list.name as sklad_name
                               from 1c_tovar
                               left join tovar on (tovar.id = 1c_tovar.id_tovar)
                               left join 1c_amount on (1c_amount.id_tovar = 1c_tovar.id)
                               left join 1с_sklad_list on (1с_sklad_list.id = 1c_amount.id_sklad)
                               where (1c_tovar.arc = 0)
                               order by 1c_tovar.id_tovar,
                                        1c_tovar.name');

        $res_popup = $mysqli->query('select id, name
                                     from tovar
                                     where del = 0
                                     order by name');
        $cur_id = -1;
        $cap_do = '';
        $cap_sklad = '';
        $cap_posle = '';
        $cap_button = '';
        $cap_close = '';
        $cnt = 0;
        while ($row = $res->fetch_object()) {
            $cap_sklad .= $row->sklad_name . ': ' . $row->amount . ' шт<br>';
            $cap_do = '<tr>
                           <td>' . $row->id . '</td>
                           <td>' . $row->name . '</td>';
            $cap_posle = '<td>' . number_format(($row->price), 2, ',', ' ') . '</td>';
            if ($row->id_tovar == null) {
                $cap_button = '<td></td>
                               <td class="options">
                                   <span class="pri" data="' . $row->id . '" data-name="' . htmlspecialchars($row->name) . '">Привязать</span>
                                   <span class="to_arc" data="' . $row->id . '" data-name="' . htmlspecialchars($row->name) . '">Убрать в архив</span>
                               </td>
                               <td></td>';
            } else {
                if ($flag == -1) {
                    $this->html .= '</table><h3>Привязанные товары (отсортированы по артикулу интернет-магазина)</h3>'.$table_head;
                    $flag = 0;
                }
                $cap_button = '<td>' . str_repeat('0', 5 - strlen($row->id_tovar)) . $row->id_tovar . '</td>
                               <td>
                                   <span class="pri" data="' . $row->id . '" data-name="' . htmlspecialchars($row->name) . '">' . $row->magaz . '</span>
                               </td>
                               <td>
                                   <i data="' . $row->id . '" class="fa fa-times-circle-o unmount" aria-hidden="true"></i>
                               </td>';
            }
            $cap_close = '</tr>';
            if ($cur_id != $row->id) {
                $this->html .= $cap_do . $cap_posle . $cap_button . $cap_close;
                $cur_id = $row->id;
                $cap_sklad = '';
                $cnt++;
            }
        }
        $this->html .= '</table>
             <div class="white-popup mfp-hide pop">
                 <div class="white-popup__title" id="colontitul"></div>
                 <input id="cur_1c" type="hidden">
                 <table id="tovar_popup">
                 <thead>
                            <tr>
                                <td style="width: 15%">№</td>
                                <td style="width: 60%">Наименование</td>
                                <td style="width: 25%"></td>
                            </tr>
                            </thead>';
        $cnt_popup = 1;
        while ($row_popup = $res_popup->fetch_object()) {
            $this->html .= '<tr>
                                <td>' . $cnt_popup . '</td>
                                <td>' . $row_popup->name . '</td>
                                <td>
                                    <button onClick="priv(' . $row_popup->id . ')">Привязать</button>
                                </td>
                            </tr>';
            $cnt_popup++;
        }
        $this->html .= '</table>

                     <div class="white-popup__buttons">
                     <a class="blue" onclick="$.magnificPopup.close()">Закрыть</a>
                 </div>
             </div>
             <script>
                function to_arc(id) {
                    $.ajax({
                        url: "/admin_new/obmen/db.php",
                        type: "post",
                        data: {
                           id: id,
                           doIt: "to_arc"
                        },
                        success: function(xml) {
                            alert($("head", xml).text());
                            window.location.reload();
                        }
                    });
                }

                function priv(id) {
                    $.ajax({
                        url: "/admin_new/obmen/db.php",
                        type: "post",
                        data: {
                           id: id,
                           cur_1c: $("input#cur_1c").val(),
                           doIt: "priv"
                        },
                        success: function(xml) {
                            alert($("head", xml).text());
                            window.location.href = "/admin_new/index.php?page=obmen";
                        }
                    });
                }

                function unmount(id) {
                    $.ajax({
                        url: "/admin_new/obmen/db.php",
                        type: "post",
                        data: {
                           id: id,
                           doIt: "unmount"
                        },
                        success: function(xml) {
                            alert($("head", xml).text());
                            window.location.href = "/admin_new/index.php?page=obmen";
                        }
                    });
                }

                $(document).ready(function () {
                    $("i.unmount").click(function(obj) {
                        unmount($(this).attr("data"));
                    });
                    $("span.to_arc").click(function(obj) {
                        to_arc($(this).attr("data"));
                    });
                    $("span.pri").magnificPopup({
                        callbacks: {
                            open: function() {
                                tovarname = $(this.st.el).attr("data-name");
                                $("input#cur_1c").val($(this.st.el).attr("data"));
                                $("#colontitul").html(tovarname);
//                                alert($(this.st.el).attr("data"));
                            },
                            close: function() {
                                // Will fire when popup is closed
                            }
                                // e.t.c.
                        },
                        items: {
                            src: "div.pop",
                            type: "inline"
                        },
                        fixedContentPos: false,
                        fixedBgPos: true,
                        closeBtnInside: true,
                        midClick: true,
                        removalDelay: 300,
                        mainClass: "mfp-fade"
                    });
                    $("table#tovar_popup").DataTable({
                        paging: true,
                        pageLength: 50,
                        stateSave: true
                    });
                }); // end document_ready
            </script>';
    }

    function get_html()
    {
        $this->obmen_list();
    }
}