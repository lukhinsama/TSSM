<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);


if (empty($_REQUEST['startDate']) && empty($_REQUEST['endDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,R.DatePayment,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['startDate']))." - ".DateThai(DateEng($_REQUEST['endDate']));
  $WHERE = "AND C.EFFDATE BETWEEN '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59'";
  $top = "";
}

  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=monitorSendMoney">
        <div class="col-md-2">
          <h4>
            ตรวจสอบการส่งเงิน
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

          <a href="http://app.thiensurat.co.th/lkh/rpt.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=11&rpt=7" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
        </div>
        </form>
      </div>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><B>รายงานติดตั้งประจำวัน</B></center></P>
            </div>
          <div class="box-body table-responsive no-padding">
          <!--<div class="box-body">-->

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ที่</th>
                <th style="text-align: center">รหัสพนักงาน</th>
                <th style="text-align: center">รหัรหัสเขตเก็บเงิน</th>
                <th style="text-align: center">ชื่อพนักงาน</th>
                <th style="text-align: center">วันที่เก็บเงิน</th>
                <th style="text-align: center">จำนวนใบเสร็จ</th>
                <th style="text-align: center">จำนวนเงิน</th>
                <th style="text-align: center">จำนวนส่งเงิน</th>
                <th style="text-align: center">ค่างส่ง</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql_select = "SELECT top 100 SR.[CreateBy]
      ,[ZoneCode]
      ,Convert(varchar,cast([datePayment] as datetime),105) AS datePayment
      ,[NumReceipt]
      ,[SumPayment]
      ,[SendMoney]
      ,[SendCount],e.FirstName,E.LastName
  FROM [TSRData_Source].[dbo].[vw_TSSM_SendMoney_Receipt] AS SR
  inner join Bighead_Mobile.dbo.Employee as e on sr.[CreateBy] = E.empid
  WHERE datePayment = '2018-11-26'";

              $stmt = sqlsrv_query($conn,$sql_select);
              $i=0;
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $i++;
                $diffMoney = $row['SumPayment'] - $row['SendMoney'];
              ?>
              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td style="text-align: center"><?=$row['CreateBy']?></td>
                <td><?=$row['ZoneCode']?></td>
                <td><?=$row['FirstName']?> <?=$row['LastName']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['datePayment'])?> น.</td>
                <td style="text-align: right"><?=number_format($row['NumReceipt'],0)?></td>
                <td style="text-align: right"><?=number_format($row['SumPayment'],2)?></td>
                <td style="text-align: right"><?=number_format($row['SendMoney'],2)?></td>
                <td style="text-align: right"><?=number_format($diffMoney,2)?></td>
              </tr>
              <?php
                }
              ?>
             </tbody>
             <tfoot>
             </tfoot>
            </table>

          </div>
          <?php
          $num_row = checkNumRow($conn,$sql_select);
          $sql_select = "SELECT SUM(SendMoney) as sumSendTotal , SUM(SumPayment) AS sumPayTotal FROM ($sql_select) AS Totals ";

          $stmt = sqlsrv_query($conn,$sql_select);
          $i=0;
          while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
            $sumSendTotal = $row1['sumSendTotal'];
            $sumPayTotal = $row1['sumPayTotal'];
          }
            ?>
          <table width="100%">
          <tr>
            <td style="text-align: right" width="10%"><B>รวม</B> </td>
            <td style="text-align: right" width="5%"> </td>
            <td style="text-align: left" width="10%"><?=number_format($sumSendTotal)?> รายการ</td>
            <td style="text-align: right"><B> รวมเงิน </B></td>
            <td style="text-align: right" width="15%"><?=number_format($sumPayTotal,2)?></td>
          </tr>
          </table>

        </div>
        <?php
        //  }

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
