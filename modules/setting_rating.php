<?php
$untuk      = regis('untuk', 1);
$template   = regis('template', 1);
$page       = regis('page', 1);
$limit      = regis('limit', 25);

if($act == "update"){
	$status		= regis("status");
	$pembuka	= regis("pembuka");
	$penutup	= regis("penutup");
	$rating_1	= regis("rating_1");
	$rating_2	= regis("rating_2");
	$rating_3	= regis("rating_3");
	$rating_4	= regis("rating_4");
	$rating_5	= regis("rating_5");
	
	$data['cmd']		= 'update';
	$data['db']			= $db;
	$data['bot']		= $bot;
	$data['status']		= $status;
	$data['pembuka']	= $pembuka;
	$data['penutup']	= $penutup;
	$data['rating_1']	= $rating_1;
	$data['rating_2']	= $rating_2;
	$data['rating_3']	= $rating_3;
	$data['rating_4']	= $rating_4;
	$data['rating_5']	= $rating_5;
	$post	= api_post($url_api.'/api/setting_rating.php', $data);
	// echo $post;
	$dec 	= json_decode($post, true);
	// echo var_export($dec, true);
	if($dec['rc'] == '200'){
		notif('./setting_rating');
	}else{
		notif('./setting_rating', $dec['rm']);
	}
}
else{
	$idx		= '-';
	$pembuka	= '';
	$penutup	= '';
	$rating_1	= '';
	$rating_2	= '';
	$rating_3	= '';
	$rating_tambahan = '';
	$data['cmd']		= 'list';
	$data['db']			= $db;
	$data['bot']		= $bot;
	// echo $bot_idx;
	$post	= api_post($url_api.'/api/setting_rating.php', $data);
    mlog('setting_rating', $post);
	$dec 	= json_decode($post, true);
	$status 	= $dec['client_ai_scoring_status'];
    if($status == 'A'){
        $option_status	= '
            <option value="A" selected>Aktif</option>
            <option value="B">Non Aktif</option>
        ';
    }elseif($status == 'B'){
        $option_status	= '
            <option value="A">Aktif</option>
            <option value="B" selected>Non Aktif</option>
        ';
    }
    $scorings	= $dec['client_ai_scoring_detail'];
    $scorings 	= preg_replace('/\s\s+/', '\\n', $scorings);
    $scorings	= json_decode($scorings, true);
    $pembuka	= $scorings['desc'];
    $penutup	= $scorings['respon'];
    $rating_1	= $scorings['button']['1'];
    $rating_2	= $scorings['button']['2'];
    $rating_3	= $scorings['button']['3'];
    $template   = gethtml('./views/setting_rating.html');
	$template		= str_replace("<!-- option_status -->",$option_status,$template);
	$template		= str_replace("<!-- pembuka -->",$pembuka,$template);
	$template		= str_replace("<!-- penutup -->",$penutup,$template);
	$template		= str_replace("<!-- rating_1 -->",$rating_1,$template);
	$template		= str_replace("<!-- rating_2 -->",$rating_2,$template);
	$template		= str_replace("<!-- rating_3 -->",$rating_3,$template);
	$template		= str_replace("<!-- rating_tambahan -->",$rating_tambahan,$template);
}