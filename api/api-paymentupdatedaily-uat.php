<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

if (isset($_GET['Empid'])) {
  //$con = connectDB_BigHead();
  $con = connectDB_BigHeadUAT();

  $SQL = "SELECT P.PaymentID, P.OrganizationCode, isnull(SendMoneyID,'') as SendMoneyID, PaymentType, convert(varchar,PayPartial) AS PayPartial
, isnull(BankCode,'')  as BankCode, isnull(ChequeNumber,'') as ChequeNumber, isnull(ChequeBankBranch,'') as ChequeBankBranch
,isnull(ChequeDate,'') as ChequeDate, isnull(CreditCardNumber,'') as CreditCardNumber, isnull(CreditCardApproveCode,'') as CreditCardApproveCode
, CreditEmployeeLevelPath, TripID, P.Status
, P.RefNo, PayPeriod, PayDate, convert(varchar,PAYAMT) AS PAYAMT, CashCode, EmpID, TeamCode
,isnull(receiptkind,'') as receiptkind, isnull(Kind,'') as Kind, isnull(BookNo,'') as BookNo, isnull(ReceiptNo,'') as ReceiptNo
, P.CreateDate, P.CreateBy, P.LastUpdateDate, P.LastUpdateBy, P.SyncedDate
from Bighead_Mobile.dbo.Payment as P
INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SP ON P.PaymentID = SP.PaymentID
INNER JOIN Bighead_Mobile.dbo.Assign AS A ON A.referenceID = SP.SalePaymentPeriodID
WHERE datediff(day,PayDate,getdate()) = 0 AND a.AssigneeEmpID = '".$_GET['Empid']."'";

  //$con = connectDB_BigHead();

  //echo $SQL;

  $stmt = sqlsrv_query($con,$SQL);
  $result=[];
  //$json_result=$arrayName = array('' => , );
  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

    $json_result = array('PaymentID'=>$row['PaymentID']
    ,'OrganizationCode'=>$row['OrganizationCode']
    ,'SendMoneyID'=>$row['SendMoneyID']
    ,'PaymentType'=>$row['PaymentType']
    ,'PayPartial'=>$row['PayPartial']
    ,'BankCode'=>$row['BankCode']
    ,'ChequeNumber'=>$row['ChequeNumber']
    ,'ChequeBankBranch'=>$row['ChequeBankBranch']
    ,'ChequeDate'=>$row['ChequeDate']
    ,'CreditCardNumber'=>$row['CreditCardNumber']
    ,'CreditCardApproveCode'=>$row['CreditCardApproveCode']
    ,'CreditEmployeeLevelPath'=>$row['CreditEmployeeLevelPath']
    ,'TripID'=>$row['TripID']
    ,'Status'=>$row['Status']
    ,'RefNo'=>$row['RefNo']
    ,'PayPeriod'=>$row['PayPeriod']
    ,'PayDate'=>$row['PayDate']
    ,'PAYAMT'=>$row['PAYAMT']
    ,'CashCode'=>$row['CashCode']
    ,'EmpID'=>$row['EmpID']
    ,'TeamCode'=>$row['TeamCode']
    ,'receiptkind'=>$row['receiptkind']
    ,'Kind'=>$row['Kind']
    ,'BookNo'=>$row['BookNo']
    ,'ReceiptNo'=>$row['ReceiptNo']
    ,'CreateDate'=>$row['CreateDate']
    ,'CreateBy'=>$row['CreateBy']
    ,'LastUpdateDate'=>$row['LastUpdateDate']
    ,'LastUpdateBy'=>$row['LastUpdateBy']
    ,'SyncedDate'=>$row['SyncedDate']
    ,'CreateBy'=>$row['CreateBy']
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
