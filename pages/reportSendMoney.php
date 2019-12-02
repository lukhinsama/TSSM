<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

  $conn = connectDB_BigHead();


  if (!empty($_REQUEST['EmpID'])) {
    $WHERE = "AND EmpID = '".$_REQUEST['EmpID']."'";
  }else {
    $WHERE = "";
  }
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportSendMoney">
        <div class="col-md-3">
          <h4>
            รายงานส่งเงิน
          </h4>
        </div>
        <div class="col-md-4">
          <div class="form-group group-sm">
            <select class="form-control select2 group-sm" name="EmpID" >
              <optgroup label="พนักงาน">
                <option value="0"> ทั้งหมด </option>
                  <?php
                $sql_case = "SELECT DISTINCT EmployeeCode,EmployeeName
FROM Bighead_Mobile.dbo.EmployeeDetail
WHERE Salecode Is Not NULL
ORDER BY EmployeeCode";

                //echo $sql_case;
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
              <option value="<?=$row['EmployeeCode']?>"><?=$row['EmployeeName']?> (<?=$row['EmployeeCode']?>)</option>
                <?php
                  }
                ?>
              </optgroup>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="input-group input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>

            <div class="input-group input-group input-daterange" id="datepicker2">
                    <input type="text" class="form-control" name="startDate" autocomplete="off" value="<?php if(isset($_REQUEST['startDate'])) {echo $_REQUEST['startDate'];}?>" placeholder="วันเริ่มต้น .." required>
                    <span class="input-group-addon">ถึง</span>
                    <input type="text" class="form-control" name="endDate" autocomplete="off" value="<?php if(isset($_REQUEST['endDate'])) {echo $_REQUEST['endDate'];}?>" placeholder="วันสิ้นสุด .." required>
                </div>

            <div class="input-group-btn">
              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>

        <div class="col-md-1">
          <!--
          <a href="http://app.thiensurat.co.th/lkh/rpt_lk1.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=1" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
        -->
        </div>
        </form>
      </div>

    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-xs-12">
          <?php
            if (!empty($_REQUEST['searchDate']) || ((!empty($_REQUEST['startDate']) && !empty($_REQUEST['endDate'])))) {

           ?>
          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><B>รายงาน เก็บเงิน / ส่งเงิน</B></center></P>
            </div>
            <?php
            $httpExcelHead = "<P><center><B>รายงาน เก็บเงิน / ส่งเงิน</B></center></P>";
             ?>
          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">รหัสพนักงาน</th>
                <th style="text-align: center">รหัสทีม</th>
                <th style="text-align: center">ชื่อพนักงาน</th>
                <th style="text-align: center">วันที่เก็บเงิน</th>
                <th style="text-align: center">เงินที่ออกใบเสร็จ</th>
                <th style="text-align: center">จำนวนเงินที่ส่ง</th>
                <th style="text-align: center">สังกัด</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $httpExcel1 = "";
                $httpExcel2 = "";

              $SQL = "SELECT A.*,ISNULL(B.SendAmount,0) as SendAmount
,ED.EmployeeName,ED.PositionName
FROM (
SELECT P.EmpID,P.CashCode,P.TeamCode,CONVERT(varchar(10),P.Paydate,121) AS PayDate,SUM(PAYAMT) AS PayAmount
FROM Bighead_Mobile.dbo.Payment AS P
WHERE DateDiff(day,p.PayDate,getdate()) <= DateDiff(day,CAST('".DateEng($_REQUEST['startDate'])."' AS datetime),getdate())
AND DateDiff(day,p.PayDate,getdate()) >= DateDiff(day,CAST('".DateEng($_REQUEST['endDate'])."' AS datetime),getdate())
GROUP BY P.EmpID,P.CashCode,P.TeamCode,CONVERT(varchar(10),P.Paydate,121)
) AS A
LEFT JOIN (
SELECT S.CreateBy,CONVERT(varchar(10),S.PaymentDate,121) AS PaymentDate,SUM(SendAmount) AS SendAmount
FROM Bighead_Mobile.dbo.SendMoney AS S
WHERE DateDiff(day,S.PaymentDate,getdate()) <= DateDiff(day,CAST('".DateEng($_REQUEST['startDate'])."' AS datetime),getdate())
AND DateDiff(day,S.PaymentDate,getdate()) >= DateDiff(day,CAST('".DateEng($_REQUEST['endDate'])."' AS datetime),getdate())
GROUP BY S.CreateBy,CONVERT(varchar(10),S.PaymentDate,121)
) AS B ON A.EmpID = B.CreateBy AND A.PayDate = B.PaymentDate
LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS ED ON ED.EmployeeCode = A.EmpID AND ED.SaleCode = A.CashCode
WHERE A.PayAmount != ISNULL(B.SendAmount,0) $WHERE";
  //ECHO $SQL;

              $sql_case = $SQL;

              $sql_print = $SQL;

              //echo $sql_case;
              //$file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              //fwrite($file,$SQL);
              //fclose($file);

              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              //$sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),1)";
      				//echo $sql_insert;

      				//$params = array($_COOKIE['tsr_emp_id'],$sql_print);
      				//print_r($params);

      				//$stmt_insert = sqlsrv_query( $conns, $sql_print, $params);
              /*
      				if( $stmt_insert === false ) {
      					 die( print_r( sqlsrv_errors(), true));
      				}
              */
              sqlsrv_close($conns);
              // เพิ่มลงฐานข้อมูล
              $num_row = checkNumRow($conn,$sql_case);
              $SumTotal = 0 ;

              $i=0;
              $stmt = sqlsrv_query($conn,$sql_case);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $SumTotal = $SumTotal + ($row['PayAmount']-$row['SendAmount']);
                $i++;
                $httpExcel2 .= "";
              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td><?=$row['EmpID']?></td>
                <td><?=$row['CashCode']?></td>
                <td><?=$row['EmployeeName']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['PayDate'])?> น.</td>
                <td style="text-align: right"><?=number_format($row['PayAmount'],2)?></td>
                <td style="text-align: right"><?=number_format($row['SendAmount'],2)?></td>
                <td><?=$row['PositionName']?></td>
              </tr>

              <?php
                }
                $httpExcel3 = "</tbody>
                <tfoot>
                </tfoot>
               </table>";
                $html_file = $html_file = $httpExcel1."".$httpExcel2."".$httpExcel3;
                write_data_for_export_excel($html_file, 'ReportCreditCom2');
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
          <a href="export_csv.php?report_type=8"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
          </div>
        </div>
        <?php
          }
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
        "pageLength": 10,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });
  </script>
