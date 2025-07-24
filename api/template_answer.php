<?php
require '../libs/func.php';

$page   = regis('page', 1);
$limit   = regis('limit', 25);
$draw   = regis('draw', 25);
$db   = regis('db', 167116906433128094);

logs(json_encode($_POST));
logs(json_encode($_GET));
$data['cmd'] = 'list';
$data['db'] = $db;
$data['page'] = $page;
$data['draw'] = $draw;
$data['search'] = !empty($_GET['search']['value']) ? $_GET['search']['value'] : '';
$post   = api_post($url_api.'api/template_answer.php', $data);
echo $post;
exit();

?>