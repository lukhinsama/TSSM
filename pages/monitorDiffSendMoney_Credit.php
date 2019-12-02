<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=monitorDiffSendMoneyAll">
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

        </div>

        <div class="col-md-1">

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
              <P><center><B>รายงานเทียบส่งเงินประจำเดือน ฝ่ายเก็บเงินภาคสนาม</B></center></P>
            </div>
          <div class="box-body table-responsive no-padding">
          <!--<div class="box-body">-->

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th rowspan="2" style="text-align: center">เขต</th>
                <th rowspan="2" style="text-align: center">รหัสพนักงาน</th>
                <th rowspan="2" style="text-align: center">ชื่อพนักงาน</th>
                <th colspan="2" style="text-align: center">เก็บเงิน Bighead</th>
                <th colspan="2" style="text-align: center">ส่งเงิน Bighead</th>
                <th rowspan="2" style="text-align: center">ค้างส่งเงิน</th>
              </tr>
              <tr>
                <th style="text-align: center">ใบเสร็จ</th>
                <th style="text-align: center">เงินที่เก็บ</th>
                <th style="text-align: center">ทำส่งเงิน</th>
                <th style="text-align: center">เงินที่ส่ง</th>             
              </tr>
            </thead>
            <tbody>
              <?php
              $sql_select = "SELECT data.*,E.FirstName+' '+E.LastName AS EmpName,ED.SaleCode,data.SumReceipt-SumSend AS DiffSend
FROM (
SELECT *
FROM (
SELECT R.CreateBy,COUNT(R.ReceiptCode) AS NumReceipt,SUM(R.TotalPayment) AS SumReceipt
FROM Bighead_Mobile.dbo.Receipt AS R
WHERE DATEDIFF(MONTH,DatePayment,GETDATE()) = 0
GROUP BY R.CreateBy) AS Z
LEFT JOIN (
SELECT S.CreateBy AS SendEmpid,COUNT(SendMoneyID) AS NumSend,SUM(SendAmount) AS SumSend
FROM Bighead_Mobile.dbo.SendMoney AS S
WHERE DATEDIFF(MONTH,CreateDate,GETDATE()) = 0 AND S.Reference1 != 'MigrateRef1'
GROUP BY S.CreateBy ) AS Y ON Z.CreateBy = Y.SendEmpid
WHERE
Z.SumReceipt != Y.SumSend
) AS data
inner JOIN Bighead_Mobile.dbo.employee as e on e.empid = data.CreateBy
LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS ED ON E.EmpID = ED.EmployeeCode
WHERE LEFT(ED.SaleCode,1) = '1'
";

$sql_select1 = $sql_select." ORDER BY ED.SaleCode";

              $stmt = sqlsrv_query($conn,$sql_select1);
              $i=0;
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $i++;
                $diffMoney = $row['SumReceipt'] - $row['SumSend'];
              ?>
              <tr>
                <td style="text-align: center"><?=$row['SaleCode']?></td>
                <td style="text-align: center"><?=$row['CreateBy']?></td>
                <td><?=$row['EmpName']?></td>
                <td style="text-align: right"><?=number_format($row['NumReceipt'],0)?></td>
                <td style="text-align: right"><?=number_format($row['SumReceipt'],2)?></td>
                <td style="text-align: right"><?=number_format($row['NumSend'],0)?></td>
                <td style="text-align: right"><?=number_format($row['SumSend'],2)?></td>
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
          $sql_select = "SELECT COUNT(CreateBy) as countRow , SUM(DiffSend) AS sumPayTotal FROM ($sql_select) AS Totals ";

          $stmt = sqlsrv_query($conn,$sql_select);
          $i=0;
          while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
            $sumSendTotal = $row1['countRow'];
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
