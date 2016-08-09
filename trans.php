<?php

$word = $argv[1];

    $url = 'http://fanyi.youdao.com/translate?smartresult=dict&smartresult=rule&smartresult=ugc&sessionFrom=https://www.baidu.com/link';
    $postData = array(
        'type' => 'AUTO',
        'i' => $word,
        'doctype' => 'json',
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $result = curl_exec($ch);

    $ret = json_decode($result, 1);
    echo "\n-------------------------------------\n";
    if (!$ret) {
        echo "NO RESULT\n";
    }
    else {
        foreach ($ret['translateResult'] as $value) {
            foreach ($value as $_value) {
                echo "{$_value['src']} {$_value['tgt']}\n";
            }
        }
        foreach ($ret['smartResult']['entries'] as $_value) {
            echo $_value, "\n";
        }
    }

    echo "\n-------------------------------------\n";
