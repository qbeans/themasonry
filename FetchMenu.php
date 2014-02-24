 <?php
 
header("Cache-Control: no-cache, must-revalidate");
 // Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$url     = "https://themasonry.ulutu.com/api/menus?api_key=";
$api_key = "db79fff71f78ae062488a4e83d2cc73c";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url.$api_key);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$ret = curl_exec($ch);
curl_close($ch);

$var = json_decode($ret);

//if the random number sid that is passed along with our AJAX call is not present, display all this debug text
if(!isset($_GET['sid']))
{
    echo "API Key:".$api_key."<br>".strlen($ret)." characters returned<br>";
    echo "<textarea style='width:600px;height:300px;'>".print_r($ret, true)."</textarea>";
    echo "<textarea style='width:600px;height:300px;'>".print_r($var, true)."</textarea><br>";
    echo "Api call was ".($var->result ? "" : "not")." successful<p>";

    foreach($var->menus as $id => $MenuGroup)
    {
        echo $MenuGroup->name."<br>";
    }

    echo "<hr>";
}
else //otherwise just return the raw JSON
{
    echo $ret;
}

/*

// commenting out for now, this is the api example of a bad response

$api_key = "db79fff71f78ae062488a4e83d2cc7ee";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url.$api_key);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$ret = curl_exec($ch);
curl_close($ch);

$var = json_decode($ret);

echo "API Key:".$api_key."<br>".strlen($ret)." characters returned<br>";
echo "<textarea style='width:600px;height:300px;'>".print_r($ret, true)."</textarea>";
echo "<textarea style='width:600px;height:300px;'>".print_r($var, true)."</textarea><br>";
echo "Api call was ".($var->result ? "" : "not")." successful<hr>";

*/
?>