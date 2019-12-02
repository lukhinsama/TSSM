<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");
if (isset($_GET['Empid'])) {
  $con = connectDB_BigHead();
  //$con = connectDB_BigHeadUAT();

  $SQL ="SELECT S.SalePaymentPeriodID ,S.RefNo ,C.CONTNO ,D.CustomerName
,S.PaymentPeriodNumber ,S.PaymentComplete ,S.PaymentDueDate ,S.PaymentAppointmentDate ,S.NetAmount
,S.TripID
,SP.CreateDate
FROM Bighead_Mobile.dbo.SalePaymentPeriod AS S
LEFT JOIN Bighead_Mobile.dbo.Contract AS C ON S.RefNo = C.RefNo
LEFT JOIN TSRData_Source.dbo.vw_DebtorCustomer AS D ON D.CustomerID = C.RefNo
INNER JOIN Bighead_Mobile.dbo.Assign AS A ON S.SalePaymentPeriodID = A.ReferenceID
INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SP ON SP.SalePaymentPeriodID = S.SalePaymentPeriodID
WHERE A.AssigneeEmpID = '".$_GET['Empid']."' AND DATEDIFF(DAY,GETDATE(),SP.CreateDate) = 0";
  //echo $SQL;
  //$con = connectDB_BigHead();

  $stmt = sqlsrv_query($con,$SQL);
  $result=[];
  //$json_result=$arrayName = array('' => , );
  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

    $json_result = array('SalePaymentPeriodID'=>$row['SalePaymentPeriodID']
    ,'RefNo'=>$row['RefNo']
    ,'ContNo'=>$row['CONTNO']
    ,'CustomerName'=>$row['CustomerName']
    ,'PaymentPeriodNumber'=>$row['PaymentPeriodNumber']
    ,'PaymentComplete'=>$row['PaymentComplete']
    ,'NetAmount'=>$row['NetAmount']
    ,'PaymentDueDate'=>$row['PaymentDueDate']
    ,'PaymentAppointmentDate'=>$row['PaymentAppointmentDate']
    );

    array_push($result,$json_result);
  }

  sqlsrv_close($con);
  echo json_encode(array('data' => $result));
  //echo json_encode($json_result);
}else {
  echo "ERROR";
}
?>
