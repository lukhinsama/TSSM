<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

if (isset($_GET['contno'])) {
  //$con = connectDB_BigHead();
  $con = connectDB_BigHeadUAT();

  $SQL = "SELECT convert(varchar(20),R.DatePayment,120) as DatePayment
  ,R.ReceiptCode
  ,C.CONTNO
  ,convert(varchar(20),C.EFFDATE,120) as EFFDATE
  ,DC.CustomerName
  ,DC.IDCard
  ,replace(replace(AddressDetail+' ม.'+AddressDetail2+' ซอย'+AddressDetail3+' ถนน'+AddressDetail4+' ต.'+SD.SubDistrictName+' อ.'+D.DistrictName+' จ.'+P.ProvinceName+' '+A.Zipcode,'ซอย-',''),' ถนน-','') AS AddressInstall
  ,PO.ProductName
  ,C.MODEL
  ,C.ProductSerialNumber
  ,CONVERT(varchar,S.PaymentPeriodNumber)+'/'+(SELECT Convert(varchar,MAX(PaymentPeriodNumber)) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = R.RefNo) AS MaxPaymentPeriodNumber
  ,R.TotalPayment
  ,(SELECT Convert(varchar,MIN(PaymentPeriodNumber)) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = R.RefNo AND PaymentComplete = 0)+'-'+(SELECT Convert(varchar,MAX(PaymentPeriodNumber)) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = R.RefNo AND PaymentComplete = 0) AS PeriodTotal
  --,(SELECT SUM(NetAmount) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = R.RefNo AND PaymentComplete = 0) AS PeriodTotalPrice
  ,(SELECT SUM(NetAmount) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = R.RefNo AND  PaymentPeriodNumber >= S.PaymentPeriodNumber) - SP.Amount AS PeriodTotalPrice
  ,E.FirstName+' '+E.LastName AS EmpName
  ,'ทีม '+ASS.AssigneeTeamCode AS EmpTeam
  ,LEFT(ED.SaleCode,1) AS Salecode
  ,R.CreateBy
  ,S.SalePaymentPeriodID,S.RefNo
  ,CONVERT(varchar,s.PaymentPeriodNumber) AS PaymentPeriodNumber
  ,CONVERT(varchar,S.PaymentAmount) AS PaymentAmount
  ,CONVERT(varchar,S.Discount) AS Discount
  ,CONVERT(varchar,S.NetAmount) AS NetAmount
  ,CONVERT(varchar,S.PaymentComplete) AS PaymentComplete
  ,S.PaymentDueDate,S.PaymentAppointmentDate,S.TripID,S.CreateDate,S.CreateBy,S.LastUpdateDate,S.LastUpdateBy,S.SyncedDate
  ,CONVERT(varchar,S.CloseAccountDiscountAmount) AS CloseAccountDiscountAmount
  --,R.paymentID
  ,R.DatePayment AS DatePayment1

  ,Pay.PaymentID
  , Pay.OrganizationCode
  , ISNULL(Pay.SendMoneyID,'') AS SendMoneyID
  , Pay.PaymentType
  , CONVERT(varchar,Pay.PayPartial) AS PayPartial
  , Pay.BankCode
  , Pay.ChequeNumber
  , ISNULL(Pay.ChequeBankBranch,'') AS ChequeBankBranch,
ISNULL(Pay.ChequeDate,'') AS ChequeDate
, ISNULL(Pay.CreditCardNumber,'') AS CreditCardNumber
, ISNULL(Pay.CreditCardApproveCode,'') AS CreditCardApproveCode
, Pay.CreditEmployeeLevelPath
, Pay.TripID
, Pay.Status
, Pay.RefNo
, Pay.PayPeriod
, Pay.PayDate
, CONVERT(varchar,Pay.PAYAMT) AS PAYAMT
, Pay.CashCode
, Pay.EmpID
, Pay.TeamCode
, ISNULL(Pay.receiptkind,'') AS receiptkind
, ISNULL(Pay.Kind,'') AS Kind
, ISNULL(Pay.BookNo,'') AS BookNo
, ISNULL(Pay.ReceiptNo,'') AS ReceiptNo
, Pay.CreateDate
, Pay.CreateBy
, Pay.LastUpdateDate
, Pay.LastUpdateBy
, Pay.SyncedDate
  FROM Bighead_Mobile.dbo.Receipt AS R
  INNER JOIN Bighead_Mobile.dbo.Contract AS C ON R.RefNo = C.RefNo
  INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC ON DC.CustomerID = C.CustomerID
  INNER JOIN Bighead_Mobile.dbo.Address AS A ON A.RefNo = C.RefNo AND AddressTypeCode = 'AddressInstall'
  INNER JOIN Bighead_Mobile.dbo.Province AS P ON P.ProvinceCode = A.ProvinceCode
  INNER JOIN Bighead_Mobile.dbo.District AS D ON D.DistrictCode = A.DistrictCode
  INNER JOIN Bighead_Mobile.dbo.SubDistrict AS SD ON SD.SubDistrictCode = A.SubDistrictCode
  INNER JOIN Bighead_Mobile.dbo.Product AS Po ON Po.ProductID = C.ProductID
  INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SP ON SP.ReceiptID = R.ReceiptID
  INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S ON S.SalePaymentPeriodID = SP.SalePaymentPeriodID
  INNER JOIN Bighead_Mobile.dbo.Assign AS ASS ON ASS.ReferenceID = Sp.SalePaymentPeriodID
  LEFT JOIN Bighead_Mobile.dbo.Employee AS E ON E.EmpID = R.CreateBy
  LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS ED ON Ed.EmployeeCode = R.CreateBy AND ED.SaleCode is not null
  LEFT JOIN Bighead_Mobile.dbo.Payment AS Pay ON Pay.PaymentID = R.PaymentID
  WHERE C.CONTNO = '".$_GET['contno']."' AND DATEDIFF (DAY,DatePayment,GETDATE()) = 0
  ORDER BY Pay.PayDate
  ";

  //$con = connectDB_BigHead();

  //echo $SQL;

  $stmt = sqlsrv_query($con,$SQL);
  $result=[];
  //$json_result=$arrayName = array('' => , );
  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

    if ($row['Salecode'] == "9") {
      if ($row['CreateBy'] == "A27367") {
        $Chanel = "(QR PAYMENT)";
        $TeamName = "";
      }elseif ($row['CreateBy'] == "A31218") {
        $Chanel = "(โอน-ธนาคารกรุงเทพ)";
        $TeamName = "";
      }elseif ($row['CreateBy'] == "A25945") {
        $Chanel = "(โอน-อื่นๆ)";
        $TeamName = "";
      }elseif ($row['CreateBy'] == "A38445") {
        $Chanel = "(โอน-ธนาคารไทยพาณิชย์)";
        $TeamName = "";
      }elseif ($row['CreateBy'] == "A36847") {
        $Chanel = "(โอน-กฎหมาย)";
        $TeamName = "";
      }elseif ($row['CreateBy'] == "A10925") {
        $Chanel = "(โอน-รวมทุกธนาคาร)";
        $TeamName = "";
      }elseif ($row['CreateBy'] == "A32599") {
        $Chanel = "(เคาร์เตอร์เซอร์วิส)";
        $TeamName = "";
      }else {
        $Chanel = "(".$row['EmpName'].")";
        $TeamName = "(".$row['EmpTeam'].")";
      }
    }else {
      $Chanel = "(".$row['EmpName'].")";
      $TeamName = "(".$row['EmpTeam'].")";
    }

    $json_result = array('ReceiptCode'=>$row['ReceiptCode']
    ,'CONTNO'=>$row['CONTNO']
    ,'CustomerName'=>$row['CustomerName']
    ,'IDCard'=>$row['IDCard']
    ,'AddressInstall'=>$row['AddressInstall']
    ,'ProductName'=>$row['ProductName']
    ,'MODEL'=>$row['MODEL']
    ,'ProductSerialNumber'=>$row['ProductSerialNumber']
    ,'MaxPaymentPeriodNumber'=>$row['MaxPaymentPeriodNumber']
    ,'TotalPayment'=>number_format($row['TotalPayment'],2)
    ,'TotalPaymentText'=>num2wordsThai($row['TotalPayment'])
    ,'PeriodTotal'=>$row['PeriodTotal']
    ,'PeriodTotalPrice'=>number_format($row['PeriodTotalPrice'])
    ,'ChanelName'=>$Chanel
    ,'TeamName'=>$TeamName
    ,'EFFDATE'=>DateThaiAPI($row['EFFDATE'])
    ,'DatePayment'=>DateThaiAPI($row['DatePayment'])

    ,'SalePaymentPeriod_SalePaymentPeriodID'=>$row['SalePaymentPeriodID']
    ,'SalePaymentPeriod_RefNo'=>$row['RefNo']
    ,'SalePaymentPeriod_PaymentPeriodNumber'=>$row['PaymentPeriodNumber']
    ,'SalePaymentPeriod_PaymentAmount'=>$row['PaymentAmount']
    ,'SalePaymentPeriod_Discount'=>$row['Discount']
    ,'SalePaymentPeriod_NetAmount'=>$row['NetAmount']
    ,'SalePaymentPeriod_PaymentComplete'=>$row['PaymentComplete']
    ,'SalePaymentPeriod_TripID'=>$row['TripID']
    ,'SalePaymentPeriod_CreateBy'=>$row['CreateBy']
    ,'SalePaymentPeriod_LastUpdateBy'=>$row['LastUpdateBy']
    ,'SalePaymentPeriod_CloseAccountDiscountAmount'=>$row['CloseAccountDiscountAmount']


    ,'Payment_paymentID'=>$row['PaymentID']
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

    ,'Payment_CreateDate'=>$row['CreateDate']
    ,'Payment_LastUpdateDate'=>$row['LastUpdateDate']
    ,'Payment_PayDate'=>$row['PayDate']
    ,'Payment_SyncedDate'=>$row['SyncedDate']
    ,'DatePayment1'=>$row['DatePayment1']
    ,'SalePaymentPeriod_CreateDate'=>$row['CreateDate']
    ,'SalePaymentPeriod_PaymentDueDate'=>$row['PaymentDueDate']
    ,'SalePaymentPeriod_PaymentAppointmentDate'=>$row['PaymentAppointmentDate']
    ,'SalePaymentPeriod_LastUpdateDate'=>$row['LastUpdateDate']
    ,'SalePaymentPeriod_SyncedDate'=>$row['SyncedDate']
    );

    array_push($result,$json_result);
  }

  sqlsrv_close($con);
  echo json_encode(array('data' => $result));
  //echo json_encode($json_result);
}else {
  echo "ERROR";
}


 function DateThaiAPI($strDate){
 		$strDate = date_format(date_create($strDate),"Y-m-d H:i:s");

 		$strYear = date("Y",strtotime($strDate));
 		$strMonth= date("n",strtotime($strDate));
 		$strDay= date("j",strtotime($strDate));
 		$strHour= date("H",strtotime($strDate));
 		$strMinute= date("i",strtotime($strDate));
 		$strSeconds= date("s",strtotime($strDate));
 		//$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
 		//$strMonthThai=$strMonthCut[$strMonth];
 		//return "$strDay $strMonthThai $strYear, $strHour:$strMinute";

    if (strlen($strMonth) == 1) {
      $strMonth = "0".$strMonth;
    }
 		return "$strDay/$strMonth/$strYear";
 }
?>
