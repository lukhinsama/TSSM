<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);


if (!empty($_REQUEST['startDate'])) {

 //echo "วันที่".DateEng($_REQUEST['startDate'])."<BR>";

$sqlupdate = "DELETE TSRDATA_Source.dbo.vw_ReceiptWithZone
WHERE  DATEDIFF(DAY,CreateDate,getdate()) = (select DATEDIFF(DAY,CAST('".DateEng($_REQUEST['startDate'])."' AS date),getdate())) AND AssigneeEmpID IS NULL

INSERT INTO TSRData_Source.dbo.vw_ReceiptWithZone
SELECT  *,(select Bighead_Mobile.dbo.fn_CheckIntStr1StOnly(ZoneCode)) AS TypeCode ,null as AssigneeEmpID,null as AssigneeSaleCode
FROM Bighead_Mobile.dbo.vw_ReceiptWithZone
WHERE DATEDIFF(DAY,CreateDate,getdate()) = (select DATEDIFF(DAY,CAST('".DateEng($_REQUEST['startDate'])."' AS date),getdate()))
AND ReceiptID NOT IN (SELECT ReceiptID FROM TSRData_Source.dbo.vw_ReceiptWithZone WHERE DATEDIFF(DAY,CreateDate,getdate()) = (select DATEDIFF(DAY,CAST('".DateEng($_REQUEST['startDate'])."' AS date),getdate())))

UPDATE R2 SET R2.TotalPayment = R1.TotalPayment
FROM Bighead_Mobile.dbo.Receipt AS R1
LEFT JOIN TSRData_Source.dbo.vw_ReceiptWithZone AS R2 ON R1.ReceiptID = R2.ReceiptID
WHERE DATEDIFF(day,R1.CreateDate,GETDATE()) = (select DATEDIFF(DAY,CAST('".DateEng($_REQUEST['startDate'])."' AS date),getdate())) AND R1.TotalPayment != R2.TotalPayment

DELETE TSRData_Source.dbo.vw_ReceiptWithZone_ALL
WHERE DATEDIFF(day,DatePayment,GETDATE()) = (select DATEDIFF(DAY,CAST('".DateEng($_REQUEST['startDate'])."' AS date),getdate())) AND (PaymentPeriodNumber = 1 OR TypeCode = 1)

INSERT INTO TSRData_Source.dbo.vw_ReceiptWithZone_ALL
SELECT DISTINCT
R.ReceiptID
,R.ReceiptCode
,SP.SalePaymentPeriodID
,R.PaymentID
,R.RefNo
,R.DatePayment
,SP.PaymentPeriodNumber
,SP.NetAmount
,R.TotalPayment
,CASE WHEN SP.NetAmount != R.TotalPayment THEN 0 ELSE 1 END AS PaymentComplete
,R.CreateDate
,R.CreateBy
,R.ZoneCode
,R.TypeCode
,ISNULL((SELECT MAX(PrintOrder) FROM Bighead_Mobile.dbo.DocumentHistory WHERE DocumentNumber = R.ReceiptID GROUP BY DocumentNumber) ,0) AS PrintOrder
,NULL AS PayDateOld
,RW.BankAccount as BankAccount
,RW.StampTransfer AS DateTransfer
,RW.Ways AS BankName
,CASE WHEN RW.Ways LIKE 'ธนาคาร%' THEN 1 ELSE 0 END AS TransferStatus
FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R
INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPP ON R.ReceiptID = SPP.ReceiptID AND R.PaymentID = SPP.PaymentID
INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS SP ON SPP.SalePaymentPeriodID = SP.SalePaymentPeriodID
LEFT JOIN TSRData_Source.dbo.TSSM_LOG_ReceiptWays AS RW ON RW.ReceiptID = R.ReceiptID
WHERE DATEDIFF(day,R.DatePayment,GETDATE()) = (select DATEDIFF(DAY,CAST('".DateEng($_REQUEST['startDate'])."' AS date),getdate())) AND (SP.PaymentPeriodNumber = 1 OR R.TypeCode = 1)
ORDER BY R.DatePayment

INSERT INTO TSRData_Source.dbo.TSSM_LogUpdateReceiptWithZoneBOF (EmpID,DateUpdate,StampTime) VALUES ('".$_REQUEST['EmpID']."','".DateEng($_REQUEST['startDate'])."',GETDATE())
";

$sqlBefore = "SELECT SUM(Ref) AS SumRef , SUM(RefNO) as SumRefNO ,SUM(PAYAMT) as SumPAYAMT ,COUNT(CCode) AS SumCCode
FROM (
 SELECT Sum(PAYAMT) AS PAYAMT,sum(num) As Ref,sum(cont) as RefNO,SaleCode AS CCode
 FROM (
 SELECT Sum(PAYAMT) AS Payamt,sum(num) As num,1 as cont,SaleCode
 FROM (
 SELECT ReceiptCode,CONTNO,SaleCode,SUM(PAYAMT)as PAYAMT,1 as num
from (

SELECT DISTINCT ReceiptCode,Right('000'+Convert(Varchar,S.PaymentPeriodNumber),2) As PaymentPeriodNumber,c.CONTNO AS CONTNO,Sy.Amount AS PAYAMT,R.ZoneCode as SaleCode
FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R WITH(NOLOCK)
LEFT JOIN Bighead_Mobile.dbo.Contract AS C WITH(NOLOCK) ON R.RefNo = C.RefNo
LEFT JOIN Bighead_Mobile.dbo.vw_GetCustomer AS GC WITH(NOLOCK) ON C.CustomerID = GC.CustomerID
LEFT JOIN SalePaymentPeriodPayment As Sy WITH(NOLOCK) ON R.PaymentID = Sy.PaymentID AND R.ReceiptID = Sy.ReceiptID
LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S WITH(NOLOCK) ON S.SalePaymentPeriodID = Sy.SalePaymentPeriodID
LEFT JOIN Bighead_Mobile.dbo.Employee AS Em WITH(NOLOCK) ON R.LastUpdateBy = EM.EmpID
WHERE  DATEDIFF(DAY,R.DatePayment,getdate()) = (select DATEDIFF(DAY,CAST('".DateEng($_REQUEST['startDate'])."' AS date),getdate()))
AND S.SalePaymentPeriodID = Sy.SalePaymentPeriodID AND Sy.Amount > 0 AND R.TypeCode = 0

) as result
GROUP BY ReceiptCode,CONTNO,SaleCode
)AS a1
GROUP BY SaleCode,CONTNO
) as a2 GROUP BY SaleCode
) AS SQL1";
//ECHO $sql;

  $conn = connectDB_BigHead();

  $stmt = sqlsrv_query( $conn, $sqlBefore );
    while ($rowBefore = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      $Before = "จำนวนใบเสร็จ ".number_format($rowBefore['SumRef'])." ใบ จำนวนสัญญา ".number_format($rowBefore['SumRefNO'])." สัญญา รวมจำนวนเงิน ".number_format($rowBefore['SumPAYAMT'],2)." บาท จากทั้งหมด ".number_format($rowBefore['SumCCode'])." เขต";
    }

  $stmt1 = sqlsrv_query( $conn, $sqlupdate );
  if( $stmt1 === false ) {
       die( print_r( sqlsrv_errors(), true));
  }else {
    $sussce = $_REQUEST['startDate'];
    }

  $stmt = sqlsrv_query( $conn, $sqlBefore );
    while ($rowBefore = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      $Afert = "จำนวนใบเสร็จ ".number_format($rowBefore['SumRef'])." ใบ จำนวนสัญญา ".number_format($rowBefore['SumRefNO'])." สัญญา รวมจำนวนเงิน ".number_format($rowBefore['SumPAYAMT'],2)." บาท จากทั้งหมด ".number_format($rowBefore['SumCCode'])." เขต";
    }
  sqlsrv_close($conn);

}
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <div class="col-md-3">
          <h4>
            อัพเดดใบเสร็จย้อนหลัง
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>


      <ol class="breadcrumb">
        <li><i class="fa fa-user"></i> รายงานระบบ</</li>
        <li class="active"> อัพเดดใบเสร็จย้อนหลัง </li>
      </ol>


    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="col-md-8">
              <h3 class="box-title"></h3>
            </div>
            <!--<div class="box-tools">-->
            <div class="col-md-4">
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=updateReceiptTable">
                <div class="input-group input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>

                  <!--<div class="input-group input-group input-daterange" id="datepicker2">-->
                          <input type="text" class="form-control" id="datepicker2" name="startDate" autocomplete="off" value="<?php if(isset($_REQUEST['startDate'])) {echo $_REQUEST['startDate'];}?>" placeholder="กรุณาเลือกวันที่" required>
                        <!--  <span class="input-group-addon">ถึง</span>
                          <input type="text" class="form-control" name="endDate" autocomplete="off" value="<?php if(isset($_REQUEST['endDate'])) {echo $_REQUEST['endDate'];}?>" placeholder="วันสิ้นสุด .." required>-->
                      <!--</div>-->

                  <div class="input-group-btn">
                    <input type="hidden" name="EmpID" value="<?=$_COOKIE['tsr_emp_id'];?>">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <?php
          if (!empty($sussce)) {
           ?>
           <div class="alert alert-success" role="alert">

              อัพข้อมูลวันที่ <strong><?=$sussce?></strong> สำเร็จ !! <BR>
              ข้อมูลก่อนอัพเดด <?=$Before?> <BR>
              ข้อมูลหลังอัพเดด <?=$Afert?> <BR>
            </div>
            <?php
          }
          ?>
        </div>
        <!-- /.box -->
      </div>
    </div>
    <!-- /.row -->
  </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script>
  $(function() {
    $( "#models" ).autocomplete({
      //source: 'search.php'
      source: '../include/inc-autocom.php?types=counto'
    });
  });
</script>
