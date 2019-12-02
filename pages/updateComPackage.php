<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);


if ((isset($_REQUEST['searchText']))) {
  $top = "";
  $searchText = $_REQUEST['searchText'];
  $where = "AND c.contno = '".$_REQUEST['searchText']."'";
}else {
  $top = "TOP 1";
  $where = "AND c.contno = '11517550'";
}


if ((!empty($_REQUEST['CONTNO'])) AND (!empty($_REQUEST['MODELID']))) {
/*
  $sql = "EXEC [dbo].usp_TSR_Contract_MoveMultipleContractToZone
  @OrganizationCode = 0,
  @CONTNOLIST = '".$_REQUEST['contno']."',
  @ZoneCode= '".$_REQUEST['AssigneeEmpID']."',
  @RemoveAssign = 'Y',
  @AutoAssign = 'Y'";
*/

  $SQL = "EXEC dbo.usp_TSR_Contract_ChangePackage
  @RefNo   = '".$_REQUEST['RefNo']."',
  @ContNo   = '".$_REQUEST['CONTNO']."',
  @ContractReferenceNo  = '".$_REQUEST['ContractReferenceNo']."',
  @NewPackage = '".$_REQUEST['MODELID']."',
  @isFirstPeriodPayment  = 0,
  @FirstPeriodPaymentAmount = 0";
  echo $SQL;

  $conn = connectDB_BigHead();
  //$stmt1 = sqlsrv_query( $conn, $SQL );
  if( $stmt === false ) {
       die( print_r( sqlsrv_errors(), true));
  }else {

    $sql_insert = "INSERT INTO TSRData_Source.dbo.Log_Tranfer_BigHead (SaleCode,Contno,CreateDate,CreateBy,ErrorStatus,errormsg) VALUES (?,?,GETDATE(),?,0,?)";
    //echo $sql_insert;

    $params = array($_REQUEST['AssigneeEmpID'],$_REQUEST['contno'],$_REQUEST['CreateBy'],$_SERVER['REMOTE_ADDR']);
    //print_r($params);


    $stmt_insert = sqlsrv_query( $conn, $sql_insert, $params);

    if( $stmt_insert === false ) {
       die( print_r( sqlsrv_errors(), true));
    }
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
            เปลี่ยน Package
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>


      <ol class="breadcrumb">
        <!--
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบจัดการข้อมูล</a></li>
        <li><i class="fa fa-user"></i> ระบบการเก็บเงิน</li>
        <li class="active"> โอนลูกค้ารายสัญญา </li>
      -->
      </ol>


    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="col-md-8">
              <h3 class="box-title">ข้อมูลเลขที่สัญญา</h3>
            </div>
            <!--<div class="box-tools">-->
            <div class="col-md-4">
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=updatepackage">
              <div class="input-group input-group-sm">
                <input type="text" name="searchText" class="form-control pull-right" id="counto" required placeholder="ค้นหา เลขที่สัญญา" value="<?php if(!empty($searchText)){ echo $searchText;}?>">

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
            <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=updatepackage">
            <table class="table table-hover table-striped">
              <tr>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">เลขที่อ้างอิง</th>
                <th>ชื่อลูกค้า</th>
                <th>งวดที่</th>
                <th>จำนวนเงิน</th>
                <th>พนักงานเก็บเงิน(บ้านแดง)</th>
                <th>พนักงานเก็บเงิน(บิ๊กเฮด)</th>
                <th>แก้ไข</th>
              </tr>

              <?php
                $conn = connectDB_BigHead();

                $sql_case = "SELECT RefNo,CONTNO,ContractReferenceNo,CustomerName,C.MODEL,C.MODE,C.TotalPrice
                FROM Bighead_Mobile.dbo.Contract AS C
                INNER JOIN Bighead_Mobile.dbo.DebtorCustomer AS DC ON C.CustomerID = DC.CustomerID
                $where ";

                //echo $sql_case;

                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  $REFNO = $row['RefNo'];
                ?>

                <tr>
                  <td style="text-align: center"><?=$row['CONTNO']?></td>
                  <td style="text-align: center"><p class="text-primary"><?=$row['ContractReferenceNo']?></p></td>
                  <td><?=$row['CustomerName']?></td>
                  <td><?=$row['MODE']?></td>
                  <td><?=$row['TotalPrice']?></td>
                  <td><?=$row['MODEL']?></td>
                  <td>
                    <select class="form-control select2" name="MODELID" style="width: 100%;">
                      <optgroup label="เลือกพนักงานเก็บเงิน">
                        <?php
                          $conns = connectDB_BigHead();

                          $sql1 = "SELECT MODEL FROM Bighead_Mobile.dbo.Package";

                          //echo $sql;
                          $stmt1 = sqlsrv_query( $conns, $sql1 );
                          while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                        ?>
                          <option value="<?=$row1['MODEL'];?>" <?php if($row1['MODEL']==$row['MODEL']){ echo "selected";}?>><?=$row1['MODEL']?></option>
                        <?php
                            }
                            sqlsrv_close($conns);
                        ?>
                      </optgroup>
                  </select>

                  </td>
                  <td style="text-align: center">
                    <input type="hidden" name="CONTNO" value="<?=$row['CONTNO'];?>">
                    <input type="hidden" name="RefNo" value="<?=$row['RefNo'];?>">
                    <input type="hidden" name="ContractReferenceNo" value="<?=$row['ContractReferenceNo'];?>">
                    <button type="summit" class="btn btn-block btn-warning"> บันทึก </button></td>
                </tr>
                <?php
                  }

                 ?>


            </table>
          </form>
          </div>

          <div class="box-body">

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">งวดที่</th>
                <th style="text-align: center">เลขที่ใบเสร็จ</th>
                <th style="text-align: center">เวลาออกใบเสร็จ</th>
                <th style="text-align: center">ค่างวด</th>
                <th style="text-align: center">ยอดชำระ</th>
                <th style="text-align: center">สถานะ</th>
              </tr>
            </thead>
            <tbody>
              <?php
                //$conn = connectDB_BigHead();

              $sql = "SELECT SP.PaymentPeriodNumber,R.ReceiptCode,R.DatePayment,SP.NetAmount,ISNULL(SPP.Amount,0) AS Amount,SP.PaymentComplete
              FROM Bighead_Mobile.dbo.SalePaymentPeriod AS SP
              LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPP ON SP.SalePaymentPeriodID = SPP.SalePaymentPeriodID
              LEFT JOIN Bighead_Mobile.dbo.Receipt AS R ON SPP.ReceiptID = R.ReceiptID
              WHERE SP.RefNo = '".$REFNO."'
              ORDER BY SP.PaymentPeriodNumber";

                //echo $sql;

                $stmt = sqlsrv_query($conn,$sql);
                while ($row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  if ($row2['PaymentComplete'] == "1") {
                    $STATUS = "ครบ";
                  }else {
                    $STATUS = "ไม่ครบ";
                  }
                ?>

              <tr>
                <td style="text-align: center"><?=$row2['PaymentPeriodNumber']?></td>
                <td>
                  <?PHP
                  if ($row2['ReceiptCode'] == NULL) {
                   ECHO "-";
                  }else {
                    ECHO $row2['ReceiptCode'];
                  }
                  ?>
                </td>
                <td style="text-align: center">
                  <?php
                  if ($row2['DatePayment'] == NULL) {
                   ECHO "-";
                  }else {
                    ECHO DateTimeThai($row2['DatePayment'])."น.";
                  }
                  ?>
                </td>
                <td style="text-align: center"><?=number_format($row2['NetAmount'],2)?></td>
                <td style="text-align: right"><?=number_format($row2['Amount'],2)?></td>
                <td style="text-align: center"><?=$STATUS?></td>
              </tr>
              <?php
            }
               ?>
             </tbody>
             <tfoot>
             </tfoot>
            </table>

          </div>
          <?php

            sqlsrv_close($conn);
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
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({
      "pageLength": 30,
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": false,
      "autoWidth": false
    });
  });
</script>
