<?php namespace App;
class query {
    function query() {}
    function adm_statica($id) { // Статические страницы
        $filter = '';
        if ($id != -1) $filter = ' and (static_pages_new.id = "'.$id.'")';
        return 'select static_pages_new.id,
                       static_pages_new.content,
                       static_pages_new.title
                from static_pages_new
                where (static_pages_new.del = 0) and
                      (static_pages_new.no_edit = 0)'.$filter;
    }
}
?>
