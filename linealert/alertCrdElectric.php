<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

//include("../include/inc-fuction.php");

define('LINE_API',"https://notify-api.line.me/api/notify");

$con = connectDB_Imy();
$SQLDate = "SELECT CONVERT(varchar(20),DATEADD(day,".$_REQUEST['day_look'].",GETDATE()),105) AS EFFDATE";
$stmtDate = sqlsrv_query($con,$SQLDate);
if ($resultDate = sqlsrv_fetch_array( $stmtDate, SQLSRV_FETCH_ASSOC)) {
  $EFFDATE = $resultDate['EFFDATE'];
}
if (isset($_REQUEST['day_look'])) {
  if ($_REQUEST['day_look'] == 0) {
    $SQLDate = "SELECT convert(varchar,GETDATE(),112) AS Todate";
  }else {
    $SQLDate = "SELECT convert(varchar,GETDATE()".$_REQUEST['day_look'].",112) AS Todate";
  }
}else {
  $SQLDate = "SELECT convert(varchar,GETDATE(),112) AS Todate";
}
$stmtDate = sqlsrv_query($con,$SQLDate);
if ($resultDate = sqlsrv_fetch_array( $stmtDate, SQLSRV_FETCH_ASSOC)) {
  $Day = $resultDate['Todate'];
}
$DayStart = $Day."000000";
if (isset($_REQUEST['hour_end'])) {
  $DayStop = $Day."".$_REQUEST['hour_end']."9999";
}else {
  $DayStop = $Day."999999";
}

//ฝ่ายขายทางโทรศัพท์

//$tokenCrd = "Zv69QHxu28FmmLtkbL8HurTvqfEwu7gV1k9DeEW1jGW";
$tokenCrd = "zw6Nt0x3DEPfx3SIpJtt2AJJw5E6KaoWMUWftU6ohdG";
//$tokenCrd = "KSsnZQbp46ynbw0eO2ldZJbkI4v9TCD2jmHlc7WbsHv";

$conLine = connectDB_BigHead();

// เพิ่มลงฐานข้อมูล
$sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_LogSendTimeLine (ReportName,StatusTime,StampDate) VALUES (?,?,GETDATE())";
$params = array("AlertCrdElectric","Start");
$stmt_insert = sqlsrv_query( $conLine, $sql_insert, $params);

$SQLLine = "SELECT [Line],[TeamCode]
  FROM [TSRData_Source].[dbo].[TSSM_LineAlertElectric]
  OrDER BY [id]";

$stmtLine = sqlsrv_query($conLine,$SQLLine);

$message = "";
$messageHead = "สรุปยอดวันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
$sumFilter = 0;
$sumElectric = 0;

while ($resultLine = sqlsrv_fetch_array( $stmtLine, SQLSRV_FETCH_ASSOC)) {

  $SQLLineSum = "SELECT SUM(Electric) AS SumElectric,sum(Filter) AS SumFilter
from (SELECT AGG.group_name,ISNULL((
  SELECT COUNT(request_id)
  from imind_tsr_db.dbo.v_CheckCode with (nolock)
  WHERE tele_last_update != ''
  AND (tele_last_update BETWEEN '".$DayStart."' AND '".$DayStop."')
  and relation_caption not like 'ติดตั้งผลิตภัณฑ์%'
  and relation_caption not like 'Inbound CS%'
  and relation_caption not like '%CRD%'
  AND group_tele IS NOT NULL
  AND (tele_result in ('45218','45549') AND qa_result in ('45454','45630'))
  AND group_tele = AGG.group_name
  GROUP BY group_tele
  ),0) AS Electric
  ,ISNULL((SELECT COUNT(request_id)
  from imind_tsr_db.dbo.v_CheckCode with (nolock)
  WHERE tele_last_update != ''
  AND (tele_last_update BETWEEN '".$DayStart."' AND '".$DayStop."')
  and relation_caption not like 'ติดตั้งผลิตภัณฑ์%'
  and relation_caption not like 'Inbound CS%'
  and relation_caption not like '%CRD%'
  AND group_tele IS NOT NULL
  AND (tele_result in ('45113') and qa_result in ('0'))
  AND group_tele = AGG.group_name
  GROUP BY group_tele
  ),0) AS Filter
  FROM [imind_tsr_db].[dbo].[agent_groups] AS AGG with (nolock)
    where active_flag = 1
    AND (group_name like '".$resultLine['TeamCode']."%')) as a";

//echo "SQLLineSum =".$SQLLineSum."= SQLLineSum";

    $stmtLineSum = sqlsrv_query($con,$SQLLineSum);

    while ($resultLineSum = sqlsrv_fetch_array( $stmtLineSum, SQLSRV_FETCH_ASSOC)) {
      $message .= "\r\n".$resultLine['Line']." สารกรอง ".$resultLineSum['SumFilter']." ไฟฟ้า ".$resultLineSum['SumElectric']."\r\n";
    }

  $SQL = "SELECT AGG.group_name,ISNULL((
  SELECT COUNT(request_id)
  from imind_tsr_db.dbo.v_CheckCode with (nolock)
  WHERE tele_last_update != ''
  AND (tele_last_update BETWEEN '".$DayStart."' AND '".$DayStop."')
  and relation_caption not like 'ติดตั้งผลิตภัณฑ์%'
  and relation_caption not like 'Inbound CS%'
  and relation_caption not like '%CRD%'
  AND group_tele IS NOT NULL
  AND (tele_result in ('45218','45549') AND qa_result in ('45454','45630'))
  AND group_tele = AGG.group_name
  GROUP BY group_tele
  ),0) AS Electric
  ,ISNULL((SELECT COUNT(request_id)
  from imind_tsr_db.dbo.v_CheckCode with (nolock)
  WHERE tele_last_update != ''
  AND (tele_last_update BETWEEN '".$DayStart."' AND '".$DayStop."')
  and relation_caption not like 'ติดตั้งผลิตภัณฑ์%'
  and relation_caption not like 'Inbound CS%'
  and relation_caption not like '%CRD%'
  AND group_tele IS NOT NULL
  AND (tele_result in ('45113'))
  AND group_tele = AGG.group_name
  GROUP BY group_tele
  ),0) AS Filter
  FROM [imind_tsr_db].[dbo].[agent_groups] AS AGG with (nolock)
    where active_flag = 1
    AND (group_name like '".$resultLine['TeamCode']."%')
    ORDER BY [group_name]
  ";
//echo "SQL =".$SQL."= SQL";

  $stmt = sqlsrv_query($con,$SQL);

  while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $message .= "".$result['group_name']." สารกรอง ".$result['Filter']." ไฟฟ้า ".$result['Electric']."\r\n";
    $sumFilter = $sumFilter + $result['Filter'];
    $sumElectric = $sumElectric + $result['Electric'];
  }

}
$sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_LogSendTimeLine (ReportName,StatusTime,StampDate) VALUES (?,?,GETDATE())";
$params = array("AlertCrdElectric","Stop");
$stmt_insert = sqlsrv_query( $conLine, $sql_insert, $params);
sqlsrv_close($conLine);
sqlsrv_close($con);

$message .= "\r\n รวม สารกรอง = ".$sumFilter." ไฟฟ้า = ".$sumElectric."";
$msg = $messageHead."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenCrd);
  //echo $msg;
}

//ฝ่ายขายทางโทรศัพท์


function notify_message($message,$stickerPkg,$stickerId,$token){
     $queryData = array(
      'message' => $message,
      'stickerPackageId'=>$stickerPkg,
      'stickerId'=>$stickerId
     );
     $queryData = http_build_query($queryData,'','&');
     $headerOptions = array(
         'http'=>array(
             'method'=>'POST',
             'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                 ."Authorization: Bearer ".$token."\r\n"
                       ."Content-Length: ".strlen($queryData)."\r\n",
             'content' => $queryData
         ),
     );
     $context = stream_context_create($headerOptions);
     $result = file_get_contents(LINE_API,FALSE,$context);
     $res = json_decode($result);
  return $res;
 }

 function connectDB_BigHead(){

 	$db_host = "192.168.110.133";
 	$db_name = "Bighead_Mobile";
 	$db_username = "TsrApp";
 	$db_password = "6z3sNrCzWp";

 	$connectionInfo = array("Database"=>$db_name, "UID"=>$db_username, "PWD"=>$db_password, 'CharacterSet' => 'UTF-8', "MultipleActiveResultSets"=>true);
   $conn = sqlsrv_connect( $db_host, $connectionInfo);

 	if( $conn === false ) {
     die( print_r( sqlsrv_errors(), true));
 	}

 	return $conn;
 }

  function connectDB_Imy(){
  	$db_host = "192.168.116.21";
  	$db_name = "imind_tsr_db";
  	$db_username = "IT_Dev";
  	$db_password = "P@ssw0rd";

  	$connectionInfo = array("Database"=>$db_name, "UID"=>$db_username, "PWD"=>$db_password, 'CharacterSet' => 'UTF-8', "MultipleActiveResultSets"=>true);
    $conn = sqlsrv_connect( $db_host, $connectionInfo);

  	if( $conn === false ) {
      die( print_r( sqlsrv_errors(), true));
  	}

  	return $conn;
  }

 function DateThai($strDate){
 		$strDate = date_format(date_create($strDate),"Y-m-d H:i:s");

 		$strYear = date("Y",strtotime($strDate))+543;
 		$strMonth= date("n",strtotime($strDate));
 		$strDay= date("j",strtotime($strDate));
 		$strHour= date("H",strtotime($strDate));
 		$strMinute= date("i",strtotime($strDate));
 		$strSeconds= date("s",strtotime($strDate));
 		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
 		$strMonthThai=$strMonthCut[$strMonth];
 		//return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
 		return "$strDay $strMonthThai $strYear";
 }
 ?>
