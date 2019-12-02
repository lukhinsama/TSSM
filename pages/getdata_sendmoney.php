<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

//Echo "Hello world";

$conn = connectDB_BigHead();
//ตรวจสอบว่า มีค่า ตัวแปร $_GET['province_id'] เข้ามาหรือไม่  //แสดงรายชืออำเภอ
if( (isset($_GET['SendDate'])) OR (isset($_GET['ref1'])) OR (isset($_GET['ref2'])) OR (isset($_GET['EmpID'])) OR (isset($_GET['EmpSave'])) ){

	if (isset($_REQUEST['SendDate'])) {
		$effdate1 = explode("/",$_REQUEST['SendDate']);
		$effdate1["2"] = $effdate1["2"]-543;
		$SendDate = $effdate1["2"]."-".$effdate1["1"]."-".$effdate1["0"];
		//$sqltextdate = "AND S.SaveTransactionNoDate = CAST('".$SendDate."' AS datetime)";
		//$sqltextdate = "AND S.CreateDate = CAST('".$SendDate."' AS datetime)";
		$sqltextdate = "AND S.SendDate = CAST('".$SendDate."' AS date)";
	  //$sqltextdate = "AND S.CreateDate BETWEEN CAST('".$SendDate." 00:00:00.000' AS datetime) AND CAST('".$SendDate." 23:59:59.999' AS datetime)";
		//$sqltextdate = "AND S.SendDate BETWEEN CAST('".$SendDate." 00:00:00.000' AS datetime) AND CAST('".$SendDate." 23:59:59.999' AS datetime)";
	}else {
		$sqltextdate = "";
	}
	if (isset($_GET['ref1'])) {
		$sqltextReference1 = "AND C.Reference1 = '".$_GET['ref1']."'";
	}else {
		$sqltextReference1 = "";
	}
	if (isset($_GET['ref2'])) {
		$sqltextReference2 = "AND C.Reference2 = '".$_GET['ref2']."'";
	}else {
		$sqltextReference2 = "";
	}
	if (isset($_GET['EmpID'])) {
		$sqltextEmpid = "AND S.EmpID = '".$_GET['EmpID']."'";
	}else {
		$sqltextEmpid = "";
	}
	if (isset($_GET['EmpSave'])) {
		$sqltextPayeeName = "AND S.PayeeName = '".$_GET['EmpSave']."'";
	}else {
		$sqltextPayeeName = "";
	}

$sql_case = "SELECT CONVERT(varchar(20),S.PaymentDate,105)as PaymentDate,S.PaymentType,S.Reference1,S.Reference2,S.SendAmount
,CONVERT(varchar(20),S.SendDate,105) as SendDate,S.TransactionNo
,S.CreateBy,S.CreateDate,Ci.ChannelItemName,S.PayeeName,S.SaveTransactionNoDate,S.EmpID,S.TeamCode,S.CashCode
,E.FirstName,E.LastName
  FROM [Bighead_Mobile].[dbo].[SendMoney] AS S
  LEFT JOIN Bighead_Mobile.dbo.Employee AS E ON S.EmpID = E.EmpID
	LEFT JOIN [Bighead_Mobile].[dbo].[ChannelItem] AS CI ON Ci.ChannelItemID = S.ChannelItemID
  WHERE LEFT(CashCode,1) NOT IN ('1','2','9')  $sqltextdate";

		//echo $sql_case;

		$stmt = sqlsrv_query($conn,$sql_case);
		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

			$SendDate = explode("-",$row['SendDate']);
			$SendDate["2"] = $SendDate["2"]+543;
			$SendDate = $SendDate["0"]."/".$SendDate["1"]."/".$SendDate["2"];

			$PaymentDate = explode("-",$row['PaymentDate']);
			$PaymentDate["2"] = $PaymentDate["2"]+543;
			$PaymentDate = $PaymentDate["0"]."/".$PaymentDate["1"]."/".$PaymentDate["2"];


			$json_result[] = ['Reference1'=>$row['Reference1']
			,'Reference2'=>$row['Reference2']
			,'SendDate'=>$SendDate
			,'PaymentDate'=>$PaymentDate
			,'SendAmount'=>$row['SendAmount']
			,'ChannelItemName'=>$row['ChannelItemName']
			,'PaymentType'=>$row['PaymentType']
			,'EmpSave'=>$row['PayeeName']
			,'EmpID'=>$row['EmpID']
			,'EmpFirstName'=>$row['FirstName']
			,'EmpLastName'=>$row['LastName']
			,'TeamCode'=>$row['TeamCode']
			,'CashCode'=>$row['CashCode']
			,];

		}

		echo json_encode($json_result);

	}

sqlsrv_close($conn);

?>
