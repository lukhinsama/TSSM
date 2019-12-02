<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
/*
$limit_per_page = 100;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;
if (empty($_REQUEST['yearPark'])) {
  $selectYear = date("Y");
  $selectPak = 1;
}else {
  $yearPark = $_REQUEST['yearPark'];
  $sprit  = explode("_",$yearPark);
  $selectYear = $sprit[1];
  $selectPak = $sprit[0];
}
*/
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ข้อมูลใบสั่งซื้อ
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบมอนิเตอร์</a></li>
        <li><i class="fa fa-user"></i> ข้อมูลบ้านแดง/บิ๊กเฮด</li>
        <li class="active">ข้อมูลใบสั่งซื้อ</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-9">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> รายละเอียด</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <div class="box-body table-responsive no-padding">
            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th rowspan="2" style="text-align: center">ลำดับ</th>
                <th colspan="5" style="text-align: center">RedHouse Data</th>
                <th colspan="5" style="text-align: center">BigHead Data</th>
              </tr>
              <tr>
                <th>เลขที่สัญญา</th>
                <th>เลขที่อ้างอิง</th>
                <th>งวดที่</th>
                <th>จำนวนเงิน</th>
                <th>รหัสพนักงานขาย</th>
                <th>เลขที่สัญญา</th>
                <th>เลขที่อ้างอิง</th>
                <th>งวดที่</th>
                <th>จำนวนเงิน</th>
                <th>รหัสพนักงานขาย</th>
              </tr>
              </thead>
              <tbody>
              <?php
              $conn = connectDB_BigHead();
              $sql_case = "SELECT
DISTINCT MC.CONTNO AS RContno,MC.RefNo AS Rrefno,MC.SALECODE as Rsalecode,MC.PAYPERIOD,MC.PREMIUM
,C.bcontno as bcontno,C.ContractReferenceNo,C.bsalecode as bsalecode
,(SELECT TOP 1 PaymentPeriodNumber FROM [Bighead_Mobile].[dbo].SalePaymentPeriod WHERE C.refno = refno AND PaymentComplete = 1 ORDER BY PaymentPeriodNumber DESC) AS PaymentPeriodNumber
,(SELECT TOP 1 NetAmount FROM [Bighead_Mobile].[dbo].SalePaymentPeriod WHERE C.refno = refno AND PaymentComplete = 1) AS NetAmount
,case when MC.CONTNO IS NULL then '1' else '0' end as error
FROM (
SELECT
DISTINCT MC.CONTNO,MC.RefNo,MC.SALECODE,MC.PAYPERIOD,MC.PREMIUM,MC.SERIALNO
FROM LINK_STOCK.[TSRDATA].[dbo].[MastCont] AS MC
WHERE Mc.STATUS IN ('N','R','L') AND MC.TYPE NOT IN ('X','?') AND (DATEPART(YEAR,MC.effdate) = '2560' OR DATEPART(YEAR,MC.effdate) = '2561' ))
AS MC
FULL OUTER JOIN (
SELECT
DISTINCT C.CONTNO as bcontno,C.ContractReferenceNo,C.RefNo,C.salecode as bsalecode
FROM [Bighead_Mobile].[dbo].[Contract] AS C
LEFT JOIN [Bighead_Mobile].[dbo].SalePaymentPeriod AS SP ON C.refno = SP.refno
WHERE C.isactive = 1 AND C.STATUS IN ('NORMAL') AND (DATEPART(YEAR,C.EFFDATE) = '2017' OR DATEPART(YEAR,C.EFFDATE) = '2018'))
AS C ON MC.refno = C.ContractReferenceNo
WHERE (C.bcontno IS NULL OR MC.CONTNO IS NULL)
";

              //echo $sql_case;
              //$num_row = checkNumRow($conn,$sql_case);
              $stmt = sqlsrv_query($conn,$sql_case);
              $i = 1;
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                if ($row['error'] == "1") {
                    $styText = "style=\"color:red;\"";
                }else {
                    $styText = "";
                }

              ?>
              <tr <?=$styText?>>
                <td style="text-align: center;"><?=$i?></td>
                <td><?=$row['RContno']?></td>
                <td><?=$row['Rrefno']?></td>
                <td><?=$row['PAYPERIOD']?></td>
                <td><?=number_format($row['PREMIUM'])?></td>
                <td><?=$row['Rsalecode']?></td>

                <td><?php if (!empty($row['bcontno'])) { echo $row['bcontno'];}else{ echo "-";}?></td>
                <td><?php if (!empty($row['ContractReferenceNo'])) { echo $row['ContractReferenceNo'];}else{ echo "-";}?></td>
                <td><?php if (!empty($row['PaymentPeriodNumber'])) { echo $row['PaymentPeriodNumber'];}else{ echo "-";}?></td>
                <td><?php if (!empty($row['NetAmount'])) { echo number_format($row['NetAmount']);}else{ echo "-";}?></td>
                <td><?php if (!empty($row['bsalecode'])) { echo $row['bsalecode'];}else{ echo "-";}?></td>

              </tr>
              <?php
               $i++;
                }
               ?>
             </tbody>
             <tfoot>
             </tfoot>
            </table>
          </div>
          <?php
          //if (isset($startDate) || isset($endDate)) {
          // echo pagelimit($_GET['pages'],$num_row,$page,$sql,"","","");
          //}

          ?>
        </div>
        </div>

        <div class="col-md-3">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> ข้อมูลรวม</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <div class="box-body">
            <table class="table table-hover table-striped">
              <!--
              <tr>
                <th style="text-align: center">ปี</th>
                <th>รวมขายสด (บาท)</th>
                <th>รวมขายผ่อน (บาท)</th>
                <th>ยอดขายรวมทั้งปี (บาท)</th>
              </tr>
            -->
              <?php
              $conn = connectDB_BigHead();
              $sql_case = "SELECT COUNT(DISTINCT  CONTNO) AS Num
FROM [Bighead_Mobile].[dbo].[Contract] AS C
LEFT JOIN [Bighead_Mobile].[dbo].SalePaymentPeriod AS SP ON C.refno = SP.refno
WHERE C.isactive = 1 AND C.STATUS IN ('NORMAL') AND (DATEPART(YEAR,C.EFFDATE) = '2017' OR DATEPART(YEAR,C.EFFDATE) = '2018')";

              $stmt = sqlsrv_query($conn,$sql_case);

              while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $RedHouse = $row1['Num'];
              ?>
              <tr>
                <td> RedHouse Data </td>
                <td><?=number_format($RedHouse)?></td>
              </tr>
              <?php
                }
               ?>
               <?php
               $conn = connectDB_BigHead();
               $sql_case = "SELECT COUNT(DISTINCT  CONTNO) AS Num
FROM LINK_STOCK.[TSRDATA].[dbo].[MastCont] AS MC
WHERE Mc.STATUS IN ('N','R','L') AND MC.TYPE NOT IN ('X','?') AND (DATEPART(YEAR,MC.effdate) = '2560' OR DATEPART(YEAR,MC.effdate) = '2561' )";

               $stmt = sqlsrv_query($conn,$sql_case);

               while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                 $BigHead = $row1['Num'];
               ?>
               <tr>
                 <td> BigHead Data</td>
                 <td><?=number_format($BigHead)?></td>
               </tr>
               <?php
                 }
                 if ($RedHouse > $BigHead) {
                   $sum = $RedHouse - $BigHead;
                   $tdt = "style=\"color:red;\"";
                 }else {
                   $sum = $BigHead - $RedHouse;
                   $tdt = "style=\"color:black;\"";
                 }
                ?>
                <tr>
                  <td <?=$tdt;?>> Diff Data </td>
                  <td <?=$tdt;?>><?=number_format($sum)?></td>
                </tr>
            </table>
          </div>

        </div>
        </div>
      </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script>
    $(function () {
      $("#example1").DataTable();
      $('#example2').DataTable({
        "pageLength": 20,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });
  </script>
