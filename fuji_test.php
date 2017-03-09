<?php
error_reporting(E_ERROR);
// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://124.251.38.204:8380/JWebService/JService");

$headerArr[] = 'Content-Type:application/x-www-form-urlencoded';

$params = array(
    'brand' => array(
        'comment' => '1.1 查询商品品牌',
        'para' => '{"FIND_BRAND":{"COLUMNTYPE":[],"PARAMETER":[[""]]}}',
    ),
    'category' => array(
        'comment' => '1.2 查询商品类',
        'para' => '{"FIND_CATEGORY":{"COLUMNTYPE":[],"PARAMETER":[[""]]}}',
    ),
    'member' => array(
        'comment' => '1.3 查询会员信息',
        'para' => '{"FIND_MEMBER":{"COLUMNTYPE":["S"],"PARAMETER":[["' . $argv[2] .'"]]}}',
        'para_desc' => '{"FIND_MEMBER":{"COLUMNTYPE":["S"],"PARAMETER":[["argv[2]"]]}}',
    ),
    'single_goods' => array(
        'comment' => '1.4 查询商品主档信息',
        'para' => '{"FIND_GOODS":{"COLUMNTYPE":["I","S"],"PARAMETER":[["'. $argv[2] . '","A00001"]]}}',
        'para_desc' => '{"FIND_GOODS":{"COLUMNTYPE":["I","S"],"PARAMETER":[["argv[2]","A00001"]]}}',
    ),
    'goods' => array(
        'comment' => '1.5 查询商品变更信息sku码',
        'para' => '{"FIND_SKUID":{"COLUMNTYPE":["S"],"PARAMETER":[["'. $argv[2] .'"]]}}',
        //'para' => '{"FIND_SKUID":{"COLUMNTYPE":["S"],"PARAMETER":[["2011-01-01 00:00:01"]]}}',
        'para_desc' => '{"FIND_SKUID":{"COLUMNTYPE":["D"],"PARAMETER":[["argv[2]"]]}}',
    ),
    'pet' => array(
        'comment' => '1.9 宠物信息查询',
        'para' => '{"FIND_PET":{"COLUMNTYPE":["I"],"PARAMETER":[["'. $argv[2] .'"]]}}',
        'para_desc' => '{"FIND_PET":{"COLUMNTYPE":["I"],"PARAMETER":[["argv[2]"]]}}',
    ),
);

if (empty($argv[1]) || !isset($params[$argv[1]])) {
    help($params);
}

function help($params) {
    foreach ($params as $key => $info) {
        $para = isset($info['para_desc']) ? $info['para_desc'] : $info['para'];
        echo "argv[1]={$key} comment={$info['comment']} para={$para}\n";
    }
    exit;
}

$para = $params[$argv[1]]['para'];

$params = array(
    'appid' => 'O2OAPP',
    'apiid' => 'FRAME_O2OINTERFACE',
    'format' => 'json',
    'exeinfo' => '{SHOPID="A00001",USERID=1}',
    'para' => $para,
    'compression' => '0',
    'timestamp' => date("Y-m-d H:i:s", $_SERVER['REQUEST_TIME']),
);
var_dump($params);

$sign = "UYTGH098E68" . $params['appid'] . $params['apiid'] . $params['format'] .  $params['exeinfo']
    . $params['para'] . $params['compression'] . $params['timestamp'];

$params['sign'] = strtoupper(md5($sign));
$params['para'] = urlencode($params['para']);

//$postData = http_build_query($params);
$postData = '';
foreach ($params as $key => $value) {
    $postData .= "{$key}={$value}&";
}
$postData = rtrim($postData, '&');
//$postData = $params;
curl_setopt($ch, CURLOPT_HTTPHEADER , $headerArr);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
$ret = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

$retArr = json_decode($ret, 1);

var_dump($retArr);
