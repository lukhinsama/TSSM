<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
$limit_per_page = 100;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;

if (empty($_REQUEST['searchDate'])) {
  //$searchDate = DateThai(date('Y-m-d'));
  //$WHERE = " datediff(DAY,R.DatePayment,GETDATE())=0 ";
  //$top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['searchDate']));
  $WHERE = "AND R.DatePayment BETWEEN '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59'";
  $top = "";
}

if (empty($_REQUEST['startDate']) && empty($_REQUEST['endDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  //$WHERE = " datediff(DAY,R.DatePayment,GETDATE())=0 ";
  //$top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['startDate']))." - ".DateThai(DateEng($_REQUEST['endDate']));
  $WHERE = "AND R.DatePayment BETWEEN '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59'";
  $top = "";
}

if (($_COOKIE['tsr_emp_permit'] == 4 )) {
  if (substr($_COOKIE['tsr_emp_id'],0,1) == "0") {
    $EmpID['0'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    $EmpID['1'] = $_COOKIE['tsr_emp_name'];

    $connss = connectDB_BigHead();
    $sql_Empid = "SELECT SaleCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE EmployeeCode = '".$EmpID['0']."' AND salecode is not null";

    //echo $sql_Empid;

    $stmt = sqlsrv_query($connss,$sql_Empid);
    while ($rowss = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      $EmpID['2'] = $rowss['SaleCode'];
    }

    sqlsrv_close($connss);

    $WHERE .= " AND R.createby = '".$EmpID['2']."'";


  }
}else {
  if (!empty($_REQUEST['searchText'])) {
    $WHERE .= " AND ( R.ReceiptCode LIKE '%".$_REQUEST['searchText']."%' OR C.CONTNO LIKE '%".$_REQUEST['searchText']."%')";
  }
}
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportReprint">
        <div class="col-md-3">
          <h4>
            รายงานปริ๊นใบเสร็จ
          </h4>
        </div>
        <div class="col-md-4">
          <?php
          if (($_COOKIE['tsr_emp_permit'] != 4)) {
           ?>
          <div class="form-group group-sm">
            <input type="text" class="form-control" name="searchText" id="searchText" required placeholder="เลขที่สัญญา / เลขที่ใบเสร็จ" >
          </div>
          <?php
        }
           ?>
        </div>
        <div class="col-md-4">
          <div class="input-group input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <!--<input type="text" class="form-control" name="searchDate" autocomplete="off" id="datepicker2"  placeholder="กรอกวันที่ .." required>-->

            <div class="input-group input-group input-daterange" id="datepicker2">
                    <input type="text" class="form-control" name="startDate" autocomplete="off" value="<?php if(isset($_REQUEST['startDate'])) {echo $_REQUEST['startDate'];}?>" placeholder="วันเริ่มต้น .." >
                    <span class="input-group-addon">ถึง</span>
                    <input type="text" class="form-control" name="endDate" autocomplete="off" value="<?php if(isset($_REQUEST['endDate'])) {echo $_REQUEST['endDate'];}?>" placeholder="วันสิ้นสุด .." >
                </div>

            <div class="input-group-btn">
              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>

        <div class="col-md-1">
          <!--<a href="http://app.thiensurat.co.th/lkh/rpt_lk1.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=1" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>-->
        </div>
        </form>
      </div>

    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-xs-12">
          <?php
          //  if (!empty($_REQUEST['searchText']) || ((!empty($_REQUEST['startDate']) && !empty($_REQUEST['endDate'])))) {
            if (!empty($_REQUEST['searchText'])) {

           ?>
          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><B>รายงานปริ๊นใบเสร็จ</B></center></P>

            </div>


          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">เลขที่ใบเสร็จ</th>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">จำนวนเงิน</th>
                <th style="text-align: center">วันที่ออกใบเสร็จ</th>
                <th style="text-align: center">จำนวนครั้งที่ออกใบเสร็จ</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
                <th style=\"text-align: center\">ลำดับ</th>
                <th style=\"text-align: center\">เลขที่ใบเสร็จ</th>
                <th style=\"text-align: center\">เลขที่สัญญา</th>
                <th style=\"text-align: center\">จำนวนเงิน</th>
                <th style=\"text-align: center\">วันที่ออกใบเสร็จ</th>
                <th style=\"text-align: center\">จำนวนครั้งที่ออกใบเสร็จ</th>
              </tr>
            </thead>
            <tbody>";
                $httpExcel2 = "";

              $sql_case = "SELECT
R.ReceiptCode, R.RefNo,C.CONTNO, R.DatePayment, R.TotalPayment,CONVERT(varchar(20),DH.DatePrint,105) +' '+ CONVERT(varchar(5),DH.DatePrint,108) AS DatePrint  ,   DH.PrintOrder
FROM Bighead_Mobile.dbo.DocumentHistory AS DH
INNER JOIN Bighead_Mobile.dbo.Receipt AS R ON DH.DocumentNumber = R.ReceiptID
INNER JOIN Bighead_Mobile.dbo.Contract AS C ON C.refno = R.RefNo

WHERE        (DH.DocumentType = 1) $WHERE
order by DH.DatePrint ASC";

              $sql_print = $sql_case;

              //echo $sql_case;
              $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              fwrite($file,$sql_case);
              fclose($file);

              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),1)";
      				//echo $sql_insert;

      				$params = array($_COOKIE['tsr_emp_id'],$sql_print);
      				//print_r($params);

      				$stmt_insert = sqlsrv_query( $conns, $sql_insert, $params);

      				if( $stmt_insert === false ) {
      					 die( print_r( sqlsrv_errors(), true));
      				}

              // เพิ่มลงฐานข้อมูล
              $num_row = checkNumRow($conn,$sql_case);
              $SumTotal = 0 ;

              $i=0;
              $stmt = sqlsrv_query($conn,$sql_case);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $SumTotal = $SumTotal + $row['PAYAMT'];
                $i++;
                $httpExcel2 .= "<tr>
                  <td style=\"text-align: center\">".$i."</td>
                  <td>".$row['ReceiptCode']."</td>
                  <td>".$row['CONTNO']."</td>
                  <td style=\"text-align: right\">".number_format($row['TotalPayment'],2)."</td>
                  <td style=\"text-align: center\">".DateTimeThai($row['DatePrint'])." น.</td>
                  <td>#".$row['PrintOrder']."</td>
                </tr>";
              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td><?=$row['ReceiptCode']?></td>
                <td style="text-align: center"><?=$row['CONTNO']?></td>
                <td style="text-align: right"><?=number_format($row['TotalPayment'],2)?></td>
                <td style="text-align: center"><?=DateTimeThai($row['DatePrint'])?> น.</td>
                <td style="text-align: center"><?=$row['PrintOrder']?></td>
              </tr>

              <?php
                }
                $httpExcel3 = "</tbody>
                <tfoot>
                </tfoot>
               </table>";
                $html_file = $html_file = $httpExcel1."".$httpExcel2."".$httpExcel3;
                write_data_for_export_excel($html_file, 'ReportCreditPar3');
               ?>
             </tbody>
             <tfoot>
             </tfoot>
            </table>

          </div>
          <div class="box-footer clearfix">

          <table width="100%">
          <tr>
            <td style="text-align: right" width="10%"><B>รวม</B> </td>
            <td style="text-align: right" width="5%"> </td>
            <td style="text-align: left" width="10%"><?=$num_row;?> ใบ</td>
            <td style="text-align: right"><B> รวมเงิน </B></td>
            <td style="text-align: right" width="15%"><?=number_format($SumTotal,2)?></td>
          </tr>

        </table>
          <a href="export_excel.php?report_type=3"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
          </div>
        </div>
        <?php
          }
          sqlsrv_close($conn);
          sqlsrv_close($conns);
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
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });
  </script>
