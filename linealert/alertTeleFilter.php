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
//ฝ่ายขายทางโทรศัพท์

//$tokenSale = "Zv69QHxu28FmmLtkbL8HurTvqfEwu7gV1k9DeEW1jGW";
$tokenSale = "zw6Nt0x3DEPfx3SIpJtt2AJJw5E6KaoWMUWftU6ohdG";
//$tokenSale = "KSsnZQbp46ynbw0eO2ldZJbkI4v9TCD2jmHlc7WbsHv";


$SQL = "SELECT SupervisorCode,COUNT(C.RefNo) AS [contract]
FROM Bighead_Mobile.DBO.EmployeeDetail AS ed
LEFT JOIN Bighead_Mobile.dbo.Contract AS c ON LEFT(C.PreSaleEmployeeCode,3) = LEFT(SupervisorCode,3)
LEFT JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
INNER JOIN TSRData_Source.dbo.ProductLineAlert AS PA ON PA.ProductCode = P.ProductCode
WHERE ED.ProcessType IN ('TELE')
AND PositionCode = 'Supervisor'
AND SupervisorCode != 'TLT'
AND PA.ProductType = 2
AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']." AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".$_REQUEST['hour_end']." AND c.Status IN ('NORMAL','F') AND c.IsMigrate = 0
GROUP BY SupervisorCode
ORDER BY SupervisorCode
";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดขายสารกรอง ฝ่ายขายทางโทรศัพท์\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
$message = "";
$sumPoint = 0;
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "\r\nทีม ".$result['SupervisorCode']." ".$result['contract']." ชุด\r\n";
  $sumPoint = $sumPoint + $result['contract'];

  $SQL2 = "SELECT PA.ProductModel,ISNULL(num,0) AS [Contract]
            FROM TSRData_Source.dbo.ProductLineAlert AS PA
            LEFT JOIN (
            SELECT p.ProductCode,COUNT(C.RefNo) AS Num
            FROM Bighead_Mobile.dbo.Contract AS C
            LEFT JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
            WHERE DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']." AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." AND DATEPART(HOUR,c.EFFDATE) < ".$_REQUEST['hour_end']."
            AND c.Status IN ('NORMAL','F') AND c.IsMigrate = 0
 AND c.Status IN ('NORMAL','F') AND c.IsMigrate = 0
            AND LEFT(C.PreSaleEmployeeCode,3) = '".$result['SupervisorCode']."' --AND P.ProductName LIKE '%สารกรอง%'
            GROUP BY p.ProductCode
            ) AS CA ON Pa.ProductCode = CA.ProductCode
            where PA.ProductType = 2
            order by PA.ProductCode";
  $stmt2 = sqlsrv_query($con,$SQL2);
  while ($result2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
    $message .= "".$result2['ProductModel']." = ".$result2['Contract']." ชุด\r\n";
  }
}
$message .= "\r\nรวมทุกทีม ".$sumPoint." ชุด";
$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenSale);
  //echo $msg;
}

$SQL = "SELECT SupervisorCode,COUNT(C.RefNo) AS [contract]
FROM Bighead_Mobile.DBO.EmployeeDetail AS ed
LEFT JOIN Bighead_Mobile.dbo.Contract AS c ON LEFT(C.SaleCode,3) = LEFT(SupervisorCode,3)
LEFT JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
INNER JOIN TSRData_Source.dbo.ProductLineAlert AS PA ON PA.ProductCode = P.ProductCode
WHERE ED.ProcessType IN ('CRD')
AND PositionCode = 'Supervisor'
AND SupervisorCode != 'TLT'
AND PA.ProductType = 2 AND C.PreSaleEmployeeCode IS NULL
AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']." AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." AND DATEPART(HOUR,c.EFFDATE) < ".$_REQUEST['hour_end']." AND c.Status IN ('NORMAL','F') AND c.IsMigrate = 0
GROUP BY SupervisorCode
ORDER BY SupervisorCode
";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดขายสารกรอง ฝ่ายธุรกิจต่อเนื่อง\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
$message = "";
$sumPoint = 0;
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "\r\nทีม ".$result['SupervisorCode']." ".$result['contract']." ชุด\r\n";
  $sumPoint = $sumPoint + $result['contract'];

  $SQL2 = "SELECT PA.ProductModel,ISNULL(num,0) AS [Contract]
            FROM TSRData_Source.dbo.ProductLineAlert AS PA
            LEFT JOIN (
            SELECT p.ProductCode,COUNT(C.RefNo) AS Num
            FROM Bighead_Mobile.dbo.Contract AS C
            LEFT JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
            WHERE DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']." AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." AND DATEPART(HOUR,c.EFFDATE) < ".$_REQUEST['hour_end']." AND c.Status IN ('NORMAL','F') AND c.IsMigrate = 0
            AND LEFT(C.SaleCode,3) = '".$result['SupervisorCode']."' AND C.PreSaleEmployeeCode IS NULL
            GROUP BY p.ProductCode
            ) AS CA ON Pa.ProductCode = CA.ProductCode
            where PA.ProductType = 2
            order by PA.ProductCode";
  $stmt2 = sqlsrv_query($con,$SQL2);
  while ($result2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
    $message .= "".$result2['ProductModel']." = ".$result2['Contract']." ชุด\r\n";
  }
}
$message .= "\r\nรวมทุกทีม ".$sumPoint." ชุด";
$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenSale);
  //echo $msg;
}


$SQL = "SELECT SupervisorCode,COUNT(C.RefNo) AS [contract]
FROM Bighead_Mobile.DBO.EmployeeDetail AS ed
LEFT JOIN Bighead_Mobile.dbo.Contract AS c ON LEFT(C.SaleCode,3) = LEFT(SupervisorCode,3)
LEFT JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
INNER JOIN TSRData_Source.dbo.ProductLineAlert AS PA ON PA.ProductCode = P.ProductCode
WHERE ED.ProcessType IN ('CRD')
AND PositionCode = 'Supervisor'
AND SupervisorCode != 'TLT'
AND PA.ProductType = 1 AND C.PreSaleEmployeeCode IS NULL
AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']." AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." AND DATEPART(HOUR,c.EFFDATE) < ".$_REQUEST['hour_end']." AND c.Status IN ('NORMAL','F') AND c.IsMigrate = 0
GROUP BY SupervisorCode
ORDER BY SupervisorCode
";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดขายเครื่องกรองน้ำ ฝ่ายธุรกิจต่อเนื่อง\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
$message = "";
$sumPoint = 0;
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "\r\nทีม ".$result['SupervisorCode']." ".$result['contract']." เครื่อง\r\n";
  $sumPoint = $sumPoint + $result['contract'];

  $SQL2 = "SELECT PA.ProductModel,ISNULL(num,0) AS [Contract]
            FROM TSRData_Source.dbo.ProductLineAlert AS PA
            LEFT JOIN (
            SELECT p.ProductCode,COUNT(C.RefNo) AS Num
            FROM Bighead_Mobile.dbo.Contract AS C
            LEFT JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
            WHERE DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']." AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." AND DATEPART(HOUR,c.EFFDATE) < ".$_REQUEST['hour_end']." AND c.Status IN ('NORMAL','F') AND c.IsMigrate = 0
            AND LEFT(C.SaleCode,3) = '".$result['SupervisorCode']."' AND C.PreSaleEmployeeCode IS NULL
            GROUP BY p.ProductCode
            ) AS CA ON Pa.ProductCode = CA.ProductCode
            where PA.ProductType = 1
            order by PA.ProductCode";
  $stmt2 = sqlsrv_query($con,$SQL2);
  while ($result2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
    $message .= "".$result2['ProductModel']." = ".$result2['Contract']." เครื่อง\r\n";
  }
}
$message .= "\r\nรวมทุกทีม ".$sumPoint." เครื่อง";
$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenSale);
  //echo $msg;
}
//ฝ่ายขายทางโทรศัพท์
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
