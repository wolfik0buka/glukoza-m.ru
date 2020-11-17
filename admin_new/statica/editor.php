<?php namespace App;

include 'query.php';

class statica
{
    var $id, $name, $content, $alias, $link, $html, $dicQ, $doIt;
    var $current_page;    // Идентификатор текущей страницы

    function __construct()
    {
        $this->id = -1;
        if (isset($_GET['id'])) $this->id = $_GET['id'];
        $lng = '';
        if (isset($_GET['lng'])) $lng = ' - ' . $_GET['lng'];
        $this->dicQ = new query();
        $this->html = '<h2>Статические страницы ' . $lng . '</h2><div class="line_dot"></div>';
    }

    function statica_list()
    {
        global $mysqli;
        $res = $mysqli->query($this->dicQ->adm_statica(-1));
        $cnt = 1;
        $this->html .= '<table class="panelTable">
                            <tr>
                                <th style="width: 60px;">№</th>
                                <th>Название</th>
                                <th style="width: 180px">Операции</th>
                            </tr>';
        while ($row = $res->fetch_object()) {
            $this->html .= '<tr>
                                <td style="text-align: right;">' . $cnt . '</td>
                                <td>' . $row->title . '</td>
                                <td style="text-align: right">
                                    <a class="uni-button" href="/admin_new/index.php?page=statica&id=' . $row->id . '">Свойства</a>
                                </td>
                            </tr>';
            $cnt++;
        }
        $this->html .= '</table>';
    }

    function statica_forma()
    {
        $this->html .= '<form id="statForma">
                            <div class="name"><span>Название</span></div>
                            <div><textarea id="statName" name="statName" class="one_string">' . $this->title . '</textarea></div>
                            <div class="clear20"></div>
                            <div class="name"><span>Содержание</span></div>
                            <div><textarea style="width: 100%; height: 400px;" id="stat" name="stat">' . $this->content . '</textarea></div>
                            <input type="hidden" id="doIt" name="doIt" value="' . $this->doIt . '">
                            <input type="hidden" id="statId" name="statId" value="' . $this->id . '">
                        </form>
                        <div class="clear20"></div>
                        <a class="uni-button" onClick="save(' . $this->id . ')">Сохранить</a>
                        <div class="clear20"></div>
                        <a class="uni-button" href="/admin_new/index.php?page=statica">« Вернуться к списку страниц</a>
                        <script>
                            $("textarea#stat").redactor();
                        </script>';
    }

    function statica_editor()
    {
        global $mysqli;
        if ($this->id == 0) {
            $this->doIt = 'add';
            $this->title = '';
            $this->content = '';
        } else {
            $this->doIt = 'edit';
            $res = $mysqli->query($this->dicQ->adm_statica($this->id));
            while ($row = $res->fetch_object()) {
                $this->title = $row->title;
                $this->content = $row->content;
            }
        }
        $this->statica_forma();
    }

    function get_html()
    {
        if ($this->id == -1) $this->statica_list(); else $this->statica_editor();
    }
}

?>