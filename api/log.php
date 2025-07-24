<?php
require '../libs/func.php';

// {
//     "cmd":"list",
//     "bot":167116906433373188,
//     "start":0,
//     "length":10,
//     "column":3,
//     "dir":"DESC",
//     "search":""
// }

// $page   = regis('page', 1);
// $limit   = regis('limit', 25);
$draw   = regis('draw');
$db     = regis('db', 167116906433128094);
$bot    = regis('bot', 167116906433373188);
$start  = regis('start', 0);
$length = regis('length', 10);
$awal   = regis('awal');
$akhir  = regis('akhir');

// logs(json_encode($_GET));

$data['cmd']    = 'list';
$data['db']     = (int)$db;
$data['draw']   = (int)$draw;
$data['start']  = (int)$start;
$data['length'] = (int)$length;
$data['column'] = (int)!empty($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : '';
$data['dir']    = (int)!empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'DESC';
$data['search'] = !empty($_GET['search']['value']) ? $_GET['search']['value'] : '';
$data['awal']   = $awal;
$data['akhir']  = $akhir;
// logs(json_encode($data));

$post   = api_post($url_api.'api/logs.php', $data);
logs($post);

echo $post;
exit();

?>