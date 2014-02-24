<?php

$url     = "https://themasonry.ulutu.com/api/menus?api_key=";
$api_key = "db79fff71f78ae062488a4e83d2cc73c";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url.$api_key);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$ret = curl_exec($ch);
curl_close($ch);

$var = json_decode($ret);

echo "<textarea style='width:600px;height:300px;'>".print_r($var, true)."</textarea><br>";
echo $var->menus."<br>";
echo "Api call was ".($var->result ? "" : "not")." successful<hr>";

?>