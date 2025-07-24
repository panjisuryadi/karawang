<?php
$page       = regis('page', 1);
$limit      = regis('limit', 25);
$nohp       = regis('nohp');
$status     = regis('status');
$id         = regis('id');

if($act == 'insert'){
    $data['cmd']        = 'insert';
    $data['bot']        = $bot;
    $data['nohp']       = $nohp;
    mlog('blacklist', json_encode($data));
    
    $post   = api_post($url_api.'api/blacklist.php', $data);
    mlog('blacklist', $post);
    $dec    = json_decode($post, true);
    notif('./blacklist', $dec['rm']);
}elseif($act == 'update'){
    $data['cmd']        = 'update';
    $data['id']         = $id;
    $data['status']     = $status;
    mlog('blacklist', json_encode($data));
    
    $post   = api_post($url_api.'api/blacklist.php', $data);
    mlog('blacklist', $post);
    $dec    = json_decode($post, true);
    notif('./blacklist', $dec['rm']);

}else{

    $table  = '';
    $no     = 1;
    $untuk  = array();
    $untuk['A'] = 'Agent';
    $untuk['S'] = 'Semua';
    
    mlog('template_answer', $post);
    $template = gethtml('./views/blacklist.html');
    // $template   = str_replace('<!-- table -->', $table, $template);
}