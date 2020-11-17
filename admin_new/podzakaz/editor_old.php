<?php namespace App;
require_once '../php/Controller.php';
require_once 'query.php';

class nom extends Controller
{
    var $id,
        $art,
        $name,
        $desc_full,
        $description,
        $price,
        $price_old,
        $html,
        $dicQ,
        $video_cons,
        $video_models;
    var $doIt;                // Действие
    var $current_page;        // Идентификатор текущей страницы
    var $current_str = 0;     // Идентификатор текущей строки

    function __construct()
    {
        $this->blade = $this->blade_init();
        $this->id = -1;
        $dop = '';
        if (isset($_GET['id'])) {
            $this->id = $_GET['id'];
            if ($this->id == 0) $dop = ' (добавление)';
            if (($this->id != 0) && ($this->id != -1)) $dop = ' (изменение)';
        }
        $this->dicQ = new query();
        $this->html = '<h2>Номенклатура' . $dop . '</h2>
                       <div class="line_dot"></div>
                       <div class="privyazka">';
    }

    function nom_list()
    {
        $this->current_page = 'nom_list';
        $this->html .= '
            <div class="row">
                <div class="col-xs-12">
                    <div class="btn-group">
                        <a class="btn btn-default" href="/admin_new/index.php?page=nom&id=0">Новый товар</a>
                    </div>
                </div>
            </div>
            <div class="clear20"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel">
                        <div class="clear10"></div>
                        <table id="nom" class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px">Артикул</th>
                                    <th>Фото</th>
                                    <th>Название</th>
                                    <th style="width: 70px">Цена</th>
                                    <th>Sale</th>
                                    <th>Hit</th>
                                    <th>Ждем</th>
                                    <th style="width:100px;">Операции</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . $this->get_tovar_list() . '
                            </tbody>
                        </table>
                        <div class="clear20"></div>
                    </div>
                </div>
            </div>';
    }

    function nom_forma()
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
                    <!-- <a class="uni-button" href="/admin_new/index.php?page=nom&str=' . $this->id . $search_param . '">« Вернуться к списку товаров</a> -->
                    <a class="uni-button" onclick="history.back()">« Вернуться к списку товаров</a>
                    <script>
                        $("#desc_full").redactor();
                        $("#description").redactor();
                    </script>';
    }

    /* Вывод дерева каталога для привязки товаров - начало */
    function adm_tree_cat($tovar, $parentId)
    {
        global $htmlTreeCat, $mysqli;
        $res = $mysqli->query($this->dicQ->adm_cat_link($tovar, $parentId));
        $cnt = 0;
        while ($row = $res->fetch_object()) {
            //$this->current_tovar_name = $row->tovar_name;
            $checked = '';
            $doIt = 1;
            if ($tovar == -1) {
                $func = ' disabled';
            } else {
                if ($row->id_tovar == $tovar) {
                    $checked = 'checked';
                    $doIt = 0;
                }
                $func = ' onClick="cat_linker(' . $tovar . ',' . $row->id . ',' . $doIt . ')" ' . $checked;
            }
            if ($cnt == 0) $htmlTreeCat .= '<ul>';
            $htmlTreeCat .= '<li><input type="checkbox" id="type' . $row->id . '"' . $func . '>' . $row->name . '</li>';
            /*
            if ($parentId != 1) $htmlTreeCat .= '<li><input type="checkbox" id="type'.$row->id.'"'.$func.'>'.$row->name_ru.'</li>';
            else $htmlTreeCat .= '<li>'.$row->name_ru.'</li>';
            */
            $cnt++;
            $this->adm_tree_cat($tovar, $row->id);
        }
        if ($cnt != 0) $htmlTreeCat .= '</ul>';
        $res->close();
        return $htmlTreeCat;
    }
    /* Вывод дерева каталога для привязки товаров - конец */

    public function nom_editor()
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

    public function get_tovar_list()
    {
        global $mysqli;
        $dicQ = new query();
        $res = $mysqli->query($dicQ->adm_nom_list());
        $txt = '';
        while ($row = $res->fetch_object()) {
            $sale = '';     if ($row->sale == 1) $sale = 'checked';
            $hit = '';      if ($row->hit == 1) $hit = 'checked';
            $podzakaz = ''; if ($row->podzakaz == 1) $podzakaz = 'checked';
            $data = [
                'result' => $row,
                'sale' => $sale,
                'hit' => $hit,
                'podzakaz' => $podzakaz
            ];
            $txt .= $this->blade->render('admin.nom.tovar_in_nom', $data);
        }
        $txt .= '<script>
                     $("td.price").click(function() {
                         edit_price_in_list(this);
                     });
                 </script>';
        return $txt;
    }

    function photo_edit()
    {
        global $mysqli;
        if ($this->id != 0) {
            $send_file = '<form id="addPhoto" target="trash" name="addPhoto" method="POST" action="/admin_new/nom/addPhotoGallery.php?id=' . $_GET['id'] . '" enctype="multipart/form-data">
                          <table width="40%" border="0">
                              <tr><td>Новое фото: <input id="x" type="file" size="70" name="x" onChange=addPhoto.submit()></td></tr>
                          </table>
                      </form>';
            $forma = new photo();
            $res = $mysqli->query($this->dicQ->adm_photo($_GET['id']));
            $cnt = 1;
            $photo_list = '';
            while ($row = $res->fetch_object()) {
                if ($cnt == 1) $name_tovar = '<div style="font-weight: bold; margin-bottom: 10px;">' . $row->name . ' (артикул: ' . str_repeat('0', 5 - strlen($row->id)) . $row->id . ')</div>';
                $forma->id = $_GET['id'];
                $forma->photo = $row->id;
                $photo_list .= $forma->photoForma();
                $cnt++;
            }
            return $name_tovar . $send_file . $photo_list . '<div class="clear20"></div>';
        } else {
            return '<div style="display: block; float: left;">Для загрузки фото товар необходимо сохранить</div><div class="clear20"></div>';
        }
    }


    function get_html()
    {
        if (($this->id == -1) && (!isset($_GET['photo'])) && (!isset($_GET['dop']))) {
            $this->nom_list();
        } else {
            if ((!isset($_GET['photo'])) && (!isset($_GET['dop']))) {
                $this->nom_editor();
            }
        }
        $this->html .= '</div>';
    }
}

class photo
{
    var $id, $photo, $ext, $basic;

    function photo()
    {
    }

    function photoForma()
    {
        $classPhoto = 'no_basic_photo';
        if ($this->basic == 1) $classPhoto = 'basic_photo';
        return '<div class="photo_box" id="photo' . $this->photo . '">
                    <img id="photo_in' . $this->photo . '" class="' . $classPhoto . '" src="/img/resize.php?src=/img/catalog/pic_' . $this->photo . '.jpg&rnd=' . rand(100, 999) . '&q=100&w=180&h=180">
                    <div style="display: block; height: 5px;"></div>
                    <!-- <a class="uni-button" onClick="delPhoto(' . $this->id . ',' . $this->photo . ')">Удалить</a> -->
                </div>';
    }
}

?>