<?php
$untuk      = regis('untuk', 1);
$template   = regis('template', 1);
$page       = regis('page', 1);
$limit      = regis('limit', 25);

if($act == 'insert'){
    
}else{

    $template = gethtml('./views/web_view.html');
}