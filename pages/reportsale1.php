<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
$limit_per_page = 50;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;
/*
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

if (empty($_REQUEST['searchDate'])) {
   $searchDate = DateThai(date('Y-m-d'));
   $y = date('Y');
   $m = date('m');
   $d = date('d');
   $dateSearch = $d."/".$m."/".$y;
   //$WHERE = "AND datediff(DAY,P.DatePayment,GETDATE())=0 ";
   $WHERE = "AND datediff(DAY,Receipt.DatePayment,GETDATE())=0 ";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['searchDate']));
  $y =  substr($_REQUEST['searchDate'],6,4);
  $m =  substr($_REQUEST['searchDate'],3,2);
  $d =  substr($_REQUEST['searchDate'],0,2);
  $dateSearch = "(".$d."/".$m."/".$y." - ".$d."/".$m."/".$y.")";
  //$WHERE = "AND P.DatePayment BETWEEN '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59'";
  $WHERE = "cast(Receipt.DatePayment as date) BETWEEN cast('".DateEng($_REQUEST['startDate'])."' as date) AND cast('".DateEng($_REQUEST['startDate'])."' as date) ";
}

if (empty($_REQUEST['startDate']) && empty($_REQUEST['endDate'])) {
   $searchDate = DateThai(date('Y-m-d'));
   $y = date('Y');
   $m = date('m');
   $d = date('d');
   $dateSearch = $d."/".$m."/".$y;
   //$WHERE = "AND datediff(DAY,P.DatePayment,GETDATE())=0 ";
   $WHERE = "AND datediff(DAY,Receipt.DatePayment,GETDATE())=0 ";
}else {
  $searchDate =  DateThai(DateEng($_REQUEST['startDate']))." - ".DateThai(DateEng($_REQUEST['endDate']));
  $y =  substr($_REQUEST['searchDate'],6,4);
  $m =  substr($_REQUEST['searchDate'],3,2);
  $d =  substr($_REQUEST['searchDate'],0,2);
  $dateSearch = "(".$d."/".$m."/".$y." - ".$d."/".$m."/".$y.")";
  //$WHERE = "AND P.DatePayment BETWEEN '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59'";
  $WHERE = "cast(Receipt.DatePayment as date) BETWEEN cast('".DateEng($_REQUEST['startDate'])."' as date) AND cast('".DateEng($_REQUEST['endDate'])."' as date) ";
}

if (!empty($_REQUEST['TeamCode']) ) {

  $teamcode = explode("_",$_REQUEST['TeamCode']);
  //$WHERE .= " AND Emd.SupervisorCode = '".$_REQUEST['TeamCode']."'";
  //$WHERE .= " AND Emd.SupervisorCode = '".$teamcode[0]."'";
  $WHERE2 = " AND emp.SupervisorCode = '".$teamcode[0]."'";
}

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <div class="col-md-3">
          <h4>
            การเก็บเงินงวดแรกรายวัน
          </h4>
        </div>
        <div class="col-md-3">
          <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportsale1">

            <select class="form-control select2 group-sm" name="TeamCode" >
              <optgroup label="ทีม">
                <option value="0"> ทั้งหมด </option>
                <?php
                $conn = connectDB_BigHead();

                $sql_case = "SELECT [SupervisorCode],
                CASE [SupervisorCode]
                WHEN '101' THEN 'เครดิต ทีม A'
                WHEN '102' THEN 'เครดิต ทีม B'
                WHEN '104' THEN 'เครดิต ทีม C'
                WHEN '103' THEN 'เครดิต ทีม D'
                WHEN '105' THEN 'เครดิต ทีม F'
                WHEN '106' THEN 'เครดิต ทีม H'
                WHEN '107' THEN 'เครดิต ทีม I'
                WHEN '108' THEN 'เครดิต กรณีพิเศษ'
                WHEN '80101' THEN 'เร่งรัต ทีม 1'
                WHEN '80102' THEN 'เร่งรัต ทีม 2'
                ELSE 'ทีมอื่นๆ'
                END AS TeamCode
                FROM [Bighead_Mobile].[dbo].[EmployeeDetail] AS Emd
                LEFT JOIN [Bighead_Mobile].[dbo].[Employee] AS Em
                ON Emd.EmployeeCode = Em.EmpID
                WHERE SourceSystem = 'Credit' AND saleCode is not null AND (EmployeeTypeCode is not null AND EmployeeTypeCode != '') $supcode
                GROUP BY [SupervisorCode]";

                //echo $sql_case;
                $stmt = sqlsrv_query($conn,$sql_case);

                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
              <option value="<?=$row['SupervisorCode']?>_<?=$row['TeamCode']?>"><?=$row['TeamCode']?></option>
                <?php
                  }
                  sqlsrv_close($conn);
                ?>
              </optgroup>
            </select>

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

              <!--<input type="text" class="form-control" name="searchDate" id="datepicker2" autocomplete="off" placeholder="กรอกวันที่ .." required>-->
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-2">
          <a href="http://app.thiensurat.co.th/lkh/rpt_lk2.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=2" target="_blank" class="btn btn-default">
            <i class="fa fa-print"></i> </a>
        </div>
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

        <div class="col-md-12">
          <?php
            if (!empty($_REQUEST['searchDate']) || ((!empty($_REQUEST['startDate']) && !empty($_REQUEST['endDate'])))) {
           ?>
           <div class="box box-info">
             <div class="box-header with-border">
               <B>รายการเก็บเงินงวดแรก ประจำวันที่ <?=$searchDate;?></B>
             </div>

           <div class="box-body table-responsive no-padding">

             <table class="table table-hover table-striped">
               <tr>
                 <th></th>
                 <th style="text-align: center">จำนวนใบเสร็จ</th>
                 <th style="text-align: center">จำนวนสัญญา</th>
                 <th style="text-align: center">รวมจำนวนเงิน</th>
               </tr>
               <?php

               $conn = connectDB_BigHead();
/*
              $sql_case = "SELECT  CCode, Name,SupCode
              , sum(ReceiptCode) as Ref,count(RefNo) as RefNO
              , sum(TotalPayment) as Payment,sum(Discounts) as PAYAMT , '".$searchDate."' as printdate ,'".$teamcode[1]."' AS TeamCode
              , 'รายการเก็บเงิน' AS printHead
              from (
              select count(ReceiptCode) as ReceiptCode, emp.SaleCode as CCode, emp.EmployeeName as Name, emp.TeamCode,emp.SupervisorCode AS SupCode,Sum(Discounts) As Discounts,sum(TotalPayment) as TotalPayment,RefNo
              from (
              SELECT Receipt.ReceiptCode,len(Receipt.ReceiptCode) as lenReceipt, Receipt.TotalPayment, SUM(SalePaymentPeriodPayment.CloseAccountDiscountAmount) AS Discount
              ,Receipt.TotalPayment-SUM(SalePaymentPeriodPayment.CloseAccountDiscountAmount) AS Discounts, SUM(SalePaymentPeriodPayment.Amount) AS NetPayment, Receipt.RefNo,Receipt.DatePayment, Receipt.CreateBy
              FROM Receipt INNER JOIN SalePaymentPeriodPayment ON Receipt.ReceiptID = SalePaymentPeriodPayment.ReceiptID
              WHERE $WHERE AND TotalPayment > 0
              GROUP BY Receipt.ReceiptCode, Receipt.TotalPayment, Receipt.RefNo, Receipt.DatePayment, Receipt.CreateBy)
              AS vw_PaymentSummary
              inner join EmployeeDetail emp on vw_PaymentSummary.CreateBy = emp.EmployeeCode
              where emp.SourceSystem != 'sale' AND emp.SaleCode IS NOT NULL AND lenReceipt = 17 $WHERE2
              Group BY emp.SaleCode,emp.EmployeeName,emp.TeamCode,emp.SupervisorCode,RefNo
              )
              AS vw_ReceiptByEmployee
              group by CCode, Name ,SupCode ";
*/

                            $sql_case = "SELECT  CCode, Name,SupCode
                            , sum(ReceiptCode) as Ref,count(RefNo) as RefNO
                            , sum(TotalPayment) as Payment,sum(Discounts) as PAYAMT , '".$searchDate."' as printdate ,'".$teamcode[1]."' AS TeamCode
                            , 'รายการเก็บเงิน' AS printHead,zonecode
                            from (
                            select count(ReceiptCode) as ReceiptCode, emp.SaleCode as CCode, emp.EmployeeName as Name, emp.TeamCode,emp.SupervisorCode AS SupCode,Sum(Discounts) As Discounts,sum(TotalPayment) as TotalPayment,RefNo,ZoneCode
                            from (
                            SELECT Receipt.ReceiptCode,len(Receipt.ReceiptCode) as lenReceipt, Receipt.TotalPayment, SUM(SalePaymentPeriodPayment.CloseAccountDiscountAmount) AS Discount
                            ,Receipt.TotalPayment-SUM(SalePaymentPeriodPayment.CloseAccountDiscountAmount) AS Discounts, SUM(SalePaymentPeriodPayment.Amount) AS NetPayment, Receipt.RefNo,Receipt.DatePayment, Receipt.CreateBy,ZoneCode
                            FROM tsrdata_source.dbo.vw_ReceiptWithZone as Receipt INNER JOIN SalePaymentPeriodPayment ON Receipt.ReceiptID = SalePaymentPeriodPayment.ReceiptID
                            WHERE $WHERE AND TotalPayment > 0
                            GROUP BY Receipt.ReceiptCode, Receipt.TotalPayment, Receipt.RefNo, Receipt.DatePayment, Receipt.CreateBy,ZoneCode)
                            AS vw_PaymentSummary
                            inner join EmployeeDetail emp on vw_PaymentSummary.ZoneCode = emp.SaleCode
                            where emp.SourceSystem = 'sale' $WHERE2
                            Group BY emp.SaleCode,emp.EmployeeName,emp.TeamCode,emp.SupervisorCode,RefNo,ZoneCode
                            )
                            AS vw_ReceiptByEmployee
                            group by CCode, Name ,SupCode,zonecode ";

                   $sql = "SELECT SUM(Ref) AS SumRef , SUM(RefNO) as SumRefNO ,SUM(PAYAMT) as SumPAYAMT FROM (".$sql_case." ) AS C";
                   //echo $sql_case;

                   $stmt = sqlsrv_query($conn,$sql);
                   while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
               <tr>
                 <td style="text-align: center" ><B>รวม</B> </td>
                 <td style="text-align: right" width="16%"><?=number_format($row['SumRef'])?> ใบ</td>
                 <td style="text-align: right" width="16%"><?=number_format($row['SumRefNO'])?> ใบ</td>
                 <td style="text-align: right" width="16%"><?=number_format($row['SumPAYAMT'],2)?></td>
               </tr>
               <?php
                 }

                ?>
             </table>
           </div>
         </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><H4>รายงานสรุปการเก็บเงินงวดแรก</H4></center><center><B><?=$teamcode[1]?> ประจำวันที่ <?=$searchDate;?></B></center></P>
            </div>

          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">
            <?php
            $httpExcelHead = "<P><center><H4>รายงานสรุปการเก็บเงิน</H4></center><center><B>".$teamcode[1]." ประจำวันที่ ".$searchDate."</B></center></P>";
            $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
                <th>พนักงานขาย</th>
                <th></th>
                <th style=\"text-align: center\">จำนวนใบเสร็จ</th>
                <th style=\"text-align: center\">จำนวนสัญญา</th>
                <th style=\"text-align: center\">รวมจำนวนเงิน</th>
              </tr>
              </thead>
              <tbody>";
              $httpExcel2 = "";

             ?>

            <table  id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th>พนักงานขาย</th>
                <th></th>
                <th style="text-align: center">จำนวนใบเสร็จ</th>
                <th style="text-align: center">จำนวนสัญญา</th>
                <th style="text-align: center">รวมจำนวนเงิน</th>
              </tr>
              </thead>
              <tbody>

              <?php

              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),2)";
      				//echo $sql_insert;

      				$params = array($_COOKIE['tsr_emp_id'],$sql_case);
      				//print_r($params);

      				$stmt_insert = sqlsrv_query( $conns, $sql_insert, $params);

      				if( $stmt_insert === false ) {
      					 die( print_r( sqlsrv_errors(), true));
      				}

              $SumRef = 0;
              $SumRefNO = 0;
              $SumPAYAMT = 0;

              $stmt = sqlsrv_query($conn,$sql_case);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $SumRef = $SumRef + $row['Ref'];
                $SumRefNO = $SumRefNO + $row['RefNO'];
                $SumPAYAMT = $SumPAYAMT + $row['PAYAMT'];

                $httpExcel2 .= "<tr>
                  <td>".$row['CCode']."</td>
                  <td>".$row['Name']."</td>
                  <td style=\"text-align: right\">".$row['Ref']."</td>
                  <td style=\"text-align: right\">".$row['RefNO']."</td>
                  <td style=\"text-align: right\">".number_format($row['PAYAMT'],2)."</td>
                </tr>";

              ?>

              <tr>
                <td><?=$row['CCode']?></td>
                <td><?=$row['Name']?></td>
                <td style="text-align: right"><?=$row['Ref']?></td>
                <td style="text-align: right"><?=$row['RefNO']?></td>
                <td style="text-align: right"><?=number_format($row['PAYAMT'],2)?></td>
              </tr>

              <?php
                }
                 sqlsrv_close($conn);
               ?>

             </tbody>
             <tfoot>
             </tfoot>
            </table>

          <?php

          $httpExcel3 = "</tbody>
          <tfoot>
          </tfoot>
         </table>";
        $html_file = $httpExcelHead."".$httpExcel1."".$httpExcel2."".$httpExcel3;
          write_data_for_export_excel($html_file, 'ReportCreditPar2');

          //echo $html_file;
           ?>
          </div>
          <div class="box-footer clearfix">

          <a href="export_excel.php?report_type=2"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
          </div>

        </div>

        <?php
          }
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
