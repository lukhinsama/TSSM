<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportOperNotSave">
        <div class="col-md-3">
          <h4>
            ใบเสร็จที่ไม่ถูกเพิ่มในระบบ
          </h4>
        </div>
        <div class="col-md-4">

          <div class="form-group group-sm">
            <select class="form-control select2 group-sm" name="EmpID" >
              <optgroup label="พนักงานเก็บเงิน">
                  <option value="0"> ทั้งหมด </option>
                <?php
                $sql_case = "SELECT SaleCode as mcode,EmployeeName AS Name ,EmployeeCode AS EmpID,case when SaleCode is null then '-' else SaleCode end as SaleCode ,SupervisorCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE  salecode is not null AND SupervisorCode is not null  ORDER BY mcode";

                //echo $sql_case;
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
              <option value="<?=$row['EmpID']?>"><?=$row['EmpID']?> (<?=$row['SaleCode']?>) <?=$row['Name']?> </option>
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
            <!--<input type="text" class="form-control" name="searchDate" autocomplete="off" id="datepicker2"  placeholder="กรอกวันที่ .." required>-->

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
          <a href="http://app.thiensurat.co.th/lkh/rpt_lk1.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=1" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
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
          <?php
            if (!empty($_REQUEST['searchDate']) || ((!empty($_REQUEST['startDate']) && !empty($_REQUEST['endDate'])))) {

           ?>
          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><B>รายงานใบเสร็จที่ไม่ถูกเพิ่มในระบบ</B></center></P>
            </div>
            <?php
            $httpExcelHead = "<P><center><B>รายงานใบเสร็จที่ไม่ถูกเพิ่มในระบบ</B></center></P><P><center><B> พิมพ์โดย : ".$_COOKIE['tsr_emp_name']."</B></center></P>";

             ?>

          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">เลขที่ใบเสร็จ</th>
                <th style="text-align: center">เวลาออกใบเสร็จ</th>
                <th style="text-align: center">พนักงานเก็บเงิน</th>
                <th style="text-align: center">เลขที่อ้างอิง</th>
                <th style="text-align: center">จำนวนเงิน</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
                <th style=\"text-align: center\">ลำดับ</th>
                <th style=\"text-align: center\">เลขที่ใบเสร็จ</th>
                <th style=\"text-align: center\">เวลาออกใบเสร็จ</th>
                <th style=\"text-align: center\">พนักงานเก็บเงิน</th>
                <th style=\"text-align: center\">เลขที่อ้างอิง</th>
                <th style=\"text-align: center\">จำนวนเงิน</th>
              </tr>
            </thead>
            <tbody>";
                $httpExcel2 = "";

                if (!empty($_REQUEST['EmpID'])) {
                  $CreateBy = "AND CreateBy =  '".$_REQUEST['EmpID']."'";
                }else {
                  $CreateBy = "";
                }
              $sql_select = "SELECT ServiceInputData AS DATA FROM Bighead_Mobile.dbo.TransactionLogSkip WHERE CreateDate BETWEEN  '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59' $CreateBy AND ServiceOutputType = 'th.co.thiensurat.service.data.AddReceiptOutputInfo' ORDER BY  TransactionDate ASC";

              //ECHO $sql_select;

              $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              fwrite($file,$sql_case);
              fclose($file);

              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),1)";
      				//echo $sql_insert;

      				$params = array($_COOKIE['tsr_emp_id'],$sql_select);
      				//print_r($params);

      				$stmt_insert = sqlsrv_query( $conns, $sql_insert, $params);

      				if( $stmt_insert === false ) {
      					 die( print_r( sqlsrv_errors(), true));
      				}

              // เพิ่มลงฐานข้อมูล
              $num_row = checkNumRow($conn,$sql_select);
              $SumTotal = 0 ;

              $i=0;
              $stmt = sqlsrv_query($conn,$sql_select);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                //$Data = array($row['DATA']);
                $Data = json_decode($row['DATA'], true); // decode json
                $SumTotal = $SumTotal + $Data['TotalPayment'];
                //$Data = array('0','1','2','3','4','5','6','7','8','9','10','11');
                //print_r($Data);
                $i++;

                $httpExcel2 .= "<tr>
                  <td style=\"text-align: center\">".$i."</td>
                  <td>".$Data['ReceiptCode']."</td>
                  <td>".$Data['DatePayment']."</td>
                  <td>".$Data['CreateBy']."</td>
                  <td>".$Data['RefNo']."</td>
                  <td style=\"text-align: right\">".number_format($Data['TotalPayment'],2)."</td>
                </tr>";

              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td><?=$Data['ReceiptCode']?></td>
                <td style="text-align: center"><?=$Data['DatePayment']?></td>
                <td style="text-align: center"><?=$Data['CreateBy']?></td>
                <td style="text-align: center"><?=$Data['RefNo']?></td>
                <td style="text-align: right"><?=number_format($Data['TotalPayment'],2)?></td>
              </tr>

              <?php
                }
                $httpExcel3 = "</tbody>
                <tfoot>
                </tfoot>
               </table>";
                $html_file = $html_file = $httpExcelHead."".$httpExcel1."".$httpExcel2."".$httpExcel3;
                write_data_for_export_excel($html_file, 'ReportCreditPar3');
               ?>
             </tbody>
             <tfoot>
             </tfoot>
            </table>

          </div>
          <div class="box-footer clearfix">
            <!--
            <ul class="pagination pagination-sm no-margin pull-right">
              <li><a href="#">&laquo;</a></li>
              <li><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">&raquo;</a></li>
            </ul>
          -->
          <table width="100%">
          <tr>
            <td style="text-align: right" width="10%"><B>รวม</B> </td>
            <td style="text-align: right" width="5%"> </td>
            <td style="text-align: left" width="10%"><?=$num_row;?> ใบ</td>
            <td style="text-align: right"><B> รวมเงิน </B></td>
            <td style="text-align: right" width="15%"><?=number_format($SumTotal,2)?></td>
          </tr>
          <!--
          <tr>
            <td style="text-align: right" width="10%"> </td>
            <td style="text-align: right" width="5%"> </td>
            <td style="text-align: left" width="10%"></td>
            <td style="text-align: right"><B> ยอดส่งเงิน </B></td>
            <td style="text-align: right" width="15%"><?=number_format($Sendmoney,2)?></td>
          </tr>
        -->
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
