<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$conn = connectDB_BigHead();
	//ตรวจสอบว่า มีค่า ตัวแปร $_GET['province_id'] เข้ามาหรือไม่  //แสดงรายชืออำเภอ
	if(((isset($_GET['paydate1'])) OR (isset($_GET['paydate2']))) OR (isset($_GET['refno'])) OR (isset($_GET['contno']))){

		if ((isset($_REQUEST['paydate1'])) OR (isset($_REQUEST['paydate2']))) {
			$paydate1 = explode("/",$_REQUEST['paydate1']);
			$paydate2 = explode("/",$_REQUEST['paydate2']);
			$paydate1["2"] = $paydate1["2"]-543;
			$paydate2["2"] = $paydate2["2"]-543;
			$paydatestart = $paydate1["2"]."-".$paydate1["1"]."-".$paydate1["0"];
			$paydateend = $paydate2["2"]."-".$paydate2["1"]."-".$paydate2["0"];
			$sqltextdate = "AND R.DatePayment BETWEEN CAST('".$paydatestart." 00:00' AS datetime) AND CAST('".$paydateend." 23:59' AS datetime)";
		}else {
			$sqltextdate = "";
		}
		if (isset($_GET['refno'])) {
			$sqltextrefno = "AND C.ContractReferenceNo = '".$_GET['refno']."'";
		}else {
			$sqltextrefno = "";
		}
		if (isset($_GET['contno'])) {
			$sqltextcontno = "AND C.contno = '".$_GET['contno']."'";
		}else {
			$sqltextcontno = "";
		}
		/*
		if (isset($_GET['period'])) {
			$sqltextperiod = " AND SP.PaymentPeriodNumber = '".$_GET['period']."'";
		}else {
		  $sqltextperiod = "";
		}
		*/
		//$json_result[] = ['Refno'=>'','CONTNO'=>'',];

		$sql_case = "SELECT DISTINCT R.ReceiptCode,CONVERT(varchar(20),R.DatePayment,105) AS Paydate,CONVERT(varchar(5),R.DatePayment,108) AS Paytime
,CASE WHEN R.TotalPayment > 0 THEN 'N' ELSE 'Y' END AS Void
,CASE WHEN (SPP.Amount+SP.CloseAccountDiscountAmount) = Sp.NetAmount THEN 'N' ELSE 'Y' END AS Pantal
,P.TeamCode,R.TotalPayment,R.CreateBy AS EmpID,R.ZoneCode AS SaleCode
,CASE WHEN C.TradeInDiscount IS NULL THEN 'N' ELSE 'Y' END Turn
,E.FirstName+' '+E.LastName AS Cashname
,SP.PaymentPeriodNumber,SP.PaymentAmount,SP.Discount,SP.NetAmount,SP.PaymentComplete,SP.CloseAccountDiscountAmount
,SPP.Amount
,P.PAYAMT
,C.CONTNO,C.ContractReferenceNo AS RefNo
,M.ManualVolumeNo,M.ManualRunningNo
,CASE WHEN Em.ProcessType = 'Sale' THEN '01' WHEN Em.ProcessType = 'BRN' THEN '01' WHEN Em.ProcessType = 'CRD' THEN '02' WHEN Em.ProcessType = 'Credit' THEN '03' ELSE null END AS Depart
,DC.PrefixName +' '+DC.CustomerName AS CustomerName
,P.PaymentType,P.CreditCardNumber,P.BankCode,B.BankName
  FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R
  INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPP ON R.PaymentID = SPP.PaymentID AND R.ReceiptID = SPP.ReceiptID
  INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS SP ON SP.SalePaymentPeriodID = SPP.SalePaymentPeriodID
  INNER JOIN Bighead_Mobile.dbo.Contract AS C ON C.RefNo = R.RefNo and SP.RefNo = C.RefNo
  INNER JOIN Bighead_Mobile.dbo.Payment AS P ON P.PaymentID = R.PaymentID
  INNER JOIN Bighead_Mobile.dbo.Employee AS E ON R.CreateBy = E.EmpID
  INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC ON C.CustomerID = DC.CustomerID
  LEFT JOIN Bighead_Mobile.dbo.ManualDocument AS M ON M.DocumentNumber = R.ReceiptID
  LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Em ON Em.EmployeeCode = R.CreateBy
	LEFT JOIN Bighead_Mobile.dbo.Bank AS B ON p.BankCode = B.BankCode
	WHERE SP.PaymentPeriodNumber = '01' $sqltextdate $sqltextrefno $sqltextcontno $sqltextperiod";

		//echo $sql_case;

		$stmt = sqlsrv_query($conn,$sql_case);
		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
			//$Name = explode(" ",$row['CustomerName']);
			$DatePayment = explode("-",$row['Paydate']);

			$DatePayment["2"] = $DatePayment["2"]+543;

			$DatePayment1 = $DatePayment["0"]."/".$DatePayment["1"]."/".$DatePayment["2"];

			//$firstName = $Name["0"];
			//$lastName = "";
			/*
			for ($i=1; $i < count($Name); $i++) {
				$lastName .= $Name[$i];
			}
			*/
			//$lastName = $Name["1"]." ".$Name["2"];
			$json_result[] = ['CONTNO'=>$row['CONTNO']
			,'RefNo'=>$row['RefNo']
			,'InvNo'=>$row['ReceiptCode']
			,'Cashcode'=>$row['SaleCode']
			,'EmpID'=>$row['EmpID']
			,'Cashname'=>$row['Cashname']
			,'Paydate'=>$DatePayment1
			,'Paytime'=>$row['Paytime']
			,'Void'=>$row['Void']
			,'Partial'=>$row['Pantal']
			,'TeamCode'=>$row['TeamCode']
			,'Turn'=>$row['Turn']
			,'PayPeriod'=>$row['PaymentPeriodNumber']
			,'Premium'=>$row['PaymentAmount']
			,'Discfirst'=>$row['Discount']
			,'NetAmount'=>$row['NetAmount']
			,'CloseAccountDiscountAmount'=>$row['CloseAccountDiscountAmount']
			,'PAYAMT'=>$row['PAYAMT']
			,'Amount'=>$row['Amount']
			,'TotalPayment'=>$row['TotalPayment']
			,'PaymentComplete'=>$row['PaymentComplete']
			,'Bookno'=>$row['ManualVolumeNo']
			,'ReceiptNo'=>$row['ManualRunningNo']
			,'Custname'=>$row['CustomerName']
			,'PaymentType'=>$row['PaymentType']
			,'BankName'=>$row['BankName']
			,'Depart'=>$row['Depart']
			,];
		}

		echo json_encode($json_result);
	}

sqlsrv_close($conn);

?>
