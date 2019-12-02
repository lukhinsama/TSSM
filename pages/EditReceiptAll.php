<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
$EmpID = ConvertEmpIDInsertA($_COOKIE['tsr_emp_id']);

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <div class="col-md-3">
          <h4>
            แก้ไขใบเสร็จ
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>


      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> งานระบบ</a></li>
        <li><i class="fa fa-user"></i> แก้ไขใบเสร็จ</li>
        <li class="active"> แก้ไขใบเสร็จ </li>
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
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=receiptedit">
              <div class="input-group input-group-sm">
                <input type="text" name="searchText" class="form-control pull-right" id="counto" required placeholder="เลขที่ใบเสร็จ" value="<?php if(!empty($searchText)){ echo $searchText;}?>">

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
            <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=receiptedit">
            <table class="table table-hover table-striped">
              <tr>
                <th>เลขที่สัญญา</th>
                <th>เลขที่อ้างอิง</th>
                <th>เลขที่ใบเสร็จ</th>
                <th>งวดที่</th>
                <th>ค่างวด</th>
                <th>จำนวนเงิน</th>
                <th>สถานะ</th>
                <th>ชื่อลูกค้า</th>
                <th>แก้ไข</th>
              </tr>

              <?php
                $conn = connectDB_BigHead();

                $sql_case = "SELECT
                C.ContractReferenceNo,C.CONTNO,DC.CustomerName,E.FirstName+' '+E.LastName AS EmpName
  ,SP.SalePaymentPeriodID,SP.PaymentPeriodNumber,SP.PaymentAmount,Sp.Discount,SP.NetAmount,SP.PaymentComplete
  ,SPP.Amount,P.CashCode,P.EmpID,R2.TotalPayment
  ,R.ReceiptCode,R.DatePayment,CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) as DayPay
  FROM Bighead_Mobile.dbo.Contract AS C
  INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS SP ON SP.RefNo = C.RefNo
  LEFT JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC ON DC.CustomerID = C.CustomerID
  LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPP ON SPP.SalePaymentPeriodID = SP.SalePaymentPeriodID
  LEFT JOIN Bighead_Mobile.dbo.Payment AS P ON P.PaymentID = SPP.PaymentID
  LEFT JOIN Bighead_Mobile.dbo.Receipt AS R ON R.RefNo = C.RefNo AND R.ReceiptID = SPP.ReceiptID AND R.PaymentID = P.PaymentID
  LEFT JOIN Bighead_Mobile.dbo.ReceiptVoid AS R2 ON R2.RefNo = C.RefNo AND R2.ReceiptID = SPP.ReceiptID AND R2.PaymentID = P.PaymentID
  LEFT JOIN Bighead_Mobile.dbo.Employee AS E ON E.EmpID = p.EmpID
  WHERE (R.ReceiptCode = '".$_REQUEST['searchText']."')";

                //echo $sql_case;

                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  if ($row["PaymentComplete"] == 1) {
                    $Paystatus = "ครบ";
                  }else {
                    $Paystatus = "ไม่ครบ";
                  }
                ?>
                <tr>
                  <td><?=$row['CONTNO']?></td>
                  <td><?=$row['ContractReferenceNo']?></td>
                  <td><input class="form-control" type="text" name="ReceiptCode" value="<?=$row['ReceiptCode']?>"></td>
                  <td><?=$row['PaymentPeriodNumber']?></td>
                  <td><?=$row['NetAmount']?></td>
                  <td><input class="form-control" type="text" name="ReceiptCode" value="<?=$row['Amount']?>"></td>
                  <td><?=$Paystatus?></td>
                  <td><?=$row['EmpName']?></td>
                  <td>
                    <input type="hidden" name="SalePaymentPeriodID" value="<?=$row['SalePaymentPeriodID'];?>">
                    <button type="summit" class="btn btn-block btn-warning"> บันทึก </button></td>
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
?>
