<?php
error_reporting(1);
session_start();

define("__title__", "Dashboard EVA");

$bot_u_idx		= (array_key_exists('bot_u_idx', $_SESSION) ? $_SESSION['bot_u_idx'] : '');
$bot_idx		= (array_key_exists('bot_idx', $_SESSION) ? $_SESSION['bot_idx'] : '');
$bot_user		= (array_key_exists('bot_user', $_SESSION) ? $_SESSION['bot_user'] : '');
$bot_username		= (array_key_exists('bot_username', $_SESSION) ? $_SESSION['bot_username'] : '');
$bot_level		= (array_key_exists('bot_level', $_SESSION) ? $_SESSION['bot_level'] : '');
$bot_name		= (array_key_exists('bot_name', $_SESSION) ? $_SESSION['bot_name'] : '');
$bot_acc_db		= (array_key_exists('bot_acc_db', $_SESSION) ? $_SESSION['bot_acc_db'] : '');

$acc_bot		= $bot_idx;
$url_api 	= "http://localhost/pdam_karawang_api/";
$tampil		= "";
$s_page		= "";
$tablena	= "";
$hasilna	= false;
$lanjut		= true;
$msg		= "Hubungi Admin";
$halaman	= "";
#$arr_plat	= array("3"=>"web","5"=>"telegram","6"=>"fb","8"=>"line","9"=>"app","16"=>"whatsapp","17"=>"widget",);
$arr_plat	= array("3"=>"web","5"=>"telegram","6"=>"fb","8"=>"line","15"=>"whatsapp","16"=>"whatsapp","17"=>"widget","19"=>"whatsapp","21"=>"instagram","22"=>"whatsapp","24"=>"whatsapp",);

function max30hari($tgl_awal, $url){
	// 166606453671983003
	
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($tgl_awal);
	$diff = $date2->diff($date1);
	$different = $diff->format('%R%a');
	$different = substr($different, 1);
	return true;
	if($_SESSION['bot_acc_db'] == '166606453671983003' || $_SESSION['bot_acc_db'] == '168195711521163105'){ // PDAM BATAM & BTPN
		if($different > 124){
			$notif	= notif($url, 'tanggal melebihi batas pencarian (93 Hari)');
			exit();
		}
	}else{
		if($different > 31){
			$notif	= notif($url, 'tanggal melebihi batas pencarian (31 Hari)');
			exit();
		}
	}
}

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a Hari, %h Jam, %i Menit, %s Detik');
}

function api_post($url, $data){
	$payload = json_encode($data);
	#$ch = curl_init($url);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = trim(curl_exec($ch));
	curl_close($ch);
	return $result;
}

function api_get($url, $data) {
    // Build query string from the data array
    $query = http_build_query($data);
    
    // Append query string to the URL
    $fullUrl = $url . '?' . $query;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = trim(curl_exec($ch));
    curl_close($ch);
    return $result;
}

function httpPosts($url,$params)
{
  if ( is_array($params) )
  {
     $postData = '';
     //create name value pairs seperated by &
     foreach($params as $k => $v) 
     { 
      $postData .= $k . '='.$v.'&'; 
     }
     rtrim($postData, '&');
  }
  else
  {
    $postData = $params;
  }
 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));  
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
   
    $output=curl_exec($ch);

  if ( false === $output )
    {
    //addLogs(basename(FILE)."(".LINE.")-> ERR CURL ". ' no:'. curl_errno($ch).' msg:' . curl_error($ch), _MODULE );    
    }
  
  $info = curl_getinfo($ch);  
  
  //addLogs(basename(FILE)."(".LINE.")-> CURL ". ' content type:'. $info['content_type'].' http code:' . $info['http_code'], _MODULE );         
  
    curl_close($ch);
    return $output;
 
}

function notif($url, $pesan=''){
	$send = '<script language="javascript">';
	if($pesan !== ''){
	  $send .= "alert('".$pesan."');";
	}
	$send .= 'window.location = "'.$url.'"';
	$send .= '</script>';
	echo $send;
}

function goBack($jabatan="CS"){
	$hasil		= "";
	if($jabatan == "CS"){
		$hasil	= '	<script type="text/javascript">history.go(-1);</script>';
	}
	return $hasil;
}

function gethtml($showfile){
	if(file_exists($showfile)){
		$handle = fopen($showfile,"r");
		$contents = fread($handle,filesize($showfile));
		fclose($handle);
		return $contents;
	#}else return "\n maap file : ".$showfile.", tidak found !";
	}else return "-".$showfile;
}


function regis($varname, $defval=NULL){
	if (array_key_exists($varname, $_SERVER)) { $retval = $_SERVER[$varname]; }
		elseif (array_key_exists($varname, $_COOKIE)) { $retval = $_COOKIE[$varname]; }
		elseif (array_key_exists($varname, $_POST)) { $retval = $_POST[$varname]; }
		elseif (array_key_exists($varname, $_GET)) { $retval = $_GET[$varname]; }
		elseif (array_key_exists($varname, $_ENV)) { $retval = $_ENV[$varname]; }
		elseif (array_key_exists($varname, $_FILES)) { $retval = $_FILES[$varname]; }
	else{
		$retval = $defval; 
	}
	return $retval;
}

function microtimeget() {
	$usec = microtime();
	list($ts_usec, $ts_sec) = explode(" ", $usec);
	//real usec, 6 digit
	$ts_usec = substr($ts_usec, 2, 6);
	return date("YmdHis", $ts_sec) . $ts_usec;
}

function gedebug($str,$folder = '') {
	$dirlog		= $_SERVER['DIR_OUT']."logs";
	$warna=chr(27).'[37;40m';
	$warni='<font color=white>';

	if(strpos($str,'INFO') !== false){
		$warna = chr(27).'[32;40m';
		$warni = '<font color=green>';
	}elseif(strpos($str,'SQL') !== false){
		$warna = chr(27).'[31;40m';
		$warni = '<font color=red>';
	}elseif(strpos($str,'connected') !== false){
		$warna = chr(27).'[31;40m';
		$warni = '<font color=red>';
	}elseif(strpos($str,'gagal') !== false){
		$warna = chr(27).'[31;40m';
		$warni = '<font color=red>';
	}elseif(strpos($str,'GAGAL') !== false){
		$warna = chr(27).'[31;40m';
		$warni = '<font color=red>';
	}else{
		$warna=chr(27).'[37;40m';
		$warni='<font color=white>';
	}
	
	$ayeuna = date('Ymd');
	$jamtgl = microtimeget();
	$str = chr(27).chr(10).chr(13).$jamtgl.':'.$str;
	
	if($folder == ''){
		$fhandle = fopen($dirlog.'/debug_'.$ayeuna.'.log','a+');
	}else{
		if($folder == 'index'){
			$fhandle = fopen($dirlog.'/debug_'.$folder.'_'.$ayeuna.'.log','a+');
		}
		else{
			$fhandle = fopen($dirlog.'/debug_'.$folder.'_'.$ayeuna.'.log','a+');
		}		
	}	
	fwrite($fhandle,$warna.$str.chr(27).'[37;40m'.chr(27).'[0m'.chr(10).chr(13));
	fclose($fhandle);
}

function get_content($url){
	$arrContextOptions=array(
	  "ssl"=>array(
		  "verify_peer"=>false,
		  "verify_peer_name"=>false,
	  ),
	);  
  
	$response = file_get_contents($url, false, stream_context_create($arrContextOptions));
	return $response;
  }

function tampil($temp_body='',$temp_ket='',$temp_js='',$temp_css=''){
	global $dir_assets_lgn;

	#include($_SERVER['DIR_OUT']."assets/view/head.php");
	include($_SERVER['DIR_OUT']."assets/view/left.php");
	
	$tampil	= gethtml($_SERVER['DIR_OUT']."assets/view/body.php");
	$tampil	= str_replace("<!-- temp_left -->",$temp_left,$tampil);
	#$tampil	= str_replace("<!-- temp_head -->",$temp_head,$tampil);
	$tampil	= str_replace("<!-- date -->",date('Y'),$tampil);
	$tampil	= str_replace("<!-- dir_assets_lgn -->",$dir_assets_lgn,$tampil);
	
	if($temp_body){
		$uri_body	= $_SERVER['REQUEST_URI'];
		$ex_uri		= explode("/",$uri_body);
		// $temp_body	= file_get_html($_SERVER['BASE_URL']."models/".$ex_uri[2]."/views/".$temp_body.".php");
		#$temp_body	= file_get_html($_SERVER['BASE_URL']."models/".$ex_uri[3]."/views/".$temp_body.".php"); #eva.id/cs/
		#$tampil		= str_replace("<!-- temp_body -->",$_SERVER['BASE_URL']."models/".$ex_uri[2]."/views/".$temp_body.".php",$tampil);
		$tampil		= str_replace("<!-- temp_body -->",$temp_body,$tampil);
	}
	
	if(is_array($temp_js)){
		if(count($temp_js)){
			$temp_jss	= '';
			foreach($temp_js as $k_js => $v_js){
				$temp_jss	.= '<script type="text/javascript" src="../assets/js/'.$v_js.'"></script>';				
			}
			$tampil	= str_replace("<!-- temp_js -->",$temp_jss,$tampil);
		}
	}
	
	if(is_array($temp_css)){
		if(count($temp_css)){
			$temp_csss	= '';
			foreach($temp_css as $k_css => $v_css){
				$temp_csss	.= '<link href="../assets/css/'.$v_css.'" type="text/css" rel="stylesheet" media="screen,projection">';				
			}
			$tampil	= str_replace("<!-- temp_css -->",$temp_csss,$tampil);
		}
	}
	
	if(is_array($temp_ket)){
		if(count($temp_ket)){
			if (array_key_exists('judul_halaman', $temp_ket)) { $tampil	= str_replace("<!-- judul_halaman -->",$temp_ket['judul_halaman'],$tampil); }
			if (array_key_exists('main_menu', $temp_ket)) { $tampil	= str_replace("<!-- main_menu -->",$temp_ket['main_menu'],$tampil); }
			if (array_key_exists('sub_menu', $temp_ket)) { $tampil	= str_replace("<!-- sub_menu -->",$temp_ket['sub_menu'],$tampil); }
			if (array_key_exists('judul_menu', $temp_ket)) { $tampil	= str_replace("<!-- judul_menu -->",$temp_ket['judul_menu'],$tampil); }
		}
	}
	
	return $tampil;
}

function curl( $url, $method = 'get', $data = '', $header = array(), $keep = true ){
  global $ch, $curl_cookie_file;

  $res = array();
  if ( false == $ch )
  {
    $ch = curl_init();
  }

  $_header = array(
    'Accept-Language: en-US,en;q=0.7,fr;q=0.3',
    'Accept-Encoding: gzip, deflate',
    );
  $header += $_header;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
  curl_setopt($ch, CURLOPT_TIMEOUT, 40);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_ENCODING, '');
  curl_setopt($ch, CURLOPT_VERBOSE, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:37.0) Gecko/20100101 Firefox/37.0');
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

  if ( 'post' == $method AND '' != $data )
  {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  }

  if ( strpos($url, 'https://') !== false )
  {
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    #curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  }

  if ( $curl_cookie_file )
  {
    curl_setopt($ch, CURLOPT_COOKIEJAR, $curl_cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $curl_cookie_file);
  }

  $ce = curl_exec($ch);
  if ( false === $ce )
  {
    $res = array(
      'eno' => curl_errno($ch),
      'emsg' => curl_error($ch),
      );
  }
  else
  {
    $res = array(
      'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
      'body' => $ce,
      );
  }

  if ( false != $keep )
  {
    curl_close($ch);
    $ch = false;
  }

  return $res;
}

function encode($array_request){
	if(!empty($array_request))
	{
		$hsl_serialize = serialize($array_request);
		$hsl_encode64 = base64_encode($hsl_serialize);
		return $hsl_encode64;
	}
	else return "\n maap array request kosong ! <p>";
}


function decode($array_respon){
	if(!empty($array_respon))
	{
		$hsl_decode64 = base64_decode($array_respon);
		$hsl_unserialize = unserialize($hsl_decode64);
		return $hsl_unserialize;
	}
	else return "\n maap array respon kosong ! <p>";
}

function get_real_ip(){
    if (isset($_SERVER["HTTP_CLIENT_IP"])){
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"])){
        return $_SERVER["HTTP_FORWARDED"];
    }
    else{
        return $_SERVER["REMOTE_ADDR"];
    }
}
 
function kirim_apis($file,$datana = array()){
	$hasil		= false;
	$data_chat	= false;
	#$kirim_api	= curl("https://rz.helobot.id/api_rz/".$file.".php","post",$datana);	
	$kirim_api	= curl("http://10.6.11.77/api_dashboard/".$file.".php","post",$datana);	
	$code		= $kirim_api["code"];
	$body		= $kirim_api["body"];
	$bodys		= decode($body);
	#die("https://rz.helobot.id/api_rz/".$file.".php<pre>".var_export($datana,true)."</pre><pre>".var_export($bodys,true)."</pre>");
	if($code == "200"){
		$hasil			= $bodys;
	}
	return $hasil;
}

function kirim_apib($file,$datana = array()){
	$hasil		= false;
	$data_chat	= false;
	#$kirim_api	= curl("https://rz.helobot.id/api_rz/".$file.".php","post",$datana);	
	$kirim_api	= curl("https://broadcast.eva.id/api_dashboard/".$file.".php","post",$datana);	
	$code		= $kirim_api["code"];
	$body		= $kirim_api["body"];
	$bodys		= decode($body);
	#die("https://rz.helobot.id/api_rz/".$file.".php<pre>".var_export($datana,true)."</pre><pre>".var_export($bodys,true)."</pre>");
	if($code == "200"){
		$hasil			= $bodys;
	}
	return $hasil;
}

function kirim_api($file,$datana = array()){
	$data_chat	= false;
	#$kirim_api	= curl("https://kawaluyaan.eva.id/gibran_la/api_cs/".$file.".php","post",$datana);
	$kirim_api	= curl("http://10.6.11.77/api_cs/".$file.".php","post",$datana);
	$code		= $kirim_api["code"];
	$body		= $kirim_api["body"];
	$bodys		= decode($body);
	#die(__LINE__."-".var_export($bodys,true));
	if($code == "200"){
		$rc				= $bodys['rc'];
		$data_chat		= $bodys['message'];
		$data_chat2		= !empty($bodys['data']) ? $bodys['data'] : '';
	}
	$datana		= array("data_chat"=>$data_chat,"data_chat2"=>$data_chat2);
	#return json_encode($datana);
	return $datana;
}

function get_detail_user($op){
	global $bot_idx;
	$status		= false;
	$lolos		= true;
	$isi		= "";
	
	if(trim($op) != ''){
		#$op					= decode($op);
		$datana				= array();
		$datana['act']		= "detail";
		$datana['op']		= $op;
		$datana['bot']		= $bot_idx;
		$kirim_api			= kirim_api("operator",$datana);
		if($kirim_api){
			$rc				= $kirim_api['rc'];
			$isi			= $kirim_api['message'];
			if($rc == "200"){
				$status		= true;
			}
		}
	}
	
	$msg		= array("status"=>$status,"isi"=>$isi,);
	return $msg;
}

function conv_date($in){
	$in			= strtotime($in) + 25200;
	$in			= date('Y-m-d H:i:s',$in);
	return $in;
}

function conv_tanggal($in){
	$in			= strtotime($in) - 25200;
	$in			= date('Y-m-d H:i:s',$in);
	return $in;
}

function conv_platform($idplat){
	$hasil		= "-";
	$arr_plat	= array("3"=>"web","5"=>"telegram","6"=>"fb","8"=>"line","9"=>"app","15"=>"whatsapp","16"=>"whatsapp","17"=>"web widget","19"=>"whatsapp","20"=>"WA extention","21"=>"Instagram","22"=>"whatsapp","24"=>"whatsapp","28"=>"whatsapp",);
	if (array_key_exists($idplat, $arr_plat)) {
		$hasil	= $arr_plat[$idplat];
	}
	return $hasil;
}

function upload_berkas($name, $tmp){
    $u_digit = 4;
    $u_ts = microtime(true);
    if (strpos($u_ts, '.') === false) {
        $ts = trim($u_ts); 
        $u = ''; 
    } else {
        list($ts, $u) = explode('.', $u_ts); 
    }
    $dt = date('YmdHis', $u_ts);
    $usec = substr(str_repeat('0', ($u_digit - strlen($u))) . $u, 0, $u_digit); 
    $micro = $dt . $usec;

    $dir = '/home/d.eva.id/public_html/uploads/';
    try {
        $uploading = move_uploaded_file($tmp, $dir . $micro . $name);
        if ($uploading) {
            return 'https://d.eva.id/uploads/' . $micro . $name;
        } else {
            return 'fail';
        }
    } catch (\Throwable $th) {
        return $th->getMessage();
    }
}

function upload_geneva($filenya,$simpan = "admin"){
	$allowed		= array('jpg', 'jpeg', 'jfif', 'png', 'pdf', 'gif', 'pptx', 'xls', 'xlsx', 'doc', 'docx', 'txt');
	$ext 			= strtolower(pathinfo($filenya['name'], PATHINFO_EXTENSION));
	if(in_array($ext, $allowed)){
		$simpan			= "";
		#$target_dir 	= $_SERVER['CONTEXT_DOCUMENT_ROOT']."/eva/caleg/uploads/";
		/*if($_SERVER['DOCUMENT_ROOT']){
			$target_dir 	= $_SERVER['DOCUMENT_ROOT']."/".$simpan."/uploads/";		
		}
		else{
			$target_dir 	= "/var/www/gibran/".$simpan."/uploads/";
		}*/
		$target_dir 	= $_SERVER['DOCUMENT_ROOT'].$simpan."/uploads/";		
		$target_file 	= $target_dir . basename($filenya["name"]);
		$uploadOk = 1;
		$imageFileType 	= strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// $namana			= microtimeget().".".$imageFileType;
		$micro			= microtimeget();
		$sub			= substr($micro, 10);
		$namana			= $sub.'_'.$filenya['name'];
		$target_file	= $target_dir.$namana;
		$call_dir		= "https://".$_SERVER['HTTP_HOST']."/uploads/".$namana;
		$msgs			= "";
		$rc				= "00";
		$check = getimagesize($filenya["tmp_name"]);
		if($check !== false) {
			$tipes	= "2";
		} else {
			$tipes	= "4";
		}
	
		if (file_exists($target_file)) {
			$msgs	= "Sorry, file already exists.";
			$uploadOk = 0;
		}
		
		// if ($filenya["size"] > 10000000) {
		// 	$msgs	= "Sorry, your file is too large.";
		// 	$uploadOk = 0;
		// }
		
		if ($uploadOk == 0) {
			
		} else {
			if (move_uploaded_file($filenya["tmp_name"], $target_file)) {
				$rc		= "200";
				$msgs	= $call_dir;
			} else {
				$msgs	= "Sorry, there was an error uploading your file.";
				// $msgs	= $filenya;
			}
		}
		return array("rc"=>$rc,"tipe"=>$tipes,"msg"=>$msgs,"msgs"=>$target_file,);
	}else{
		return 'file not allowed';
	}
}

function upload($filenya,$simpan = "admin"){
	$allowed		= array('jpg', 'jpeg', 'jfif', 'png', 'pdf', 'gif', 'pptx', 'xls', 'xlsx', 'doc', 'docx', 'txt');
	$ext 			= strtolower(pathinfo($filenya['name'], PATHINFO_EXTENSION));
	if(in_array($ext, $allowed)){
		$simpan			= "";
		#$target_dir 	= $_SERVER['CONTEXT_DOCUMENT_ROOT']."/eva/caleg/uploads/";
		/*if($_SERVER['DOCUMENT_ROOT']){
			$target_dir 	= $_SERVER['DOCUMENT_ROOT']."/".$simpan."/uploads/";		
		}
		else{
			$target_dir 	= "/var/www/gibran/".$simpan."/uploads/";
		}*/
		$target_dir 	= $_SERVER['DOCUMENT_ROOT'].$simpan."/uploads/";		
		$target_file 	= $target_dir . basename($filenya["name"]);
		$uploadOk = 1;
		$imageFileType 	= strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$namana			= microtimeget().".".$imageFileType;
		$target_file	= $target_dir.$namana;
		$call_dir		= "https://".$_SERVER['HTTP_HOST']."/uploads/".$namana;
		$msgs			= "";
		$rc				= "00";
		$check = getimagesize($filenya["tmp_name"]);
		if($check !== false) {
			$tipes	= "2";
		} else {
			$tipes	= "4";
		}
	
		if (file_exists($target_file)) {
			$msgs	= "Sorry, file already exists.";
			$uploadOk = 0;
		}
		
		if ($filenya["size"] > 5000000) {
			$msgs	= "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		
		if ($uploadOk == 0) {
			
		} else {
			if (move_uploaded_file($filenya["tmp_name"], $target_file)) {
				$rc		= "200";
				$msgs	= $call_dir;
			} else {
				$msgs	= "Sorry, there was an error uploading your file.";
				// $msgs	= $filenya;
			}
		}
		return array("rc"=>$rc,"tipe"=>$tipes,"msg"=>$msgs,"msgs"=>$target_file,);
	}else{
		return 'file not allowed';
	}
}

function upload_multi_file($filenya,$simpan = "admin"){
	$simpan			= "";
	$hasil			= array();
	$target_dir 	= $_SERVER['DOCUMENT_ROOT']."/".$simpan."/uploads/";
	$allowed		= array('jpg', 'jpeg', 'png', 'pdf', 'gif', 'pptx', 'xls', 'xlsx', 'txt', 'doc', 'docx');
	foreach($filenya["name"] as $k => $v){
		if($v != ''){

			$ext 			= pathinfo($v, PATHINFO_EXTENSION);
			if(in_array($ext, $allowed)){
				$target_file 	= $target_dir . basename($filenya["name"][$k]);
				$imageFileType 	= strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				$namana			= microtimeget().".".$imageFileType;
				$target_file	= $target_dir.$namana;
				$call_dir		= "https://".$_SERVER['HTTP_HOST']."/uploads/".$namana;
				if(move_uploaded_file($filenya["tmp_name"][$k], $target_file)){
					$hasil[$k]	= $call_dir;
				}else{
					$hasil[$k]	= '';
				}
				// if($check !== false){
				// }
			}
		}
		else{
			$hasil[$k]	= '';
		}
	}
	/*$target_file 	= $target_dir . basename($filenya["name"]);
	$uploadOk = 1;
	$imageFileType 	= strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$namana			= microtimeget().".".$imageFileType;
	$target_file	= $target_dir.$namana;
	$call_dir		= "https://".$_SERVER['HTTP_HOST']."/uploads/".$namana;
	$msgs			= "";
	$rc				= "00";
	$check = getimagesize($filenya["tmp_name"]);
	if($check !== false) {
		$tipes	= "2";
	} else {
		$tipes	= "4";
	}

	if (file_exists($target_file)) {
		$msgs	= "Sorry, file already exists.";
		$uploadOk = 0;
	}
	
	if ($filenya["size"] > 5000000) {
		$msgs	= "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	
	if ($uploadOk == 0) {
		
	} else {
		if (move_uploaded_file($filenya["tmp_name"], $target_file)) {
			$rc		= "200";
			$msgs	= $call_dir;
		} else {
			$msgs	= "Sorry, there was an error uploading your file.";
			$msgs	= $filenya;
		}
	}
	#return array("rc"=>$rc,"tipe"=>$tipes,"msg"=>$msgs,"msgs"=>$target_file,);*/
	return $hasil;
}

function upload_multi($filenya,$simpan = "admin"){
	$simpan			= "";
	$hasil			= array();
	$target_dir 	= $_SERVER['DOCUMENT_ROOT']."/".$simpan."/uploads/";
	$allowed		= array('jpg', 'jpeg', 'png', 'pdf', 'gif', 'pptx', 'xls', 'xlsx');
	foreach($filenya["name"] as $k => $v){
		if($v != ''){

			$ext 			= pathinfo($v, PATHINFO_EXTENSION);
			if(in_array($ext, $allowed)){
				$target_file 	= $target_dir . basename($filenya["name"][$k]);
				$imageFileType 	= strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				$namana			= microtimeget().".".$imageFileType;
				$target_file	= $target_dir.$namana;
				$call_dir		= "https://".$_SERVER['HTTP_HOST']."/uploads/".$namana;
				$check 			= getimagesize($filenya["tmp_name"][$k]);
				if($check !== false){
					move_uploaded_file($filenya["tmp_name"][$k], $target_file);
					$hasil[$k]	= $call_dir;
				}
			}
		}
		else{
			$hasil[$k]	= '';
		}
	}
	/*$target_file 	= $target_dir . basename($filenya["name"]);
	$uploadOk = 1;
	$imageFileType 	= strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$namana			= microtimeget().".".$imageFileType;
	$target_file	= $target_dir.$namana;
	$call_dir		= "https://".$_SERVER['HTTP_HOST']."/uploads/".$namana;
	$msgs			= "";
	$rc				= "00";
	$check = getimagesize($filenya["tmp_name"]);
	if($check !== false) {
		$tipes	= "2";
	} else {
		$tipes	= "4";
	}

	if (file_exists($target_file)) {
		$msgs	= "Sorry, file already exists.";
		$uploadOk = 0;
	}
	
	if ($filenya["size"] > 5000000) {
		$msgs	= "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	
	if ($uploadOk == 0) {
		
	} else {
		if (move_uploaded_file($filenya["tmp_name"], $target_file)) {
			$rc		= "200";
			$msgs	= $call_dir;
		} else {
			$msgs	= "Sorry, there was an error uploading your file.";
			$msgs	= $filenya;
		}
	}
	#return array("rc"=>$rc,"tipe"=>$tipes,"msg"=>$msgs,"msgs"=>$target_file,);*/
	return $hasil;
}

function get_tipe_answer($tipe){
	$retval = ""; 
	$arr_tipe =	array(
		"1" => "Text",
		"2" => "Image",
		"4" => "File",
		"20" => "AI",
		"41" => "Menu",
		"42" => "Button",
	);
	if (array_key_exists($tipe, $arr_tipe)) { $retval = $arr_tipe[$tipe]; }
	return $retval;
}

function get_menu($idmenu){
	global $bot_idx;
	$status		= false;
	$lolos		= true;
	$isi		= "";
	
	if(trim($idmenu) != ''){
		$datana				= array();
		$datana['act']		= "get_menu";
		$datana['idx']		= $idmenu;
		$datana['bot']		= $bot_idx;
		$kirim_api			= kirim_apis("menu_utama",$datana);
		if($kirim_api){
			$rc				= $kirim_api['rc'];
			$isi			= $kirim_api['message'];
			if($rc == "200"){
				$status		= true;
			}
			else{
				$datana				= array();
				$datana['act']		= "get_ai2";
				$datana['idx']		= $idmenu;
				$datana['bot']		= $bot_idx;
				$kirim_api	= kirim_apis("menu_utama",$datana);
				$rc				= $kirim_api['rc'];
				$isi			= $kirim_api['message'];
				if($rc == "200"){
					$status		= true;
				}
			}
		}
	}
	
	$msg		= array("status"=>$status,"isi"=>$isi,);
	return $msg;
}

function get_ai($idmenu){
	global $bot_idx;
	$status		= false;
	$lolos		= true;
	$isi		= "";
	
	if(trim($idmenu) != ''){
		$datana				= array();
		$datana['act']		= "get_ai";
		$datana['idx']		= $idmenu;
		$datana['bot']		= $bot_idx;
		$kirim_api			= kirim_apis("menu_utama",$datana);
		if($kirim_api){
			$rc				= $kirim_api['rc'];
			$isi			= $kirim_api['message'];
			if($rc == "200"){
				$status		= true;
			}
		}
	}
	
	$msg		= array("status"=>$status,"isi"=>$isi,);
	return $msg;
}

function get_ai2($idmenu){
	global $bot_idx;
	$status		= false;
	$lolos		= true;
	$isi		= "";
	
	if(trim($idmenu) != ''){
		$datana				= array();
		$datana['act']		= "get_ai2";
		$datana['idx']		= $idmenu;
		$datana['bot']		= $bot_idx;
		$kirim_api			= kirim_apis("menu_utama",$datana);
		if($kirim_api){
			$rc				= $kirim_api['rc'];
			$isi			= $kirim_api['message'];
			if($rc == "200"){
				$status		= true;
			}
		}
	}
	
	$msg		= array("status"=>$status,"isi"=>$isi,);
	return $msg;
}

function base64_encode_url($string)
{
  $out = base64_encode($string);
  $out = str_replace(array('+','/','='), array('-','_',''), $out);
  return $out;
}

function base64_decode_url($string)
{
  $out = str_replace(array('-', '_', ' ', "\t", "\r", "\n"),
    array('+', '/', ''), $string);
  $out .= substr( '====', (strlen($out) % 4) );
  $out = base64_decode($out);
  return $out;
}

function ecee($string, $konci) { 
$result = ''; 
for($i=0; $i<strlen($string); $i++) { 
$char = substr($string, $i, 1); 
$koncichar = substr($konci, ($i % strlen($konci))-1, 1); 
$char = chr(ord($char)+ord($koncichar)); 
$result.=$char; 
} 
return rawurlencode(base64_encode_url($result)); 
}

function dcee($string, $konci) {
$string = rawurldecode($string);	
$result = ''; 
$string = base64_decode_url($string); 
for($i=0; $i<strlen($string); $i++) { 
$char = substr($string, $i, 1); 
$koncichar = substr($konci, ($i % strlen($konci))-1, 1); 
$char = chr(ord($char)-ord($koncichar)); 
$result.=$char; 
} 
return $result; 
}

function push($tipe="1",$data="",$urle="",$content_data=""){
	#global $eva_name,$platform_id,$sender_chat_id;
	$eva_name	= "sasa";
	$platform_id	= "5";
	$sender_chat_id	= ecee("191381761","www.t-arakpop.com");
	$arr_push = array();
	$arr_push["content_type"] = $tipe;
	$arr_push["eva_name"] = $eva_name;
	$arr_push["platform_id"] = $platform_id;
	$arr_push["sender_chat_id"] = $sender_chat_id;
	$arr_push["text"] = $data;
	$arr_push["url_src"] = $urle;
	$arr_push["content_data"] = $content_data;
	$arr_push["keep_session"] = true;
	$push = push_pesanan($arr_push);
	return $push;
}

function push_pesanan($arr_push) {
		#$api_url = "https://api.eva.id/ais/"._AI_TOKEN."/send_msg";
		#$api_url = "https://helobot.eva.id/ais/". _AI_TOKEN ."/send_msg";
		#api_url = "https://kawaluyaan.eva.id/ais/4eafe271225c4225a88326278e301f30f5465b29/send_msg";
		$api_url = "http://10.6.11.209/ais/4eafe271225c4225a88326278e301f30f5465b29/send_msg";
		

		$ref_id                    = gmdate("YmdHis");
		$eva_name                  = ( isset($arr_push["eva_name"]) )  ? $arr_push["eva_name"] : ""; //Beny12
		$platform_id               = ( isset($arr_push["platform_id"]) )   ? $arr_push["platform_id"] : ""; //5
		$sender_chat_id            = ( isset($arr_push["sender_chat_id"]) )    ? $arr_push["sender_chat_id"] : ""; //n66tq2WmX5Wj
		$text                      = ( isset($arr_push["text"]) )  	? $arr_push["text"] : ""; //isiyang di push
		$url_src                   = ( isset($arr_push["url_src"]) )   ? $arr_push["url_src"] : ""; //isi kalo image
		$content_type              = ( isset($arr_push["content_type"]) )  ? $arr_push["content_type"] : ""; // 1 unutuk text saya
		$content_data              = ( isset($arr_push["content_data"]) )  ? $arr_push["content_data"] : ""; // kososngin aja kalo 1
		$keep_session   = ( isset($arr_push["keep_session"]) )  ? $arr_push["keep_session"] : ""; // true
		$date_send	               = gmdate("Y-m-d H:i:s");

    if($content_type == 6 || $content_type == 42)
    {
      $result_arr_push = array(
         "ref_id" => $ref_id,
         "eva_ref_id" => 0,
         "eva_name" => $eva_name,
         "dest_platform_id" => $platform_id,
         "dest_chat_id" => $sender_chat_id,
         "content_type" => $content_type,
         "content_data" => $content_data,
         "keep_session" => $keep_session,
         "eva_session" => "",
         "dt_sent" => $date_send,
    		);
    }else {
      $result_arr_push = array(
         "ref_id" => $ref_id,
         "eva_ref_id" => 0,
         "eva_name" => $eva_name,
         "dest_platform_id" => $platform_id,
         "dest_chat_id" => $sender_chat_id,
         "content_type" => $content_type,
         "content_data" => array("text" => $text, "url_src" => $url_src),
         "keep_session" => $keep_session,
         "eva_session" => "",
         "dt_sent" => $date_send,
         );
    }
		$output_data = json_encode($result_arr_push);
		$push_data = curl( $api_url, "post", $output_data );

		return $push_data;
	}
	
function paginate($item_per_page, $current_page, $total_records, $total_pages, $page_url)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination justify-content-end">';
        
        $right_links    = $current_page + 3; 
        $previous       = $current_page - 3; //previous link 
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link
        
        if($current_page > 1){
			$previous_link = ($previous==0)?1:$previous;
            $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'&page=1" title="First"><<</a></li>'; //first link
            $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'&page='.$previous_link.'" title="Previous">Previous</a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'&page='.$i.'">'.$i.'</a></li>';
                    }
                }   
            $first_link = false; //set first link to false
        }
        
        if($first_link){ //if current active page is first link
            #$pagination .= '<li class="first active">'.$current_page.'</li>';
            $pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'&page='.$current_page.'">'.$current_page.'</a></li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            #$pagination .= '<li class="last active">'.$current_page.'</li>';
            $pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'&page='.$current_page.'">'.$current_page.'</a></li>';
        }else{ //regular current link
            #$pagination .= '<li class="active">'.$current_page.'</li>';
			$pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'&page='.$current_page.'">'.$current_page.'</a></li>';
        }
                
        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'&page='.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){ 
				$next_link = ($i > $total_pages)? $total_pages : $i;
                $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'&page='.$next_link.'">Next</a></li>'; //next link
                $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'&page='.$total_pages.'" title="Last">>></a></li>'; //last link
        }
        
        $pagination .= '</ul>'; 
    }
    return $pagination; //return pagination links
}

function logs($teks){
    // die();
    $basename   = $_SERVER["SCRIPT_FILENAME"];
    $basename   = explode('/', $basename);
    $nama   = str_replace('.php', '', end($basename));
    $bt     = debug_backtrace();
    $caller = array_shift($bt);
    $line   = $caller['line'];
    $jamtgl = date("YmdHis");
    $date   = date("Ymd");
    $teksna = $jamtgl.'|'.$line.'= '.$teks.PHP_EOL;
    $myfile = fopen("/Applications/XAMPP/htdocs/pdam_karawng/logs/".$date.'_'.$nama.".log", "a+") or die("Unable to open file!");
    
    fwrite($myfile, $teksna);

    fclose($myfile);
    return true;
}
function errors($teks){
    // die();
    $basename   = $_SERVER["SCRIPT_FILENAME"];
    $basename   = explode('/', $basename);
    $nama   = 'error_'.str_replace('.php', '', end($basename));
    $bt     = debug_backtrace();
    $caller = array_shift($bt);
    $line   = $caller['line'];
    $jamtgl = date("YmdHis");
    $date   = date("Ymd");
    $teksna = $jamtgl.'|'.$line.'= '.$teks.PHP_EOL;
    $myfile = fopen("/Applications/XAMPP/htdocs/pdam_karawng/logs_error/".$date.'_'.$nama.".log", "a+") or die("Unable to open file!");
    
    fwrite($myfile, $teksna);

    fclose($myfile);
    return true;
}

function mlog($nama, $teks){
    // die();
    $jamtgl = date("YmdHis");
    $date   = date("Ymd");
    $teksna = $jamtgl.'= '.$teks.PHP_EOL;
    #TUTUP TULIS FILE DULU
	
	$myfile = fopen("/Applications/XAMPP/htdocs/pdam_karawng/logs/".$date.'_'.$nama.".log", "a+") or die("Unable to open file!");
    
    fwrite($myfile, $teksna);

    fclose($myfile);
    return true;
}

function paging($url, $page, $rownya, $maxnya=25, $cari=''){
	if($url == 'operator'){
		mlog('operator', __LINE__.'==='.$maxnya);
	}
	$paginate = '';
	$maxnya	= ceil($rownya/$maxnya);
	$kurang	= $page-1;
	$lebih	= $page+1;
	if($page > 1){
		$paginate	.= '<li class="first"><a href="'.$url.$cari.'&page=1" title="First">&#8810;</a></li>';
		$paginate	.= '<li><a href="'.$url.$cari.'&page='.$kurang.'"><</a></li>';
	}
	for ($i=0; $i < 3; $i++) {
		if($i == 0){
			if($page <= 1){

			}elseif($page >= 1){
				$paginate	.= '<li><a href="'.$url.$cari.'&page='.$kurang.'">'.$kurang.'</a></li>';
			}
		}elseif($i == 1){
			$paginate	.= '<li><a style="background-color: #f9d5d8;" href="'.$url.$cari.'&page='.$page.'">'.$page.'</a></li>';
		}elseif($i == 2){
			if($page >= $maxnya){

			}else{
				$paginate	.= '<li><a href="'.$url.$cari.'&page='.$lebih.'">'.$lebih.'</a></li>';
			}
		}
	}
	if($page < $maxnya){
		$paginate	.= '<li><a href="'.$url.$cari.'&page='.$lebih.'">></a></li>';
		$paginate	.= '<li class="last"><a href="'.$url.$cari.'&page='.$maxnya.'" title="Last">&#8811;</a></li>';
	}
	return $paginate;
}
?>