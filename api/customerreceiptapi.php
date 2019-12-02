<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$con = connectDB_BigHead();

		if (isset($_REQUEST['contno'])) {

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$sql_case2 = " INSERT INTO [TSRData_Source].[dbo].[onlineRecipt] (ContNo, StampDate) VALUES ('".$_REQUEST['contno']."',GETDATE() )";
		$stmt2 = sqlsrv_query($con,$sql_case2);

			$sql_case = "SELECT --R.DatePayment
				CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) AS DatePayment
				,R.ReceiptCode,C.CONTNO,DC.CustomerName,PO.ProductName,S.PaymentPeriodNumber
			  ,SP.Amount
				,ISNULL((SELECT MIN(PaymentPeriodNumber) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = C.refno AND PaymentPeriodNumber > S.PaymentPeriodNumber),0) AS minPeriod
			  ,ISNULL((SELECT MAX(PaymentPeriodNumber) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = C.refno),0) AS maxPeriod
			  ,ISNULL((SELECT SUM(NetAmount) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = C.refno AND PaymentPeriodNumber > S.PaymentPeriodNumber),0) AS balance
				,E.FirstName+' '+E.LastName AS EmpName
			  ,LEFT(ED.SaleCode,1) AS Salecode
			  ,R.CreateBy
				FROM Bighead_Mobile.dbo.contract AS C
			  INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S ON C.refno = S.RefNo
			  INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SP ON S.SalePaymentPeriodID = SP.SalePaymentPeriodID
			  INNER JOIN Bighead_Mobile.dbo.Receipt AS R ON R.ReceiptID = SP.ReceiptID AND R.RefNo = C.RefNo
			  INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC ON DC.CustomerID = C.CustomerID
			  INNER JOIN Bighead_Mobile.dbo.Product AS PO ON PO.ProductID = C.ProductID
				LEFT JOIN Bighead_Mobile.dbo.Employee AS E ON E.EmpID = R.CreateBy
			  LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS ED ON Ed.EmployeeCode = R.CreateBy AND ED.SaleCode is not null
			  WHERE C.CONTNO = '".$_REQUEST['contno']."'
			  ORDER BY S.PaymentPeriodNumber DESC";
					//echo $sql_case;

		$stmt = sqlsrv_query($con,$sql_case);

		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
			if ($row['Salecode'] == "9") {
	      if ($row['CreateBy'] == "A27367") {
	        $Chanel = "(QR PAYMENT)";
	      }elseif ($row['CreateBy'] == "A31218") {
	        $Chanel = "(โอน-ธนาคารกรุงเทพ)";
	      }elseif ($row['CreateBy'] == "A25945") {
	        $Chanel = "(โอน-อื่นๆ)";
	      }elseif ($row['CreateBy'] == "A38445") {
	        $Chanel = "(โอน-ธนาคารไทยพาณิชย์)";
	      }elseif ($row['CreateBy'] == "A36847") {
	        $Chanel = "(โอน-กฎหมาย)";
	      }elseif ($row['CreateBy'] == "A10925") {
	        $Chanel = "(โอน-รวมทุกธนาคาร)";
	      }elseif ($row['CreateBy'] == "A32599") {
	        $Chanel = "(เคาร์เตอร์เซอร์วิส)";
	      }else {
	        $Chanel = "(".$row['EmpName'].")";
	      }
	    }else {
	      $Chanel = "(".$row['EmpName'].")";
	    }
			//$lastName = $Name["1"]." ".$Name["2"];
			$json_result[] = ['DatePayment'=>DateTimeThai($row['DatePayment'])
			,'ReceiptCode'=>$row['ReceiptCode']
			,'CONTNO'=>$row['CONTNO']
			,'CustomerName'=>$row['CustomerName']
			,'ProductName'=>$row['ProductName']
			,'PaymentPeriodNumber'=>$row['PaymentPeriodNumber']
			,'Amount'=>number_format($row['Amount'])
			,'minPeriod'=>$row['minPeriod']
			,'maxPeriod'=>$row['maxPeriod']
			,'balance'=>number_format($row['balance'])
			,'Chanel'=>$Chanel
			,];
		}

		echo json_encode($json_result);
	}
sqlsrv_close($con);

?>
