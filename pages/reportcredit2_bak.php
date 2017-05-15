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

if (empty($_REQUEST['EmpID'])) {
  $EmpID = array('0','-');
}else {
  $EmpID = explode("_",$_REQUEST['EmpID']);
  $WHERE .= " AND R.LastUpdateBy = '".$EmpID['0']."'";
}
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportcredit2">
        <div class="col-md-3">
          <h4>
            สรุปการเก็บเงินรายบุคคล
          </h4>
        </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-4">

        </div>

        <div class="col-md-1">

        </div>
        </form>
      </div>

      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportcredit2">
        <div class="col-md-3">
          <div class="form-group group-sm">
  						<!-- แสดงตัวเลือก จังหวัด -->
  						<select class="form-control select2 group-sm" name ="TeamCode" id="province">
  							         <option value="0"> เลือกทีม </option>
  						</select>
  					</div>
        </div>
        <div class="col-md-4">
          <div class="form-group group-sm">
						<!-- แสดงตัวเลือก อำเภอ -->
						<select class="form-control select2 group-sm" name = "EmpID" id="amphur">
							         <option value="0"> กรุณาเลือกพนักงานเก็บเงิน </option>-->
						</select>
					</div>
        </div>
        <div class="col-md-4">
          <div class="input-group input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control" name="searchDate" id="datepicker2"  placeholder="กรอกวันที่ .." required>
            <!--
            <div class="input-group input-group input-daterange" id="datepicker2">
                    <input type="text" class="form-control" name="startDate" value="<?php if(isset($_REQUEST['startDate'])) {echo $_REQUEST['startDate'];}?>" placeholder="วันเริ่มต้น .." required>
                    <span class="input-group-addon">ถึง</span>
                    <input type="text" class="form-control" name="endDate" value="<?php if(isset($_REQUEST['endDate'])) {echo $_REQUEST['endDate'];}?>" placeholder="วันสิ้นสุด .." required>
                </div>
              -->
            <div class="input-group-btn">
              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>

        <div class="col-md-1">
          <a href="http://app.thiensurat.co.th/lkh/rpt_lk1.aspx" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
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
    <script>

    			$(function(){

    				//ดึงข้อมูล province จากไฟล์ get_data.php
    				$.ajax({
    					url:"pages/getdata.php",
    					dataType: "json", //กำหนดให้มีรูปแบบเป็น Json
    					data:{show_province:'show_province'}, //ส่งค่าตัวแปร show_province เพื่อดึงข้อมูล จังหวัด
    					success:function(data){

    						//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
    						$.each(data, function( index, value ) {
    							//แทรก Elements ใน id province  ด้วยคำสั่ง append
    							  $("#province").append("<option value='"+ value.id +"'> " + value.name + "</option>");
    						});
    					}
    				});


    				//แสดงข้อมูล อำเภอ  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
    				$("#province").change(function(){

    					//กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
    					var province_id = $(this).val();

    					$.ajax({
    						url:"pages/getdata.php",
    						dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
    						data:{province_id:province_id},//ส่งค่าตัวแปร province_id เพื่อดึงข้อมูล อำเภอ ที่มี province_id เท่ากับค่าที่ส่งไป
    						success:function(data){

    							//กำหนดให้ข้อมูลใน #amphur เป็นค่าว่าง
    							$("#amphur").text("");
                  $("#amphur").append("<option value='0'> กรุณาเลือกพนักงานเก็บเงิน </option>");
    							//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
    							$.each(data, function( index, value ) {

    								//แทรก Elements ข้อมูลที่ได้  ใน id amphur  ด้วยคำสั่ง append
    								  $("#amphur").append("<option value='"+ value.id +"'> " + value.name + "</option>");
    							});
    						}
    					});

    				});

    			});

    	</script>
    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-md-12">
          <?php
            if (!empty($_REQUEST['searchDate'])) {
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

          <div class="box-body table-responsive no-padding">

            <table class="table table-hover table-striped">
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">เลขที่ใบเสร็จ</th>
                <th style="text-align: center">วันครบชำระ</th>
                <th style="text-align: center">งวดที่</th>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">ชื่อ - สกุล</th>
                <th style="text-align: center">จำนวนเงิน</th>
              </tr>
              <?php

              $sql_select = "SELECT $top row_number() OVER (ORDER BY ReceiptCode ASC) AS rownum,ReceiptCode,CONVERT(varchar,PaymentDueDate,105) as PaymentDueDate ,S.PaymentPeriodNumber,c.CONTNO AS CONTNO,CustomerName,R.TotalPayment AS PAYAMT ,ISNULL ((select SendAmount from [Bighead_Mobile].[dbo].SendMoney  WHERE SaveTransactionNoDate is not null  AND CreateBy = em.EmpID AND SaveTransactionNoDate BETWEEN '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59' ),0) as Sendmoney ,Em.EmpID,Ca.Ccode,case when ed.SaleCode is null then '-' else ed.SaleCode end as SaleCode";

              //$sql_body = " FROM Bighead_Mobile.dbo.Payment AS P LEFT JOIN Bighead_Mobile.dbo.Contract AS C ON P.CONTNO = C.CONTNO               LEFT JOIN Bighead_Mobile.dbo.vw_GetCustomer AS GC ON C.CustomerID = GC.CustomerID  LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S ON S.RefNo = P.RefNo AND P.PayPeriod = S.PaymentPeriodNumber LEFT JOIN Bighead_Mobile.dbo.Receipt AS R ON R.PaymentID = P.PaymentID  LEFT JOIN Bighead_Mobile.dbo.Employee AS Em ON p.EmpID = EM.EmpID WHERE $WHERE ";

              $sql_body = " FROM Bighead_Mobile.dbo.Receipt AS R LEFT JOIN Bighead_Mobile.dbo.Contract AS C ON R.RefNo = C.RefNo LEFT JOIN Bighead_Mobile.dbo.vw_GetCustomer AS GC ON C.CustomerID = GC.CustomerID LEFT JOIN SalePaymentPeriodPayment As Sy ON R.PaymentID = Sy.PaymentID AND R.ReceiptID = Sy.ReceiptID LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S ON S.SalePaymentPeriodID = Sy.SalePaymentPeriodID LEFT JOIN Bighead_Mobile.dbo.Employee AS Em ON R.LastUpdateBy = EM.EmpID LEFT JOIN TsrData_source.dbo.CArea AS Ca ON R.LastUpdateBy = Ca.EmpID LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed ON Ed.EmployeeCode = EM.EmpID AND Ed.EmployeeTypeCode = 'Credit' WHERE $WHERE AND Ca.CCode is not null ORDER BY ReceiptCode";

              $sql_print = "SELECT ReceiptCode
              ,CONVERT(varchar,PaymentDueDate,105) as PaymentDueDate
              ,Right('000'+Convert(Varchar,S.PaymentPeriodNumber),2) As PaymentPeriodNumber,c.CONTNO AS CONTNO,CustomerName,R.TotalPayment AS PAYAMT
              ,ISNULL ((select SendAmount from [Bighead_Mobile].[dbo].SendMoney  WHERE SaveTransactionNoDate is not null  AND CreateBy = em.EmpID AND SaveTransactionNoDate BETWEEN '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59' ),0) as Sendmoney, R.LastUpdateBy AS EmpID, Ed.FirstName + ' ' + Ed.LastName AS Names , CONVERT(varchar,R.DatePayment,105) AS Paydate , '".$_COOKIE['tsr_emp_name']."' AS PrintName ,Em.EmpID,Ca.Ccode,case when ed.SaleCode is null then '-' else ed.SaleCode end as SaleCode";

              $sql_case = $sql_select." ".$sql_body;

              $sql_print = $sql_print." ".$sql_body;
              //echo $sql_case;
              $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              fwrite($file,$sql_print);
              fclose($file);

              $num_row = checkNumRow($conn,$sql_case);
              $SumTotal = 0 ;


              $stmt = sqlsrv_query($conn,$sql_case);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $SumTotal = $SumTotal + $row['PAYAMT'];
                $Sendmoney = $row['Sendmoney'];

              ?>

              <tr>
                <td style="text-align: center"><?=$row['rownum']?></td>
                <td><?=$row['ReceiptCode']?></td>
                <td style="text-align: center"><?=DateThai($row['PaymentDueDate'])?></td>
                <td style="text-align: center"><?=$row['PaymentPeriodNumber']?></td>
                <td style="text-align: center"><?=$row['CONTNO']?></td>
                <td><?=$row['CustomerName']?></td>
                <td style="text-align: right"><?=number_format($row['PAYAMT'],2)?></td>
              </tr>

              <?php
                }
               ?>
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
          <tr>
            <td style="text-align: right" width="10%"></td>
            <td style="text-align: right" width="5%"> </td>
            <td style="text-align: left" width="10%"></td>
            <td style="text-align: right"><B> ยอดส่งเงิน </B></td>
            <td style="text-align: right" width="15%"><?=number_format($Sendmoney,2)?></td>
          </tr>
        </table>
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
