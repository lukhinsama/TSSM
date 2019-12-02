<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);


if ((!empty($_REQUEST['empname'])) AND (!empty($_REQUEST['emplname'])) AND (!empty($_REQUEST['BranchID'])) AND (!empty($_REQUEST['CodeID']))) {

  $EmpID = "A".substr($_COOKIE['tsr_emp_id'],1);

  $BranchCode = explode("_",$_REQUEST['BranchCode']);

  $conn = connectDB_BigHead();
  $sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_SaleOuting (teamSaleCode,BranchCode,SupCode,StartDate,EndDate,EmpID,AddDate) VALUES (?,?,?,?,?,?,GETDATE())";
  //echo $sql_insert;
  $params = array($_REQUEST['TeamCode'],$BranchCode['0'],$BranchCode['1'],$startDate,$endDate,$EmpID);
  //print_r($params);
  $stmt_insert = sqlsrv_query( $conn, $sql_insert, $params);
  sqlsrv_close($conn);
}
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <div class="col-md-3">
          <h4>
            เพิ่มพนักงาน ตำแหน่งช่างบริการ
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>


      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบจัดการข้อมูล</a></li>
        <li><i class="fa fa-user"></i> ระบบขายพักแรม</li>
        <li class="active"> เพิ่มทีมขายพักแรม </li>
      </ol>


    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=addEmpService">
          <div class="box-header">
            <div class="col-md-3">
            <input type="text" class="form-control" name="empname" placeholder="กรุณากรอกชื่อ" required>
            </div>
            <!--<div class="box-tools">-->

            <div class="col-md-3">
                <input type="text" class="form-control" name="emplname" placeholder="กรุณากรอกนามสกุล" required>
            </div>
            <div class="col-md-3">
              <select class="form-control select2" name="BranchID" style="width: 100%;">
                <optgroup label="เลือกสาขา">
                  <option value="0">- กรุณาเลือกสาขา -</option>
                  <?php
                    $conns = connectDB_BigHead();

                    $sql1 = "SELECT id,BranchName,BranchID FROM TSRData_Source.dbo.TSSM_BranchData";

                    //echo $sql;
                    $stmt1 = sqlsrv_query( $conns, $sql1 );
                    while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                  ?>
                    <option value="<?=$row1['BranchID'];?>"><?=$row1['BranchName']?></option>
                  <?php
                      }
                      sqlsrv_close($conns);
                  ?>
                </optgroup>
              </select>
            </div>
            <div class="col-md-3">
              <div class="input-group input-group">

                <select class="form-control select2" name="CodeID" style="width: 100%;">
                  <optgroup label="เลือกสาขา">
                    <option value="0">- กรุณาเลือกเขตบริการ -</option>
                    <?php
                      $conns = connectDB_BigHead();

                      $sql1 = "SELECT DISTINCT CodeID FROM TSRData_Source.dbo.TSSM_Technician_Area AS TA ORDER BY CodeID";

                      //echo $sql;
                      $stmt1 = sqlsrv_query( $conns, $sql1 );
                      while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                    ?>
                      <option value="<?=$row1['CodeID'];?>"><?=$row1['CodeID']?></option>
                    <?php
                        }
                        sqlsrv_close($conns);
                    ?>
                  </optgroup>
                </select>

                <div class="input-group-btn">
                  <button type="submit" class="btn btn-primary">เพิ่ม</button>
                </div>
              </div>
            </div>
          </div>
          </form>
          <!-- /.box-header -->
          <?php
          //  if ((isset($_REQUEST['searchText']))) {
          ?>

          <div class="box-body table-responsive no-padding">
            <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=tranCByC">
            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">ชื่อ - นามสกุล</th>
                <th style="text-align: center">สาขาที่สังกัด</th>
                <th style="text-align: center">เขตพื่นที่</th>
                <th style="text-align: center">สถานะพนักงาน</th>
                <th style="text-align: center">เปลี่ยนสถานะพนักงาน</th>
              </tr>
              </thead>
              <tbody>
              <?php
                $conn = connectDB_BigHead();

                $sql_case = "SELECT TP.id,TP.CodeID,TP.BranchID,FirstName,LastName,TP.StatusCode,BranchName FROM TSRData_Source.dbo.TSSM_Technician_Profile AS TP INNER JOIN TSRData_Source.dbo.TSSM_BranchData AS BD ON BD.BranchID = TP.BranchID ORDER BY TP.id DESC";

                //echo $sql_case;

                $stmt = sqlsrv_query($conn,$sql_case);
                $i = 1;
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  if ($row['StatusCode'] === 0) {
                    // code...
                    $status = 'ลาออก';
                  }else {
                    // code...
                    $status = 'พนักงาน';
                  }
                ?>

                <tr>
                  <td style="text-align: center"><?=$i++?></td>
                  <td><?=$row['FirstName']?> <?=$row['LastName']?></td>
                  <td style="text-align: center"><?=$row['BranchName']?></td>
                  <td style="text-align: center"><?=$row['CodeID']?></td>
                  <td style="text-align: center"><?=$status?></td>
                  <td style="text-align: center"><a href="pages/editEmpService.php?id=<?=$row['id']?>" class="btn btn-block btn-warning"> แก้ไขสถานะพนักงาน </a></td></td>
                </tr>
                <?php
                  }
                  sqlsrv_close($conn);
                 ?>

               </tbody>
               <tfoot>
               </tfoot>
            </table>
          </form>
          </div>
          <?php
            //  }
           ?>
          <!-- /.box-body -->

        </div>
        <!-- /.box -->
      </div>
    </div>
    <!-- /.row -->
  </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script>
    $(function () {
      $('#example2').DataTable({
        "pageLength": 10,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });
    /*
  function TeamSelect(){
        if($("#TeamCode").val() != "0"){  //your desired condition
          document.getElementById('div1').style.display='';
          //alert("0");
      }else {
          document.getElementById('div1').style.display='none';
          //alert("!0");
      }
    }
    */
  </script>
  <script>

			$(function(){
				//แสดงข้อมูล อำเภอ  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
				$("#TeamCode").change(function(){
          //alert("!0");
					//กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
					var TeamCode = $(this).val();
					$.ajax({
						url:"pages/getdata_teamsale.php",//url:"get_data.php",
						dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
						data:{TeamCode:TeamCode},//ส่งค่าตัวแปร province_id เพื่อดึงข้อมูล อำเภอ ที่มี province_id เท่ากับค่าที่ส่งไป
						success:function(data){
							//กำหนดให้ข้อมูลใน #amphur เป็นค่าว่าง
							$("#empTable").text("");
							//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
							$.each(data, function( index, value ) {
								//แทรก Elements ข้อมูลที่ได้  ใน id amphur  ด้วยคำสั่ง append
								  $("#empTable").append("<tr><td>"+ value.salecode +"</td><td>"+ value.empName +"</td><td>"+ value.teamcode +"</td><td>"+ value.empName +"</td></tr></tbody>");
							});
						}
					});
				});
			});
	</script>
