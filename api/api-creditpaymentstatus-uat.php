<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

if (isset($_GET['Empid'])) {
  //$con = connectDB_BigHead();
  $con = connectDB_BigHeadUAT();

  $SQL = "SELECT top 100 C.CONTNO,MAX(R.DatePayment) AS DatePayment,DC.CustomerName,
 C.RefNo,
    C.CustomerID,
  C.OrganizationCode,
  C.STATUS,
  C.StatusCode,
  Convert(varchar,C.SALES) AS SALES,
  Convert(varchar,C.TotalPrice) AS TotalPrice,
  C.EFFDATE,
  Convert(varchar,C.HasTradeIn) AS HasTradeIn,
  isnull(C.TradeInProductCode,'') AS TradeInProductCode,
  isnull(C.TradeInBrandCode,'') AS TradeInBrandCode,
  isnull(C.TradeInProductModel,'') AS TradeInProductModel,
  Convert(varchar,C.TradeInDiscount) AS TradeInDiscount,
  isnull(C.PreSaleSaleCode,'') AS PreSaleSaleCode,
  isnull(C.PreSaleEmployeeCode,'') AS PreSaleEmployeeCode,
  isnull(C.PreSaleEmployeeName,'') AS PreSaleEmployeeName,
  isnull(C.PreSaleTeamCode,'') AS PreSaleTeamCode,
  C.SaleCode,
  C.SaleEmployeeCode,
  C.SaleTeamCode,
  C.InstallerSaleCode,
  C.InstallerEmployeeCode,
  C.InstallerTeamCode,
  C.InstallDate,
  C.ProductSerialNumber,
  C.ProductID,
  C.SaleEmployeeLevelPath,
  Convert(varchar,C.MODE) AS MODE,
  C.FortnightID,
  isnull(C.ProblemID,'') AS ProblemID,
  isnull(C.svcontno,'') AS svcontno,
  Convert(varchar,C.isActive) AS isActive,
  C.MODEL,
  isnull(C.fromrefno,'') AS fromrefno,
  isnull(C.fromcontno,'') AS fromcontno,
  C.todate,
  isnull(C.tocontno,'') AS tocontno,
  isnull(C.torefno,'') AS torefno,
  C.CreateDate,
  C.CreateBy,
  C.LastUpdateDate,
  C.LastUpdateBy,
  C.SyncedDate,
  isnull(C.SaleSubTeamCode,'') AS SaleSubTeamCode,
  Convert(varchar,isnull(C.TradeInReturnFlag,'')) AS TradeInReturnFlag,
  Convert(varchar,C.IsReadyForSaleAudit) AS IsReadyForSaleAudit,
  C.ContractReferenceNo,
  Convert(varchar,C.IsMigrate) AS IsMigrate
  ,CASE WHEN R.CreateBy = 'A10925' THEN 'โอนเงิน' WHEN R.CreateBy = 'A27367' THEN 'QR Payment' WHEN R.CreateBy = 'A29545' THEN 'โอนเงิน' ELSE 'เครดิต' END AS PayStatus
  ,P.PaymentID, P.OrganizationCode, isnull(P.SendMoneyID,'') as SendMoneyID, P.PaymentType, convert(varchar,P.PayPartial) AS PayPartial
  , isnull(P.BankCode,'')  as BankCode, isnull(P.ChequeNumber,'') as ChequeNumber, isnull(P.ChequeBankBranch,'') as ChequeBankBranch
  ,isnull(P.ChequeDate,'') as ChequeDate, isnull(P.CreditCardNumber,'') as CreditCardNumber, isnull(P.CreditCardApproveCode,'') as CreditCardApproveCode
  , P.CreditEmployeeLevelPath, P.TripID, P.Status
  , P.RefNo, P.PayPeriod, P.PayDate, convert(varchar,P.PAYAMT) AS PAYAMT, P.CashCode, P.EmpID, P.TeamCode
  ,isnull(P.receiptkind,'') as receiptkind, isnull(P.Kind,'') as Kind, isnull(P.BookNo,'') as BookNo, isnull(P.ReceiptNo,'') as ReceiptNo
  , P.CreateDate, P.CreateBy, P.LastUpdateDate, P.LastUpdateBy, P.SyncedDate
  FROM Bighead_Mobile.dbo.Contract AS C With(NOLOCK)
  INNER JOIN Bighead_Mobile.dbo.Assign AS ASS With(NOLOCK) ON C.RefNo = ASS.RefNo
  INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPP With(NOLOCK) ON SPP.SalePaymentPeriodID = ASS.ReferenceID
  INNER JOIN Bighead_Mobile.dbo.Receipt AS R With(NOLOCK) ON R.ReceiptID = SPP.ReceiptID
  INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC ON C.CustomerID = DC.CustomerID
  INNER JOIN Bighead_Mobile.dbo.Payment as P ON SPP.PaymentID = P.PaymentID AND R.PaymentID = P.PaymentID
  WHERE C.STATUS IN ('NORMAL','F')
  AND ASS.AssigneeEmpID = '".$_GET['Empid']."'
  AND DATEDIFF(MONTH,R.DatePayment,GETDATE()) < 12
  GROUP BY C.CONTNO,R.CreateBy,DC.CustomerName,
  C.RefNo,
  C.CustomerID,
  C.OrganizationCode,
  C.STATUS,
  C.StatusCode,
  C.SALES,
  C.TotalPrice,
  C.EFFDATE,
  C.HasTradeIn,
  C.TradeInProductCode,
  C.TradeInBrandCode,
  C.TradeInProductModel,
  C.TradeInDiscount,
  C.PreSaleSaleCode,
  C.PreSaleEmployeeCode,
  C.PreSaleEmployeeName,
  C.PreSaleTeamCode,
  C.SaleCode,
  C.SaleEmployeeCode,
  C.SaleTeamCode,
  C.InstallerSaleCode,
  C.InstallerEmployeeCode,
  C.InstallerTeamCode,
  C.InstallDate,
  C.ProductSerialNumber,
  C.ProductID,
  C.SaleEmployeeLevelPath,
  C.MODE,
  C.FortnightID,
  C.ProblemID,
  C.svcontno,
  C.isActive,
  C.MODEL,
  C.fromrefno,
  C.fromcontno,
  C.todate,
  C.tocontno,
  C.torefno,
  C.CreateDate,
  C.CreateBy,
  C.LastUpdateDate,
  C.LastUpdateBy,
  C.SyncedDate,
  C.SaleSubTeamCode,
  C.TradeInReturnFlag,
  C.IsReadyForSaleAudit,
  C.ContractReferenceNo,
  C.IsMigrate
  ,P.PaymentID
, P.OrganizationCode
, P.SendMoneyID
, P.PaymentType
, P.PayPartial
  , P.BankCode
  , P.ChequeNumber
  , P.ChequeBankBranch
  ,P.ChequeDate
  , P.CreditCardNumber
  , P.CreditCardApproveCode
  , P.CreditEmployeeLevelPath
  , P.TripID
  , P.Status
  , P.RefNo, P.PayPeriod, P.PayDate
  , P.PAYAMT
  ,P.CashCode
  , P.EmpID
  , P.TeamCode
  ,P.receiptkind
  , P.Kind
  , P.BookNo
  ,P.ReceiptNo
  , P.CreateDate
  , P.CreateBy
  , P.LastUpdateDate
  , P.LastUpdateBy
  , P.SyncedDate
  ORDER BY DatePayment DESC";

  //$con = connectDB_BigHead();

  //echo $SQL;

  $stmt = sqlsrv_query($con,$SQL);
  $result=[];
  //$json_result=$arrayName = array('' => , );
  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $json_result = array('CONTNO'=>$row['CONTNO']
    ,'CustomerName'=>$row['CustomerName']
    ,'RefNo'=>$row['RefNo']
    ,'CustomerID'=>$row['CustomerID']
    ,'OrganizationCode'=>$row['OrganizationCode']
    ,'STATUS'=>$row['STATUS']
    ,'StatusCode'=>$row['StatusCode']
    ,'SALES'=>$row['SALES']
    ,'TotalPrice'=>$row['TotalPrice']
    ,'HasTradeIn'=>$row['HasTradeIn']
    ,'TradeInProductCode'=>$row['TradeInProductCode']
    ,'TradeInBrandCode'=>$row['TradeInBrandCode']
    ,'TradeInProductModel'=>$row['TradeInProductModel']
    ,'TradeInDiscount'=>$row['TradeInDiscount']
    ,'PreSaleSaleCode'=>$row['PreSaleSaleCode']
    ,'PreSaleEmployeeCode'=>$row['PreSaleEmployeeCode']
    ,'PreSaleEmployeeName'=>$row['PreSaleEmployeeName']
    ,'PreSaleTeamCode'=>$row['PreSaleTeamCode']
    ,'SaleCode'=>$row['SaleCode']
    ,'SaleEmployeeCode'=>$row['SaleEmployeeCode']
    ,'SaleTeamCode'=>$row['SaleTeamCode']
    ,'InstallerSaleCode'=>$row['InstallerSaleCode']
    ,'InstallerEmployeeCode'=>$row['InstallerEmployeeCode']
    ,'InstallerTeamCode'=>$row['InstallerTeamCode']
    ,'ProductSerialNumber'=>$row['ProductSerialNumber']
    ,'ProductID'=>$row['ProductID']
    ,'SaleEmployeeLevelPath'=>$row['SaleEmployeeLevelPath']
    ,'MODE'=>$row['MODE']
    ,'FortnightID'=>$row['FortnightID']
    ,'ProblemID'=>$row['ProblemID']
    ,'svcontno'=>$row['svcontno']
    ,'isActive'=>$row['isActive']
    ,'MODEL'=>$row['MODEL']
    ,'fromrefno'=>$row['fromrefno']
    ,'fromcontno'=>$row['fromcontno']
    ,'tocontno'=>$row['tocontno']
    ,'torefno'=>$row['torefno']
    ,'CreateBy'=>$row['CreateBy']
    ,'LastUpdateBy'=>$row['LastUpdateBy']
    ,'SaleSubTeamCode'=>$row['SaleSubTeamCode']
    ,'TradeInReturnFlag'=>$row['TradeInReturnFlag']
    ,'IsReadyForSaleAudit'=>$row['IsReadyForSaleAudit']
    ,'ContractReferenceNo'=>$row['ContractReferenceNo']
    ,'IsMigrate'=>$row['IsMigrate']
    ,'PayStatus'=>$row['PayStatus']

    ,'Payment_PaymentID'=>$row['PaymentID']
    ,'Payment_OrganizationCode'=>$row['OrganizationCode']
    ,'Payment_SendMoneyID'=>$row['SendMoneyID']
    ,'Payment_PaymentType'=>$row['PaymentType']
    ,'Payment_PayPartial'=>$row['PayPartial']
    ,'Payment_BankCode'=>$row['BankCode']
    ,'Payment_ChequeNumber'=>$row['ChequeNumber']
    ,'Payment_ChequeBankBranch'=>$row['ChequeBankBranch']
    ,'Payment_ChequeDate'=>$row['ChequeDate']
    ,'Payment_CreditCardNumber'=>$row['CreditCardNumber']
    ,'Payment_CreditCardApproveCode'=>$row['CreditCardApproveCode']
    ,'Payment_CreditEmployeeLevelPath'=>$row['CreditEmployeeLevelPath']
    ,'Payment_TripID'=>$row['TripID']
    ,'Payment_Status'=>$row['Status']
    ,'Payment_RefNo'=>$row['RefNo']
    ,'Payment_PayPeriod'=>$row['PayPeriod']
    ,'Payment_PAYAMT'=>$row['PAYAMT']
    ,'Payment_CashCode'=>$row['CashCode']
    ,'Payment_EmpID'=>$row['EmpID']
    ,'Payment_TeamCode'=>$row['TeamCode']
    ,'Payment_receiptkind'=>$row['receiptkind']
    ,'Payment_Kind'=>$row['Kind']
    ,'Payment_BookNo'=>$row['BookNo']
    ,'Payment_ReceiptNo'=>$row['ReceiptNo']
    ,'Payment_CreateBy'=>$row['CreateBy']
    ,'Payment_LastUpdateBy'=>$row['LastUpdateBy']
    ,'Payment_CreateBy'=>$row['CreateBy']

    ,'EFFDATE'=>$row['EFFDATE']
    ,'InstallDate'=>$row['InstallDate']
    ,'todate'=>$row['todate']
    ,'CreateDate'=>$row['CreateDate']
    ,'LastUpdateDate'=>$row['LastUpdateDate']
    ,'SyncedDate'=>$row['SyncedDate']
    ,'DatePayment'=>$row['DatePayment']

    ,'Payment_PayDate'=>$row['PayDate']
    ,'Payment_CreateDate'=>$row['CreateDate']
    ,'Payment_LastUpdateDate'=>$row['LastUpdateDate']
    ,'Payment_SyncedDate'=>$row['SyncedDate']
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
