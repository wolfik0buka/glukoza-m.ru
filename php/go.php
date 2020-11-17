<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<?php
$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
if(preg_match('#(http?|ftp)://\S+[^\s.,>)\];\'\"!?]#i',$url)){
    sleep(0);
    //header("Location: $url");
    echo "<html><head><meta http-equiv=\"refresh\" content=\"0;url=$url\"></head></html>";
    exit();
}
?>