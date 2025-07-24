<?php
$untuk      = regis('untuk', 1);
$template   = regis('template', 1);
$page       = regis('page', 1);
$limit      = regis('limit', 25);

if($act == 'insert'){
    $data['cmd']        = 'insert';
    $data['db']         = $db;
    $data['untuk']      = $untuk;
    $data['agent']      = $bot_idx;
    $data['template']   = $template;
    mlog('template_answer', json_encode($data));
    
    $post   = api_post($url_api.'api/template_answer.php', $data);
    mlog('template_answer', $post);
    $dec    = json_decode($post, true);
    notif('./template_answer', $dec['rm']);
}elseif($act == 'update'){
    notif('./template_answer');

}elseif($act == 'delete'){

}elseif($act == 'index'){
    $data['cmd'] = 'list';
    $data['db'] = $db;
    $data['page'] = $page;
    $data['limit'] = $limit;
    $post   = api_post($url_api.'api/template_answer.php', $data);
    echo $post;
    exit();
}else{

    $table  = '';
    $no     = 1;
    $untuk  = array();
    $untuk['A'] = 'Agent';
    $untuk['S'] = 'Semua';

    $data['cmd'] = 'list';
    $data['db'] = $db;
    $data['page'] = $page;
    $data['limit'] = $limit;
    $post   = api_post($url_api.'api/template_answer.php', $data);
    mlog('template_answer', $post);
    $dec    = json_decode($post, true);
    if($dec['rc'] == 200){
        foreach($dec['rm']['data'] as $d){
            $table  .= '
                <tr>
                    <td class="text-center">'.$no++.'</td>
                    <td>'.$d['ta_template'].'</td>
                    <td>'.$untuk[$d['ta_untuk']].'</td>
                    <td>Aktif</td>
                    <td>'.$d['ta_updated'].'</td>
                    <td>
                        <a href="#" onclick="detail_answer();"><i class="fas fa-edit"></i></a>
                        <a href="./template_answer&act=delete&id='.$d['ta_id'].'" onclick="return confirm(`Delete this template?`);"><i class="far fa-trash-alt"></i></a>
                    </td>
                </tr>
            ';
        }
    }else{

    }
    mlog('template_answer', $post);
    $template = gethtml('./views/template_answer.html');
    // $template   = str_replace('<!-- table -->', $table, $template);
}