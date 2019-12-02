<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

//include("../include/inc-fuction.php");

define('LINE_API',"https://notify-api.line.me/api/notify");
$token = "9rKfIHoo7LAjV89kYRNAM4qeL3XsZNhiskwoSgUOnEl";
/*
$a = array("ทดสอบ","ระบบล่มแล้ว","แก้ด่วน","แก้เดี่ยวนี้","ตายๆ ตายห่าแน่ๆ");
$random_keys = array_rand($a,3);
$str = $a[$random_keys[0]];
*/
$con = connectDB_BigHead();

//ใบเสร็จ-2
$SQL = "SELECT ReceiptCode,CONVERT(varchar(20),DatePayment,105) +' '+ CONVERT(varchar(5),DatePayment,108) AS DatePayment,CreateBy
  FROM [Bighead_Mobile].[dbo].[Receipt]
  WHERE ReceiptCode like '%-2' AND ReceiptCode NOT IN (SELECT ReceiptCode FROM TSRData_Source.dbo.TSSM_LogReceiptAlertBle WHERE DATEDIFF(DAY,DateStamp,GETDATE()) = 0)
  AND DATEDIFF(DAY,GETDATE(),syncedDate) = 0";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "ใบเสร็จ-2 !! \r\n";
$message = "";
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "เลขที่ใบเสร็จ ".$result['ReceiptCode']." วันที่ออกใบเสร็จ ".DateTimeThai($result['DatePayment'])." รหัสพนักงาน ".$result['CreateBy']." \r\n";
  $sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_LogReceiptAlertBle (ReceiptCode,DateStamp) VALUES (?,GETDATE())";
  $params = array($result['ReceiptCode']);
  $stmt_insert = sqlsrv_query( $con, $sql_insert, $params);
}

$msg = $messageHead." ".$message;
if (!empty($message)) {
  notify_message($msg,"","",$token);
}
//ใบเสร็จ-2

//ใบเสร็จซ้ำ
$SQL = "SELECT R.ReceiptCode,C.CONTNO,R.ZoneCode,R.CreateBy
FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R
INNER JOIN Bighead_Mobile.dbo.Contract AS C ON R.refno = C.refno
WHERE ReceiptCode IN (
SELECT ReceiptCode
FROM TSRData_Source.dbo.vw_ReceiptWithZone
WHERE TotalPayment > 0
GROUP BY ReceiptCode
HAVING COUNT(ReceiptCode) > 1
)
AND DATEDIFF(DAY,DatePayment,GETDATE()) = 0
ORDER BY ReceiptCode,CONTNO";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "ใบเสร็จซ้ำ !! \r\n";
$message = "";
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "เลขที่ใบเสร็จ ".$result['ReceiptCode']." เลขที่สัญญา ".$result['CONTNO']." รหัสเขต ".$result['ZoneCode']." รหัสพนักงาน ".$result['CreateBy']." \r\n";
}

$msg = $messageHead." ".$message;
if (!empty($message)) {
  notify_message($msg,"","",$token);
}
//ใบเสร็จซ้ำ

//ใบงวดซ้ำ
$SQL = "SELECT RefNo,PaymentPeriodNumber , COUNT(PaymentPeriodNumber) AS NUM
,(SELECT CONTNO FROM Bighead_Mobile.dbo.Contract WHERE STATUS = 'NORMAL' AND isActive = 1 AND Sp.RefNo = RefNo) AS CONTNO
FROM Bighead_Mobile.dbo.SalePaymentPeriod AS Sp
WHERE RefNo IN (SELECT RefNo FROM Bighead_Mobile.dbo.Contract WHERE STATUS = 'NORMAL' AND isActive = 1)
GROUP BY RefNo,PaymentPeriodNumber
HAVING COUNT(PaymentPeriodNumber) > 1
ORDER BY RefNo , PaymentPeriodNumber";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "งวดซ้ำ !! \r\n";
$message = "";
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "เลขที่สัญญา ".$result['CONTNO']." งวดที่ ".$result['PaymentPeriodNumber']." จำนวน ".$result['NUM']." งวด \r\n";
}

$msg = $messageHead." ".$message;
if (!empty($message)) {
  notify_message($msg,"","",$token);
}
//ใบงวดซ้ำ


//ใบเสร็จมือซ้ำ
$SQL = "SELECT R.ReceiptCode,M.ManualVolumeNo,M.ManualRunningNo,COUNT(R.ReceiptID) AS NUM
FROM Bighead_Mobile.dbo.Receipt AS R
INNER JOIN Bighead_Mobile.dbo.ManualDocument AS M ON R.ReceiptID = M.DocumentNumber
WHERE M.ManualVolumeNo IS NOT NULL AND M.ManualRunningNo IS NOT NULL
GROUP BY R.ReceiptCode, M.ManualVolumeNo,M.ManualRunningNo
HAVING COUNT(R.ReceiptID) > 1";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "ใบเสร็จมือซ้ำ !! \r\n";
$message = "";
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "เลขใบเสร็จ ".$result['ReceiptCode']." เล่มใบเสร็จมือ ".$result['ManualVolumeNo']." เลขใบเสร็จมือ ".$result['ManualRunningNo']." \r\n";
}

$msg = $messageHead." ".$message;
if (!empty($message)) {
  notify_message($msg,"","",$token);
}
//ใบเสร็จมือซ้ำ

//รหัสผู้แนะนำผิด
$SQL = "SELECT
C.CONTNO
,C.ContractReferenceNo
,C.SaleCode
,REPLACE(C.PreSaleEmployeeCode,' ','') AS PreSaleEmployeeCode
,'Salecode ไม่พบในโครงสร้าง' AS AlertMsg
,CONVERT(varchar(20),C.EFFDATE,105) +' '+ CONVERT(varchar(5),C.EFFDATE,108) AS EFFDATE
FROM Bighead_Mobile.dbo.Contract AS C
WHERE PreSaleEmployeeCode IS NOT NULL AND PreSaleSaleCode IS NULL AND C.isActive = 1 AND PreSaleEmployeeCode NOT IN (SELECT DISTINCT SaleCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE SaleCode IS NOT NULL AND ProcessType IN ('CRD','TELE')) AND DATEDIFF(DAY,C.EFFDATE,GETDATE()) = 1
UNION ALL
SELECT
C.CONTNO
,C.ContractReferenceNo
,C.SaleCode
,REPLACE(C.PreSaleEmployeeCode,' ','') AS PreSaleEmployeeCode
,'Salecode เดียวกันกับพนักงานขาย' AS AlertMsg
,CONVERT(varchar(20),C.EFFDATE,105) +' '+ CONVERT(varchar(5),C.EFFDATE,108) AS EFFDATE
FROM Bighead_Mobile.dbo.Contract AS C
WHERE PreSaleEmployeeCode = SaleCode AND DATEDIFF(DAY,C.EFFDATE,GETDATE()) = 1 AND C.isActive = 1
ORDER BY EFFDATE DESC";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "รหัสผู้แนะนำผิดรูปแบบ !! \r\n";
$message = "";
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  //$message .= "เลขที่สัญญา ".$result['CONTNO']." รหัสพนักงานขาย ".$result['SaleCode']." รหัสผู้แนะนำ ".$result['PreSaleEmployeeCode']." \r\n";
}

$msg = $messageHead." ".$message;
if (!empty($message)) {
  notify_message($msg,"","",$token);
}
//รหัสผู้แนะนำผิด

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

 ?>
