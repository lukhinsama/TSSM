<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);


if (!empty($_REQUEST['EmpID'])) {

  if ($_REQUEST['EmpID'] == "7") {
    if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 6)) {
      $_REQUEST['EmpID'] = "A00098";
    }else {
      $_REQUEST['EmpID'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    }
  }
    $WHERE = "AND ZoneCode IN (SELECT DISTINCT SaleCode FROM [TSRData_Source].[dbo].[vw_EmployeeDataParentcredit]  WHERE (EmployeeCodeLV2 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV3 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV4 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV5 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV6 = '".$_REQUEST['EmpID']."' OR ParentEmployeeCode = '".$_REQUEST['EmpID']."'))";
}else {
    //$_REQUEST['EmpID'] = "A00098";
    if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 6)) {
      $_REQUEST['EmpID'] = "A00098";
    }else {
      $_REQUEST['EmpID'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    }
    $WHERE = "AND ZoneCode IN (SELECT DISTINCT SaleCode FROM [TSRData_Source].[dbo].[vw_EmployeeDataParentcredit]  WHERE (EmployeeCodeLV2 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV3 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV4 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV5 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV6 = '".$_REQUEST['EmpID']."' OR ParentEmployeeCode = '".$_REQUEST['EmpID']."') )";
}

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportoper15">
        <div class="col-md-2">
          <h4>
            รายงานผลประโยชน์
          </h4>
        </div>
        <div class="col-md-2">
          <div class="form-group group-sm">

              <?PHP
              if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 7)) {
                $level = 6 ;
              }else {
                //$EmpID['0'] = "A06797";
                $sql_case = "SELECT TOP 1 PositionLevel FROM [Bighead_Mobile].[dbo].[Position] WHERE PositionID in (SELECT PositionCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE (EmployeeCode = 'A".substr($_COOKIE['tsr_emp_id'],1,5)."')) ORDER BY PositionLevel DESC";

                //echo $sql_case;
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  $level = $row['PositionLevel'];
                  }
              }


              switch ($level) {

              case "6":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="5">กรุณาเลือก</option>
                <option value="4">ฝ่าย</option>
                <option value="3">สาย</option>
                <option value="2">พนักงาน</option>
                <option value="1">ประเภท</option>
              </select>
              <?php
              break;
              case "2":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="5">กรุณาเลือก</option>
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              }
              ?>


          </div>
        </div>
        <div class="col-md-3" >
          <select class="form-control select2 group-sm" id="EmpID"  name="EmpID">
            <option id="Emp_list" value="7">กรุณาเลือก</option>
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

            <div class="input-group-btn">
              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>

        <div class="col-md-1">
          <!--<a href="http://app.thiensurat.co.th/lkh/rpt.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=11&rpt=7" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>-->
        </div>
        </form>
      </div>

    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-md-12">
          <?php
            if ((!empty($_REQUEST['startDate']) && !empty($_REQUEST['endDate']))) {
           ?>
           <div class="box box-info">
             <div class="box-header with-border">
               <B>รายงานผลประโยชน์ ประจำวันที่ <?=DateThai(DateEng($_REQUEST['startDate']));?> ถึง <?=DateThai(DateEng($_REQUEST['endDate']));?></B>
             </div>

           <div class="box-body table-responsive no-padding">

             <table class="table table-hover table-striped">
               <tr>
                 <th></th>
                 <th style="text-align: center">จำนวนสัญญา</th>
                 <th style="text-align: center">ยอดเก็บเงิน(บาท)</th>
                 <th style="text-align: center">ฐานเก็บเงิน(บาท)</th>
               </tr>
               <?php

               $conn = connectDB_BigHead();

              $sql_case = "SELECT ZoneCode,EmpName,SUM(STATUSs) AS NUM ,SUM(TotalPayment) AS TotalPayment , SUM(COMMITs) AS COMMITs
                  FROM(
                  SELECT
                  CONTNO
                  ,CusName
                  ,ReceiptCode
                  ,ZoneCode
                  ,EmpName
                  ,ISNULL(CCode,0) AS CCode
                  ,DatePayment
                  ,NetAmount
                  ,ProCommit
                  ,TotalPayment
                  ,TotalPayment - DiffCom AS COMMITs
                  ,CASE WHEN STATUSs = 2 AND DiscountAmount > 0 THEN 0 ELSE DiffCom END AS DiffCom
                  ,DiscountAmount
                  ,CASE WHEN STATUSs = 2 THEN 0 ELSE 1 END AS STATUSs
                  FROM (
                  SELECT ROW_NUMBER()OVER(PARTITION BY CONTNO ORDER BY DatePayment) AS STATUSs,CONTNO
                        ,CusName
                        ,ReceiptCode
                        ,ZoneCode
                        ,EmpName
                        ,CCode
                        ,DatePayment
                        ,NetAmount
                        ,ProCommit
                        ,TotalPayment
                        ,COMMITs
                        ,DiffCom
                  	  ,DiscountAmount
                  FROM (
                    SELECT  DISTINCT rc.*,ISNULL(CC.DiscountAmount,0) AS DiscountAmount
                      FROM [TSRData_Source].[dbo].[ReceiptWithCommit] as rc
                  	INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPP ON RC.ReceiptID = SPP.ReceiptID
                  	LEFT JOIN [Bighead_Mobile].[dbo].[ContractCloseAccount] AS CC ON SPP.PaymentID = CC.PaymentID
                    WHERE  DatePayment BETWEEN CAST('".DateEng($_REQUEST['startDate'])."' as date)  AND CAST('".DateEng($_REQUEST['endDate'])."' AS date)

                  $WHERE
                  ) AS C
                  ) AS B
                  ) AS A

                   GROUP BY ZoneCode,EmpName";
                   $sql = "SELECT SUM(NUM) AS NUM , SUM(TotalPayment) as TotalPayment ,SUM(COMMITs) as COMMITs FROM (".$sql_case." ) AS D";
                   //echo $sql_case;
                   //echo $sql;

                   $stmt = sqlsrv_query($conn,$sql);
                   while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
               <tr>
                 <td style="text-align: center" ><B>รวม</B> </td>
                 <td style="text-align: right" width="16%"><?=number_format($row['NUM'])?></td>
                 <td style="text-align: right" width="16%"><?=number_format($row['TotalPayment'],2)?></td>
                 <td style="text-align: right" width="16%"><?=number_format($row['COMMITs'],2)?></td>
               </tr>
               <?php
                 }

                ?>
             </table>
           </div>
         </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><H4>รายงานผลประโยชน์ </H4></center><center><B>ประจำวันที่ <?=DateThai(DateEng($_REQUEST['startDate']))?> ถึง <?=DateThai(DateEng($_REQUEST['endDate']));?></B></center></P>
            </div>

          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">
            <?php
            $httpExcelHead = "<P><center><H4>รายงานผลประโยชน์</H4></center><center><B> ประจำวันที่ ".DateThai(DateEng($_REQUEST['startDate']))." ถึง ".DateThai(DateEng($_REQUEST['endDate']))."</B></center></P>";
            $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
                <th>เขตเก็บเงิน</th>
                <th>พนักงานเก็บเงิน</th>
                <th style=\"text-align: center\">จำนวนสัญญา</th>
                <th style=\"text-align: center\">ยอดเก็บเงิน(บาท)</th>
                <th style=\"text-align: center\">ฐานเก็บเงิน(บาท)</th>
              </tr>
              </thead>
              <tbody>";
              $httpExcel2 = "";

             ?>

            <table  id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th>เขตเก็บเงิน</th>
                <th>พนักงานเก็บเงิน</th>
                <th style="text-align: center">จำนวนสัญญา</th>
                <th style="text-align: center">ยอดเก็บเงิน(บาท)</th>
                <th style="text-align: center">ฐานเก็บเงิน(บาท)</th>
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


                $httpExcel2 .= "<tr>
                  <td>".$row['ZoneCode']."</td>
                  <td>".$row['EmpName']."</td>
                  <td style=\"text-align: right\">".number_format($row['NUM'])."</td>
                  <td style=\"text-align: right\">".number_format($row['TotalPayment'],2)."</td>
                  <td style=\"text-align: right\">".number_format($row['COMMITs'],2)."</td>
                </tr>";

              ?>

              <tr>
                <td><a href="index.php?pages=reportoper16&EmpID=<?=$row['ZoneCode']?>&startDate=<?=$_REQUEST['startDate']?>&endDate=<?=$_REQUEST['endDate']?>" target="_blank"><?=$row['ZoneCode']?></a></td>
                <td><?=$row['EmpName']?></td>
                <td style="text-align: right"><?=number_format($row['NUM'])?></td>
                <td style="text-align: right"><?=number_format($row['TotalPayment'],2)?></td>
                <td style="text-align: right"><?=number_format($row['COMMITs'],2)?></td>
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
          write_data_for_export_excel($html_file, 'ReportCreditCom');

          //echo $html_file;
           ?>
          </div>
          <div class="box-footer clearfix">

          <a href="export_excel.php?report_type=5"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
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

  	<script>

  			$(function(){
  				//แสดงข้อมูล อำเภอ  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
  				$("#LvEmp").change(function(){
  					//กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
  					var LvEmp = $(this).val();
  					$.ajax({
  						url:"pages/getdata_type_search_test.php",//url:"get_data.php",
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
