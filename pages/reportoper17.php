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
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportoper17">
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

          <a href="http://app.thiensurat.co.th/lkh/rpt.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=13&rpt=9" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>

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
                <th style="text-align: center">สถานะ</th>
                <th style="text-align: center">วันที่จ่ายไม่ครบ</th>
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
                <th style=\"text-align: center\">สถานะ</th>
                <th style=\"text-align: center\">วันที่จ่ายไม่ครบ</th>
                <th style=\"text-align: center\">เล่มใบเสร็จมือ</th>
                <th style=\"text-align: center\">เลขใบเสร็จมือ</th>
                <th style=\"text-align: center\">จำนวนพิมพ์</th>
              </tr>
            </thead>
            <tbody>";
                $httpExcel2 = "";


              $sql_case = "SELECT DISTINCT '".$_COOKIE['tsr_emp_name']."' AS PrintName,'".$searchDate."' AS printHead, R.ReceiptCode,R.DatePayment AS Paydate,R.DatePayment,CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) as PaymentDueDate,R.PaymentPeriodNumber,C.ContractReferenceNo,C.CONTNO,DC.CustomerName,R.ZoneCode,R.CreateBy AS EmpID,E.FirstName+' '+E.LastName AS EmpName,R.NetAmount,R.TotalPayment AS PAYAMT,R.PaymentComplete,CASE WHEN R.TotalPayment != 0 THEN CASE WHEN R.PaymentComplete = 1 AND NetAmount = TotalPayment THEN 'ส่งครบ' WHEN PaymentComplete = 0 THEN 'ส่งไม่ครบ' ELSE 'ส่งบางส่วนครบ' END ELSE 'ยกเลิกใบเสร็จ' END AS CompleteStatus,R.PrintOrder,ISNULL(R.PayDateOld,'-') AS PayDateOld ,ISNULL(B.BookNo,'-') AS BookNo ,ISNULL(B.ReceiptNo,'-') AS ReceiptNo
                FROM TSRData_Source.dbo.vw_ReceiptWithZone_ALL AS R INNER JOIN Bighead_Mobile.dbo.Contract AS C ON R.RefNo = C.RefNo INNER JOIN Bighead_Mobile.dbo.DebtorCustomer AS DC ON C.CustomerID = DC.CustomerID INNER JOIN Bighead_Mobile.dbo.Employee AS E ON R.CreateBy = EmpID LEFT JOIN Bighead_Mobile.dbo.MigrateReportDailyReceiptB AS B ON B.InvNo = R.ReceiptCode WHERE DatePayment BETWEEN CAST('".DateEng($_REQUEST['startDate'])." 00:00' AS datetime) AND CAST('".DateEng($_REQUEST['endDate'])." 23:59' AS datetime) AND TypeCode = 1 ORDER BY R.DatePayment";

                //ECHO $sql_case ;
              $sql_print = $sql_case;

              //echo $sql_case;
              $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              fwrite($file,$sql_case);
              fclose($file);

              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),13)";
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
                if ($row['PaymentComplete'] == '1' AND ($row['NetAmount'] == $row['PAYAMT'])) {
                  $complete = 'ส่งครบ';
                }elseif ($row['PaymentComplete'] == '0') {
                  $complete = 'ส่งไม่ครบ';
                } else {
                 $complete = 'บางส่วนครบ';
                }

                if ($row['PAYAMT'] == 0) {
                  $complete = 'ยกเลิกใบเสร็จ';
                }


                $SumTotal = $SumTotal + $row['PAYAMT'];
                $i++;
                $httpExcel2 .= "<tr>
                  <td style=\"text-align: center\">".$i."</td>
                  <td>".$row['EmpName']."</td>
                  <td>".$row['ZoneCode']."</td>
                  <td>'".$row['ReceiptCode']."</td>
                  <td style=\"text-align: center\">".DateTimeThai($row['PaymentDueDate'])." น.</td>
                  <td style=\"text-align: center\">".$row['PaymentPeriodNumber']."</td>
                  <td style=\"text-align: center\">".$row['ContractReferenceNo']."</td>
                  <td style=\"text-align: center\">".$row['CONTNO']."</td>
                  <td>".$row['CustomerName']."</td>
                  <td style=\"text-align: right\">".number_format($row['NetAmount'],2)."</td>
                  <td style=\"text-align: right\">".number_format($row['PAYAMT'],2)."</td>
                  <td style=\"text-align: center\">".$complete."</td>
                  <td style=\"text-align: center\">".$row['PayDateOld']."</td>
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
                  <td style="text-align: center"><a class="text-danger"><?=$row['ContractReferenceNo']?></a></td>
                  <td style="text-align: center"><a class="text-danger"><?=$row['CONTNO']?></a></td>
                  <td><a class="text-danger"><?=$row['CustomerName']?></a></td>
                  <td style="text-align: right"><a class="text-danger"><?=number_format($row['NetAmount'],2)?></a></td>
                  <td style="text-align: right"><a class="text-danger"><?=number_format($row['PAYAMT'],2)?></a></td>
                  <td style="text-align: center"><a class="text-danger"><?=$complete?></a></td>
                  <td style="text-align: center"><a class="text-danger"><?=$row['PayDateOld']?></a></td>
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
                    <td style="text-align: center"><a class="text-warning"><?=$row['ContractReferenceNo']?></a></td>
                    <td style="text-align: center"><a class="text-warning"><?=$row['CONTNO']?></a></td>
                    <td><a class="text-warning"><?=$row['CustomerName']?></a></td>
                    <td style="text-align: right"><a class="text-warning"><?=number_format($row['NetAmount'],2)?></a></td>
                    <td style="text-align: right"><a class="text-warning"><?=number_format($row['PAYAMT'],2)?></a></td>
                    <td style="text-align: center"><a class="text-warning"><?=$complete?></a></td>
                    <td style="text-align: center"><a class="text-warning"><?=$row['PayDateOld']?></a></td>
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
                    <td style="text-align: center"><?=$row['ContractReferenceNo']?></td>
                    <td style="text-align: center"><?=$row['CONTNO']?></td>
                    <td><?=$row['CustomerName']?></td>
                    <td style="text-align: right"><?=number_format($row['NetAmount'],2)?></td>
                    <td style="text-align: right"><?=number_format($row['PAYAMT'],2)?></td>
                    <td style="text-align: center"><?=$complete?></td>
                    <td style="text-align: center"><?=$row['PayDateOld']?></td>
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
                write_data_for_export_excel($html_file, 'ReportCreditPar4');
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
          <a href="export_excel.php?report_type=4"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
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
            url:"pages/getdata_type_search_brn.php",//url:"get_data.php",
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
