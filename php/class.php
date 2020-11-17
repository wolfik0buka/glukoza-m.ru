<?php
function mainEventsPreview() { // Новости на главной
    $query = "select events.id,
                     events.tit,
                     events.content,
                     events.date_event,
                     events.parent
              from events
              where events.del = 0
              order by events.parent,
                       events.date_event desc";
    $parent = "";
    $res = ibase_query($query);
    while ($row = ibase_fetch_assoc($res)) {
        if ($parent != $row["PARENT"]) {
            $parent = $row["PARENT"];
        }
        if ($row["PARENT"] != 1) 
            $zagolovok = getBLOB($row["TIT"]);
        else
            $zagolovok = date("d.m.Y", strtotime($row["DATE_EVENT"]))." - ".getBLOB($row["TIT"]);
        $preview = "";
        $body = explode (" ", strip_tags(getBLOB($row["CONTENT"])));
	    for ($i=0; $i<20; $i++) $preview .= " ".$body[$i];
            $content[$parent] .= "<b>".$zagolovok."</b>
                                 <p>".$preview."</p>
                                 <a style=\"float: right\" href=\"index.php?page=events&amp;type=".$row["PARENT"]."&amp;idEvent=".$row["ID"]."\">Подробнее</a>
                                 <br /><br />";
        $cnt++;
    }
    return $content;
}

$way['full'] = '';
$way['fullAdm'] = '';
function showWay_new($catId) {
    global $way, $mysqli;
    $query = 'select category.id,
                     category.name,
                     category.parent
              from category
              where (category.id = '.$catId.')';
    $res = $mysqli->query($query);
    while ($row = $res->fetch_assoc()) {
        $way["current"] = $row["name"];
        $way["full"] = "<a href=\"index.php?page=cat&amp;cat=".$row["id"]."\">".$row["name"]."</a> > ".$way["full"];
        $way["fullAdm"] .= "<span class=\"admWay\" onClick=\"getTovarInCat(".$row["parent"].")\">".$row["name"]."</span> > ";
        $way["massCat"][] = $row["parent"];
        if ($row["parent"] != -1) showWay_new($row['parent']);
    }
    return $way;
}



function showWay($id, $dbh) {
    $cnt = 1;
    $query = "SELECT TEST.idparent,
                     CATEGORY.name
                  from test(".$id.")
                  inner join CATEGORY on(CATEGORY.id = test.idparent)
                  UNION
                  select ".$id.",
                         CATEGORY.NAME
                  from CATEGORY
                  where (CATEGORY.ID = ".$id.")";
        $res = ibase_query($dbh, $query);
        $way["full"] = "";
        while ($row = ibase_fetch_assoc($res)) {
            $way["current"] = $row["NAME"];
            $way["full"] .= "<a href=\"index.php?page=cat&amp;cat=".$row["IDPARENT"]."\">".$row["NAME"]."</a> > ";
            $way["fullAdm"] .= "<span class=\"admWay\" onClick=\"getTovarInCat(".$row["IDPARENT"].")\">".$row["NAME"]."</span> > ";
            $way["massCat"][] = $row["IDPARENT"];
            $cnt++;
        }
        return $way;
    }
?>
