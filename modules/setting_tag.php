<?php
$untuk      = regis('untuk', 1);
$template   = regis('template', 1);
$page       = regis('page', 1);
$limit      = regis('limit', 25);
$status		= regis("status");

if($act == "update"){
	$tag_1		= regis("tag_1");
	$tag_2		= regis("tag_2");
	$tag_3		= regis("tag_3");
	$tag_4		= regis("tag_4");
	$tag_5		= regis("tag_5");
	$tag_6		= regis("tag_6");
	$tag_7		= regis("tag_7");
	$tag_8		= regis("tag_8");
	$tag_9		= regis("tag_9");
	$tag_10		= regis("tag_10");
	$tag_11		= regis("tag_11");
	$tag_12		= regis("tag_12");
	$tag_13		= regis("tag_13");
	$tag_14		= regis("tag_14");
	$tag_15		= regis("tag_15");
	$tag_16		= regis("tag_16");
	$tag_17		= regis("tag_17");
	$tag_18		= regis("tag_18");
	$tag_19		= regis("tag_19");
	$tag_20		= regis("tag_20");
	$tag_21		= regis("tag_21");
	$tag_22		= regis("tag_22");
	$tag_23		= regis("tag_23");
	$tag_24		= regis("tag_24");
	$tag_25		= regis("tag_25");
	$tag_26		= regis("tag_26");
	$tag_27		= regis("tag_27");
	$tag_28		= regis("tag_28");
	$tag_29		= regis("tag_29");
	$tag_30		= regis("tag_30");
	
	$data['cmd']		= 'update';
	$data['db']			= $db;
	$data['bot']		= $bot;
	$data['status']		= $status;
	$data['tag_1']		= $tag_1;
	$data['tag_2']		= $tag_2;
	$data['tag_3']		= $tag_3;
	$data['tag_4']		= $tag_4;
	$data['tag_5']		= $tag_5;
	$data['tag_6']		= $tag_6;
	$data['tag_7']		= $tag_7;
	$data['tag_8']		= $tag_8;
	$data['tag_9']		= $tag_9;
	$data['tag_10']		= $tag_10;
	$data['tag_11']		= $tag_11;
	$data['tag_12']		= $tag_12;
	$data['tag_13']		= $tag_13;
	$data['tag_14']		= $tag_14;
	$data['tag_15']		= $tag_15;
	$data['tag_16']		= $tag_16;
	$data['tag_17']		= $tag_17;
	$data['tag_18']		= $tag_18;
	$data['tag_19']		= $tag_19;
	$data['tag_20']		= $tag_20;
	$data['tag_21']		= $tag_21;
	$data['tag_22']		= $tag_22;
	$data['tag_23']		= $tag_23;
	$data['tag_24']		= $tag_24;
	$data['tag_25']		= $tag_25;
	$data['tag_26']		= $tag_26;
	$data['tag_27']		= $tag_27;
	$data['tag_28']		= $tag_28;
	$data['tag_29']		= $tag_29;
	$data['tag_30']		= $tag_30;

	$post	= api_post($url_api.'/api/setting_tag.php', $data);
	// echo $post;
	$dec 	= json_decode($post, true);
	// echo var_export($dec, true);
	if($dec['rc'] == '200'){
		notif('./setting_tag');
	}else{
		notif('./setting_tag', $dec['rm']);
	}
}
else{
	$tag    = array();

	$tag[0]		= '';
	$tag[1]		= '';
	$tag[2]		= '';
	$tag[3]		= '';
	$tag[4]		= '';
	$tag[5]		= '';
	$tag[6]		= '';
	$tag[7]		= '';
	$tag[8]		= '';
	$tag[9]		= '';
	$tag[10]		= '';
	$tag[11]		= '';
	$tag[12]		= '';
	$tag[13]		= '';
	$tag[14]		= '';
	$tag[15]		= '';
	$tag[16]		= '';
	$tag[17]		= '';
	$tag[18]		= '';
	$tag[19]		= '';
	$tag[20]		= '';
	$tag[21]		= '';
	$tag[22]		= '';
	$tag[23]		= '';
	$tag[24]		= '';
	$tag[25]		= '';
	$tag[26]		= '';
	$tag[27]		= '';
	$tag[28]		= '';
	$tag[29]		= '';

	$data['cmd']		= 'list';
	$data['db']			= $db;
	$data['bot']		= $bot;
	$post	= api_post($url_api.'/api/setting_tag.php', $data);
	$dec 	= json_decode($post, true);
	if($dec['rc'] == '200'){
		$status	= $dec['rm']['client_tag_status'];
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
		$tags	= $dec['rm']['client_tag_detail'];
		$tags	= json_decode($tags, true);
		if(is_array($tags)){
			$no = 0;
			foreach($tags as $t){
				$tag[$no] = $t;
				$no++;
			}
		}

	}
    $template   	= gethtml('./views/setting_tag.html');
	$template		= str_replace("<!-- option_status -->",$option_status,$template);
	$template		= str_replace("<!-- tag_1 -->",$tag[0],$template);
	$template		= str_replace("<!-- tag_2 -->",$tag[1],$template);
	$template		= str_replace("<!-- tag_3 -->",$tag[2],$template);
	$template		= str_replace("<!-- tag_4 -->",$tag[3],$template);
	$template		= str_replace("<!-- tag_5 -->",$tag[4],$template);
	$template		= str_replace("<!-- tag_6 -->",$tag[5],$template);
	$template		= str_replace("<!-- tag_7 -->",$tag[6],$template);
	$template		= str_replace("<!-- tag_8 -->",$tag[7],$template);
	$template		= str_replace("<!-- tag_9 -->",$tag[8],$template);
	$template		= str_replace("<!-- tag_10 -->",$tag[9],$template);
	$template		= str_replace("<!-- tag_11 -->",$tag[10],$template);
	$template		= str_replace("<!-- tag_12 -->",$tag[11],$template);
	$template		= str_replace("<!-- tag_13 -->",$tag[12],$template);
	$template		= str_replace("<!-- tag_14 -->",$tag[13],$template);
	$template		= str_replace("<!-- tag_15 -->",$tag[14],$template);
	$template		= str_replace("<!-- tag_16 -->",$tag[15],$template);
	$template		= str_replace("<!-- tag_17 -->",$tag[16],$template);
	$template		= str_replace("<!-- tag_18 -->",$tag[17],$template);
	$template		= str_replace("<!-- tag_19 -->",$tag[18],$template);
	$template		= str_replace("<!-- tag_20 -->",$tag[19],$template);
	$template		= str_replace("<!-- tag_21 -->",$tag[20],$template);
	$template		= str_replace("<!-- tag_22 -->",$tag[21],$template);
	$template		= str_replace("<!-- tag_23 -->",$tag[22],$template);
	$template		= str_replace("<!-- tag_24 -->",$tag[23],$template);
	$template		= str_replace("<!-- tag_25 -->",$tag[24],$template);
	$template		= str_replace("<!-- tag_26 -->",$tag[25],$template);
	$template		= str_replace("<!-- tag_27 -->",$tag[26],$template);
	$template		= str_replace("<!-- tag_28 -->",$tag[27],$template);
	$template		= str_replace("<!-- tag_29 -->",$tag[28],$template);
	$template		= str_replace("<!-- tag_30 -->",$tag[29],$template);
}