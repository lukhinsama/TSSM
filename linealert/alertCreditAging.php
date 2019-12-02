<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

?>
<meta charset="UTF-8">
<?php

//include("../include/inc-fuction.php");

define('LINE_API',"https://notify-api.line.me/api/notify");

$con = connectDB_BigHead();
/*
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
*/

//ฝ่ายขายทางโทรศัพท์
$tokenTest = "qxryis2mNjOsy6829vstXtOnv13pnv44oFZqZ361YOL";

$tokenLukhinGroup = "1Av44dSeDxHuW2GB6mMy9sDswSA0PkfheHK7YLqSLjr";

// เพิ่มลงฐานข้อมูล
$sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_LogSendTimeLine (ReportName,StatusTime,StampDate) VALUES (?,?,GETDATE())";
$params = array("AlertCreditAging","Start");
$stmt_insert = sqlsrv_query( $con, $sql_insert, $params);

$message = "";
//$messageHead = "% Aging ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n\n";

//หาทริปเก็บเงิน
/*
$sql = "SELECT Fortnight_no,Fortnight_year
,convert(varchar,mindate,120) as mindate
,convert(varchar(10),mindate,105) as startdate
,convert(varchar(10),maxdate,120)+' 23:59:59.999' AS maxdate
--,convert(varchar(10),maxdate,105) as stopdate
,convert(varchar(10),getdate(),105) as stopdate
from TSR_Application.dbo.view_Fortnight_Table_M with(NOLOCK)
WHERE DepID = 5 AND GETDATE() BETWEEN mindate AND maxdate";

$stmt = sqlsrv_query($con,$sql);
while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $startdate = $row['startdate'];
  $stopdate = $row['stopdate'];
  $mindate = $row['mindate'];
  $maxdate = $row['maxdate'];
}
*/
$sql = "SELECT convert(varchar,GETDATE()+1-datepart(day,GETDATE()),120) AS mindate,convert(varchar(19),GETDATE(),120) AS maxdate
,convert(varchar(10),GETDATE()+1-datepart(day,GETDATE()),105) as startdate
,convert(varchar(10),getdate(),105) as stopdate";

$stmt = sqlsrv_query($con,$sql);
while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $startdate = $row['startdate'];
  $stopdate = $row['stopdate'];
  $mindate = $row['mindate'];
  $maxdate = $row['maxdate'];
}
$messageHead = "% Aging ".DateThai($startdate)." - ".DateThai($stopdate)."\r\n\n";
//หาทริปเก็บเงิน

//หาการ์ดทั้งหมด
/*
$sql = "SELECT count(refno) as num
,CASE WHEN isnull(agingcumulative,0) <= 0 THEN 0 WHEN isnull(agingcumulative,0) > 3 THEN 4 ELSE agingcumulative END as agingcumulative
from TSR_Application.dbo.DebtorAnalyze_Master with(nolock)
WHERE Company = 'TSR' AND customerstatus = 'N' AND aginote is null
AND DATEPART(YEAR,EffDate) > 2015 AND LEFT(CONTNO,1) != 'X'
GROUP BY CASE WHEN isnull(agingcumulative,0) <= 0 THEN 0 WHEN isnull(agingcumulative,0) > 3 THEN 4 ELSE agingcumulative END
ORDER BY agingcumulative";
$stmt = sqlsrv_query($con,$sql);
while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $agingcumulative = $row1['agingcumulative'];
  $num[$agingcumulative] = $row1['num'];
}
*/
/*
$sql = "SELECT count(refno) as num
from TSR_Application.dbo.DebtorAnalyze_Master with(nolock)
WHERE Company = 'TSR' AND customerstatus = 'N' AND aginote is null AND (agingcumulative IS NULL OR agingcumulative <= 1)
AND DATEPART(YEAR,EffDate) > 2015 AND LEFT(CONTNO,1) != 'X' ";
$stmt = sqlsrv_query($con,$sql);
while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $contnum = $row1['num'];
}
*/
//หาการ์ดทั้งหมด
$sql = "SELECT count(refno) as num
,CASE WHEN isnull(agingcumulative,0) <= 0 THEN 0 WHEN isnull(agingcumulative,0) > 3 THEN 4 ELSE agingcumulative END as agingcumulative
from TSR_Application.dbo.DebtorAnalyze_Master with(nolock)
WHERE Company = 'TSR'
AND (customerstatus = 'N' OR (customerstatus = 'F' AND Refno IN (SELECT refno FROM TSR_Application.dbo.vw_MastPay WHERE DATEDIFF(MONTH,PayDate,GETDATE()) = 0)))
AND aginote is null
AND DATEPART(YEAR,EffDate) > 2015 AND LEFT(CONTNO,1) != 'X'
--เฉพาะเครดิตที่ติดตั้งเกิน 30 วัน
AND CashName = 'Credit'
AND DATEDIFF(DAY,EffDate,GETDATE()) >= 30
--เฉพาะเครดิตที่ติดตั้งเกิน 30 วัน
GROUP BY CASE WHEN isnull(agingcumulative,0) <= 0 THEN 0 WHEN isnull(agingcumulative,0) > 3 THEN 4 ELSE agingcumulative END
ORDER BY agingcumulative";
$stmt = sqlsrv_query($con,$sql);
while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $agingcumulative = $row1['agingcumulative'];
    $contnum[$agingcumulative] = $row1['num'];
}
//หาการ์ดทั้งหมด

//หาการ์ดทั้งหมดที่เก็บเงินได้
/*
$sql = "SELECT count(PayAmt) as paynum
from TSR_Application.dbo.vw_MastPay as M With(nolock)
WHERE refno in (select refno
from TSR_Application.dbo.DebtorAnalyze_Master with(nolock)
WHERE Company = 'TSR' AND customerstatus = 'N' AND aginote is null AND (agingcumulative IS NULL OR agingcumulative <= 1)
AND DATEPART(YEAR,EffDate) > 2015 AND LEFT(CONTNO,1) != 'X')
AND cast(PAYDATE as DateTime) BETWEEN '".$mindate."' and '".$maxdate."'";
$stmt = sqlsrv_query($con,$sql);
while ($row4 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $paynum = $row4['paynum'];
}
*/
$sql = "SELECT count(PayAmt) as paynum,agingcumulative
from TSR_Application.dbo.vw_MastPay as M With(nolock)
INNER JOIN (select refno,CASE WHEN isnull(agingcumulative,0) <= 0 THEN 0 WHEN isnull(agingcumulative,0) > 3 THEN 4 ELSE agingcumulative END as agingcumulative
from TSR_Application.dbo.DebtorAnalyze_Master with(nolock)
WHERE Company = 'TSR' AND (customerstatus = 'N' OR customerstatus = 'F') AND aginote is null
AND DATEPART(YEAR,EffDate) > 2015 AND LEFT(CONTNO,1) != 'X'
--เฉพาะเครดิตที่ติดตั้งเกิน 30 วัน
AND CashName = 'Credit'
AND DATEDIFF(DAY,EffDate,GETDATE()) >= 30
--เฉพาะเครดิตที่ติดตั้งเกิน 30 วัน
) AS A ON M.RefNo = A.RefNo

WHERE cast(PAYDATE as DateTime) BETWEEN '".$mindate."' and '".$maxdate."'
group by agingcumulative";

//echo $sql;
$stmt = sqlsrv_query($con,$sql);
while ($row4 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $agingcumulative = $row4['agingcumulative'];
    $paynum[$agingcumulative] = $row4['paynum'];
}
//หาการ์ดทั้งหมดที่เก็บเงินได้

//หาค่างวดทั้งหมด
$sql = "SELECT SUM(PayPeriod) as Period
,CASE WHEN isnull(agingcumulative,0) <= 0 THEN 0 WHEN isnull(agingcumulative,0) > 3 THEN 4 ELSE agingcumulative END as agingcumulative
from TSR_Application.dbo.DebtorAnalyze_Master with(nolock)
WHERE Company = 'TSR'
AND (customerstatus = 'N' OR (customerstatus = 'F' AND Refno IN (SELECT refno FROM TSR_Application.dbo.vw_MastPay WHERE DATEDIFF(MONTH,PayDate,GETDATE()) = 0)))
AND aginote is null
AND DATEPART(YEAR,EffDate) > 2015 AND LEFT(CONTNO,1) != 'X'
--เฉพาะเครดิตที่ติดตั้งเกิน 30 วัน
AND CashName = 'Credit'
AND DATEDIFF(DAY,EffDate,GETDATE()) >= 30
--เฉพาะเครดิตที่ติดตั้งเกิน 30 วัน
GROUP BY CASE WHEN isnull(agingcumulative,0) <= 0 THEN 0 WHEN isnull(agingcumulative,0) > 3 THEN 4 ELSE agingcumulative END
ORDER BY agingcumulative";
$stmt = sqlsrv_query($con,$sql);
while ($row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $agingcumulative = $row2['agingcumulative'];
  $Period[$agingcumulative] = $row2['Period'];
}
//หาค่างวดทั้งหมด

//การ์ดที่เก็บเงินแล้วทั้งหมด
$sql = "SELECT SUM(PayAmt) as PayAmt,agingcumulative
from TSR_Application.dbo.vw_MastPay as M With(nolock)
INNER JOIN (select refno
,CASE WHEN isnull(agingcumulative,0) <= 0 THEN 0 WHEN isnull(agingcumulative,0) > 3 THEN 4 ELSE agingcumulative END as agingcumulative
from TSR_Application.dbo.DebtorAnalyze_Master with(nolock)
WHERE Company = 'TSR'
AND (customerstatus = 'N' OR customerstatus = 'F')
AND aginote is null
AND DATEPART(YEAR,EffDate) > 2015 AND LEFT(CONTNO,1) != 'X'
--เฉพาะเครดิตที่ติดตั้งเกิน 30 วัน
AND CashName = 'Credit'
AND DATEDIFF(DAY,EffDate,GETDATE()) >= 30
--เฉพาะเครดิตที่ติดตั้งเกิน 30 วัน
) AS A ON A.Refno = M.RefNo
WHERE cast(PAYDATE as DateTime) BETWEEN '".$mindate."' and '".$maxdate."'
GROUP BY agingcumulative";

$stmt = sqlsrv_query($con,$sql);
while ($row3 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $agingcumulative = $row3['agingcumulative'];
  $pay[$agingcumulative] = $row3['PayAmt'];
}
//การ์ดที่เก็บเงินแล้วทั้งหมด
$pay01 = $pay[0] + $pay[1];
$Period01 = $Period[0] + $Period[1];
$contnum01 = $contnum[0] + $contnum[1];
$paynum01 = $paynum[0] + $paynum[1];

$targetAging = (($Period01 * 92) / 100);
$targetCollection = (($contnum01 * 92) / 100);

//$message .= "สีเขียว(ไม่ค้าง) \t 0 เดือน \t ".number_format(($num0 / $numAll)*100,2)."%\r\n";
//$message .= "สีเหลือง(ค้าง1) \t 1 เดือน \t ".number_format(($num1 / $numAll)*100,2)."%\r\n\n";
$message .= "สีเขียว(ไม่ค้าง) \t 0 เดือน \t ".number_format(($pay[0] / $Period01)*100,2)."%\n".number_format($pay[0])." บาท\r\n";
$message .= "สีเหลือง(ค้าง1) \t 1 เดือน \t ".number_format(($pay[1] / $Period01)*100,2)."%\n".number_format($pay[1])." บาท\r\n\n";

//$message .= "รวม Actual Aging \t 0-1 เดือน \t ".number_format((($num0+$num1) / $numAll)*100,2)."%\r\n";
//$message .= "Target Aging \t \t 92.00%\r\n";
//$message .= "+เกิน/-ขาด เป้าหมาย \t \t ".number_format(((($num0+$num1) / $numAll)*100)-92,2)."%\r\n\n";

//echo $Period[0]." ".$Period[1]." ".$Period[2]." ".$Period[3]." ".$Period[4]."<BR>";
//echo $pay[0]." ".$pay[1]." ".$pay[2]." ".$pay[3]." ".$pay[4]."<BR>";

$message .= "รวม Actual Aging \t 0-1 เดือน \t ".number_format(($pay01 / $Period01)*100,2)."%\n".number_format($pay01)." บาท\r\n";
$message .= "Target Aging \t \t 92.00%\n".number_format($targetAging)." บาท\r\n";
$message .= "+เกิน/-ขาด เป้าหมาย \t \t ".number_format(($pay01 / $Period01) * 100 - 92,2)."%\n".number_format($pay01 - $targetAging)." บาท\r\n\n";
/*
$message .= "สีส้ม (ค้าง 2) \t 2 เดือน \t ".number_format(($num2 / $numAll)*100,2)."%\r\n";
$message .= "สีแดง (ค้าง 3) \t 3 เดือน \t ".number_format(($num3 / $numAll)*100,2)."%\r\n";
$message .= "สีแดง (ค้าง 3 up) \t >3 เดือน \t ".number_format(($num4 / $numAll)*100,2)."%\r\n\n";
*/
$message .= "สีส้ม (ค้าง 2) \t 2 เดือน \t ".number_format(($pay[2] / $Period[2])*100,2)."%\n".number_format($pay[2])." บาท\r\n";
$message .= "สีแดง (ค้าง 3) \t 3 เดือน \t ".number_format(($pay[3] / $Period[3])*100,2)."%\n".number_format($pay[3])." บาท\r\n";
$message .= "สีแดง (ค้าง 3 up) \t >3 เดือน \t ".number_format(($pay[4] / $Period[4])*100,2)."%\n".number_format($pay[4])." บาท\r\n";

$msg = $messageHead."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenTest);
  notify_message($msg,"","",$tokenLukhinGroup);
  //echo $msg;
}

$messageHead = "% Collection ".DateThai($startdate)." - ".DateThai($stopdate)."\r\n\n";

$message = "สีเขียว(ไม่ค้าง) \t 0 เดือน \t ".number_format(($paynum[0] / $contnum01)*100,2)."%\n".number_format($paynum[0])." สัญญา\r\n";
$message .= "สีเหลือง(ค้าง1) \t 1 เดือน \t ".number_format(($paynum[1] / $contnum01)*100,2)."%\n".number_format($paynum[1])." สัญญา\r\n\n";

$message .= "% เก็บเงินได้ (เรทการ์ด) \t \t ".number_format(($paynum01 / $contnum01)*100,2)."%\n".number_format($paynum01)." สัญญา\r\n";
$message .= "Target % Collection \t \t 92.00%\n".number_format($targetCollection)." สัญญา\r\n";
$message .= "+เกิน/-ขาด เป้าหมาย \t \t ".number_format((($paynum01 / $contnum01)*100) - 92,2)."%\n".number_format($paynum01 - $targetCollection)." สัญญา\r\n\n";

$message .= "สีส้ม (ค้าง 2) \t 2 เดือน \t ".number_format(($paynum[2] / $contnum[2])*100,2)."%\n".number_format($paynum[2])." สัญญา\r\n";
$message .= "สีแดง (ค้าง 3) \t 3 เดือน \t ".number_format(($paynum[3] / $contnum[3])*100,2)."%\n".number_format($paynum[3])." สัญญา\r\n";
$message .= "สีแดง (ค้าง 3 up) \t >3 เดือน \t ".number_format(($paynum[4] / $contnum[4])*100,2)."%\n".number_format($paynum[4])." สัญญา\r\n";

//$message .= "% เก็บเงินได้ (เรทเงิน) \t \t 95.00%\r\n";

$messageTest = "วันที่ ".DateThai($startdate)." - ".DateThai($stopdate)."\r\n\n";
$messageTest .= "เงินตั้งต้นงวด 0 ".number_format($Period[0])." บาท เก็บได้ ".number_format($pay[0])." บาท\r\n";
$messageTest .= "เงินตั้งต้นงวด 1 ".number_format($Period[1])." บาท เก็บได้ ".number_format($pay[1])." บาท\r\n";
$messageTest .= "เงินตั้งต้นงวด 2 ".number_format($Period[2])." บาท เก็บได้ ".number_format($pay[2])." บาท\r\n";
$messageTest .= "เงินตั้งต้นงวด 3 ".number_format($Period[3])." บาท เก็บได้ ".number_format($pay[3])." บาท\r\n";
$messageTest .= "เงินตั้งต้นงวด >3 ".number_format($Period[4])." บาท เก็บได้ ".number_format($pay[4])." บาท\r\n\n";
$messageTest .= "การ์ดตั้งต้นงวด 0 ".number_format($contnum[0])." สัญญา เก็บได้ ".number_format($paynum[0])." สัญญา\r\n";
$messageTest .= "การ์ดตั้งต้นงวด 1 ".number_format($contnum[1])." สัญญา เก็บได้ ".number_format($paynum[1])." สัญญา\r\n";
$messageTest .= "การ์ดตั้งต้นงวด 2 ".number_format($contnum[2])." สัญญา เก็บได้ ".number_format($paynum[2])." สัญญา\r\n";
$messageTest .= "การ์ดตั้งต้นงวด 3 ".number_format($contnum[3])." สัญญา เก็บได้ ".number_format($paynum[3])." สัญญา\r\n";
$messageTest .= "การ์ดตั้งต้นงวด >3 ".number_format($contnum[4])." สัญญา เก็บได้ ".number_format($paynum[4])." สัญญา\r\n";

$sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_LogSendTimeLine (ReportName,StatusTime,StampDate) VALUES (?,?,GETDATE())";
$params = array("AlertCreditAging","Stop");
$stmt_insert = sqlsrv_query( $con, $sql_insert, $params);

sqlsrv_close($con);

//$message .= "\r\n รวม สารกรอง = ".$sumFilter." ไฟฟ้า = ".$sumElectric."";
$msg = $messageHead."".$message;
if (!empty($message)) {
  notify_message($msg,"","",$tokenTest);
  notify_message($msg,"","",$tokenLukhinGroup);
  notify_message($messageTest,"","",$tokenLukhinGroup);
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
