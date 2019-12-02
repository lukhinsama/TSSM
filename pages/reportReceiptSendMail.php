<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);

if (empty($_REQUEST['searchDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,R.DatePayment,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['searchDate']));
  $WHERE = " R.DatePayment BETWEEN '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59'";
  $top = "";
}

if (empty($_REQUEST['startDate']) && empty($_REQUEST['endDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,R.DatePayment,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['startDate']))." - ".DateThai(DateEng($_REQUEST['endDate']));
  $WHERE = " R.DatePayment BETWEEN '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59'";
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

    $WHERE .= " AND R.ZoneCode = '".$EmpID['2']."'";


  }
}else {
  if (empty($_REQUEST['EmpID'])) {
    $EmpID = array('0','-');
  }else {
    $EmpID = explode("_",$_REQUEST['EmpID']);
    $WHERE .= " AND R.CashCode = '".$EmpID['2']."'";
  }
}
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportReceiptSendMail">
        <div class="col-md-3">
          <h4>
            รายงานใบเสร็จส่งจดหมาย
          </h4>
        </div>
        <div class="col-md-4">
          <?php
          if (($_COOKIE['tsr_emp_permit'] != 4)) {
           ?>
          <div class="form-group group-sm">
            <select class="form-control select2 group-sm" name="EmpID" >
              <optgroup label="พนักงานเก็บเงิน">
                <?PHP
                if ($_COOKIE['tsr_emp_id'] == 'ZCR001') {
                  $supcode = "AND SupervisorCode = '101'";
                }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR002') {
                  $supcode = "AND SupervisorCode = '102'";
                }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR003') {
                  $supcode = "AND SupervisorCode = '103'";
                }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR004') {
                  $supcode = "AND SupervisorCode = '104'";
                }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR005') {
                  $supcode = "AND SupervisorCode = '105'";
                }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR006') {
                  $supcode = "AND SupervisorCode = '106'";
                }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR007') {
                $supcode = "AND SupervisorCode = '107'";
                }else {
                  $supcode = "";
                  ?>
                  <option value="0"> ทั้งหมด </option>
                  <?php
                }
                $sql_case = "SELECT SaleCode as mcode,EmployeeName AS Name ,EmployeeCode AS EmpID,case when SaleCode is null then '-' else SaleCode end as SaleCode ,SupervisorCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE  salecode is not null AND SupervisorCode is not null $supcode ORDER BY mcode";

                //echo $sql_case;
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
              <option value="<?=$row['EmpID']?>_<?=$row['Name']?>_<?=$row['SaleCode']?>_<?=$row['mcode']?>"><?=$row['EmpID']?> (<?=$row['SaleCode']?>) <?=$row['Name']?> </option>
                <?php
                  }
                ?>
              </optgroup>
            </select>
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
              <P><center><B>รายงานสรุปการเก็บเงิน</B></center></P>
              <table width="100%">
                <tr>
                  <td>พนักงานเก็บเงิน : <?=$EmpID['0']?> , <?=$EmpID['2']?> , <?=$EmpID['3']?></td>
                  <td><?=$EmpID['1']?></td>
                  <td>ประจำวันที่ : <?=$searchDate?></td>
                  <td>พิมพ์โดย : <?=$_COOKIE['tsr_emp_name']?></td>
                </tr>
              </table>
            </div>
            <?php
            $httpExcelHead = "<P><center><B>รายงานสรุปการเก็บเงิน</B></center></P>
          <P><center><B> พนักงานเก็บเงิน : ".$EmpID['0']." , ".$EmpID['2']." ประจำวันที่ : ".$searchDate." พิมพ์โดย : ".$_COOKIE['tsr_emp_name']."</B></center></P>";

             ?>

          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">เลขที่ใบเสร็จ</th>
                <th style="text-align: center">เวลาออกใบเสร็จ</th>
                <th style="text-align: center">งวดที่</th>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">ชื่อ - สกุล</th>
                <th style="text-align: center">จำนวนเงิน</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
                <th style=\"text-align: center\">ลำดับ</th>
                <th style=\"text-align: center\">วันที่รับเงิน</th>
                <th style=\"text-align: center\">เลขที่ใบเสร็จ</th>
                <th style=\"text-align: center\">เลขสัญญา</th>
                <th style=\"text-align: center\">ชื่อ-สกุล (ลูกค้า)</th>
                <th style=\"text-align: center\">ผลิตภัฒฑ์</th>
                <th style=\"text-align: center\">งวดที่</th>
                <th style=\"text-align: center\">จำนวนเงิน</th>
                <th style=\"text-align: center\">งวดคงเหลือ</th>
                <th style=\"text-align: center\">จำนวนเงินคงเหลือ</th>
                <th style=\"text-align: center\">ผู้รับเงิน</th>
                <th style=\"text-align: center\">เขตเก็บเงิน</th>
                <th style=\"text-align: center\">ที่อยู่ตามบัตร</th>
                <th style=\"text-align: center\">ตำบล/แขวง ตามบัตร</th>
                <th style=\"text-align: center\">อำเภอ/เขต ตามบัตร</th>
                <th style=\"text-align: center\">จังหวัดตามบัตร</th>
                <th style=\"text-align: center\">รหัสไปรษณีย์ตามบัตร</th>
                <th style=\"text-align: center\">ที่อยู่ติดตั้ง</th>
                <th style=\"text-align: center\">ตำบล/แขวง ติดตั้ง</th>
                <th style=\"text-align: center\">อำเภอ/เขต ติดตั้ง</th>
                <th style=\"text-align: center\">จังหวัดติดตั้ง</th>
                <th style=\"text-align: center\">รหัสไปรษณีย์ติดตั้ง</th>
                <th style=\"text-align: center\">สาขา</th>
                <th style=\"text-align: center\">เลขผู้เสียภาษี</th>
                <th style=\"text-align: center\">ที่อยู่สาขา</th>
              </tr>
            </thead>
            <tbody>";
                $httpExcel2 = "";

              $SQL = "SELECT [RefNo]
      ,[ReceiptCode]
      ,[DatePayment]
      ,[TotalPayment]
      ,[CreateBy]
      ,[CONTNO]
      ,[ContractReferenceNo]
      ,[Amount]
      ,[PAYAMT]
      ,[CashCode]
      ,[EmpID]
      ,[TeamCode]
      ,[PaymentPeriodNumber]
      ,[PaymentAmount]
      ,[NetAmount]
      ,[PaymentComplete]
      ,[Discount]
      ,[CloseAccountDiscountAmount]
      ,[CustomerName]
      ,[ProductName]
      ,[BalancePeriodNumber]
      ,[BalanceAmount]
      ,[EmpName]
      ,[HouseIDCard]
      ,[SubDistrictIDCard]
      ,[DistrictIDCard]
      ,[ProvinceIDCard]
      ,[ZipcodeIDCard]
      ,[HouseInstall]
      ,[SubDistrictInstall]
      ,[DistrictInstall]
      ,[ProvinceInstall]
      ,[ZipcodeInstall]
      ,[Branch]
      ,[BranchAddress]
      ,[TexNo]
      ,CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) as DatePayments
  FROM TSRData_Source.dbo.TSSM_ReceiptDataSendMail AS R
  WHERE $WHERE ";
  //ECHO $SQL;

              $sql_case = $SQL;

              $sql_print = $SQL;

              //echo $sql_case;
              $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              fwrite($file,$SQL);
              fclose($file);

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
                $SumTotal = $SumTotal + $row['PAYAMT'];
                $i++;
                $httpExcel2 .= "<tr>
                  <td style=\"text-align: center\">".$i."</td>
                  <td>".$row['DatePayments']."</td>
                  <td>'".$row['ReceiptCode']."</td>
                  <td style=\"text-align: center\">".$row['CONTNO']."</td>
                  <td>".$row['CustomerName']."</td>
                  <td>".$row['ProductName']."</td>
                  <td style=\"text-align: center\">".$row['PaymentPeriodNumber']."</td>
                  <td style=\"text-align: right\">".number_format($row['TotalPayment'],2)."</td>
                  <td style=\"text-align: center\">'".$row['BalancePeriodNumber']."</td>
                  <td style=\"text-align: right\">".number_format($row['BalanceAmount'],2)."</td>
                  <td>".$row['EmpName']."</td>
                  <td>".$row['CashCode']."</td>
                  <td>".$row['HouseIDCard']."</td>
                  <td>".$row['SubDistrictIDCard']."</td>
                  <td>".$row['DistrictIDCard']."</td>
                  <td>".$row['ProvinceIDCard']."</td>
                  <td>".$row['ZipcodeIDCard']."</td>
                  <td>".$row['HouseInstall']."</td>
                  <td>".$row['SubDistrictInstall']."</td>
                  <td>".$row['DistrictInstall']."</td>
                  <td>".$row['ProvinceInstall']."</td>
                  <td>".$row['ZipcodeInstall']."</td>
                  <td>".$row['Branch']."</td>
                  <td>'".$row['TexNo']."</td>
                  <td>".$row['BranchAddress']."</td>
                </tr>";
              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td><?=$row['ReceiptCode']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['DatePayments'])?> น.</td>
                <td style="text-align: center"><?=$row['PaymentPeriodNumber']?></td>
                <td style="text-align: center"><?=$row['CONTNO']?></td>
                <td><?=$row['CustomerName']?></td>
                <td style="text-align: right"><?=number_format($row['PAYAMT'],2)?></td>
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
