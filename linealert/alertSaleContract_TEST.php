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

//ฝ่ายขาย
$tokenSale = "IwKC3Z39TdQMlWNCGX6nCheXrQnNaytijEJf1hXezIK";
$SQLDate = "SELECT CONVERT(varchar(20),GETDATE(),105) AS EFFDATE";
$stmtDate = sqlsrv_query($con,$SQLDate);
if ($resultDate = sqlsrv_fetch_array( $stmtDate, SQLSRV_FETCH_ASSOC)) {
  $EFFDATE = $resultDate['EFFDATE'];
}

$SQL = "SELECT ProductModel,isnull(num,0) AS Num,isnull(point,0) AS Points,isnull(Price,0) AS Price
FROM TSRData_Source.dbo.ProductLineAlert AS PLA
LEFT JOIN (
SELECT ProductCode,COUNT(CONTNO) AS num , SUM(point) AS point, SUM(Sales) AS Price
FROM (
SELECT c.CONTNO,C.Sales,P.ProductCode,C.MODEL,po.point
,(select top 1 StatusType from TSRData_Source.dbo.EmployeeDataParent_ALL WHERE EmployeeCodeLV2 = C.SaleEmployeeCode) AS StatusType
  FROM Bighead_Mobile.dbo.Contract AS C
  INNER JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
  left join LINK_STOCK.TSR_Application.dbo.Penalty_Tempprod_m m on m.model = C.MODEL
  left join LINK_STOCK.TSR_Application.dbo.Penalty_Com_Point po on po.model = m.productid
  WHERE c.STATUS in ('NORMAL','F') AND DATEDIFF(DAY,effdate,GETDATE()) = 0 AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])." and po.posid = 1 and po.policyid = 71
) AS A
WHERE a.StatusType = 'SALE'
GROUP BY ProductCode
) viewA ON viewA.ProductCode = PLA.ProductCode";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดขาย สนง.\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
$message = "";
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "".$result['ProductModel']." ".$result['Num']."เครื่อง ".$result['Points']."พอยท์ ".number_format($result['Price'])."บาท\r\n";
}

$SQL2 = "SELECT SUM(Num) AS SumNum , SUM(Points) AS SumPoint , SUM(Price) AS SumPrice FROM (".$SQL.") as result";
$stmt2 = sqlsrv_query($con,$SQL2);
if ($result2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
  $message .= "รวม ".$result2['SumNum']." เครื่อง \r\n";
  $message .= "รวม ".$result2['SumPoint']." พอยท์ \r\n";
  $message .= "รวม ".number_format($result2['SumPrice'])." บาท";
}

$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  //notify_message($msg,"","",$tokenSale);
  //echo $msg;
}
//ฝ่ายขาย

//ฝ่ายสาขา
$tokenBrn = "IwKC3Z39TdQMlWNCGX6nCheXrQnNaytijEJf1hXezIK";
$SQL = "SELECT ProductModel,isnull(num,0) AS Num,isnull(point,0) AS Points,isnull(Price,0) AS Price
FROM TSRData_Source.dbo.ProductLineAlert AS PLA
LEFT JOIN (
SELECT ProductCode,COUNT(CONTNO) AS num , SUM(point) AS point, SUM(Sales) AS Price
FROM (
SELECT c.CONTNO,C.Sales,P.ProductCode,C.MODEL,po.point
,(select top 1 StatusType from TSRData_Source.dbo.EmployeeDataParent_ALL WHERE EmployeeCodeLV2 = C.SaleEmployeeCode) AS StatusType
  FROM Bighead_Mobile.dbo.Contract AS C
  INNER JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
  left join LINK_STOCK.TSR_Application.dbo.Penalty_Tempprod_m m on m.model = C.MODEL
  left join LINK_STOCK.TSR_Application.dbo.Penalty_Com_Point po on po.model = m.productid
  WHERE c.STATUS in ('NORMAL','F') AND DATEDIFF(DAY,effdate,GETDATE()) = 0 AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])." and po.posid = 1 and po.policyid = 71
) AS A
WHERE a.StatusType = 'BRN'
GROUP BY ProductCode
) viewA ON viewA.ProductCode = PLA.ProductCode";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดขาย สาขาตจว.\r\n";
$messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
$message = "";
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  $message .= "".$result['ProductModel']." ".$result['Num']."เครื่อง ".$result['Points']."พอยท์ ".number_format($result['Price'])."บาท\r\n";
}

$SQL2 = "SELECT SUM(Num) AS SumNum , SUM(Points) AS SumPoint , SUM(Price) AS SumPrice FROM (".$SQL.") as result";
$stmt2 = sqlsrv_query($con,$SQL2);
if ($result2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
  $message .= "รวม ".$result2['SumNum']." เครื่อง \r\n";
  $message .= "รวม ".$result2['SumPoint']." พอยท์ \r\n";
  $message .= "รวม ".number_format($result2['SumPrice'])." บาท";
}

$msg = $messageHead."".$messageHead2."".$message;
if (!empty($message)) {
  //notify_message($msg,"","",$tokenBrn);
  //echo $msg;
}
//ฝ่ายสาขา

//สาย
$tokenLine = "X06WZHEexNmtc4pJlUCiFnfVHkSxXyMRCmsDE08OnQo";
$SQLLine = "SELECT DISTINCT LEFT(SupervisorCode,2) AS LineM FROM TSRData_Source.dbo.EmployeeDataParent_ALL WHERE StatusType IN ('SALE','BRN') AND  LEFT(SupervisorCode,2) != 'SP' ORDER BY LEFT(SupervisorCode,2) ";

$stmtLine = sqlsrv_query($con,$SQLLine);

while ($resultline = sqlsrv_fetch_array($stmtLine, SQLSRV_FETCH_ASSOC)) {
  $LineM = $resultline['LineM'];
  $SQL = "SELECT ProductModel,isnull(num,0) AS Num,isnull(point,0) AS Points,isnull(Price,0) AS Price
  FROM TSRData_Source.dbo.ProductLineAlert AS PLA
  LEFT JOIN (
  SELECT ProductCode,COUNT(CONTNO) AS num , SUM(point) AS point, SUM(Sales) AS Price
  FROM (
  SELECT c.CONTNO,C.Sales,P.ProductCode,C.MODEL,po.point
  ,(select top 1 LEFT(SupervisorCode,2) from TSRData_Source.dbo.EmployeeDataParent_ALL WHERE EmployeeCodeLV2 = C.SaleEmployeeCode) AS StatusType
    FROM Bighead_Mobile.dbo.Contract AS C
    INNER JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
    left join LINK_STOCK.TSR_Application.dbo.Penalty_Tempprod_m m on m.model = C.MODEL
    left join LINK_STOCK.TSR_Application.dbo.Penalty_Com_Point po on po.model = m.productid
    WHERE c.STATUS in ('NORMAL','F') AND DATEDIFF(DAY,effdate,GETDATE()) = 0 AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])." and po.posid = 1 and po.policyid = 71
  ) AS A
  WHERE a.StatusType = '".$LineM."'
  GROUP BY ProductCode
  ) viewA ON viewA.ProductCode = PLA.ProductCode";

  $stmt = sqlsrv_query($con,$SQL);

  $messageHead = "สรุปยอดขายสาย ".$LineM."\r\n";
  $messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
  $message = "";
  while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $message .= "".$result['ProductModel']." ".$result['Num']."เครื่อง ".$result['Points']."พอยท์ \r\n";
  }

  $SQL2 = "SELECT SUM(Num) AS SumNum , SUM(Points) AS SumPoint , SUM(Price) AS SumPrice FROM (".$SQL.") as result";
  $stmt2 = sqlsrv_query($con,$SQL2);
  if ($result2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
    $message .= "รวม ".$result2['SumNum']." เครื่อง \r\n";
    $message .= "รวม ".$result2['SumPoint']." พอยท์ \r\n";
  }

  $msg = $messageHead."".$messageHead2."".$message;
  if (!empty($message)) {
    //notify_message($msg,"","",$tokenLine);
    //echo $msg;
  }
}
//สาย

//ซุปฯ
$tokenLine1 = "z3OgGrEc24jgfhbLwML1Xi0qNMsWYjbxtbLxDLtv4XN";

$SQLLine = "SELECT LineGroup,LineToken FROM TSRData_Source.dbo.LineAlert_LineGroupToken ORDER BY id ";

$stmtLine = sqlsrv_query($con,$SQLLine);

while ($resultline = sqlsrv_fetch_array($stmtLine, SQLSRV_FETCH_ASSOC)) {
  $tokenLine = $resultline['LineToken'];
  $SQLSup = "SELECT DISTINCT LEFT(SupervisorCode,4) AS LineM FROM TSRData_Source.dbo.EmployeeDataParent_ALL WHERE StatusType IN ('SALE','BRN') AND  LEFT(SupervisorCode,2) = '".$resultline['LineGroup']."' ORDER BY LEFT(SupervisorCode,4) ";

  $stmtSup = sqlsrv_query($con,$SQLSup);

  while ($resultsup = sqlsrv_fetch_array($stmtSup, SQLSRV_FETCH_ASSOC)) {
    $supM = $resultsup['LineM'];
    $SQL = "SELECT ProductModel,isnull(num,0) AS Num,isnull(point,0) AS Points,isnull(Price,0) AS Price
    FROM TSRData_Source.dbo.ProductLineAlert AS PLA
    LEFT JOIN (
    SELECT ProductCode,COUNT(CONTNO) AS num , SUM(point) AS point, SUM(Sales) AS Price
    FROM (
    SELECT c.CONTNO,C.Sales,P.ProductCode,C.MODEL,po.point
    ,(select top 1 LEFT(SupervisorCode,4) from TSRData_Source.dbo.EmployeeDataParent_ALL WHERE EmployeeCodeLV2 = C.SaleEmployeeCode) AS StatusType
      FROM Bighead_Mobile.dbo.Contract AS C
      INNER JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
      left join LINK_STOCK.TSR_Application.dbo.Penalty_Tempprod_m m on m.model = C.MODEL
      left join LINK_STOCK.TSR_Application.dbo.Penalty_Com_Point po on po.model = m.productid
      WHERE c.STATUS in ('NORMAL','F') AND DATEDIFF(DAY,effdate,GETDATE()) = 0 AND DATEPART(HOUR,c.EFFDATE) >= ".$_REQUEST['hour_start']." and DATEPART(HOUR,c.EFFDATE) < ".($_REQUEST['hour_end'])." and po.posid = 1 and po.policyid = 71
    ) AS A
    WHERE a.StatusType = '".$supM."'
    GROUP BY ProductCode
    ) viewA ON viewA.ProductCode = PLA.ProductCode";

    $stmt = sqlsrv_query($con,$SQL);

    $messageHead = "สรุปยอดขาย ".$supM."\r\n";
    $messageHead2 = "ข้อมูล ณ วันที่ ".DateThai($EFFDATE)." เวลา ".$_REQUEST['hour_end'].".00 น.\r\n";
    $message = "";
    while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      $message .= "".$result['ProductModel']." ".$result['Num']."เครื่อง ".$result['Points']."พอยท์ \r\n";
    }

    $SQL2 = "SELECT SUM(Num) AS SumNum , SUM(Points) AS SumPoint , SUM(Price) AS SumPrice FROM (".$SQL.") as result";
    $stmt2 = sqlsrv_query($con,$SQL2);
    if ($result2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
      $message .= "รวม ".$result2['SumNum']." เครื่อง \r\n";
      $message .= "รวม ".$result2['SumPoint']." พอยท์ \r\n";
    }

    $msg = $messageHead."".$messageHead2."".$message;
    if (!empty($message)) {
      //notify_message($msg,"","",$tokenLine1);
      //echo $msg;
      echo "<BR>".$tokenLine." - ".$supM;
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
