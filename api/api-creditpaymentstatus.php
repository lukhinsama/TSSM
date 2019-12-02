<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

if (isset($_GET['Empid'])) {
  $con = connectDB_BigHead();
  //$con = connectDB_BigHeadUAT();

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
  FROM Bighead_Mobile.dbo.Contract AS C With(NOLOCK)
  INNER JOIN Bighead_Mobile.dbo.Assign AS ASS With(NOLOCK) ON C.RefNo = ASS.RefNo
  INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPP With(NOLOCK) ON SPP.SalePaymentPeriodID = ASS.ReferenceID
  INNER JOIN Bighead_Mobile.dbo.Receipt AS R With(NOLOCK) ON R.ReceiptID = SPP.ReceiptID
  INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC ON C.CustomerID = DC.CustomerID
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
    ,'EFFDATE'=>$row['EFFDATE']
    ,'InstallDate'=>$row['InstallDate']
    ,'todate'=>$row['todate']
    ,'CreateDate'=>$row['CreateDate']
    ,'LastUpdateDate'=>$row['LastUpdateDate']
    ,'SyncedDate'=>$row['SyncedDate']
    ,'DatePayment'=>$row['DatePayment']
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
