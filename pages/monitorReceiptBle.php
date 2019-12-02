<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);


 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <div class="col-md-8">
          <h4>
            ข้อมูลใบเสร็จ ณ. วันที่ <?=DateTimeThai(DATE('d-m-Y H:i'))?> น.
          </h4>
        </div>
        <div class="col-md-4">
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
            </div>
          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">
            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">เลขที่อ้างอิง</th>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">เลขที่ใบเสร็จ</th>
                <th style="text-align: center">พนักงานเก็บเงิน</th>
                <th style="text-align: center">เขตเก็บเงิน</th>
                <th style="text-align: center">วันที่เก็บเงิน</th>
              </tr>
            </thead>
            <tbody>
              <?php
                  $conn = connectDB_BigHead();
                  /*$SQLSELECT = "SELECT C.ContractReferenceNo,C.contno,ReceiptCode,DatePayment,R.CreateBy,CASE WHEN ZoneCode IS NULL THEN 'ไม่มีเขตในโครงสร้าง ณ ขณะนั้น' ELSE ZoneCode END AS ZoneCode,CONVERT(varchar(20),DatePayment,105) +' '+ CONVERT(varchar(5),DatePayment,108) AS DatePayments
  FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R
  INNER JOIN Bighead_Mobile.dbo.Contract AS C ON R.RefNo = C.RefNo
  WhERE ReceiptCode IN (
  SELECT ReceiptCode
  FROM TSRData_Source.dbo.vw_ReceiptWithZone
  WHERE DatePayment BETWEEN CAST('2017-03-10' AS DATE) AND GETDATE() AND DATEPART(HOUR,DatePayment) != '00'
  GROUP BY ReceiptCode
  HAVING COUNT(ReceiptCode) > 1
  )ORDER BY DatePayment DESC,ReceiptCode";*/

$SQLSELECT = "SELECT C.ContractReferenceNo,C.contno,ReceiptCode,DatePayment,R.CreateBy,CASE WHEN ZoneCode IS NULL THEN 'ไม่มีเขตในโครงสร้าง ณ ขณะนั้น' ELSE ZoneCode END AS ZoneCode,CONVERT(varchar(20),DatePayment,105) +' '+ CONVERT(varchar(5),DatePayment,108) AS DatePayments
  FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R
  INNER JOIN Bighead_Mobile.dbo.Contract AS C ON R.RefNo = C.RefNo
  WhERE ReceiptCode IN (
  SELECT ReceiptCode
  FROM TSRData_Source.dbo.vw_ReceiptWithZone
  WHERE DATEDIFF(DAY,DatePayment,GETDATE()) < 10
  AND DATEPART(HOUR,DatePayment) != '00'
  GROUP BY ReceiptCode
  HAVING COUNT(ReceiptCode) > 1
  ) OR ReceiptCode IN (SELECT ReceiptCode FROM TSRData_Source.dbo.vw_ReceiptWithZone WHERE ZoneCode is null AND DATEDIFF(DAY,DatePayment,GETDATE()) < 10-- DatePayment BETWEEN CAST('2017-03-10' AS DATE) AND GETDATE()
  AND DATEPART(HOUR,DatePayment) != '00')
  ORDER BY DatePayment DESC,ReceiptCode";

                  //echo $SQLSELECT;

                  $stmt = sqlsrv_query($conn,$SQLSELECT);
                  $i=0;
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                    $i++;
               ?>

                <tr>
                <td style="text-align: center"><?=$i?></td>
                <td style="text-align: center"><?=$row['ContractReferenceNo']?></td>
                <td style="text-align: center"><?=$row['contno']?></td>
                <td><?=$row['ReceiptCode']?></td>
                <td style="text-align: center"><?=$row['CreateBy']?></td>
                <td style="text-align: center"><?=$row['ZoneCode']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['DatePayments'])?> น.</td>
              </tr>
              <?php
                  }
               ?>
             </tbody>
             <tfoot>
             </tfoot>
            </table>

          </div>
          <div class="box-footer clearfix">
          </div>
        </div>
        <?php
          sqlsrv_close($conn);
        ?>
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
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });
  </script>
