<?php namespace App;
class query {
    function adm_nom_list() { // Номенклатура
        return 'select tovar.id,
                       tovar.name,
                       tovar.art,
                       tovar.sale,
                       tovar.hit,
                       tovar.podzakaz,
                       1c_tovar.price
                from tovar
                left join 1c_tovar on (1c_tovar.id_tovar = tovar.id)
                where (tovar.del = 0)
                order by tovar.name';
    }

    function adm_photo($id) {   // Фото заданного товара
        return 'select tovar.name,
                       tovar.id
                from tovar
                where (tovar.id = '.$id.')';
    }

    function adm_cat_link($tovar, $parent) {
        return 'select distinct category.id,
                                category.name,
                                category.parent,
                                category.order_fld,
                                link_tovar.id_tovar
                from category
                left join link_tovar on (link_tovar.id_cat = category.id and link_tovar.id_tovar = '.$tovar.')
                where (category.parent = '.$parent.') and
                      (category.del = 0)
                order by category.order_fld';
    }
    function adm_color_link($tovar) {
        return 'select colors.id,
                       colors.color_name_ru,
                       color_link.tovar_id as tovar
                from colors
                left join color_link on (color_link.color_id = colors.id) and
                                        (color_link.tovar_id = '.$tovar.')
                where (colors.type = 1)
                order by colors.color_name_ru';
    }
    function adm_nom_tovar($id) { // Номенклатура
        return 'select tovar.id,
                       tovar.art,
                       tovar.name,
                       tovar.description,
                       tovar.desc_full,
                       tovar.price,
                       tovar.price_old,
                       tovar.sale,
                       tovar.hit,
                       tovar.podzakaz
                from tovar
                where (tovar.del = 0) and
                      (tovar.id = '.$id.')
                order by tovar.name';
    }
}
?>
