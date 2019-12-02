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
            ข้อมูลสัญญาผู้แนะนำผิด ณ. วันที่ <?=DateTimeThai(DATE('d-m-Y H:i'))?> น.
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
                <th style="text-align: center">รหัสพนักงานขาย</th>
                <th style="text-align: center">รหัสผู้แนะนำ</th>
                <th style="text-align: center">ปัญหา</th>
                <th style="text-align: center">วันที่ติดตั้ง</th>
              </tr>
            </thead>
            <tbody>
              <?php
                  $conn = connectDB_BigHead();
                  $SQLSELECT = "SELECT
C.CONTNO
,C.ContractReferenceNo
,C.SaleCode
,REPLACE(C.PreSaleEmployeeCode,' ','') AS PreSaleEmployeeCode
,'Salecode ไม่พบในโครงสร้าง' AS AlertMsg
,CONVERT(varchar(20),C.EFFDATE,105) +' '+ CONVERT(varchar(5),C.EFFDATE,108) AS EFFDATE
FROM Bighead_Mobile.dbo.Contract AS C
WHERE PreSaleEmployeeCode IS NOT NULL AND PreSaleSaleCode IS NULL AND C.isActive = 1 AND PreSaleEmployeeCode NOT IN (SELECT DISTINCT SaleCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE SaleCode IS NOT NULL AND ProcessType IN ('CRD','TELE')) AND C.EFFDATE BETWEEN CAST('2017-12-11 00:00' AS datetime) AND GETDATE()
UNION ALL
SELECT
C.CONTNO
,C.ContractReferenceNo
,C.SaleCode
,REPLACE(C.PreSaleEmployeeCode,' ','') AS PreSaleEmployeeCode
,'Salecode เดียวกันกับพนักงานขาย' AS AlertMsg
,CONVERT(varchar(20),C.EFFDATE,105) +' '+ CONVERT(varchar(5),C.EFFDATE,108) AS EFFDATE
FROM Bighead_Mobile.dbo.Contract AS C
WHERE PreSaleEmployeeCode = SaleCode AND C.EFFDATE BETWEEN CAST('2017-12-11 00:00' AS datetime) AND GETDATE() AND C.isActive = 1
ORDER BY EFFDATE DESC";

                  //echo $SQLSELECT;

                  $stmt = sqlsrv_query($conn,$SQLSELECT);
                  $i=0;
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                    $i++;
               ?>

                <tr>
                <td style="text-align: center"><?=$i?></td>
                <td style="text-align: center"><?=$row['ContractReferenceNo']?></td>
                <td style="text-align: center"><?=$row['CONTNO']?></td>
                <td style="text-align: left"><?=$row['SaleCode']?></td>
                <td style="text-align: left"><?=$row['PreSaleEmployeeCode']?></td>
                <td style="text-align: left"><?=$row['AlertMsg']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['EFFDATE'])?> น.</td>
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
