<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

//include("../include/inc-fuction.php");

define('LINE_API',"https://notify-api.line.me/api/notify");
//$token = "shYPCwaXno0VWyK6bVMtCRl7TZLH5p4iKsn9bPhendp";
//$tokenTest = "RMKK94PpmDyV5dygGXggKHSf2YQuBXVCNFT1Eo8TpuB";

/*
$a = array("ทดสอบ","ระบบล่มแล้ว","แก้ด่วน","แก้เดี่ยวนี้","ตายๆ ตายห่าแน่ๆ");
$random_keys = array_rand($a,3);
$str = $a[$random_keys[0]];
*/
$con = connectDB_BigHead();
$SQLDate = "SELECT CONVERT(varchar(20),DATEADD(day,".$_REQUEST['day_look'].",GETDATE()),105) AS EFFDATE";
$stmtDate = sqlsrv_query($con,$SQLDate);
if ($resultDate = sqlsrv_fetch_array( $stmtDate, SQLSRV_FETCH_ASSOC)) {
  $EFFDATE = $resultDate['EFFDATE'];
}
//ฝ่ายเครดิต %เก็บเงิน

$tokenSale = "Zv69QHxu28FmmLtkbL8HurTvqfEwu7gV1k9DeEW1jGW";
$SQL = "SELECT DISTINCT SupervisorCode
FROM Bighead_Mobile.dbo.EmployeeDetail
WHERE ProcessType = 'CREDIT'
AND (SupervisorCode IS NOT NULL AND SupervisorCode != 'YAAA000' AND SupervisorCode IS NOT NULL AND SupervisorCode NOT IN ('103','105','108','109'))
ORDER BY SupervisorCode
";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปการเก็บเงิน ฝ่ายเครดิต\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)."\r\n";
$message = "";

while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "\r\nสาย ".$result['SupervisorCode']."\r\n";

  $SQL2 = "SELECT DISTINCT ED.SupervisorCode
,(SELECT COUNT(DISTINCT [CONTNO]) FROM [TSRData_Source].[dbo].[TSSM_LogCreditPayment] WHERE ED.SupervisorCode = LEFT(AssigneeTeamCode,3)) AS ConTractAll
,(SELECT COUNT(DISTINCT [CONTNO]) FROM [TSRData_Source].[dbo].[TSSM_LogCreditPayment] WHERE ED.SupervisorCode = LEFT(AssigneeTeamCode,3) AND PaymentComplete = 1) AS Payment
,(SELECT COUNT(DISTINCT [CONTNO]) FROM [TSRData_Source].[dbo].[TSSM_LogCreditPayment] WHERE ED.SupervisorCode = LEFT([CashCode],3)) AS Credit
,(SELECT COUNT(DISTINCT [CONTNO]) FROM [TSRData_Source].[dbo].[TSSM_LogCreditPayment] WHERE ED.SupervisorCode = LEFT([CashCode],3) AND PaymentComplete = 1) AS CreditComplete
,(SELECT COUNT(DISTINCT [CONTNO]) FROM [TSRData_Source].[dbo].[TSSM_LogCreditPayment] WHERE ED.SupervisorCode = LEFT(AssigneeTeamCode,3) AND PaymentComplete = 1 AND cashcode != '90101623' AND LEFT(cashcode,1) = '9') AS Bank
,(SELECT COUNT(DISTINCT [CONTNO]) FROM [TSRData_Source].[dbo].[TSSM_LogCreditPayment] WHERE ED.SupervisorCode = LEFT(AssigneeTeamCode,3) AND PaymentComplete = 1 AND cashcode = '90101623') AS QR
FROM Bighead_Mobile.dbo.EmployeeDetail AS ED
WHERE ED.ProcessType = 'Credit' AND ED.SupervisorCode = '".$result['SupervisorCode']."'
ORDER BY ED.SupervisorCode";

  $stmt2 = sqlsrv_query($con,$SQL2);
  $percen = 0;
  while ($result2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
    $percen = (($result2['CreditComplete']+$result2['Bank']+$result2['QR'])*100)/$result2['Credit'];
    $message .= "จำนวนสัญญา ".number_format($result2['Credit'])." เก็บเงินได้ ".number_format($result2['CreditComplete'])." โอนเงิน ".number_format($result2['Bank'])." QRPayment ".number_format($result2['QR'])." คิดเป็น ".number_format($percen,2)." %\r\n";
  }
}

$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenSale);
  //echo $msg;
}
//ฝ่ายเครดิต %เก็บเงิน
sqlsrv_close($con);

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
