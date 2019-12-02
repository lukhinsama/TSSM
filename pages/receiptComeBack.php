<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);


if (!empty($_REQUEST['ReceiptCode'])) {

//  echo "เลขใบเสร็จ".$_REQUEST['ReceiptCode']."<BR>";
$sql = "Declare @ReceiptCode varchar(64)
Declare @TotalPayment varchar(10)
Declare @ReceiptID varchar(70)
Declare @PaymentId varchar(70)
Declare @SalePaymentPeriodID varchar(70)
Declare @SalePaymentPeriodIDNEW varchar(70)
Declare @RefNo varchar(70)
Declare @PaymentPeriodNumber varchar(70)

set @ReceiptCode = '".$_REQUEST['ReceiptCode']."'
set @PaymentPeriodNumber = '".$_REQUEST['PaymentPeriodNumber']."'

SELECT @TotalPayment = TotalPayment,@ReceiptID = ReceiptID FROM Bighead_Mobile.dbo.ReceiptVoid WHERE ReceiptCode = @ReceiptCode
SELECT @SalePaymentPeriodID = SalePaymentPeriodID,@PaymentId = PaymentID FROM Bighead_Mobile.dbo.SalePaymentPeriodPayment WHERE ReceiptID = @ReceiptID

UPDATE Bighead_Mobile.dbo.Payment SET PAYAMT = PAYAMT + @TotalPayment WHERE PaymentID = @PaymentId
UPDATE Bighead_Mobile.dbo.Receipt SET TotalPayment = TotalPayment + @TotalPayment WHERE ReceiptID = @ReceiptID
UPDATE Bighead_Mobile.dbo.SalePaymentPeriodPayment SET Amount = @TotalPayment WHERE @ReceiptID = @ReceiptID AND PaymentID = @PaymentId
UPDATE Bighead_Mobile.dbo.SalePaymentPeriod SET PaymentComplete = 1 WHERE SalePaymentPeriodID = @SalePaymentPeriodID

SELECT @SalePaymentPeriodIDNEW = SalePaymentPeriodID FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = @RefNo AND PaymentPeriodNumber = @PaymentPeriodNumber
UPDATE Bighead_Mobile.dbo.SalePaymentPeriodPayment SET SalePaymentPeriodID = @SalePaymentPeriodIDNEW WHERE @ReceiptID = @ReceiptID AND PaymentID = @PaymentId


DELETE Bighead_Mobile.dbo.ReceiptVoid WHERE ReceiptID = @ReceiptID

INSERT INTO TSRData_Source.dbo.TSSM_LogReceiptComeBack (EmpID,ReceiptCode,RefNo,CONTNO,TotalPayment,StampTime) VALUES ('".$_REQUEST['EmpID']."','".$_REQUEST['ReceiptCode']."','".$_REQUEST['RefNo']."','".$_REQUEST['CONTNO']."',@TotalPayment,GETDATE())
";
//echo $sql;

  $conn = connectDB_BigHead();
  $stmt1 = sqlsrv_query( $conn, $sql );
  if( $stmt === false ) {
       die( print_r( sqlsrv_errors(), true));
  }else {
?>
<script>
alert("ดึงใบเสร็จยกเลิก กลับมาเรียบร้อย !!");
</script>
<?php
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
            ยกเลิกใบเสร็จยกเลิก
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>


      <ol class="breadcrumb">
        <li><i class="fa fa-user"></i> รายงานระบบ</</li>
        <li class="active"> ยกเลิกใบเสร็จยกเลิก </li>
      </ol>


    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="col-md-8">
              <h3 class="box-title">ข้อมูลในเสร็จ</h3>
            </div>
            <!--<div class="box-tools">-->
            <div class="col-md-4">
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=receiptComeBack">
              <div class="input-group input-group-sm">
                <input type="text" name="searchText" class="form-control pull-right" id="counto" required placeholder="เลขที่ใบเสร็จ" value="<?php if(!empty($_REQUEST['searchText'])){ echo $_REQUEST['searchText'];}?>">

                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
              </form>
            </div>
          </div>
          <!-- /.box-header -->
          <?php
            if ((isset($_REQUEST['searchText']))) {
          ?>
          <div class="box-body table-responsive no-padding">
            <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=receiptComeBack">
            <table class="table table-hover table-striped">
              <tr>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">เลขที่อ้างอิง</th>
                <th>เลขที่ใบเสร็จ</th>
                <th>งวดที่</th>
                <th>เขตเก็บเงิน</th>
                <th>ค่างวด</th>
                <th>จำนวนเงิน</th>
                <th>แก้ไข</th>
              </tr>

              <?php
                $conn = connectDB_BigHead();

                $sql_case = "SELECT * FROM TSRData_Source.dbo.vw_TSSM_ReceiptFullDetail WHERE ReceiptCode = '". $_REQUEST['searchText']."'";

                //echo $sql_case;

                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  if ($row['NetAmount'] == $row['TotalPayment']) {
                    $complete = 1;
                  }else {
                    $complete = 0;
                  }
                ?>
                <tr>
                  <td style="text-align: center"><?=$row['CONTNO']?></td>
                  <td style="text-align: center"><?=$row['ContractReferenceNo']?></td>
                  <td><?=$row['ReceiptCode']?></td>
                  <td><?=$row['PaymentPeriodNumber']?></td>
                  <td><?=$row['CashCode']?></td>
                  <td><?=number_format($row['NetAmount'],2)?></td>
                  <td><?=number_format($row['TotalPayment'],2)?></td>
                  <td style="text-align: center">
                    <input type="hidden" name="ReceiptCode" value="<?=$row['ReceiptCode'];?>">
                    <input type="hidden" name="PaymentPeriodNumber" value="<?=$row['PaymentPeriodNumber'];?>">
                    <input type="hidden" name="EmpID" value="<?=$_COOKIE['tsr_emp_id'];?>">
                    <input type="hidden" name="RefNo" value="<?=$row['ContractReferenceNo'];?>">
                    <input type="hidden" name="CONTNO" value="<?=$row['CONTNO'];?>">
                    <input type="hidden" name="Complete" value="<?=$complete?>">
                    <button type="summit" class="btn btn-block btn-danger" <?PHP if($row['TotalPayment'] > 0){ echo "disabled";} ?> > ยกเลิกใบเสร็จยกเลิก </button></td>
                </tr>
                <?php
                  }
                  sqlsrv_close($conn);
                 ?>


            </table>
          </form>
          </div>
          <?php
              }
           ?>
          <!-- /.box-body -->

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
