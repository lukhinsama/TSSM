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
//ฝ่ายขาย

$tokenSale = "IwKC3Z39TdQMlWNCGX6nCheXrQnNaytijEJf1hXezIK";
$tokenSaleTech = "Zv69QHxu28FmmLtkbL8HurTvqfEwu7gV1k9DeEW1jGW";

$SQL = "SELECT SubDepartmentCode,ContractAll,PayComplete
,format(CASE WHEN PayComplete = 0 THEN 0 ELSE (CONVERT(float,PayComplete)*100)/(CONVERT(float,ContractAll)) END , '#,##0.00') AS percen
FROM (
SELECT Ed.SubDepartmentCode
,ISNULL((
SELECT
CoUNT(c.refno)
FROM Bighead_Mobile.dbo.Contract AS C
WHERE C.STATUS IN ('NORMAL','F') AND IsMigrate = 0 AND
LEFT(C.saleCode,2) = ed.SubDepartmentCode
AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']."  AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])."
),0) AS ContractAll
,ISNULL((
SELECT
CoUNT(c.refno)
FROM Bighead_Mobile.dbo.Contract AS C
INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S ON S.RefNo = C.RefNo AND S.PaymentPeriodNumber = 1 AND S.PaymentComplete = 1
WHERE C.STATUS IN ('NORMAL','F') AND IsMigrate = 0 AND
LEFT(C.saleCode,2) = ed.SubDepartmentCode
AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']."  AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])."
),0) AS PayComplete
FROM Bighead_Mobile.DBO.EmployeeDetail AS ed
WHERE ED.ProcessType IN ('SALE')
AND ED.PositionCode = 'LineManager'
AND ed.SubDepartmentCode != 'SP'
) AS result
ORDER BY SubDepartmentCode";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดเก็บเงิน สนง.\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
$message = "";
$SumContractAll = 0;
$SumPayComplete = 0;
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "".$result['SubDepartmentCode']." ".$result['ContractAll']." (".$result['PayComplete'].") = ".$result['percen']."%\r\n";
  $SumContractAll = $SumContractAll + $result['ContractAll'];
  $SumPayComplete = $SumPayComplete + $result['PayComplete'];
}
$Sumpercen = ($SumPayComplete*100)/$SumContractAll;
$message .= "รวม ".$SumContractAll." (".$SumPayComplete.") = ".number_format($Sumpercen,2)."%";
$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenSale);
  notify_message($msg,"","",$tokenSaleTach);
  //echo $msg;
}
//ฝ่ายขาย

//ฝ่ายสาขา
$tokenBrn = "IwKC3Z39TdQMlWNCGX6nCheXrQnNaytijEJf1hXezIK";
$tokenBrnTach = "Zv69QHxu28FmmLtkbL8HurTvqfEwu7gV1k9DeEW1jGW";
$SQL = "SELECT SubDepartmentCode,ContractAll,PayComplete
,format(CASE WHEN PayComplete = 0 THEN 0 ELSE (CONVERT(float,PayComplete)*100)/(CONVERT(float,ContractAll)) END , '#,##0.00') AS percen
FROM (
SELECT Ed.SubDepartmentCode
,ISNULL((
SELECT
CoUNT(c.refno)
FROM Bighead_Mobile.dbo.Contract AS C
WHERE C.STATUS IN ('NORMAL','F') AND IsMigrate = 0 AND
LEFT(C.saleCode,2) = ed.SubDepartmentCode
AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']."  AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])."
),0) AS ContractAll
,ISNULL((
SELECT
CoUNT(c.refno)
FROM Bighead_Mobile.dbo.Contract AS C
INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S ON S.RefNo = C.RefNo AND S.PaymentPeriodNumber = 1 AND S.PaymentComplete = 1
WHERE C.STATUS IN ('NORMAL','F') AND IsMigrate = 0 AND
LEFT(C.saleCode,2) = ed.SubDepartmentCode
AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']."  AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])."
),0) AS PayComplete
FROM Bighead_Mobile.DBO.EmployeeDetail AS ed
WHERE ED.ProcessType IN ('BRN')
AND ED.PositionCode = 'LineManager'
AND ed.SubDepartmentCode != 'SP'
) AS result
ORDER BY SubDepartmentCode";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดเก็บเงิน สาขาตจว.\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
$message = "";
$SumContractAll = 0;
$SumPayComplete = 0;
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "".$result['SubDepartmentCode']." ".$result['ContractAll']." (".$result['PayComplete'].") = ".$result['percen']."%\r\n";
  $SumContractAll = $SumContractAll + $result['ContractAll'];
  $SumPayComplete = $SumPayComplete + $result['PayComplete'];
}
$Sumpercen = ($SumPayComplete*100)/$SumContractAll;
$message .= "รวม ".$SumContractAll." (".$SumPayComplete.") = ".number_format($Sumpercen,2)."%";
$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenBrn);
  notify_message($msg,"","",$tokenBrnTach);
  //echo $msg;
}
//ฝ่ายสาขา

//สาย

$tokenLine = "X06WZHEexNmtc4pJlUCiFnfVHkSxXyMRCmsDE08OnQo";
$tokenLineTach = "Zv69QHxu28FmmLtkbL8HurTvqfEwu7gV1k9DeEW1jGW";

$SQLLine = "SELECT DISTINCT LEFT(SupervisorCode,2) AS LineM FROM TSRData_Source.dbo.EmployeeDataParent_ALL WHERE StatusType IN ('SALE','BRN') AND  LEFT(SupervisorCode,2) != 'SP' ORDER BY LEFT(SupervisorCode,2) ";

$stmtLine = sqlsrv_query($con,$SQLLine);

while ($resultline = sqlsrv_fetch_array($stmtLine, SQLSRV_FETCH_ASSOC)) {
  $lineM = $resultline['LineM'];
    $SQL = "SELECT EmployeeName,ContractAll,PayComplete
            ,format(CASE WHEN PayComplete = 0 THEN 0 ELSE (CONVERT(float,PayComplete)*100)/(CONVERT(float,ContractAll)) END , '#,##0.00') AS percen
            FROM (
            SELECT Ed.EmployeeName
            ,ISNULL((
            SELECT
            CoUNT(c.refno)
            FROM Bighead_Mobile.dbo.Contract AS C
            WHERE LEFT(C.saleCode,4) = ed.SupervisorCode AND C.STATUS IN ('NORMAL','F') AND IsMigrate = 0
            AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']."  AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])."
            ),0) AS ContractAll
            ,ISNULL((
            SELECT
            CoUNT(c.refno)
            FROM Bighead_Mobile.dbo.Contract AS C
            INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S ON S.RefNo = C.RefNo AND S.PaymentPeriodNumber = 1 AND S.PaymentComplete = 1
            WHERE LEFT(C.saleCode,4) = ed.SupervisorCode AND C.STATUS IN ('NORMAL','F') AND IsMigrate = 0
            AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']."  AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])."
            ),0) AS PayComplete

            FROM Bighead_Mobile.DBO.EmployeeDetail AS ed
            WHERE ED.ProcessType IN ('SALE','BRN')
            AND ED.PositionCode = 'Supervisor'
            AND ED.SubDepartmentCode = '".$lineM."'
            ) AS result
            ORDER BY EmployeeName";

  $stmt = sqlsrv_query($con,$SQL);
  $messageHead = "สรุปยอดเก็บเงินสาย ".$lineM."\r\n";
  $messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
  $message = "";
  $SumContractAll = 0;
  $SumPayComplete = 0;
  while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $message .= "".$result['EmployeeName']." ".$result['ContractAll']." (".$result['PayComplete'].") = ".$result['percen']."%\r\n";
    $SumContractAll = $SumContractAll + $result['ContractAll'];
    $SumPayComplete = $SumPayComplete + $result['PayComplete'];
  }
  $Sumpercen = ($SumPayComplete*100)/$SumContractAll;
  $message .= "รวม ".$SumContractAll." (".$SumPayComplete.") = ".number_format($Sumpercen,2)."%";
  $msg = $messageHead."".$messageHead2."".$message;
  if (!empty($message)) {
    notify_message($msg,"","",$tokenLine);
    notify_message($msg,"","",$tokenLineTach);
    //echo $msg;
  }
}
//สาย

//ซุปฯ
$SQLLine = "SELECT LineGroup,LineToken FROM TSRData_Source.dbo.LineAlert_LineGroupToken ORDER BY id ";

$stmtLine = sqlsrv_query($con,$SQLLine);

while ($resultline = sqlsrv_fetch_array($stmtLine, SQLSRV_FETCH_ASSOC)) {
  $tokenSub = $resultline['LineToken'];
  $tokenSubTach = "Zv69QHxu28FmmLtkbL8HurTvqfEwu7gV1k9DeEW1jGW";
  $SQLSup = "SELECT EmployeeName,SupervisorCode
from Bighead_Mobile.DBO.EmployeeDetail
WHERE ProcessType IN ('SALE','BRN') and PositionCode = 'Supervisor' AND LEFT(SupervisorCode,2) = '".$resultline['LineGroup']."' ORDER BY SupervisorCode";

  $stmtSup = sqlsrv_query($con,$SQLSup);

  while ($resultsup = sqlsrv_fetch_array($stmtSup, SQLSRV_FETCH_ASSOC)) {
    $supM = $resultsup['SupervisorCode'];

    $SQL = "SELECT EmployeeName,ContractAll,PayComplete
            ,format(CASE WHEN PayComplete = 0 THEN 0 ELSE (CONVERT(float,PayComplete)*100)/(CONVERT(float,ContractAll)) END , '#,##0.00') AS percen
            FROM (
            SELECT Ed.EmployeeName
            ,ISNULL((
            SELECT
            CoUNT(c.refno)
            FROM Bighead_Mobile.dbo.Contract AS C
            WHERE C.saleteamcode = ed.TeamCode AND C.STATUS IN ('NORMAL','F') AND IsMigrate = 0
            AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']."  AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])."
            ),0) AS ContractAll
            ,ISNULL((
            SELECT
            CoUNT(c.refno)
            FROM Bighead_Mobile.dbo.Contract AS C
            INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S ON S.RefNo = C.RefNo AND S.PaymentPeriodNumber = 1 AND S.PaymentComplete = 1
            WHERE C.saleteamcode = ed.TeamCode AND C.STATUS IN ('NORMAL','F') AND IsMigrate = 0
            AND DATEDIFF(DAY,GETDATE(),effdate) = ".$_REQUEST['day_look']."  AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])."

            ),0) AS PayComplete
            FROM Bighead_Mobile.DBO.EmployeeDetail AS ed
            WHERE ED.ProcessType IN ('SALE','BRN')
            AND ED.PositionCode = 'SaleLeader'
            AND ED.SupervisorCode = '".$supM."'
            ) AS result
            ORDER BY EmployeeName";

    $stmt = sqlsrv_query($con,$SQL);

    $messageHead = "สรุปยอดเก็บเงิน ".$resultsup['EmployeeName']."\r\n";
    $messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
    $message = "";
    $SumContractAll = 0;
    $SumPayComplete = 0;
    while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      $message .= "".$result['EmployeeName']." ".$result['ContractAll']." (".$result['PayComplete'].") = ".$result['percen']."%\r\n";
      $SumContractAll = $SumContractAll + $result['ContractAll'];
      $SumPayComplete = $SumPayComplete + $result['PayComplete'];
    }
    $Sumpercen = ($SumPayComplete*100)/$SumContractAll;
    $message .= "รวม ".$SumContractAll." (".$SumPayComplete.") = ".number_format($Sumpercen,2)."%";
    $msg = $messageHead."".$messageHead2."".$message;
    if (!empty($message)) {
      notify_message($msg,"","",$tokenSub);
      notify_message($msg,"","",$tokenSubTach);
      //echo $tokenLine."<BR>";
      //echo $resultsup['SupervisorCode']."<BR>";
    }
  }
}
//ซุปฯ
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
