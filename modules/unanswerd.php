<?php
$tipe_0       = regis('tipe_0');
$tipe_1       = regis('tipe_1');
$tipe_2       = regis('tipe_2');

$text_0       = regis('text_0');
$text_1       = regis('text_1');
$text_2       = regis('text_2');

$file_0       = regis('file_0');
$file_1       = regis('file_1');
$file_2       = regis('file_2');

$id         = regis('id');
$page       = regis('page', 1);
$limit      = regis('limit', 25);

if($act == 'insert'){
    // echo json_encode($_POST);
    // mlog('unanswerd', json_encode($_POST));

    // exit();
    // {
    //     "cmd":"insert",
    //     "db":167116906433128094,
    //     "bot":167116906433373188,
    //     "keyword":"__no_answer__",
    //     "tipe_0":1,
    //     "text_0":"hello ada yang bisa di bantu",
    //     "tipe_1":1,
    //     "text_1":"haii",
    //     "tipe_2":1,
    //     "text_2":"apa kabar"
    // }

    $data['cmd']        = 'insert';
    $data['db']         = $db;
    $data['bot']        = $bot;
    $data['keyword']    = '__no_answer__';
    $data['tipe_0']     = conv_tipe($tipe_0);
    $data['tipe_1']     = conv_tipe($tipe_1);
    $data['tipe_2']     = conv_tipe($tipe_2);
    $data['text_0']     = $text_0;
    $data['text_1']     = $text_1;
    $data['text_2']     = $text_2;
    
    $post   = api_post($url_api.'api/unsnwerd.php', $data);
    mlog('unanswerd', json_encode($data));
    mlog('unanswerd', $post);
    $dec    = json_decode($post, true);
    if($dec['rc'] == 200){
        notif('./unanswerd');
    }else{
        notif('./unanswerd', $dec['rm']);
    }
}elseif($act == 'update'){
    notif('./template_answer');

}elseif($act == 'delete'){
    $data['cmd']        = 'delete';
    $data['db']         = $db;
    $data['bot']        = $bot;
    $data['id']         = $id;
    
    $post   = api_post($url_api.'api/unsnwerd.php', $data);
    $dec    = json_decode($post, true);
    if($dec['rc'] == 200){
        notif('./unanswerd');
    }else{
        notif('./unanswerd', $dec['rm']);
    }
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

    $data['cmd']    = 'index';
    $data['db']     = $db;
    $data['bot']    = $bot;
    $data['page']   = $page;
    $data['limit']  = $limit;
    $post   = api_post($url_api.'api/unsnwerd.php', $data);
    $dec    = json_decode($post, true);
    if($dec['rc'] == 200){
        mlog('unanswerd', $table);
        foreach($dec['rm'] as $d){
            $answer = $d['kb_a_answer'];
            $answers    = json_decode($answer, true);
            $jawab  = '';
            if(is_array($answers)){
                $number = 1;
                foreach($answers as $a){
                    
                    if($a['tipe'] == 1){ // text
                        $ty = 'Text';

                        $content    = $a['content'];
                        $jawab  .= '
                        '.$number.'. Tipe: <span class="fw-medium ms-1">'.$ty.'</span><br>
                        <span class="mt-1 bg-light-primary rounded-1 d-inline-block text-dark">'.$content.'</span>
                        <hr>
                        <br>
                        '.PHP_EOL;
                    }elseif($a['tipe'] == 2){

                    }else{

                    }
                    $number++;
                }
            }
            $table  .= '
                <tr>
                    <td class="text-center">'.$no++.'</td>
                    <td>'.$jawab.'</td>
                    <td>
                        <a href="./unanswerd&act=delete&id='.$d['kb_a_id'].'" onclick="return confirm(`Delete this unanswered?`);"><i class="far fa-trash-alt"></i></a>
                    </td>
                </tr>
            ';
        }
    }
    $template = gethtml('./views/unanswerd.html');
    mlog('unanswerd', $table);

    $template   = str_replace('<!-- table -->', $table, $template);
}

function conv_tipe($tipe){
    if($tipe == 'text'){
        $type   = '1';
    }elseif($tipe == 'file'){
        $type   = '2';
    }

    return $type;
}