<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);


 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportcredit2">
        <div class="col-md-8">
          <h4>
            รายงานโอนการ์ด 500 ครั้งหลังสุด ณ. วันที่ <?=DateTimeThai(DATE('d-m-Y H:i'))?> น.
          </h4>
        </div>
        <div class="col-md-4">
        </div>
        </form>
      </div>

      <!--
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> รายงาน</a></li>
        <li><i class="fa fa-user"></i> รายงาน(ฝ่ายเครดิต)</li>
        <li class="active"> สรุปการเก็บเงินรายวัน </li>
      </ol>
    -->

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
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">ชื่อลูกค้า</th>
                <th style="text-align: center">เขตที่รับโอน</th>
                <th style="text-align: center">พนักงานเขตรับโอน</th>
                <th style="text-align: center">ผู้ที่โอน</th>
                <th style="text-align: center">วันเวลาที่โอน</th>
              </tr>
            </thead>

            <tbody>
              <?php
                  $conn = connectDB_BigHead();
                  $SQLSELECT = "SELECT TOP 500 A.*,EM.FirstName+' '+EM.LastName AS EmpNAME
                  FROM (
                  SELECT LTB.Createby,
                  CASE WHEN SUBSTRING(LTB.Createby,1,1) = '0' THEN 'A'+ SUBSTRING(LTB.Createby,2,6)
                  ELSE LTB.Createby END AS EmpID,
                  LTB.Contno,CUS.PrefixName+' '+CUS.CustomerName AS CustomerName,LTB.salecode,EMD.EmployeeName
                  ,LTB.createDate,CONVERT(varchar(20),LTB.createDate,105) +' '+ CONVERT(varchar(5),LTB.createDate,108) AS createDates
                  FROM TSRData_Source.dbo.Log_Tranfer_BigHead AS LTB
                  INNER JOIN Bighead_Mobile.dbo.EmployeeDetail AS EMD
                  ON LTB.salecode = EMD.salecode
                  INNER JOIN Bighead_Mobile.dbo.Contract AS CON
                  ON CON.CONTNO = LTB.Contno
                  INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS CUS
                  ON CON.CustomerID = CUS.CustomerID
                  WHERE LTB.CreateBy IS NOT NULL AND LTB.CreateBy <> ''
                  ) AS A
                  LEFT JOIN Bighead_Mobile.dbo.Employee AS EM
                  ON A.EmpID = EM.EmpID
                  ORDER BY A.CreateDate DESC";

                  //echo $SQLSELECT;

                  $stmt = sqlsrv_query($conn,$SQLSELECT);
                  $i=0;
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                    $i++;
               ?>

                <tr>
                <td style="text-align: center"><?=$i?></td>
                <td style="text-align: center"><?=$row['Contno']?></td>
                <td><?=$row['CustomerName']?></td>
                <td style="text-align: center"><?=$row['salecode']?></td>
                <td><?=$row['EmployeeName']?></td>
                <td>(<?=$row['EmpID']?>) <?=$row['EmpNAME']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['createDates'])?> น.</td>
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
