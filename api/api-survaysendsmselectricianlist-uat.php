<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include("../sendsms/inc-function-sendsms.php");

if ((isset($_GET['telno'])) AND (isset($_GET['contno']))) {
  /* ShotURL BY bitly
  require_once('bitly.php');

  $user_access_token = 'ae66d3ee9640be07b4c0bc125fb4e4bf27855390';
  $user_login = 'Lukhin';
  $user_api_key = '';

  $params = array();
  $params['access_token'] = $user_access_token;
  $params['longUrl'] = 'http://toss.thiensurat.co.th/sv/?contract='.$_GET['contno'].'';
  $params['domain'] = 'j.mp';
  $results = bitly_get('shorten', $params);
  //$msg = "ขอบคุณที่ใช้บริการผ่อนสบาย โปรดแนะนำบริการได้ที่ ".$results['data']['url']."";
  */
  $longURL = "http://toss.thiensurat.co.th/sv/?contract=".$_GET['contno'];
  $urlWithoutProtocol = "http://api.tsrurl.in/?full_url=".$longURL."&recorder_id=30371&record_channel=survey";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
  curl_close($ch);
  //echo $response;
  $shotURL = $response;

  if (substr($shotURL,0,1) == "h") {
    $msg = "ขอบคุณที่ใช้บริการผ่อนสบาย แนะนำบริการได้ที่ ".$shotURL;
  }else {
    $msg = "ขอบคุณที่ใช้บริการผ่อนสบาย แนะนำบริการได้ที่ ".$longURL;
  }
  //$msg = "ขอบคุณที่ใช้บริการผ่อนสบาย โปรดแนะนำบริการได้ที่ http://toss.thiensurat.co.th/sv/?contract=".$_GET['contno']."";
  $con = connectDB_TSR();
    $sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_LogSendSmsSurvay (contno,telno,longURL,shotURL,message,stamptime) VALUES (?,?,?,?,?,GETDATE())";
    $params = array($_GET['contno'],$_GET['telno'],$longURL,$shotURL,$msg);
    $stmt_insert = sqlsrv_query( $con, $sql_insert, $params);
  sqlsrv_close($con);

  echo Sendsms($_GET['telno'],$msg,"TOSS","TOSS");
}else {
  echo "error";
}

?>
