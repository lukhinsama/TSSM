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

    $WHERE .= "AND R.ZoneCode IN (
      SELECT DISTINCT Salecode  FROM [TSRData_Source].[dbo].[vw_EmployeeDataParent] WHERE (EmployeeCodeLV2 = '".$EmpID['0']."' OR EmployeeCodeLV3 = '".$EmpID['0']."'   OR EmployeeCodeLV4 = '".$EmpID['0']."' OR EmployeeCodeLV5 = '".$EmpID['0']."' OR EmployeeCodeLV6 = '".$EmpID['0']."' OR ParentEmployeeCode = '".$EmpID['0']."')  )
 ";


  }
}else {
  if (!empty($_REQUEST['EmpID'])) {

    if ($_REQUEST['EmpID'] == "7") {
      if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 6)) {
        $_REQUEST['EmpID'] = "A00098";
      }else {
        $_REQUEST['EmpID'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
      }
    }
      $WHERE1 = "AND R.ZoneCode IN (SELECT DISTINCT SaleCode FROM [TSRData_Source].[dbo].[vw_EmployeeDataParent]  WHERE (EmployeeCodeLV2 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV3 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV4 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV5 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV6 = '".$_REQUEST['EmpID']."' OR ParentEmployeeCode = '".$_REQUEST['EmpID']."')  )";
  }else {
    if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 6)) {
      $_REQUEST['EmpID'] = "A00098";
    }else {
      $_REQUEST['EmpID'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    }
      $WHERE1 = "AND R.ZoneCode IN (SELECT DISTINCT SaleCode FROM [TSRData_Source].[dbo].[vw_EmployeeDataParent]  WHERE (EmployeeCodeLV2 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV3 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV4 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV5 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV6 = '".$_REQUEST['EmpID']."' OR ParentEmployeeCode = '".$_REQUEST['EmpID']."')  )";
  }
}

  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportsale11">
        <div class="col-md-2">
          <h4>
            เก็บเงินงวดแรกรวม
          </h4>
        </div>
        <div class="col-md-2">

        </div>
        <div class="col-md-3" >

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
          <a href="http://app.thiensurat.co.th/lkh/rpt.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=12&rpt=8" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
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
            if (!empty($_REQUEST['startDate']) && !empty($_REQUEST['endDate'])) {

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

          <div class="box-body table-responsive no-padding">
          <!--<div class="box-body">-->

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
                <th style="text-align: center">งวดแรก</th>
                <th style="text-align: center">จำนวนเงิน</th>
                <th style="text-align: center">เล่มใบเสร็จมือ</th>
                <th style="text-align: center">เลขใบเสร็จมือ</th>
                <th style="text-align: center">จำนวนพิมพ์</th>
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
                <th style=\"text-align: center\">งวดแรก</th>
                <th style=\"text-align: center\">จำนวนเงิน</th>
                <th style=\"text-align: center\">เล่มใบเสร็จมือ</th>
                <th style=\"text-align: center\">เลขใบเสร็จมือ</th>
                <th style=\"text-align: center\">จำนวนพิมพ์</th>
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
              , 'รายงานสรุปการเก็บเงินงวดแรก' AS printHead
              ,NetAmount
              ,ISNULL((SELECT MAX(PrintOrder) FROM Bighead_Mobile.dbo.DocumentHistory WHERE DocumentNumber = result.ReceiptID GROUP BY DocumentNumber) ,0) AS PrintOrder
              ,ISNULL(BookNo,'-') AS BookNo
              ,ISNULL(ReceiptNo,'-') AS ReceiptNo
              from (SELECT DISTINCT ReceiptCode
              ,R.ReceiptID,B.BookNo,B.ReceiptNo
              ,CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) as PaymentDueDate
              ,Right('000'+Convert(Varchar,S.PaymentPeriodNumber),2) As PaymentPeriodNumber,c.CONTNO AS CONTNO,C.ContractReferenceNo AS RefNo,CustomerName,Sy.Amount AS PAYAMT
              , Em.FirstName + ' ' + Em.LastName AS Names , '".$searchDate."' AS Paydate , '".$_COOKIE['tsr_emp_name']."' AS PrintName,R.CreateBy as EmpID,R.ZoneCode as SaleCode ,S.NetAmount";


              $sql_body = " FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R WITH(NOLOCK) LEFT JOIN Bighead_Mobile.dbo.Contract AS C WITH(NOLOCK) ON R.RefNo = C.RefNo LEFT JOIN Bighead_Mobile.dbo.vw_GetCustomer AS GC WITH(NOLOCK) ON C.CustomerID = GC.CustomerID LEFT JOIN SalePaymentPeriodPayment As Sy WITH(NOLOCK) ON R.PaymentID = Sy.PaymentID AND R.ReceiptID = Sy.ReceiptID LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S WITH(NOLOCK) ON S.SalePaymentPeriodID = Sy.SalePaymentPeriodID LEFT JOIN Bighead_Mobile.dbo.Employee AS Em WITH(NOLOCK) ON R.LastUpdateBy = EM.EmpID LEFT JOIN Bighead_Mobile.dbo.MigrateReportDailyReceiptB AS B ON B.InvNo = R.ReceiptCode WHERE $WHERE AND S.SalePaymentPeriodID = Sy.SalePaymentPeriodID AND R.TypeCode = 1
              ) as result GROUP BY ReceiptCode,PaymentDueDate,CONTNO,CustomerName,EmpID,SaleCode,Names,Paydate,PrintName,RefNo,NetAmount,ReceiptID,BookNo,ReceiptNo ORDER BY ReceiptCode";

              $sql_case = $sql_select." ".$sql_body;

              /*
              $sql_case = "  SELECT R.ReceiptCode,R.DatePayment,R.PaymentPeriodNumber,C.ContractReferenceNo,C.CONTNO,DC.CustomerName,R.ZoneCode,R.CreateBy AS EmpID,E.FirstName+' '+E.LastName AS EmpName,R.NetAmount,R.TotalPayment,R.PaymentComplete,R.PrintOrder,R.PayDateOld
  FROM TSRData_Source.dbo.vw_ReceiptWithZone_ALL AS R
  INNER JOIN Bighead_Mobile.dbo.Contract AS C ON R.RefNo = C.RefNo
  INNER JOIN Bighead_Mobile.dbo.DebtorCustomer AS DC ON C.CustomerID = DC.CustomerID
  INNER JOIN Bighead_Mobile.dbo.Employee AS E ON R.CreateBy = EmpID
  WHERE DatePayment BETWEEN CAST('2017-07-01 00:00' AS datetime) AND CAST('2017-07-01 23:59' AS datetime)
  AND TypeCode = 1";
  */

              $sql_print = $sql_case;

              //echo $sql_case;
              $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              fwrite($file,$sql_case);
              fclose($file);

              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),12)";
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
                  <td style=\"text-align: right\">".number_format($row['NetAmount'],2)."</td>
                  <td style=\"text-align: right\">".number_format($row['PAYAMT'],2)."</td>
                  <td style=\"text-align: center\">".$row['BookNo']."</td>
                  <td style=\"text-align: center\">".$row['ReceiptNo']."</td>
                  <td style=\"text-align: center\">".$row['PrintOrder']."</td>
                </tr>";
              ?>

              <tr>

                <?php
                if ($row['PAYAMT'] == '0') {
                  ?>
                  <td style="text-align: center"><a class="text-danger"><?=$i?></a></td>
                  <td><a class="text-danger"><?=$row['ReceiptCode']?></a></td>
                  <td style="text-align: center"><a class="text-danger"><?=DateTimeThai($row['PaymentDueDate'])?> น.</a></td>
                  <td style="text-align: center"><a class="text-danger"><?=$row['PaymentPeriodNumber']?></a></td>
                  <td style="text-align: center"><a class="text-danger"><?=$row['RefNo']?></a></td>
                  <td style="text-align: center"><a class="text-danger"><?=$row['CONTNO']?></a></td>
                  <td><a class="text-danger"><?=$row['CustomerName']?></a></td>
                  <td style="text-align: right"><a class="text-danger"><?=number_format($row['NetAmount'],2)?></a></td>
                  <td style="text-align: right"><a class="text-danger"><?=number_format($row['PAYAMT'],2)?></a></td>
                  <td style="text-align: center"><a class="text-danger"><?=$row['BookNo']?></a></td>
                  <td style="text-align: center"><a class="text-danger"><?=$row['ReceiptNo']?></a></td>
                  <td style="text-align: center"><a class="text-danger"><?=$row['PrintOrder']?></a></td>
                  <?PHP
                }elseif ($row['NetAmount'] != $row['PAYAMT']) {
                    ?>
                    <td style="text-align: center"><a class="text-warning"><?=$i?></a></td>
                    <td><a class="text-warning"><?=$row['ReceiptCode']?></a></td>
                    <td style="text-align: center"><a class="text-warning"><?=DateTimeThai($row['PaymentDueDate'])?> น.</a></td>
                    <td style="text-align: center"><a class="text-warning"><?=$row['PaymentPeriodNumber']?></a></td>
                    <td style="text-align: center"><a class="text-warning"><?=$row['RefNo']?></a></td>
                    <td style="text-align: center"><a class="text-warning"><?=$row['CONTNO']?></a></td>
                    <td><a class="text-warning"><?=$row['CustomerName']?></a></td>
                    <td style="text-align: right"><a class="text-warning"><?=number_format($row['PAYAMT'],2)?></a></td>
                    <td style="text-align: right"><a class="text-warning"><?=number_format($row['NetAmount'],2)?></a></td>
                    <td style="text-align: center"><a class="text-warning"><?=$row['BookNo']?></a></td>
                    <td style="text-align: center"><a class="text-warning"><?=$row['ReceiptNo']?></a></td>
                    <td style="text-align: center"><a class="text-warning"><?=$row['PrintOrder']?></a></td>
                    <?PHP
                  }else {
                    ?>
                    <td style="text-align: center"><?=$i?></td>
                    <td><?=$row['ReceiptCode']?></td>
                    <td style="text-align: center"><?=DateTimeThai($row['PaymentDueDate'])?> น.</td>
                    <td style="text-align: center"><?=$row['PaymentPeriodNumber']?></td>
                    <td style="text-align: center"><?=$row['RefNo']?></td>
                    <td style="text-align: center"><?=$row['CONTNO']?></td>
                    <td><?=$row['CustomerName']?></td>
                    <td style="text-align: right"><?=number_format($row['PAYAMT'],2)?></td>
                    <td style="text-align: right"><?=number_format($row['NetAmount'],2)?></td>
                    <td style="text-align: center"><?=$row['BookNo']?></td>
                    <td style="text-align: center"><?=$row['ReceiptNo']?></td>
                    <td style="text-align: center"><?=$row['PrintOrder']?></td>
                    <?PHP
                  }
                 ?>
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
          <a href="export_excel.php?report_type=5"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
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
  <script>
      $(function(){

        //แสดงข้อมูล อำเภอ  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
        $("#LvEmp").change(function(){
          //กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
          var LvEmp = $(this).val();
          $.ajax({
            url:"pages/getdata_type_search.php",//url:"get_data.php",
            dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
            data:{LvEmp:LvEmp},//ส่งค่าตัวแปร province_id เพื่อดึงข้อมูล อำเภอ ที่มี province_id เท่ากับค่าที่ส่งไป
            success:function(data){
              //กำหนดให้ข้อมูลใน #amphur เป็นค่าว่าง
              $("#EmpID").text("");
              //วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
              $.each(data, function( index, value ) {
                //แทรก Elements ข้อมูลที่ได้  ใน id amphur  ด้วยคำสั่ง append
                  $("#EmpID").append("<option value='"+ value.id +"'> " + value.name + "</option>");
              });
            }
          });
        });
      });
  </script>
