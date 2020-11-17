<?php
$events = mainEventsPreview();
$content = new content();
$alias = "about";

$html = '
<table class="mainEventsTable">
    <tr>
        <td>'. $events[1] .'</td>
        <td>'. $events[2] .'</td>
        <td>'. $events[3] .'</td>
    </tr>
</table>
';

$html .= $content->stat();

