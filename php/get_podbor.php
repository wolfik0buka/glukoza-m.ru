<?php
    header('Content-Type: text/xml; charset=utf-8');
function getBLOB($fld) {
    $txt = '';
    if ($fld) {
        $txtData = ibase_blob_info($fld);
        $hndlText = ibase_blob_open($fld);
        $txt = ibase_blob_get($hndlText, $txtData[0]);
        ibase_blob_close($hndlText);
    }
    return $txt;
}    
    include 'ibaseconnect.php';
    global $dbh;
    $txt = 'Ошибка';
    $linker = ''; $filter = '';
    foreach ($_POST as $key => $value) {
        $type = explode('_', $key);
        $linker .= ' inner join link_prop as link_'.$type[1].' on (link_'.$type[1].'.tovar = tovar.id)
                     inner join prop_value as '.$key.' on ('.$key.'.id = link_'.$type[1].'.prop and '.$key.'.type = '.$type[1].') ';
        $filter .= ' and ('.$key.'.id = '.$value.') ';
    }    

$query = 'select distinct tovar.id,
                 tovar.name,
                 tovar.description,
                 tovar.price,
                 tovar.art,
                 tovar.sale,
                 tovar.balance,
                 tovar.hit,
                 tovar.podzakaz,
                 category.id as idCat,
                 tovar.price_old
          from tovar
          left join link_tovar on (tovar.id = link_tovar.id_tovar)
          left join category on (link_tovar.id_cat = category.id)
          
          '.$linker.'
          where (tovar.del = 0) and
                (link_tovar.del <> 1) and
                (category.id = 2)'.$filter;
        $res = ibase_query($dbh, $query);
        while ($row = ibase_fetch_assoc($res)) {
            $hitClass = '';
            $disabled = ' ';
            $circle = 'green';
            if ($row["PODZAKAZ"] == 1) {
                $disabled = ' disabled ';
                $circle = 'red';
            }
            $imgSrc = '../img/catalog/pic_'.$row['ID'].'.jpg';
            if(!file_exists($imgSrc)) $imgSrc = '../img/catalog/nophoto.jpg';
            $old = '';
            $price = '<span>'.number_format($row['PRICE'], 2, '-', ' ').'</span>';
            if ($row['SALE'] == 1) $price = '<span style="color: red">'.number_format($row['PRICE'], 2, '-', ' ').'</span>';
            if ($row['HIT'] == 1) $hitClass = '<img style="position: absolute; top: 0; left: 0" src="img/hit.png">';
            if ($row['PRICE'] == 0) $price = 'call';
            if ($row['PRICE_OLD'] != 0) {
                $old = '<div style="float: left;" class="price_old"><span>'.number_format($row['PRICE_OLD'], 0, '-', ' ').'<span> руб</div>';
                //$bld = 'style="font-weight: bold';
            }
            $html .= '<table class="tableProduct">
                          <tr>
                              <td class="tdImg">'.$hitClass.'<img src="'.$imgSrc.'" alt="'.str_replace('"', '', $row['NAME']).'"></td>
                              <td class="tdDesc">
                                  <a id="tovarName'.$row['ID'].'" class="productName" href="index.php?page=cat&amp;cat='.$row['IDCAT'].'&amp;idTov='.$row['ID'].'">'.$row['NAME'].'</a>
                                  <div class="desc">'.getBLOB($row['DESCRIPTION']).'</div>
                                  <button onClick=window.open("index.php?page=cat&amp;cat='.$row['IDCAT'].'&amp;idTov='.$row['ID'].'","_self")>Подробнее</button>
                                  <button'.$disabled.'onClick="addToBasket('.$row['ID'].','.$row['PRICE'].')">В корзину</button>
                                  <div style="width: 100%; clear: both;"></div>
                                  <div style="background-color: '.$circle.'" class="circle"></div>
                                  <div style="float: left;" class="price">Цена: <span>'.$price.'</span> руб</div>'.$old.'
                              </td>
                          </tr>
                      </table>';
        }
    echo '<?xml version="1.0" encoding="utf-8"?>
          <head><![CDATA['.$html.']]></head>';
    ibase_close($dbh);
?>