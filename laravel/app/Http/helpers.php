<?php



/**
 * Форматирование телефонного номера по шаблону и маске для замены
 * @return string
 */
function phone_format($phone) {
    $phone = str_replace(['+', '-', '‒', '(', ')', ' '], "", trim($phone));

    if (substr($phone, 0,1) === "8" && substr($phone, 1,1) === "9") {
        $phone = "7".substr($phone, 1);
    }

    if (mb_strlen($phone) === 10 && substr($phone, 0,1) === "9") {
        $phone = "7".$phone;
    }

    return $phone;
}


/**
 * @param int $cat_id
 * @param int $product_id
 * @return string
 */
function link_product($cat_id, $product_id)
{
    return '/index.php?page=cat&cat='.$cat_id.'&idTov='.$product_id;
}


/**
 * Create link to category page
 *
 * @param int $cat_id
 * @return string
 */
function link_cat($cat_id)
{
    return '/index.php?page=cat&cat='.$cat_id;
}


/**
 * Create link to static page
 *
 * @param string $alias
 * @return string
 */
function link_static($alias)
{
    return '/index.php?page=stat&alias='.$alias;
}


/**
 * Formatting full price (with kopecks)
 * @param int $price
 * @return string
 */
function priceFull($price)
{
    return number_format($price, '2', ',', ' ');
}


/**
 * Число прописью
 * @param int $price
 * @return string
 */
function priceWordString($price)
{
    $price = explode('.', $price)[0];

    $m = [ // Все варианты написания чисел прописью от 0 до 999 скомпануем в один небольшой массив
        ['ноль'],
        ['-', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'],
        ['-', '-', 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'],
        ['-', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'],
        ['-', 'одна', 'две']
    ];

    // Все варианты написания разрядов прописью скомпануем в один небольшой массив
    $r=array(
        array('...ллион','','а','ов'),
        array('тысяч','а','и',''),
        array('миллион','','а','ов'),
        array('миллиард','','а','ов'),
        array('триллион','','а','ов'),
        array('квадриллион','','а','ов'),
        array('квинтиллион','','а','ов')
    );

    if($price==0)return$m[0][0]; # Если число ноль, сразу сообщить об этом и выйти
    $o=array(); # Сюда записываем все получаемые результаты преобразования

    // Разложим исходное число на несколько трехзначных чисел и каждое полученное такое число обработаем отдельно
    foreach(array_reverse(str_split(str_pad($price,ceil(strlen($price)/3)*3,'0',STR_PAD_LEFT),3)) as $k => $p)
    {
        $o[$k]=array();

        // Алгоритм, преобразующий трехзначное число в строку прописью
        foreach ($n = str_split($p) as $kk => $pp)
            if (!$pp) continue; else
                switch($kk){
                    case 0:
                        $o[$k][]=$m[4][$pp];
                    break;
                    case 1:if($pp==1){$o[$k][]=$m[2][$n[2]];break 2;}else$o[$k][]=$m[3][$pp];break;
                    case 2:if(($k==1)&&($pp<=2))$o[$k][]=$m[5][$pp];else$o[$k][]=$m[1][$pp];break;
                }$p*=1;if(!$r[$k])$r[$k]=reset($r);

        // Алгоритм, добавляющий разряд, учитывающий окончание руского языка
        if($p&&$k)switch(true){
            case preg_match("/^[1]$|^\\d*[0,2-9][1]$/",$p):$o[$k][]=$r[$k][0].$r[$k][1];break;
            case preg_match("/^[2-4]$|\\d*[0,2-9][2-4]$/",$p):$o[$k][]=$r[$k][0].$r[$k][2];break;
            default:$o[$k][]=$r[$k][0].$r[$k][3];break;
        }$o[$k]=implode(' ',$o[$k]);
    }

    return implode(' ',array_reverse($o));
}


/**
 * Get settings element by id
 * @param string $id
 * @return
 */
function getSettings($id)
{
    $temp = config('settings')->find($id);
    if ($temp) {
        return config('settings')->find($id)->value;
    }
    
}