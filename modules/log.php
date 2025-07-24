<?php
$page       = regis('page', 1);
$limit      = regis('limit', 25);
$nohp       = regis('nohp');
$status     = regis('status');
$id         = regis('id');

if($act == 'insert'){
    
}elseif($act == 'update'){
    
}else{

    $table  = '';
    $no     = 1;
    $untuk  = array();
    $untuk['A'] = 'Agent';
    $untuk['S'] = 'Semua';
    
    mlog('template_answer', $post);
    $template = gethtml('./views/logs.html');
    // $template   = str_replace('<!-- table -->', $table, $template);
}