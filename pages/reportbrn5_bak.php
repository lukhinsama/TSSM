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


/*
  if (substr($_COOKIE['tsr_emp_id'],0,1) == "0") {
    $EmpID['0'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    $EmpID['1'] = $_COOKIE['tsr_emp_name'];

    $WHERE1 = "AND SaleTeamCode IN (SELECT DISTINCT TeamCode  FROM [TSRData_Source].[dbo].[vw_EmployeeDataParentBRN]  WHERE EmployeeCodeLV2 = '".$EmpID['0']."' OR EmployeeCodeLV3 = '".$EmpID['0']."' OR EmployeeCodeLV4 = '".$EmpID['0']."' OR EmployeeCodeLV5 = '".$EmpID['0']."' OR EmployeeCodeLV6 = '".$EmpID['0']."' OR ParentEmployeeCode = '".$EmpID['0']."')";
}else {
    $WHERE1 = "";
}
*/
if (!empty($_REQUEST['EmpID'])) {
//echo "NOT";
  if ($_REQUEST['EmpID'] == "7") {
    if (($_COOKIE['tsr_emp_permit'] == 1) || $_COOKIE['tsr_emp_permit'] == 2) {
      $_REQUEST['EmpID'] = "A00095";
    }else {
      $_REQUEST['EmpID'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    }
  }
    $WHERE1 = "AND C.salecode IN (SELECT DISTINCT SaleCode FROM [TSRData_Source].[dbo].[vw_EmployeeDataParentBRN]  WHERE (EmployeeCodeLV2 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV3 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV4 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV5 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV6 = '".$_REQUEST['EmpID']."' OR ParentEmployeeCode = '".$_REQUEST['EmpID']."') AND EmployeeCodeLV1 IS NOT NULL AND SaleCode IS NOT NULL AND SaleCode != EmployeeCodeLV1 )";
}else {
  //EcHO "YES";
  /*
    if (($_COOKIE['tsr_emp_permit'] == 1) || $_COOKIE['tsr_emp_permit'] == 2) {
      $_REQUEST['EmpID'] = "A00095";
    }else {
      $_REQUEST['EmpID'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    }
    */
    $_REQUEST['EmpID'] = "A00095";
    $WHERE1 = "AND C.salecode IN (SELECT DISTINCT SaleCode FROM [TSRData_Source].[dbo].[vw_EmployeeDataParentBRN]  WHERE (EmployeeCodeLV2 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV3 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV4 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV5 = '".$_REQUEST['EmpID']."' OR EmployeeCodeLV6 = '".$_REQUEST['EmpID']."' OR ParentEmployeeCode = '".$_REQUEST['EmpID']."') AND EmployeeCodeLV1 IS NOT NULL AND SaleCode IS NOT NULL AND SaleCode != EmployeeCodeLV1 )";
}
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportbrn5_bak">
        <div class="col-md-2">
          <h4>
            รายงานติดตั้ง
          </h4>
        </div>
        <div class="col-md-2">
          <div class="form-group group-sm">

              <?PHP
              if (($_COOKIE['tsr_emp_permit'] == 1) || $_COOKIE['tsr_emp_permit'] == 2) {
                $level = 6 ;
              }else {
                //$EmpID['0'] = "A06797";
                $sql_case = "SELECT TOP 1 PositionLevel FROM [Bighead_Mobile].[dbo].[Position] WHERE PositionID in (SELECT PositionCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE (EmployeeCode = 'A".substr($_COOKIE['tsr_emp_id'],1,5)."')) AND SourceSystem = 'Sale' ORDER BY PositionLevel DESC";

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
                <option value="6">ภาค</option>
                <option value="5">สาขา</option>
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
                <option value="5">สาขา</option>
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
          <a href="http://app.thiensurat.co.th/lkh/rpt_lk1.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=1" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
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
                <th style="text-align: center">ที่</th>
                <th style="text-align: center">เลขที่อ้างอิง</th>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">วันที่ติดตั้ง</th>
                <th style="text-align: center">พนักงานขาย</th>
                <th style="text-align: center">รหัสทีม</th>
                <th style="text-align: center">สินค้า</th>
                <th style="text-align: center">ชื่อลูกค้า</th>
                <th style="text-align: center">ราคาขาย</th>
                <th style="text-align: center">ส่วนลด</th>
                <th style="text-align: center">ราคาขายสุทธิ</th>
                <th style="text-align: center"></th>
              </tr>
            </thead>
            <tbody>
              <?php

              $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
                <th style=\"text-align: center\">ที่</th>
                <th style=\"text-align: center\">เลขที่อ้างอิง</th>
                <th style=\"text-align: center\">เลขที่สัญญา</th>
                <th style=\"text-align: center\">วันที่ติดตั้ง</th>
                <th style=\"text-align: center\">รหัสพนักงานขาย</th>
                <th style=\"text-align: center\">ชื่อพนักงานขาย</th>
                <th style=\"text-align: center\">สินค้า</th>
                <th style=\"text-align: center\">จำนวนงวด</th>
                <th style=\"text-align: center\">ราคาขาย</th>
                <th style=\"text-align: center\">ส่วนลด</th>
                <th style=\"text-align: center\">ราคาขายสุทธิ</th>
                <th style=\"text-align: center\">ค่างวดแรก</th>
                <th style=\"text-align: center\">ค่างวดแรกสุทธิ</th>
                <th style=\"text-align: center\">ค่างวดต่อไป</th>
                <th style=\"text-align: center\">ชื่อลูกค้า</th>
                <th style=\"text-align: center\">เลขบัตร</th>
                <th style=\"text-align: center\">ที่อยู่ติดตั้ง</th>
                <th style=\"text-align: center\">ที่อยู่เก็บเงิน</th>
                <th style=\"text-align: center\">โทร</th>
              </tr>
            </thead>
            <tbody>";
                $httpExcel2 = "";

              $sql_select = "SELECT C.ContractReferenceNo as Refno
              ,C.CONTNO as CONTNO
              ,C.Refno AS RefNoR,c.CONTNO As CONTNOR
  ,C.EFFDATE
  ,CONVERT(varchar(20),C.EFFDATE,105) +' '+ CONVERT(varchar(5),C.EFFDATE,108) as EFFDATE2
  ,C.INSTALLDATE,C.SaleCode,C.SaleEmployeeCode,C.SaleTeamCode,C.ProductSerialNumber,C.Model,C.MODE,C.sales,C.tradeindiscount,C.totalprice
  ,left(C.[status],1) as status,C.Service,C.organizationCode,C.fortnightID,C.lastupdateDate
  ,(SELECT PaymentAmount FROM [Bighead_Mobile].[dbo].[PackagePeriodDetail] where model = c.Model AND paymentperiodnumber = 1) + tradeindiscount AS FirstPayment
  ,(SELECT PaymentAmount FROM [Bighead_Mobile].[dbo].[PackagePeriodDetail] where model = c.Model AND paymentperiodnumber = 1) AS FirstPaymentPeriod
  ,(SELECT PaymentAmount FROM [Bighead_Mobile].[dbo].[PackagePeriodDetail] where model = c.Model AND paymentperiodnumber = 2) AS PaymentPeriod
  ,DC.PrefixName,DC.CustomerName,DC.IDCard
  ,(SELECT AddressString FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS AddressInstall
  ,(SELECT [TelHome] + ' , ' + [TelMobile] + ' , ' + [TelOffice] FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS AddressInstallTel
  ,(SELECT AddressString FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressPayment' AND refno = C.refno) AS AddressPayment
  ,(SELECT [TelHome] + ' , ' + [TelMobile] + ' , ' + [TelOffice] FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressPayment' AND refno = C.refno) AS AddressPaymentTel
  ,C.[TradeInProductCode] + ' ' +C.[TradeInBrandCode] + ' ' + C.[TradeInProductModel] AS TradeIn
  ,E.[FirstName] +' '+ E.LastName AS SaleName
  ,Ed.[FirstName] +' '+ Ed.LastName AS ServiceName
  ,OG.OrganizationDescription
  ,FT.[StartDate],FT.[EndDate],FT.[FortnightNumber],FT.[Year]
 ,(SELECT SUM(Payamt) FROM Bighead_Mobile.dbo.Payment WHERE Refno = C.Refno) as Payamt
  ,(SELECT SUM(Discount) FROM [Bighead_Mobile].[dbo].[SalePaymentPeriod] WHERE Refno = C.Refno)  as DiscountAmt
  ,(SELECT SUM(NetAmount) FROM [Bighead_Mobile].[dbo].[SalePaymentPeriod] WHERE Refno = C.Refno) -
   (SELECT SUM(Payamt) FROM Bighead_Mobile.dbo.Payment WHERE Refno = c.Refno) as PayAll
  ,(SELECT TOP 1 PaymentPeriodNumber FROM [Bighead_Mobile].[dbo].[SalePaymentPeriod] WHERE Refno =
  C.Refno AND PaymentComplete = 1 ORDER BY PaymentPeriodNumber DESC) as PaymentPeriodNumber
  ,PD.ProductName
  FROM [Bighead_Mobile].[dbo].[Contract] AS C
  INNER JOIN [Bighead_Mobile].[dbo].[DebtorCustomer] AS DC  ON c.CustomerID = DC.CustomerID
  INNER JOIN Bighead_Mobile.dbo.Employee AS E ON E.empID = C.saleEmployeecode
  LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed ON Ed.salecode = C.service
  INNER JOIN [Bighead_Mobile].[dbo].Organization AS OG ON C.organizationCode = OG.organizationCode
  INNER JOIN [Bighead_Mobile].[dbo].[Fortnight] As Ft ON FT.FortnightID = C.FortnightID
  INNER JOIN [Bighead_Mobile].[dbo].[Product] AS PD ON PD.ProductID = C.ProductID
  where c.STATUS IN ('NORMAL','F') AND C.IsMigrate = 0 $WHERE $WHERE1 ORDER BY C.EFFDATE,C.REFNO ASC";



              $sql_case = $sql_select;

              $sql_print = $sql_select;

              echo $sql_case;
              $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              fwrite($file,$sql_case);
              fclose($file);

              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys_bak (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),11)";
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
                $SumTotal = $SumTotal + $row['totalprice'];
                $i++;
                $httpExcel2 .= "<tr>
                  <td style=\"text-align: center\">".$i."</td>
                  <td>".$row['Refno']."</td>
                  <td>".$row['CONTNO']."</td>
                  <td style=\"text-align: center\">".DateTimeThai($row['EFFDATE2'])." น.</td>
                  <td>".$row['SaleCode']."</td>
                  <th style=\"text-align: center\">".$row['SaleName']."</td>
                  <td style=\"text-align: center\">".$row['Model']." - ".$row['ProductName']."</td>
                  <td style=\"text-align: center\">".$row['MODE']."</td>
                  <td style=\"text-align: right\">".number_format($row['sales'],2)."</td>
                  <td style=\"text-align: right\">".number_format($row['tradeindiscount'],2)."</td>
                  <td style=\"text-align: right\">".number_format($row['totalprice'],2)."</td>

                  <td style=\"text-align: right\">".number_format($row['FirstPayment'],2)."</td>
                  <td style=\"text-align: right\">".number_format($row['FirstPaymentPeriod'],2)."</td>
                  <td style=\"text-align: right\">".number_format($row['PaymentPeriod'],2)."</td>
                  <th style=\"text-align: center\">".$row['CustomerName']."</td>
                  <th style=\"text-align: center\">'".$row['IDCard']."</td>
                  <th style=\"text-align: center\">".$row['AddressInstall']."</td>
                  <th style=\"text-align: center\">".$row['AddressPayment']."</td>
                  <th style=\"text-align: center\">".$row['AddressInstallTel']."</td>
                </tr>";
              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td style="text-align: center"><?=$row['Refno']?></td>
                <td style="text-align: center"><?=$row['CONTNO']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['EFFDATE2'])?> น.</td>
                <td><?=$row['SaleCode']?> - <?=$row['SaleName']?></td>
                <td><?=$row['SaleTeamCode']?></td>
                <td style="text-align: center"><?=$row['Model']?></td>
                <td><?=$row['CustomerName']?></td>
                <td style="text-align: right"><?=number_format($row['sales'],2)?></td>
                <td style="text-align: right"><?=number_format($row['tradeindiscount'],2)?></td>
                <td style="text-align: right"><?=number_format($row['totalprice'],2)?></td>
                <td style="text-align: right"><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_ViewContnoEmployee.aspx?refno=<?=$row['RefNoR']?>&contno=<?=$row['CONTNOR']?>" target="_blank"><i class="fa fa-search"></i></a></td>
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
