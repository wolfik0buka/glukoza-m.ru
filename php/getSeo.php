<?php
class seo {
    function getHtml () {
        global $cat, $page, $alias, $mysqli;
        $seo['h1'] = '';
        $seo['up_text'] = '';
        $seo['down_text'] = '';
        $seo['tit'] = '';
        $seo['description'] = '';
        $seo['keywords'] = '';
        switch ($page) {
            case ('cat'):
                if (!isset($_GET['idTov'])) {
                    $query = "select category.h1,
                                     category.up_text,
                                     category.down_text,
                                     category.keywords,
                                     category.description,
                                     category.tit
                              from category
                              where (category.id = ".$cat.") and
                                    (category.del<>1)";
                } else {
                    $query = "select tovar.name,
                                     tovar.seo_tit
                              from tovar
                              where tovar.id = ".$_GET["idTov"];
                }
            break;
            case ('stat'):
                $query = "select tit,
                                 keywords,
                                 description,
                                 h1,
                                 up_text
                          from static_pages
                          where alias = '".$alias."'";
            break;
            case ('events'):
                if (isset($_GET['idEvent'])) $query = "select tit from events where id = ".$_GET['idEvent']; else $seo['tit'] = 'Новости интернет-магазина Глюкоза';
            break;
            case ('article'):
                if(isset($_GET['id'])) $query = "select tit from articles where id = '".$_GET['id']."'";
                break;


        }
        if (($page != 'basket') && ($page != 'recovery') && ($page != 'events') && ($page != 'logout') && ($page != 'reg') && ($page != 'searchMyResult') && ($page != 'cabinet')) {
            $res = $mysqli->query($query);
            while ($row = $res->fetch_assoc()) {
                if (!isset($_GET['idTov'])) {
                    $seo['tit'] = $row['tit'];
                } else {
                    if ($row['seo_tit'] == '') $seo['tit'] = $row['name'] . ' купить'; else $seo['tit'] = $row['seo_tit'];
                }
                if (isset($row['h1']) && ($row['h1'] != '')) $seo['h1'] = '<h1>' . $row['h1'] . '</h1>';
                if (isset($row['up_text']) && ($row['up_text'] != '')) $seo['up_text'] = '<div class="seo_up_txt">' . $row['up_text'] . '</div>';
                if (isset($row['down_text']) && ($row['down_text'] != '')) $seo['down_text'] = '<div class="seo_down_txt">' . $row['down_text'] . '</div>';
                if (isset($row['description']) && ($row['description'] != '')) $seo['description'] = $row['description'];
                if (isset($row['keywords']) && ($row['keywords'] != '')) $seo['keywords'] = $row['keywords'];
            }
        }
        return $seo;
    }
}
?>