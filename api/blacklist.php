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

// logs(json_encode($_GET));

$data['cmd']    = 'list';
$data['bot']    = (int)$bot;
$data['draw']   = (int)$draw;
$data['start']  = (int)$start;
$data['length'] = (int)$length;
$data['column'] = (int)!empty($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : '';
$data['dir']    = (int)!empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : '';
$data['search'] = !empty($_GET['search']['value']) ? $_GET['search']['value'] : '';
// logs(json_encode($data));

$post   = api_post($url_api.'api/blacklist.php', $data);
logs($post);

echo $post;
exit();

?>