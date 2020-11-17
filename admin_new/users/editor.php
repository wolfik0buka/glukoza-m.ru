<?php namespace App;
require_once 'query.php';
require_once '../php/Controller.php';

class users
{
    public $html;
    public $id;
    public $current_page;

    function __construct()
    {
        $this->id = -1;
        $dop = '';
        if (isset($_GET['id'])) {
            $this->id = $_GET['id'];
            if ($this->id == 0) $dop = ' (добавление)';
            if (($this->id != 0) && ($this->id != -1)) $dop = ' (изменение)';
        }
        $this->dicQ = new query();
        $this->html = '<h2>Зарегистрированные пользователи</h2>
                       <div class="line_dot"></div>
                       <div class="privyazka">';
    }

    function users_list()
    {
        $this->current_page = 'users_list';
//        $search = '';
//        if (isset($_GET['search'])) $search = $_GET['search'];
        $this->html .= '<div class="search_panel">
                            <div class="oborot">
                                <span>Оборот:</span>
                                <i class="fa fa-sort-amount-desc"></i>
                                <i class="fa fa-sort-amount-asc"></i>
                                <span style="margin-left: 10px;">Поиск:</span>
                                <input type="text" id="search">
                                <a class="uni-button" onClick="user_list(\'desc\')">Найти</a>
                            </div>
                        </div>
                        <table class="panelTable users">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Фамилия, имя</th>
                                    <th>Оборот, руб</th>
                                    <th>Бонусы</th>
                                    <th>Телефон</th>
                                    <th>E-mail</th>
                                    <th>Операции</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7">
                                        <img style="display: block; margin: 50px auto;" src="/img/loading.gif">
                                    </td>
                                </tr>
                            </tbody>
                        </table>';
    }

    function users_forma()
    {
        $search_param = '';
        if (isset($_GET['search'])) $search_param = '&search=' . $_GET['search'];
        $this->html .= '<div class="prop">
                        <form id="tovarForma">
                        <!-- <div style="font-weight: bold; margin-bottom: 10px;">Артикул: ' . str_repeat('0', 5 - strlen($this->id)) . $this->id . '</div> -->
                        <div class="clear10"></div>
                        
                        <div class="name"><span>Наименование</span></div>
                        <div><textarea id="name" name="name" class="ru one_string">' . $this->name . '</textarea></div>
                        <div class="clear10"></div>

                        <div class="name"><span>Описание краткое</span></div>
                        <div><textarea id="description" name="description" class="ru many_string">' . $this->description . '</textarea></div>
                        <div class="clear10"></div>

                        <div class="name"><span>Описание полное</span></div>
                        <div><textarea id="desc_full" name="desc_full" class="ru many_string">' . $this->desc_full . '</textarea></div>
                        <div class="clear10"></div>

                        <div>Цена</div>
                        <div><textarea id="price" name="price" class="one_string">' . $this->price . '</textarea></div>
                        <div class="clear10"></div>

                        <div>Цена старая</div>
                        <div><textarea id="price_old" name="price_old" class="one_string">' . $this->price_old . '</textarea></div>
                        <div class="clear10"></div>
                        <input type="hidden" name="doIt" value="' . $this->doIt . '">
                    </form>
                    </div>
                    <br>
                    <div class="clear20"></div>
                    <a class="uni-button" onClick="saveTovar(' . $this->id . ')">Сохранить</a>
                    <div class="clear20"></div>
                    <a class="uni-button" href="/admin_new/index.php?page=nom&str=' . $this->id . $search_param . '">« Вернуться к списку товаров</a>
                    <script>
                        $("#desc_full").redactor();
                        $("#description").redactor();
                    </script>';
    }

    function nom_editor()
    {
        global $mysqli;
        if ($this->id != 0) {
            $res = $mysqli->query($this->dicQ->adm_nom_tovar($this->id));
            while ($row = $res->fetch_object()) {
                $this->doIt = 'saveTovar';
                $this->name = $row->name;
                $this->art = $row->art;
                $this->desc_full = $row->desc_full;
                $this->description = $row->description;
                $this->price = $row->price;
                $this->price_old = $row->price_old;
            }
        } else {
            $this->doIt = 'addTovar';
        }
        $this->html .= $this->photo_edit() . '<div class="cat"><div class="name">Привязка к разделам:</div>' . $this->adm_tree_cat($this->id, 1) . '</div>';
        $this->nom_forma();
    }

    function get_html()
    {
        if ($this->id == -1) {
            $this->users_list();
        } else {

        }
//        $this->html .= '</div>';
    }
}

?>