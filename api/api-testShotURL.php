<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

if ((isset($_GET['telno'])) AND (isset($_GET['contno']))) {
  include("../sendsms/inc-function-sendsms.php");
  //$urlWithoutProtocol = "http://api.tsrurl.in/?full_url=http://toss.thiensurat.co.th/sv/?contract=".$_GET['contno']."&recorder_id=30371&record_channel=survey";
  $urlWithoutProtocol = "https://tssm.thiensurat.co.th/api/test.php";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
  curl_close($ch);
  echo $response;

  $msg = "ขอบคุณที่ใช้บริการผ่อนสบาย โปรดแนะนำบริการได้ที่";
  //var_dump($results);
  echo $msg;
  //echo Sendsms($_GET['telno'],$msg,"TOSS","TOSS");
  /*
  $short_url = json_decode(file_get_contents("http://api.bit.ly/v3/shorten?login=Lukhin&apiKey=ae66d3ee9640be07b4c0bc125fb4e4bf27855390&longUrl=".urlencode("http://toss.thiensurat.co.th/sv/?contract=".$_GET['contno']."")."&format=json"))->data->url;
  */
  //$msg = "ขอบคุณที่ใช้บริการผ่อนสบาย โปรดแนะนำบริการได้ที่ http://toss.thiensurat.co.th/sv/?contract=".$_GET['contno']."";
  //ECHO $msg;
  //echo Sendsms($_GET['telno'],$msg,"TOSS","TOSS");
}else {
  echo "ERROR";
}
?>
