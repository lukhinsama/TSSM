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
//$SQLDate = "SELECT CONVERT(varchar(20),DATEADD(day,".$_REQUEST['day_look'].",GETDATE()),105) AS EFFDATE";
$SQLDate = "SELECT CONVERT(varchar(20),DATEADD(day,-1,GETDATE()),105) AS EFFDATE,DATEPART(DAY,GETDATE()) as [DAY]";
$stmtDate = sqlsrv_query($con,$SQLDate);
if ($resultDate = sqlsrv_fetch_array( $stmtDate, SQLSRV_FETCH_ASSOC)) {
  $EFFDATE = $resultDate['EFFDATE'];
  $DAY = $resultDate['DAY'];
}

if ($DAY == '1') {
  $DiffMonth = 1;
}else {
  $DiffMonth = 0;
}
//ฝ่ายขาย

$tokenSale = "IwKC3Z39TdQMlWNCGX6nCheXrQnNaytijEJf1hXezIK";
//$tokenSale = "Zv69QHxu28FmmLtkbL8HurTvqfEwu7gV1k9DeEW1jGW";
$SQL = "SELECT ED.SubDepartmentCode
,(
SELECT SUM(TotalPrice)
FROM Bighead_Mobile.dbo.Contract
WHERE LEFT(SaleCode,2) = ED.SubDepartmentCode AND IsMigrate = 0 AND ((STATUS ='NORMAL' AND isActive = 1) OR (STATUS ='F'))
AND DATEDIFF(MONTH,EFFDATE,GETDATE()) = ".$DiffMonth." GROUP BY LEFT(SaleCode,2)) AS TotalPrice
FROM Bighead_Mobile.dbo.EmployeeDetail AS ED
WHERE ED.ProcessType IN ('SALE') AND ED.SubDepartmentCode != 'SP'
GROUP BY ED.SubDepartmentCode
ORDER BY ED.SubDepartmentCode";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดขายสะสม สนง.\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)."\r\n";
$message = "";
$sumPrice = 0;
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "".$result['SubDepartmentCode']." ".number_format($result['TotalPrice'])." บาท\r\n";
  $sumPrice = $sumPrice + $result['TotalPrice'];
}
$message .= "รวม ".number_format($sumPrice)." บาท";
$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenSale);
  //echo $msg;
}
//ฝ่ายขาย

//ฝ่ายสาขา
$tokenBrn = "IwKC3Z39TdQMlWNCGX6nCheXrQnNaytijEJf1hXezIK";
//$tokenBrn = "Zv69QHxu28FmmLtkbL8HurTvqfEwu7gV1k9DeEW1jGW";
$SQL = "SELECT ED.SubDepartmentCode
,(
SELECT SUM(TotalPrice)
FROM Bighead_Mobile.dbo.Contract
WHERE LEFT(SaleCode,2) = ED.SubDepartmentCode AND IsMigrate = 0 AND ((STATUS ='NORMAL' AND isActive = 1) OR (STATUS ='F'))
AND DATEDIFF(MONTH,EFFDATE,GETDATE()) = ".$DiffMonth." GROUP BY LEFT(SaleCode,2)) AS TotalPrice
FROM Bighead_Mobile.dbo.EmployeeDetail AS ED
WHERE ED.ProcessType IN ('BRN') AND ED.SubDepartmentCode != 'SP'
GROUP BY ED.SubDepartmentCode
ORDER BY ED.SubDepartmentCode";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดขายสะสม สาขาตจว.\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)."\r\n";
$message = "";
$sumPrice = 0;
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "".$result['SubDepartmentCode']." ".number_format($result['TotalPrice'])." บาท\r\n";
  $sumPrice = $sumPrice + $result['TotalPrice'];
}
$message .= "รวม ".number_format($sumPrice)." บาท";
$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenBrn);
  //echo $msg;
}
//ฝ่ายสาขา

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
