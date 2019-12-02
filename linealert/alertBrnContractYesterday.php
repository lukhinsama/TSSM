<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

//include("../include/inc-fuction.php");

define('LINE_API',"https://notify-api.line.me/api/notify");
$token = "RMKK94PpmDyV5dygGXggKHSf2YQuBXVCNFT1Eo8TpuB";
/*
$a = array("ทดสอบ","ระบบล่มแล้ว","แก้ด่วน","แก้เดี่ยวนี้","ตายๆ ตายห่าแน่ๆ");
$random_keys = array_rand($a,3);
$str = $a[$random_keys[0]];
*/
$con = connectDB_BigHead();

//แจ้งยอดเมื่อวาน
$SQL = "SELECT ProductModel,isnull(num,0) AS Num,isnull(point,0) AS Points,isnull(Price,0) AS Price,EFFDATE
FROM TSRData_Source.dbo.ProductLineAlert AS PLA
LEFT JOIN (
SELECT ProductCode,COUNT(CONTNO) AS num , SUM(point) AS point, SUM(Sales) AS Price ,EFFDATE
FROM (
SELECT c.CONTNO,C.Sales,CONVERT(varchar(20),C.EFFDATE,105) AS EFFDATE,P.ProductCode,C.MODEL,po.point
,(select top 1 StatusType from TSRData_Source.dbo.EmployeeDataParent_ALL WHERE EmployeeCodeLV2 = C.SaleEmployeeCode) AS StatusType
  FROM Bighead_Mobile.dbo.Contract AS C
  INNER JOIN Bighead_Mobile.dbo.Product AS P ON C.ProductID = P.ProductID
  left join LINK_STOCK.TSR_Application.dbo.Penalty_Tempprod_m m on m.model = C.MODEL
  left join LINK_STOCK.TSR_Application.dbo.Penalty_Com_Point po on po.model = m.productid
  WHERE c.STATUS in ('NORMAL','F') AND DATEDIFF(DAY,effdate,GETDATE()) = 1 and po.posid = 1 and po.policyid = 71
) AS A
WHERE a.StatusType = 'BRN'
GROUP BY ProductCode,EFFDATE
) viewA ON viewA.ProductCode = PLA.ProductCode";

$stmt = sqlsrv_query($con,$SQL);

$messageHead = "สรุปยอดขาย สาขาตจว.\r\n";

$message = "";
while ($result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
  if ($result['EFFDATE'] != NULL) {
    $messageHead2 = "วันที่ ".DateThai($result['EFFDATE'])."\r\n";
  }
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
  notify_message($msg,"","",$token);
  //echo $msg;
}
//แจ้งยอดเมื่อวาน


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
