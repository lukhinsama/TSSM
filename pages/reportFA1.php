<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);


if (empty($_REQUEST['startDate']) && empty($_REQUEST['endDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,C.EFFDATE,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['startDate']))." - ".DateThai(DateEng($_REQUEST['endDate']));
  $WHERE = "AND C.EFFDATE BETWEEN '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59'";
  $top = "";
}

if (!empty($_REQUEST['EmpID'])) {

  if ($_REQUEST['EmpID'] == "7") {
    if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2)) {
      $_REQUEST['EmpID'] = "A00000";
    }else {
      $_REQUEST['EmpID'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    }
  }
    $WHERE1 = "AND C.PreSaleEmployeeCode IN (SELECT DISTINCT SaleCode FROM [TSRData_Source].[dbo].[EmployeeDataParent_ALL]  WHERE StatusType = 'FA' AND (EmployeeCodeLV2 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV3 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV4 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV5 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV6 = '".$_REQUEST['EmpID']."' OR ParentEmployeeCode = '".$_REQUEST['EmpID']."'))";
}else {
    //$_REQUEST['EmpID'] = "A00098";
    if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2)) {
      $_REQUEST['EmpID'] = "A00000";
    }else {
      $_REQUEST['EmpID'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    }
    $WHERE1 = "AND C.PreSaleEmployeeCode IN (SELECT DISTINCT SaleCode FROM [TSRData_Source].[dbo].[EmployeeDataParent_ALL]  WHERE StatusType = 'FA' AND (EmployeeCodeLV2 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV3 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV4 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV5 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV6 = '".$_REQUEST['EmpID']."' OR ParentEmployeeCode = '".$_REQUEST['EmpID']."') )";
}
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportfa1">
        <div class="col-md-2">
          <h4>
            ข้อมูลสถานะลูกค้า
          </h4>
        </div>
        <div class="col-md-2">
          <div class="form-group group-sm">

              <?PHP
              if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2)) {
                $level = 6 ;
              }else {
                if ($_COOKIE['tsr_emp_id'] == "000000") {
                  $_COOKIE['tsr_emp_id'] = "A00000";
                }
                //$EmpID['0'] = "A06797";
                $sql_case = "SELECT TOP 1 PositionLevel FROM [Bighead_Mobile].[dbo].[Position] WHERE PositionID in (SELECT PositionCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE (EmployeeCode = '".$_COOKIE['tsr_emp_id']."') AND ProcessType = 'FA') ORDER BY PositionLevel DESC";

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
                <option value="7">ทั้งหมด</option>
                <option value="6">บริษัท</option>
                <option value="5">ซุป</option>
                <option value="4">ทีม</option>
                <option value="3">หน่วย</option>
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              case "5":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="7">ทั้งหมด</option>
                <option value="5">ซุป</option>
                <option value="4">ทีม</option>
                <option value="3">หน่วย</option>
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              case "4":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="7">ทั้งหมด</option>
                <option value="4">ทีม</option>
                <option value="3">หน่วย</option>
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              case "3":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="7">ทั้งหมด</option>
                <option value="3">หน่วย</option>
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              case "2":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="7">ทั้งหมด</option>
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
            <option id="Emp_list" value="7">ทั้งหมด</option>
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
          <!--
          <a href="http://app.thiensurat.co.th/lkh/rpt.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=11&rpt=7" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
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
              <P><center><B>ข้อมูลสถานะลูกค้าจากลีดของฝ่ายการตลาด</B></center></P>
            </div>

            <?php
            $httpExcelHead = "<P><center><B>ข้อมูลสถานะลูกค้าจากลีดของฝ่ายการตลาด</B></center></P>
          <P><center><B>  ประจำวันที่ : ".$searchDate." พิมพ์โดย : ".$_COOKIE['tsr_emp_name']."</B></center></P>";

             ?>

          <div class="box-body table-responsive no-padding">
          <!--<div class="box-body">-->

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ที่</th>
                <th style="text-align: center">วันที่ติดตั้ง</th>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">เลขที่อ้างอิง</th>
                <th style="text-align: center">รุ่น</th>
                <th style="text-align: center">เลขเครื่อง</th>
                <th style="text-align: center">ชื่อลูกค้า</th>
                <th style="text-align: center">ราคาขาย</th>
                <th style="text-align: center">ส่วนลด</th>
                <th style="text-align: center">ราคาสุทธิ</th>
                <th style="text-align: center">สถานะล่าสุด</th>
                <th style="text-align: center">รหัสพนักงานขาย</th>
                <th style="text-align: center">ชื่อพนักงานขาย</th>
                <th style="text-align: center">รหัสพนักงานติดตั้ง</th>
                <th style="text-align: center">ชื่อพนักงานติดตั้ง</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
              <th style=\"text-align: center\">ที่</th>
              <th style=\"text-align: center\">วันที่ติดตั้ง</th>
              <th style=\"text-align: center\">เลขที่สัญญา</th>
              <th style=\"text-align: center\">เลขที่อ้างอิง</th>
              <th style=\"text-align: center\">รุ่น</th>
              <th style=\"text-align: center\">เลขเครื่อง</th>
              <th style=\"text-align: center\">ชื่อลูกค้า</th>
              <th style=\"text-align: center\">ราคาขาย</th>
              <th style=\"text-align: center\">ส่วนลด</th>
              <th style=\"text-align: center\">ราคาสุทธิ</th>
              <th style=\"text-align: center\">สถานะล่าสุด</th>
              <th style=\"text-align: center\">รหัสพนักงานขาย</th>
              <th style=\"text-align: center\">ชื่อพนักงานขาย</th>
              <th style=\"text-align: center\">รหัสพนักงานติดตั้ง</th>
              <th style=\"text-align: center\">ชื่อพนักงานติดตั้ง</th>
              </tr>
            </thead>
            <tbody>";
                $httpExcel2 = "";

              $sql_select = "SELECT CONVERT(varchar(20),C.EFFDATE,105) +' '+ CONVERT(varchar(5),C.EFFDATE,108) as EFFDATE,C.CONTNO,C.ContractReferenceNo,P.ProductCode,C.ProductSerialNumber
  ,DC.CustomerName,C.SALES,C.TradeInDiscount,C.TotalPrice,LEFT(C.STATUS,1) AS [STATUS]
  ,C.PreSaleSaleCode ,C.PreSaleEmployeeCode,ED.EmployeeName AS Ename1
  ,C.SaleCode,ED1.EmployeeName AS Ename2
  FROM Bighead_Mobile.dbo.Contract AS C With(NOLOCK)
  INNER JOIN Bighead_Mobile.dbo.Product AS P With(NOLOCK) ON P.ProductID = C.ProductID
  INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC With(NOLOCK) ON DC.CustomerID = C.CustomerID
  INNER JOIN Bighead_Mobile.dbo.EmployeeDetail aS ED With(NOLOCK) ON ED.SaleCode = C.PreSaleEmployeeCode
  INNER JOIN Bighead_Mobile.dbo.EmployeeDetail aS ED1 With(NOLOCK) ON ED1.SaleCode = C.SaleCode
  WHERE C.STATUS NOT IN ('VOID','DRAFT')
  $WHERE $WHERE1
  ORDER BY C.EFFDATE ";

              $sql_case = $sql_select;

              $sql_print = $sql_select;

              //echo $sql_case;
              /*
              $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              fwrite($file,$sql_case);
              fclose($file);
              */
              /*
              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),11)";
      				//echo $sql_insert;

      				$params = array($_COOKIE['tsr_emp_id'],$sql_print);
      				//print_r($params);

      				$stmt_insert = sqlsrv_query( $conns, $sql_insert, $params);

      				if( $stmt_insert === false ) {
      					 die( print_r( sqlsrv_errors(), true));
      				}
              sqlsrv_close($conns);
              */
              // เพิ่มลงฐานข้อมูล
              $num_row = checkNumRow($conn,$sql_case);
              $SumTotal = 0 ;

              $i=0;
              $stmt = sqlsrv_query($conn,$sql_case);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $SumTotal = $SumTotal + $row['TotalPrice'];
                $i++;
                $httpExcel2 .= "<tr>
                  <td style=\"text-align: center\">".$i."</td>
                  <td style=\"text-align: center\">".DateTimeThai($row['EFFDATE'])." น.</td>
                  <td>".$row['CONTNO']."</td>
                  <td>".$row['ContractReferenceNo']."</td>
                  <td>".$row['ProductCode']."</td>
                  <th style=\"text-align: center\">".$row['ProductSerialNumber']."</td>
                  <td>".$row['CustomerName']."</td>
                  <td style=\"text-align: right\">".number_format($row['SALES'],2)."</td>
                  <td style=\"text-align: right\">".number_format($row['TradeInDiscount'],2)."</td>
                  <td style=\"text-align: right\">".number_format($row['TotalPrice'],2)."</td>
                  <th style=\"text-align: center\">".$row['STATUS']."</td>
                  <th style=\"text-align: center\">".$row['PreSaleSaleCode']."</td>
                  <th style=\"text-align: center\">".$row['Ename1']."</td>
                  <th style=\"text-align: center\">".$row['SaleCode']."</td>
                  <th style=\"text-align: center\">".$row['Ename2']."</td>
                </tr>";
              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td style="text-align: center"><?=DateTimeThai($row['EFFDATE'])?> น.</td>
                <td style="text-align: center"><?=$row['CONTNO']?></td>
                <td style="text-align: center"><?=$row['ContractReferenceNo']?></td>
                <td style="text-align: center"><?=$row['ProductCode']?></td>
                <td style="text-align: center"><?=$row['ProductSerialNumber']?></td>
                <td><?=$row['CustomerName']?></td>
                <td style="text-align: right"><?=number_format($row['SALES'],2)?></td>
                <td style="text-align: right"><?=number_format($row['TradeInDiscount'],2)?></td>
                <td style="text-align: right"><?=number_format($row['TotalPrice'],2)?></td>
                <td style="text-align: center"><?=$row['STATUS']?></td>
                <td style="text-align: center"><?=$row['PreSaleSaleCode']?></td>
                <td style="text-align: center"><?=$row['Ename1']?></td>
                <td style="text-align: center"><?=$row['SaleCode']?></td>
                <td style="text-align: center"><?=$row['Ename2']?></td>
              </tr>

              <?php
                }
                $httpExcel3 = "</tbody>
                <tfoot>
                </tfoot>
               </table>";
                $html_file = $html_file = $httpExcel1."".$httpExcel2."".$httpExcel3;
                write_data_for_export_excel($html_file, 'ReportFreeAgent');
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

          <a href="export_excel.php?report_type=11"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>

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

	<script>

			$(function(){

				//เรียกใช้งาน Select2
				//$(".select2-single").select2();
				//ดึงข้อมูล province จากไฟล์ get_data.php
        /*
				$.ajax({
					url:"pages/getdata_type_search.php",//url:"get_data.php",
					dataType: "json", //กำหนดให้มีรูปแบบเป็น Json
					data:{levelEmp:'A00091'}, //ส่งค่าตัวแปร show_province เพื่อดึงข้อมูล จังหวัด
					success:function(data){
						//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
						$.each(data, function( index, value ) {
							//แทรก Elements ใน id province  ด้วยคำสั่ง append
							  $("#LvEmp").append("<option value='"+ value.id +"'> " + value.name + "</option>");
						});
					}
				});
        */

				//แสดงข้อมูล อำเภอ  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
				$("#LvEmp").change(function(){
					//กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
					var LvEmp = $(this).val();
					$.ajax({
						url:"pages/getdata_type_search_fa.php",//url:"get_data.php",
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
