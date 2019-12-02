<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);


if (!empty($_REQUEST['ReceiptCode'])) {
/*
  echo "เลขใบเสร็จ".$_REQUEST['ReceiptCode']."<BR>";
  echo "งวดเดิม".$_REQUEST['BeforPeriodNumber']."<BR>";
  echo "งวดใหม่".$_REQUEST['AfterPeriodNumber']."<BR>";
  echo "งวดใหม่".$_REQUEST['empID']."<BR>";
  */

$sql = "Declare @ReceiptCode varchar(64)
  Declare @ReceiptID varchar(64)
  Declare @BeforPeriodNumber varchar(10)
  Declare @AfterPeriodNumber varchar(10)
  Declare @PaymentId varchar(64)
  Declare @RefNo varchar(64)

  set @ReceiptCode = '".$_REQUEST['ReceiptCode']."'
  set @BeforPeriodNumber = '".$_REQUEST['BeforPeriodNumber']."'
  set @AfterPeriodNumber = '".$_REQUEST['AfterPeriodNumber']."'

  SELECT @RefNo = RefNo, @PaymentId = PaymentID ,@ReceiptID = ReceiptID FROM Bighead_Mobile.dbo.Receipt WHERE ReceiptCode = @ReceiptCode";

if ($_REQUEST['Complete'] == '1') {
  $sql .= " UPDATE Bighead_Mobile.dbo.SalePaymentPeriod
    SET PaymentComplete = 0
    WHERE SalePaymentPeriodID in (SELECT SalePaymentPeriodID FROM Bighead_Mobile.dbo.SalePaymentPeriod
    WHERE RefNo = @RefNo AND PaymentPeriodNumber = @BeforPeriodNumber)

    UPDATE Bighead_Mobile.dbo.SalePaymentPeriod
    SET PaymentComplete = 1
    WHERE SalePaymentPeriodID in (SELECT SalePaymentPeriodID FROM Bighead_Mobile.dbo.SalePaymentPeriod
    WHERE RefNo = @RefNo AND PaymentPeriodNumber = @AfterPeriodNumber)";
}

$sql .= " UPDATE Bighead_Mobile.dbo.SalePaymentPeriodPayment
  SET SalePaymentPeriodID = (SELECT SalePaymentPeriodID FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = @RefNo AND PaymentPeriodNumber = @AfterPeriodNumber)
  WHERE PaymentID = @PaymentId AND ReceiptID = @ReceiptID

  INSERT INTO TSRData_Source.dbo.TSSM_LogChangeReceiptPeriod (EmpID,ReceiptCode,RefNo,CONTNO,BeforPeriodNumber,AfterPeriodNumber,StampTime) VALUES ('".$_REQUEST['EmpID']."','".$_REQUEST['ReceiptCode']."','".$_REQUEST['RefNo']."','".$_REQUEST['CONTNO']."','".$_REQUEST['BeforPeriodNumber']."','".$_REQUEST['AfterPeriodNumber']."',GETDATE())";

  //echo $sql;

  $conn = connectDB_BigHead();
  $stmt1 = sqlsrv_query( $conn, $sql );
  if( $stmt === false ) {
       die( print_r( sqlsrv_errors(), true));
  }else {
    $sussce = $_REQUEST['ReceiptCode'];
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
            แก้ไขงวดใบเสร็จ
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>


      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบจัดการข้อมูล</a></li>
        <li><i class="fa fa-user"></i> ระบบการเก็บเงิน</li>
        <li class="active"> แก้ไขงวดใบเสร็จ </li>
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
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=changeReceiptPeriod">
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
            <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=changeReceiptPeriod">
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
                  <td>
                    <select class="select2 group-sm" name="AfterPeriodNumber" >
                      <optgroup label="งวดที่">
                        <?PHP

                        $sql2 = "SELECT PaymentPeriodNumber FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = '".$row['RefNo']."'";

                        //echo $sql_case;
                        $stmt2 = sqlsrv_query($conn,$sql2);
                        while ($row1 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC)) {
                        ?>
                      <option value="<?=$row1['PaymentPeriodNumber']?>" <?PHP if($row1['PaymentPeriodNumber'] === $row['PaymentPeriodNumber']) { ECHO "selected";} ?>>
                        <?=$row1['PaymentPeriodNumber']?>
                      </option>
                        <?php
                          }
                        ?>
                      </optgroup>
                    </select>

                  </td>
                  <td><?=$row['CashCode']?></td>
                  <td><?=number_format($row['NetAmount'],2)?></td>
                  <td><?=number_format($row['TotalPayment'],2)?></td>
                  <td style="text-align: center">
                    <input type="hidden" name="ReceiptCode" value="<?=$row['ReceiptCode'];?>">
                    <input type="hidden" name="BeforPeriodNumber" value="<?=$row['PaymentPeriodNumber'];?>">
                    <input type="hidden" name="EmpID" value="<?=$_COOKIE['tsr_emp_id'];?>">
                    <input type="hidden" name="RefNo" value="<?=$row['ContractReferenceNo'];?>">
                    <input type="hidden" name="CONTNO" value="<?=$row['CONTNO'];?>">
                    <input type="hidden" name="Complete" value="<?=$complete?>">
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
        }else {
          if ($sussce != NULL) {
           ?>
           <div class="alert alert-success" role="alert">
              แก้ไขใบเสร็จ <strong><?=$sussce?></strong> สำเร็จ !!
            </div>
            <?php
          }
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
