<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
$limit_per_page = 100;
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

  //  $connss = connectDB_BigHead();
    //$sql_Empid = "SELECT SaleCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE EmployeeCode = '".$EmpID['0']."'";
    $WHERE .= "AND R.ZoneCode in (SELECT SaleCode FROM Bighead_Mobile.dbo.EmployeeDetail AS Ed WHERE ed.salecode is not null AND ed.TeamCode IN (
      SELECT DISTINCT TeamCode  FROM [TSRData_Source].[dbo].[vw_EmployeeDataParent] WHERE EmployeeCodeLV2 = '".$EmpID['0']."' OR EmployeeCodeLV3 = '".$EmpID['0']."'   OR EmployeeCodeLV4 = '".$EmpID['0']."' OR EmployeeCodeLV5 = '".$EmpID['0']."' OR EmployeeCodeLV6 = '".$EmpID['0']."' OR ParentEmployeeCode = '".$EmpID['0']."'))
 ";


    //echo $sql_Empid;
    /*
    $stmt = sqlsrv_query($connss,$sql_Empid);
    while ($rowss = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      $EmpID['2'] = $rowss['SaleCode'];
    }
    */
    //sqlsrv_close($connss);

    //$WHERE .= " AND R.ZoneCode = '".$EmpID['2']."'";


  }
}else {
  if (empty($_REQUEST['EmpID'])) {
    $EmpID = array('0','-');
  }else {
    $EmpID = explode("_",$_REQUEST['EmpID']);
    $WHERE .= " AND R.ZoneCode = '".$EmpID['2']."'";
  }
}
/*
if (!empty($_REQUEST['sortcontno'])) {
  if ($_REQUEST['sortcontno'] == "ASC") {
    $sort = "ORDER BY CONTNO";
    $sortcontno = "DESC";
    $sortreceipt = "DESC";
  }else {
    $sort = "ORDER BY CONTNO DESC";
    $sortcontno = "ASC";
    $sortreceipt = "DESC";
  }
}elseif(!empty($_REQUEST['sortreceipt'])) {
  if ($_REQUEST['sortreceipt'] == "ASC") {
    $sort = "ORDER BY ReceiptCode";
    $sortreceipt = "DESC";
    $sortcontno = "ASC";
  }else {
    $sort = "ORDER BY ReceiptCode DESC";
    $sortreceipt = "ASC";
    $sortcontno = "ASC";
  }
}else {
  $sort = "ORDER BY ReceiptCode,PaymentPeriodNumber";
  $sortcontno = "ASC";
  $sortreceipt = "DESC";
}
*/
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportsale7">
        <div class="col-md-3">
          <h4>
            เก็บงวดแรก(บางส่วนครบ)
          </h4>
        </div>
        <div class="col-md-4">
          <?php
          if (($_COOKIE['tsr_emp_permit'] != 4)) {
           ?>
          <div class="form-group group-sm">
            <select class="form-control select2 group-sm" name="EmpID" >
              <optgroup label="พนักงานเก็บเงิน">

                  <option value="0"> ทั้งหมด </option>
                  <?php

                $sql_case = "SELECT SaleCode as mcode,TeamCode,EmployeeName AS Name ,EmployeeCode AS EmpID,case when SaleCode is null then '-' else SaleCode end as SaleCode ,SupervisorCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE  salecode is not null AND SupervisorCode is not null AND ProcessType = 'sale' AND TeamCode is not null ORDER BY TeamCode";

                //echo $sql_case;
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
              <option value="<?=$row['EmpID']?>_<?=$row['Name']?>_<?=$row['SaleCode']?>_<?=$row['mcode']?>"><?=$row['EmpID']?> (<?=$row['TeamCode']?>) <?=$row['Name']?> </option>
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
              <P><center><B>รายงานสรุปการเก็บเงินงวดแรก</B></center></P>
              <table width="100%">
                <tr>
                  <td>พนักงานขาย : <?=$EmpID['0']?> , <?=$EmpID['2']?></td>
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
                <th style="text-align: center">เลขที่อ้างอิง</th>
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
                <th style=\"text-align: center\">ชื่อพนักงานเก็บเงิน</th>
                <th style=\"text-align: center\">รหัสเขตเก็บเงิน</th>
                <th style=\"text-align: center\">เลขที่ใบเสร็จ</th>
                <th style=\"text-align: center\">เวลาออกใบเสร็จ</th>
                <th style=\"text-align: center\">งวดที่</th>
                <th style=\"text-align: center\">เลขที่อ้างอิง</th>
                <th style=\"text-align: center\">เลขที่สัญญา</th>
                <th style=\"text-align: center\">ชื่อ - สกุล</th>
                <th style=\"text-align: center\">จำนวนเงิน</th>
              </tr>
            </thead>
            <tbody>";
                $httpExcel2 = "";
              $sql_select = "SELECT ReceiptCode
              ,CONVERT(varchar,PaymentDueDate) as PaymentDueDate
              ,case when count(ReceiptCode) = 1 then convert(varchar,min(PaymentPeriodNumber)) else convert(varchar,min(PaymentPeriodNumber)) + ' - ' + convert(varchar,Max(PaymentPeriodNumber)) end as PaymentPeriodNumber
              ,CONTNO,RefNo
              ,CustomerName
              ,case when count(ReceiptCode) = 1 then SUM(PAYAMT) else SUM(PAYAMT) end as PAYAMT
              ,case when count(ReceiptCode) = 1 then SUM(PAYAMT) else SUM(PAYAMT) end as PAYAMTS
              ,EmpID
              ,Names
              ,Paydate
              ,PrintName
              ,SaleCode
              , 'รายงานสรุปการเก็บเงิน' AS printHead
              from (SELECT DISTINCT ReceiptCode
              ,CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) as PaymentDueDate
              ,Right('000'+Convert(Varchar,S.PaymentPeriodNumber),2) As PaymentPeriodNumber,c.CONTNO AS CONTNO,C.ContractReferenceNo AS RefNo,CustomerName,Sy.Amount AS PAYAMT
              , Em.FirstName + ' ' + Em.LastName AS Names , '".$searchDate."' AS Paydate , '".$_COOKIE['tsr_emp_name']."' AS PrintName,R.CreateBy as EmpID,R.ZoneCode as SaleCode ";


              $sql_body = " FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R WITH(NOLOCK) LEFT JOIN Bighead_Mobile.dbo.Contract AS C WITH(NOLOCK) ON R.RefNo = C.RefNo LEFT JOIN Bighead_Mobile.dbo.vw_GetCustomer AS GC WITH(NOLOCK) ON C.CustomerID = GC.CustomerID LEFT JOIN SalePaymentPeriodPayment As Sy WITH(NOLOCK) ON R.PaymentID = Sy.PaymentID AND R.ReceiptID = Sy.ReceiptID LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S WITH(NOLOCK) ON S.SalePaymentPeriodID = Sy.SalePaymentPeriodID LEFT JOIN Bighead_Mobile.dbo.Employee AS Em WITH(NOLOCK) ON R.LastUpdateBy = EM.EmpID WHERE $WHERE

              AND c.CONTNO IN (SELECT C.CONTNO
              FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R WITH(NOLOCK)
              INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPPP WITH(NOLOCK) ON R.ReceiptID = SPPP.ReceiptID AND SPPP.Amount > 0
              INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS SPP WITH(NOLOCK) ON SPPP.SalePaymentPeriodID = SPP.SalePaymentPeriodID AND SPP.PaymentComplete = 1
              INNER JOIN Bighead_Mobile.dbo.Contract AS C WITH(NOLOCK) ON C.Refno = R.Refno
              WHERE $WHERE AND TypeCode = 1
              GROUP BY C.CONTNO
              HAVING count(CONTNO) > 1)

              ) as result WHERE LEFT(CONTNO,1) = 'X' GROUP BY ReceiptCode,PaymentDueDate,CONTNO,CustomerName,EmpID,SaleCode,Names,Paydate,PrintName,RefNo ORDER BY CONTNO,PaymentDueDate";


              $sql_print = "SELECT row_number() OVER (ORDER BY ReceiptCode ASC) AS rownum
              ,ReceiptCode
              ,CONVERT(varchar,PaymentDueDate) as PaymentDueDate
              ,case when count(ReceiptCode) = 1 then convert(varchar,min(PaymentPeriodNumber)) else convert(varchar,min(PaymentPeriodNumber)) + ' - ' + convert(varchar,Max(PaymentPeriodNumber)) end as PaymentPeriodNumber
              ,CONTNO
              ,CustomerName
              ,case when count(ReceiptCode) = 1 then SUM(PAYAMT) else SUM(PAYAMT) end as PAYAMT
              ,case when count(ReceiptCode) = 1 then SUM(PAYAMT) else SUM(PAYAMT) end as PAYAMTS
              ,EmpID
              ,'".$EmpID['1']."' AS Names
              ,Paydate
              ,PrintName
              ,SaleCode
              from (SELECT ReceiptCode
              ,CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) as PaymentDueDate
              ,Right('000'+Convert(Varchar,S.PaymentPeriodNumber),2) As PaymentPeriodNumber,c.CONTNO AS CONTNO,CustomerName,Sy.Amount AS PAYAMT
              ,ISNULL ((select SendAmount from [Bighead_Mobile].[dbo].SendMoney  WHERE SaveTransactionNoDate is not null  AND CreateBy = em.EmpID AND SaveTransactionNoDate BETWEEN '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59' ),0) as Sendmoney, Ed.FirstName + ' ' + Ed.LastName AS Names , '".$searchDate."' AS Paydate , '".$_COOKIE['tsr_emp_name']."' AS PrintName ,Em.EmpID,case when ed.SaleCode is null then '-' else ed.SaleCode end as SaleCode";

              $sql_case = $sql_select." ".$sql_body;

              $sql_print = $sql_select." ".$sql_body;

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
                  <td>".$row['Names']."</td>
                  <td>".$row['SaleCode']."</td>
                  <td>#".$row['ReceiptCode']."</td>
                  <td style=\"text-align: center\">".DateTimeThai($row['PaymentDueDate'])." น.</td>
                  <td style=\"text-align: center\">".$row['PaymentPeriodNumber']."</td>
                  <td style=\"text-align: center\">".$row['RefNo']."</td>
                  <td style=\"text-align: center\">".$row['CONTNO']."</td>
                  <td>".$row['CustomerName']."</td>
                  <td style=\"text-align: right\">".number_format($row['PAYAMT'],2)."</td>
                </tr>";
              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td><?=$row['ReceiptCode']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['PaymentDueDate'])?> น.</td>
                <td style="text-align: center"><?=$row['PaymentPeriodNumber']?></td>
                <td style="text-align: center"><?=$row['RefNo']?></td>
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
