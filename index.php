<?php

include_once('simple_html_dom.php');
$cnt = 0;
//$HTMLData = file_get_contents('https://kiwami64.com/shopbrand/ct17/');
//$doc = phpQuery::newDocument($HTMLData);
$text_path = "compare.txt";

function alert(){
  $url = "https://hooks.slack.com/services/*******************************";
  $message = [
      "channel" => "#alert",
      "text" => "サイトに更新が加えられた可能性アリ：https://*******************************",
      "username" => "AlertBot",
  ];
  
  $ch = curl_init();
  $options = [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query([
          'payload' => json_encode($message)
      ])
  ];
  curl_setopt_array($ch, $options);
  curl_exec($ch);
  curl_close($ch);
}
$url="https://kaminagakinokoen.ocnk.net/product-list/15";
$cp = curl_init();
curl_setopt($cp, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($cp, CURLOPT_URL, $url);
curl_setopt($cp, CURLOPT_TIMEOUT, 30);
curl_setopt($cp, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
$data = curl_exec($cp);
//$data = mb_convert_encoding( $data, 'UTF-8', 'SJIS-win' );
curl_close($cp);
$html= str_get_html($data);
header('Content-Type: text/html; charset=UTF-8');
$source = $html->find('#pagetd')[0];
//$json = file_get_contents('cnt.json');
//$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
//$obj = json_decode($json, true);
//foreach($obj as $key => $val) {
//    echo "cnt:".$val["cnt"]."<br>";
//    echo "flag:".$val["flag"]."<br>" ;
//}
$current_text = @file_get_contents( $text_path );
$current_text = hash('sha256',$current_text);
$hashed_source = hash('sha256',$source);

echo "current_text:".$current_text."<br>";
echo "hashed_source:".$hashed_source;
if( $current_text !== $hashed_source ) {
    // 前の文字列と異なる場合、メールを送信
    alert();
    file_put_contents( $text_path, $source );
    echo "うまいこといってるよ";
  } else {
    // 変更がない場合スルー
  }
?>
