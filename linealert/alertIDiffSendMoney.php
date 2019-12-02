<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

?>
<meta charset="UTF-8">
<?php

//include("../include/inc-fuction.php");

define('LINE_API',"https://notify-api.line.me/api/notify");

$con = connectDB_BigHead();
//ฝ่ายขายทางโทรศัพท์
$tokenTest = "qxryis2mNjOsy6829vstXtOnv13pnv44oFZqZ361YOL";

$tokenLukhinGroup = "1Av44dSeDxHuW2GB6mMy9sDswSA0PkfheHK7YLqSLjr";

// เพิ่มลงฐานข้อมูล
$sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_LogSendTimeLine (ReportName,StatusTime,StampDate) VALUES (?,?,GETDATE())";
$params = array("AlertDiffSendMoney","Start");
$stmt_insert = sqlsrv_query( $con, $sql_insert, $params);

$message = "";

$sql = "SELECT convert(varchar,GETDATE(),120) as datetoday";
$stmt = sqlsrv_query($con,$sql);
while ($row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $messageHead = "ณ. วันที่ ".DateThai($row2['datetoday'])."\r\n";
}

//$messageHead = "% Aging ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n\n";

//หาการ์ดทั้งหมด
$message = "ฝ่ายขาย \r\n";
$sql = "SELECT CashCode,TeamCode,Emp.fname,Emp.lname,Payment.EmpID,Paydate,pay
,ISNULL((SELECT SUM(SendAmount) FROM Bighead_Mobile.dbo.SendMoney WHERE EmpID = Payment.EmpID AND Status = 'SENT' AND Payment.PayDate = PaymentDate ),0) AS SendMoney
,pay - ISNULL((SELECT SUM(SendAmount) FROM Bighead_Mobile.dbo.SendMoney WHERE EmpID = Payment.EmpID AND Status = 'SENT' AND Payment.PayDate = PaymentDate ),0) AS DiffMoney
FROM (
SELECT CashCode
,TeamCode
,EmpID
,CAST(CONVERT(varchar(10),P.PayDate,120) AS datetime) AS Paydate
,sum(PAYAMT) as pay
FROM Bighead_Mobile.dbo.Payment AS P
WHERE DATEDIFF(DAY,P.PayDate,GETDATE()) = 0
AND LEFT(P.CashCode,1) IN ('A','B','C','D','J','N','P')
GROUP BY CashCode,TeamCode,EmpID,CAST(CONVERT(varchar(10),P.PayDate,120) AS datetime)
) AS Payment
LEFT JOIN TSR_Application.dbo.TSR_Full_EmployeeLogic AS Emp ON Emp.empid = Payment.EmpID
WHERE pay - ISNULL((SELECT SUM(SendAmount) FROM Bighead_Mobile.dbo.SendMoney WHERE EmpID = Payment.EmpID AND Status = 'SENT' AND Payment.PayDate = PaymentDate ),0) >= 5000
";
$stmt = sqlsrv_query($con,$sql);
while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $message .= "".$row1['fname']." ".$row1['lname']." ทีม ".$row1['TeamCode']." เก็บเงิน ".number_format($row1['pay'])." ส่งเงิน ".number_format($row1['SendMoney'])." ค่างส่ง ".number_format($row1['DiffMoney'])."\r\n\n";
}
$message1 = $messageHead."".$message;

$message = "ฝ่ายCRD \r\n";
$sql = "SELECT CashCode,TeamCode,Emp.fname,Emp.lname,Payment.EmpID,Paydate,pay
,ISNULL((SELECT SUM(SendAmount) FROM Bighead_Mobile.dbo.SendMoney WHERE EmpID = Payment.EmpID AND Status = 'SENT' AND Payment.PayDate = PaymentDate ),0) AS SendMoney
,pay - ISNULL((SELECT SUM(SendAmount) FROM Bighead_Mobile.dbo.SendMoney WHERE EmpID = Payment.EmpID AND Status = 'SENT' AND Payment.PayDate = PaymentDate ),0) AS DiffMoney
FROM (
SELECT CashCode
,TeamCode
,EmpID
,CAST(CONVERT(varchar(10),P.PayDate,120) AS datetime) AS Paydate
,sum(PAYAMT) as pay
FROM Bighead_Mobile.dbo.Payment AS P
WHERE DATEDIFF(DAY,P.PayDate,GETDATE()) = 0
AND LEFT(P.CashCode,1) IN ('S','O','T')
GROUP BY CashCode,TeamCode,EmpID,CAST(CONVERT(varchar(10),P.PayDate,120) AS datetime)
) AS Payment
LEFT JOIN TSR_Application.dbo.TSR_Full_EmployeeLogic AS Emp ON Emp.empid = Payment.EmpID
WHERE pay - ISNULL((SELECT SUM(SendAmount) FROM Bighead_Mobile.dbo.SendMoney WHERE EmpID = Payment.EmpID AND Status = 'SENT' AND Payment.PayDate = PaymentDate ),0) >= 5000
";
$stmt = sqlsrv_query($con,$sql);
while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $message .= "".$row1['fname']." ".$row1['lname']." ทีม ".$row1['TeamCode']." เก็บเงิน ".number_format($row1['pay'])." ส่งเงิน ".number_format($row1['SendMoney'])." ค่างส่ง ".number_format($row1['DiffMoney'])."\r\n\n";
}
$message2 = $messageHead."".$message;

$message = "ฝ่ายOnline \r\n";
$sql = "SELECT CashCode,TeamCode,Emp.fname,Emp.lname,Payment.EmpID,Paydate,pay
,ISNULL((SELECT SUM(SendAmount) FROM Bighead_Mobile.dbo.SendMoney WHERE EmpID = Payment.EmpID AND Status = 'SENT' AND Payment.PayDate = PaymentDate ),0) AS SendMoney
,pay - ISNULL((SELECT SUM(SendAmount) FROM Bighead_Mobile.dbo.SendMoney WHERE EmpID = Payment.EmpID AND Status = 'SENT' AND Payment.PayDate = PaymentDate ),0) AS DiffMoney
FROM (
SELECT CashCode
,TeamCode
,EmpID
,CAST(CONVERT(varchar(10),P.PayDate,120) AS datetime) AS Paydate
,sum(PAYAMT) as pay
FROM Bighead_Mobile.dbo.Payment AS P
WHERE DATEDIFF(DAY,P.PayDate,GETDATE()) = 0
AND LEFT(P.CashCode,1) IN ('O')
GROUP BY CashCode,TeamCode,EmpID,CAST(CONVERT(varchar(10),P.PayDate,120) AS datetime)
) AS Payment
LEFT JOIN TSR_Application.dbo.TSR_Full_EmployeeLogic AS Emp ON Emp.empid = Payment.EmpID
WHERE pay - ISNULL((SELECT SUM(SendAmount) FROM Bighead_Mobile.dbo.SendMoney WHERE EmpID = Payment.EmpID AND Status = 'SENT' AND Payment.PayDate = PaymentDate ),0) >= 5000
";
$stmt = sqlsrv_query($con,$sql);
while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $message .= "".$row1['fname']." ".$row1['lname']." ทีม ".$row1['TeamCode']." เก็บเงิน ".number_format($row1['pay'])." ส่งเงิน ".number_format($row1['SendMoney'])." ค้างส่ง ".number_format($row1['DiffMoney'])."\r\n\n";
}
$message3 = $messageHead."".$message;

$sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_LogSendTimeLine (ReportName,StatusTime,StampDate) VALUES (?,?,GETDATE())";
$params = array("AlertDiffSendMoney","Stop");
$stmt_insert = sqlsrv_query( $con, $sql_insert, $params);

sqlsrv_close($con);

//$message .= "\r\n รวม สารกรอง = ".$sumFilter." ไฟฟ้า = ".$sumElectric."";
//$message = $messageHead." ".$message;
if (!empty($message)) {
  notify_message($message1,"","",$tokenLukhinGroup);
  notify_message($message2,"","",$tokenLukhinGroup);
  notify_message($message3,"","",$tokenLukhinGroup);
  echo $message1;
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
