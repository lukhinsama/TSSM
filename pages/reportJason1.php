<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
//include_once("../include/inc-fuction.php");

$arrCol = array();
$resultArray = array();

$file = file_get_contents("sqlText.txt");
//fwrite($file,$sql_case);


$arrCol['query'] = $file;

array_push($resultArray,$arrCol);
echo json_encode($resultArray);
fclose($file);


/*
$arrCol['query'] = "SELECT ReceiptCode
,CONVERT(varchar,PaymentDueDate,105) as PaymentDueDate
,Right('000'+Convert(Varchar,S.PaymentPeriodNumber),2) As PaymentPeriodNumber,P.CONTNO,CustomerName,PAYAMT
,P.EmpID , [FirstName] + ' ' + [LastName] AS Names , CONVERT(varchar,P.PayDate,105) AS Paydate , 'อดิศร ชมดง' AS PrintName
FROM [Bighead_Mobile].[dbo].[Payment] AS P
LEFT JOIN [Bighead_Mobile].[dbo].[Contract] AS C
ON P.CONTNO = C.CONTNO
LEFT JOIN [Bighead_Mobile].[dbo].[vw_GetCustomer] AS GC
ON C.CustomerID = GC.CustomerID
LEFT JOIN[Bighead_Mobile].[dbo].[SalePaymentPeriod] AS S
ON S.RefNo = P.RefNo AND P.PayPeriod = S.PaymentPeriodNumber
LEFT JOIN [Bighead_Mobile].[dbo].[Receipt] AS R
ON R.PaymentID = P.PaymentID
LEFT JOIN [Bighead_Mobile].[dbo].[Employee] AS Em
ON p.EmpID = EM.EmpID
WHERE datediff(DAY,P.PayDate,GETDATE())=3

ORDER BY P.EmpID,PAYAMT ASC";

array_push($resultArray,$arrCol);
echo json_encode($resultArray);
*/

?>
