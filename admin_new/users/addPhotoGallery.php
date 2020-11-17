<!DOCTYPE HTML>
<html>
    <head>
        <title></title>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <link rel="stylesheet" href="../../css/start/jquery-ui-1.10.3.custom.min.css">
        <script src="../js/jquery-1.9.1.js"></script>
        <script src="../js/jquery-ui-1.10.3.custom.min.js"></script>
    </head>
    <body>
        <?php
        include 'image.func.gal.php';
        $form = 'x';
        $dir = '../../img/catalog/';
        $new_name = 'pic_'.$_GET['id'];
        if (uploadImage($form, $dir, 100, 1000, 1000, $new_name)) {
            $txt = 'Загружено';
            $img = "<div class='photo_box'><img class='no_basic_photo' src='/img/resize.php?src=/img/catalog/pic_".$_GET['id'].".jpg&rnd=".rand(100,999)."&q=100&w=180&h=180'></div>";
        }
        ?>
        <script type="text/javascript">
            $("div.photo_box", top.document).remove();
            $("form#addPhoto", top.document).after("<?php echo $img; ?>");
        </script>
    </body>
</html>