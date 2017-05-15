<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);


if (empty($_REQUEST['startDate']) && empty($_REQUEST['endDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,R.DatePayment,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['startDate']))." - ".DateThai(DateEng($_REQUEST['endDate']));
  $WHERE = "AND C.EFFDATE BETWEEN '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59'";
  $top = "";
}


if (($_COOKIE['tsr_emp_permit'] == 4 )) {
  //$_COOKIE['tsr_emp_id'] = '003904';
  if (substr($_COOKIE['tsr_emp_id'],0,1) == "0") {
    $EmpID['0'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    $EmpID['1'] = $_COOKIE['tsr_emp_name'];

    $WHERE1 = "AND SaleTeamCode IN (SELECT DISTINCT TeamCode  FROM [TSRData_Source].[dbo].[vw_EmployeeDataParent]  WHERE EmployeeCodeLV2 = '".$EmpID['0']."' OR EmployeeCodeLV3 = '".$EmpID['0']."' OR EmployeeCodeLV4 = '".$EmpID['0']."' OR EmployeeCodeLV5 = '".$EmpID['0']."' OR EmployeeCodeLV6 = '".$EmpID['0']."' OR ParentEmployeeCode = '".$EmpID['0']."')";
    //$WHERE1 = "AND SaleTeamCode IN (SELECT DISTINCT TeamCode  FROM [TSRData_Source].[dbo].[vw_EmployeeDataParent]  WHERE EmployeeCodeLV2 = 'A03904' OR EmployeeCodeLV3 = 'A03904' OR EmployeeCodeLV4 = 'A03904' OR EmployeeCodeLV5 = 'A03904')";
  }
}else {
    $WHERE1 = "";
}
if (!empty($_REQUEST['EmpID'])) {
  $WHERE1 = "AND C.SaleEmployeeCode = '".$_REQUEST['EmpID']."'";
}
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportsp1">
        <div class="col-md-3">
          <h4>
            รายงานติดตั้ง
          </h4>
        </div>
        <div class="col-md-4">

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
              <P><center><B>รายงานติดตั้งประจำวัน</B></center></P>
            </div>

            <?php
            $httpExcelHead = "<P><center><B>รายงานสรุปการเก็บเงิน</B></center></P>
          <P><center><B> พนักงานเก็บเงิน : ".$EmpID['0']." , ".$EmpID['2']." ประจำวันที่ : ".$searchDate." พิมพ์โดย : ".$_COOKIE['tsr_emp_name']."</B></center></P>";

             ?>

          <div class="box-body table-responsive no-padding">
          <!--<div class="box-body">-->

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">no</th>
                <th style="text-align: center">RefNo</th>
                <th style="text-align: center">CONTNO</th>
                <th style="text-align: center">EFFDATE</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
                <th style=\"text-align: center\">no</th>
                <th style=\"text-align: center\">RefNO</th>
                <th style=\"text-align: center\">CONTNO</th>
                <th style=\"text-align: center\">type</th>
                <th style=\"text-align: center\">cashcode</th>
                <th style=\"text-align: center\">EFFDATE</th>
                <th style=\"text-align: center\">StDate</th>
                <th style=\"text-align: center\">MODE</th>
                <th style=\"text-align: center\">SerialNo</th>
                <th style=\"text-align: center\">Model</th>
                <th style=\"text-align: center\">Premium</th>
                <th style=\"text-align: center\">SALES</th>
                <th style=\"text-align: center\">SaleCode</th>
                <th style=\"text-align: center\">STATUS</th>
                <th style=\"text-align: center\">MeetDate</th>
                <th style=\"text-align: center\">Checker</th>
                <th style=\"text-align: center\">Credit</th>
                <th style=\"text-align: center\">OpenDate</th>
                <th style=\"text-align: center\">CloseDate</th>
                <th style=\"text-align: center\">Errs</th>
                <th style=\"text-align: center\">ContNo2</th>
                <th style=\"text-align: center\">excstatus</th>
                <th style=\"text-align: center\">excdate</th>
                <th style=\"text-align: center\">netcredit</th>
                <th style=\"text-align: center\">nextcredit</th>
                <th style=\"text-align: center\">discfirst</th>
                <th style=\"text-align: center\">premium2</th>
                <th style=\"text-align: center\">tocode</th>
                <th style=\"text-align: center\">torefno</th>
                <th style=\"text-align: center\">tocontno</th>
                <th style=\"text-align: center\">todate</th>
                <th style=\"text-align: center\">fromrefno</th>
                <th style=\"text-align: center\">fromcontno</th>
                <th style=\"text-align: center\">channel</th>
              </tr>
            </thead>
            <tbody>";
                $httpExcel2 = "";

              $sql_select = "SELECT C.contractReferenceNo AS RefNo
,CASE WHEN C.CONTNO = C.contractReferenceNo THEN 'X'+RIGHT(C.CONTNO,7) ELSE C.CONTNO END AS CONTNO
,LEFT(CASE WHEN C.CONTNO = C.contractReferenceNo THEN 'X'+RIGHT(C.CONTNO,7) ELSE C.CONTNO END,1)[TYPE]
,'7' AS CashCode
,CONVERT(VARCHAR,C.EFFDATE,103) AS EFFDATE
,CONVERT(VARCHAR,C.SyncedDate,103) AS StDate
,C.MODE
,C.productSerialNumber AS SerialNo
,C.Model
,(SELECT NetAmount FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE Refno = c.refno AND PaymentPeriodNumber = 1) AS Premium
,(SELECT top 1 TotalPrice FROM [Bighead_Mobile].[dbo].[Package] WHERE  ProductID in (SELECT top 1 ProductID  FROM [Bighead_Mobile].[dbo].[Package] WHERE model = c.MODEL)  AND PackageTitle LIKE '%สด%') AS Sales
,C.SaleCode AS SaleCode
,LEFT(C.Status,1) AS STATUS
,(SELECT CONVERT(VARCHAR,PaymentDueDate,103) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE RefNo = C.RefNo AND PaymentPeriodNumber = 1) AS MeetDate
,'0' AS Checker
,C.SALES AS Credit
,(SELECT CONVERT(VARCHAR,StartDate,103) FROM Bighead_Mobile.dbo.Fortnight WHERE C.FortnightID = FortnightID) AS OpenDate
,(SELECT CONVERT(VARCHAR,EndDate,103) FROM Bighead_Mobile.dbo.Fortnight WHERE C.FortnightID = FortnightID) AS CloseDate
,'' AS Errs
,'' AS ContNo2
,CASE WHEN TradeInDiscount > 0 THEN 'Y' ELSE 'N' END AS excstatus
,CONVERT(VARCHAR,EFFDATE,103) AS excdate
,C.TotalPrice AS netcredit
,(SELECT NetAmount FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE Refno = c.refno AND PaymentPeriodNumber = 1) AS nextcredit
,(SELECT Discount FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE Refno = c.refno AND PaymentPeriodNumber = 1) AS discfirst
,(SELECT PaymentAmount FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE Refno = c.refno AND PaymentPeriodNumber = 1) AS premium2
,tocode,torefno,tocontno,tonote , CONVERT(VARCHAR,todate,103) as todate , fromrefno,fromcontno , '101' AS channel

FROM Bighead_Mobile.dbo.Contract as C
WHERE C.EFFDATE BETWEEN '2017-04-17 00:00' AND '2017-04-17 23:59'
AND C.STATUS IN ('NORMAL','F')
ORDER BY C.EFFDATE";



              $sql_case = $sql_select;

              $sql_print = $sql_select;

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

                $i++;
                $httpExcel2 .= "<tr>
                    <td style=\"text-align: center\">".$i."</td>
                    <td style=\"text-align: center\">".$row['RefNo']."</td>
                    <td style=\"text-align: center\">".$row['CONTNO']."</td>
                    <td style=\"text-align: center\">".$row['TYPE']."</td>
                    <td style=\"text-align: center\">".$row['CashCode']."</td>
                    <td style=\"text-align: center\">".$row['EFFDATE']."</td>
                    <td style=\"text-align: center\">".$row['StDate']."</td>
                    <td style=\"text-align: center\">".$row['MODE']."</td>
                    <td style=\"text-align: center\">".$row['SerialNo']."</td>
                    <td style=\"text-align: center\">".$row['Model']."</td>
                    <td style=\"text-align: center\">".$row['Premium']."</td>
                    <td style=\"text-align: center\">".$row['Sales']."</td>
                    <td style=\"text-align: center\">".$row['SaleCode']."</td>
                    <td style=\"text-align: center\">".$row['STATUS']."</td>
                    <td style=\"text-align: center\">".$row['MeetDate']."</td>
                    <td style=\"text-align: center\">".$row['Checker']."</td>
                    <td style=\"text-align: center\">".$row['Credit']."</td>
                    <td style=\"text-align: center\">".$row['OpenDate']."</td>
                    <td style=\"text-align: center\">".$row['CloseDate']."</td>
                    <td style=\"text-align: center\">".$row['Errs']."</td>
                    <td style=\"text-align: center\">".$row['ContNo2']."</td>
                    <td style=\"text-align: center\">".$row['excstatus']."</td>
                    <td style=\"text-align: center\">".$row['excdate']."</td>
                    <td style=\"text-align: center\">".$row['netcredit']."</td>
                    <td style=\"text-align: center\">".$row['nextcredit']."</td>
                    <td style=\"text-align: center\">".$row['discfirst']."</td>
                    <td style=\"text-align: center\">".$row['premium2']."</td>
                    <td style=\"text-align: center\">".$row['tocode']."</td>
                    <td style=\"text-align: center\">".$row['torefno']."</td>
                    <td style=\"text-align: center\">".$row['tocontno']."</td>
                    <td style=\"text-align: center\">".$row['todate']."</td>
                    <td style=\"text-align: center\">".$row['fromrefno']."</td>
                    <td style=\"text-align: center\">".$row['fromcontno']."</td>
                    <td style=\"text-align: center\">".$row['channel']."</td>

                </tr>";
              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td style="text-align: center"><?=$row['RefNo']?></td>
                <td style="text-align: center"><?=$row['CONTNO']?></td>
                <td style="text-align: center"><?=$row['EFFDATE']?></td>
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
            <td style="text-align: left" width="10%"><?=$num_row;?> รายการ</td>
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
