<?php namespace App;

class query
{
    function query()
    {
    }

    function order($idOrder, $start, $stop)
    {
        $start_db = date('Y-m-d ', strtotime($start));
        $stop_db = date('Y-m-d', strtotime($stop));
        return "select order_shop.id,
                       order_shop.date_order,
                       order_shop.fio,
                       order_shop.phone
                from order_shop
                where (order_shop.del = 0) and
                      (date_order <= '" . $stop_db . " 23:59:59') and
                      (date_order >= '" . $start_db . " 00:00:00')
                order by order_shop.date_order desc";
    }

    function getChild($parent)
    {
        return 'select category.id,
                       category.name,
                       category.parent
                from category
                where (category.parent = ' . $parent . ') and
                      (category.del = 0)
                order by category.order_fld';
    }

    function tovarInCat_search($idCat, $word)
    {
        return 'select tovar.id,
                       tovar.name,
                       tovar.description,
                       tovar.price,
                       tovar.price_old,
                       tovar.art,
                       tovar.sale,
                       tovar.balance,
                       category.name as cat,
                       tovar.hit,
                       tovar.podzakaz,
                       category.id as idCat,
                       1c_tovar.price as price_1c,
                       1c_tovar.pres
                from category
                left join link_tovar on (link_tovar.id_cat = category.id)
                left join tovar on (tovar.id = link_tovar.id_tovar)
                inner join 1c_tovar on (1c_tovar.id_tovar = tovar.id)
                where (tovar.del = 0) and
                      (link_tovar.del <> 1) and
                      (category.id = ' . $idCat . ') and
                      ((tovar.name like "%' . $word . '%") or (tovar.description like "%' . $word . '%"))
                order by binary(category.name),
                         1c_tovar.pres desc,
                         tovar.podzakaz desc,
                         binary(tovar.name)';

    }

    function tovarInCat_new($idCat)
    {
        return 'select tovar.id,
                       tovar.name,
                       tovar.description,
                       tovar.price,
                       tovar.price_old,
                       tovar.art,
                       tovar.sale,
                       tovar.balance,
                       category.name as cat,
                       tovar.hit,
                       tovar.podzakaz,
                       category.id as idCat,
                       1c_tovar.price as price_1c,
                       1c_tovar.pres
                from category
                left join link_tovar on (link_tovar.id_cat = category.id)
                left join tovar on (tovar.id = link_tovar.id_tovar)
                inner join 1c_tovar on (1c_tovar.id_tovar = tovar.id)
                where (tovar.del = 0) and
                      (link_tovar.del <> 1) and
                      (category.id = ' . $idCat . ')
                order by binary(category.name),
                         1c_tovar.pres desc,
                         binary(tovar.name),
                         tovar.podzakaz desc';

    }

    function tovarInCat($idCat)
    {
        $filter = "";
        $linker = "";
        if (isset($_GET["param"])) {
            $filter = " and (LINK_PROP.ID_PROP) = " . $_GET["param"];
            $linker = " left join LINK_PROP on (tbl21.fld24=LINK_PROP.ID_TOVAR)
                        left join PROP_VALUE on (LINK_PROP.ID_PROP=PROP_VALUE.ID) ";
        }
        if (isset($_GET["sale"])) {
            $filter = " and (description_tovar.price_old) != 0";
        }
        return "select tovar.id,
                       tovar.name,
                       tovar.description,
                       tovar.price,
                       tovar.art,
                       tovar.sale,
                       tovar.balance,
                       category.name as cat,
                       getchild.idparent,
                       tovar.hit,
                       tovar.podzakaz,
                       category.id as idCat,
                       tovar.price_old,
                       1c_tovar.pres
                from tovar
                " . $linker . "
                left join link_tovar on (tovar.id = link_tovar.id_tovar)
                left join category on (link_tovar.id_cat = category.id)
                inner join getchild(" . $idCat . ") on (getchild.idparent = link_tovar.id_cat)
                where (tovar.del = 0) and (link_tovar.del <> 1)
                union
                select tovar.id,
                       tovar.name,
                       tovar.description,
                       tovar.price,
                       tovar.art,
                       tovar.sale,
                       tovar.balance,
                       category.name as cat,
                       " . $idCat . ",
                       tovar.hit,
                       tovar.podzakaz,
                       category.id as idCat,
                       tovar.price_old
                from tovar
                " . $linker . "
                left join link_tovar on (tovar.id = link_tovar.id_tovar)
                left join category on (link_tovar.id_cat = category.id)
                where (tovar.del = 0) and (link_tovar.del <> 1) and (link_tovar.id_cat=" . $idCat . ")
                order by 8,2";
    }

    function product($idTov, $idCat)
    {
        return 'select tovar.name,
                       tovar.description,
                       tovar.desc_full,
                       tovar.price,
                       tovar.price_old,
                       tovar.art,
                       tovar.balance,
                       category.name as cat,
                       tovar.podzakaz,
                       1c_tovar.price as price_1c,
                       1c_tovar.pres
                from tovar
                left join link_tovar on (tovar.id = link_tovar.id_tovar)
                left join category on (link_tovar.id_cat = category.id)
                inner join 1c_tovar on (1c_tovar.id_tovar = tovar.id)
                where (tovar.del = 0) and
                      (link_tovar.del <> 1) and
                      (tovar.id = ' . $idTov . ') and
                      (link_tovar.id_cat = ' . $idCat . ')';
    }

    function sizeChart($idTov)
    {
        return "select tbl7.fld21 as idSize,
                       tbl5.fld23 as size,
                       tbl5.fld22
                from tbl7
                left join tbl5 on (tbl5.fld21 = tbl7.fld21)
                where (tbl7.fld24 = " . $idTov . ") and (tbl5.fld22 = 2)
                order by tbl5.fld23 desc";
    }

    function allTovar()
    {
        return "select tovar.id,
                       tovar.name,
                       tovar.art
                from tovar
                where (tovar.del = 0)
                order by tovar.name";
    }
}

?>
