<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);


if ((!empty($_REQUEST['TeamCode'])) OR (!empty($_REQUEST['BranchCode']))) {

  $startDate = DateEng($_REQUEST['startDate'])." 00:00" ;
  $endDate = DateEng($_REQUEST['endDate'])." 23:59";
  $EmpID = "A".substr($_COOKIE['tsr_emp_id'],1);

  echo $_REQUEST['chk'];

  $BranchCode = explode("_",$_REQUEST['BranchCode']);

  $conn = connectDB_BigHead();
  $sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_SaleOuting (teamSaleCode,BranchCode,SupCode,StartDate,EndDate,EmpID,AddDate) VALUES (?,?,?,?,?,?,GETDATE())";
  //echo $sql_insert;
  $params = array($_REQUEST['TeamCode'],$BranchCode['0'],$BranchCode['1'],$startDate,$endDate,$EmpID);
  //print_r($params);
  //$stmt_insert = sqlsrv_query( $conn, $sql_insert, $params);
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
            เพิ่มทีมขายพักแรม
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
          <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=addOutingTrip">
          <div class="box-header">
            <div class="col-md-2">
              <h3 class="box-title">เพิ่มทีมขายพักแรม</h3>
            </div>
            <!--<div class="box-tools">-->

            <div class="col-md-3">
              <select class="form-control select2" name="TeamCode" id="TeamCode" style="width: 100%;" onchange="TeamSelect()">
                <optgroup label="เลือกทีมขาย">
                  <option value="0" selected>- กรุณาเลือกทีมขาย -</option>
                  <?php
                    $conns = connectDB_BigHead();

                    $sql1 = "SELECT EmployeeCode,TeamCode,EmployeeName,TeamName
FROM Bighead_Mobile.dbo.EmployeeDetail
WHERE SourceSystem = 'Sale' AND TeamCode IS NOT NULL AND PositionName = 'หัวหน้าทีม'
ORDER BY TeamCode,ProcessType";

                    //echo $sql;
                    $stmt1 = sqlsrv_query( $conns, $sql1 );
                    while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                  ?>
                    <option value="<?=$row1['TeamCode'];?>"><?=$row1['TeamName']?> - <?=$row1['EmployeeName']?></option>
                  <?php
                      }
                      sqlsrv_close($conns);
                  ?>
                </optgroup>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-control select2" name="BranchCode" style="width: 100%;">
                <optgroup label="เลือกสาขา">
                  <option value="0">- กรุณาเลือกสาขา -</option>
                  <?php
                    $conns = connectDB_BigHead();

                    $sql1 = "SELECT DISTINCT EmployeeCode,EmployeeName,SupervisorName,SupervisorHeadName,SubDepartmentName,ProcessType
FROM Bighead_Mobile.dbo.EmployeeDetail
WHERE SourceSystem = 'Sale'
AND EmployeeName = SupervisorHeadName
ORDER BY ProcessType,SubDepartmentName,SupervisorName";

                    //echo $sql;
                    $stmt1 = sqlsrv_query( $conns, $sql1 );
                    while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                  ?>
                    <option value="<?=$row1['EmployeeCode'];?>_<?=$row1['SupervisorName'];?>"><?=$row1['SupervisorName']?> - <?=$row1['EmployeeName']?></option>
                  <?php
                      }
                      sqlsrv_close($conns);
                  ?>
                </optgroup>
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
                  <button type="submit" class="btn btn-primary">เพิ่ม</button>
                </div>
              </div>
            </div>
            </form>
          </div>
          <!-- /.box-header -->
          <?php
          //  if ((isset($_REQUEST['searchText']))) {
          ?>
          <!--<div class="box-body table-responsive no-padding" id="div1" style="display:none">-->
          <div class="box-body table-responsive no-padding" id="div1" style="display:">
            <table class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">รหัสพนักงานขาย</th>
                <th style="text-align: center">ชื่อพนักงานขาย</th>
                <th style="text-align: center">ทีม</th>
                <th style="text-align: center">เลือก</th>
              </tr>
              </thead>
              <tbody id="empTable">
               </tbody>
               <tfoot>
               </tfoot>
            </table>
          </div>
          </form>
          <div class="box-body table-responsive no-padding">
            <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=tranCByC">
            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">วันที่พักแรม</th>
                <th style="text-align: center">ชื่อทีมพักแรม</th>
                <th style="text-align: center">สาขาที่ส่งสัญญา</th>
                <th style="text-align: center">ยกเลิก</th>
              </tr>
              </thead>
              <tbody>
              <?php
                $conn = connectDB_BigHead();

                $sql_case = "SELECT id,teamSaleCode,BranchCode,CONVERT(varchar(20),StartDate,105) +' '+ CONVERT(varchar(5),StartDate,108) AS StartDate,CONVERT(varchar(20),EndDate,105) +' '+ CONVERT(varchar(5),EndDate,108) AS EndDate,EmpID,AddDate,
(SELECT DISTINCT SupervisorName FROM Bighead_Mobile.dbo.EmployeeDetail WHERE SOT.BranchCode = EmployeeCode AND SupervisorName IS NOT NULL) AS BranchName FROM TSRData_Source.dbo.TSSM_SaleOuting AS SOT WHERE SOT.Active = 1 AND GETDATE() < SOT.EndDate ORDER BY SOT.EndDate";

                //echo $sql_case;

                $stmt = sqlsrv_query($conn,$sql_case);
                $i = 1;
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>

                <tr>
                  <td style="text-align: center"><?=$i++?></td>
                  <td style="text-align: center"><?=DateTimeThai($row['StartDate'])?> - <?=DateTimeThai($row['EndDate'])?></td>
                  <td style="text-align: center"><?=$row['teamSaleCode']?></td>
                  <td style="text-align: center"><?=substr($row['BranchName'],27)?></td>
                  <td style="text-align: center"><a href="pages/deleteOutingTrip.php?id=<?=$row['id']?>" class="btn btn-block btn-danger"> ยกเลิก </a></td></td>
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
        "pageLength": 20,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });

  function TeamSelect(){
        if($("#TeamCode").val() != "0"){  //your desired condition
          document.getElementById('div1').style.display='';
          //alert("0");
      }else {
          document.getElementById('div1').style.display='none';
          //alert("!0");
      }
    }

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
								  $("#empTable").append("<tr><td>"+ value.salecode +"</td><td>"+ value.empName +"</td><td>"+ value.teamcode +"</td><td><input type=\"checkbox\" name=\"chk\" value = \""+ value.salecode +"\"></td></tr></tbody>");
							});
						}
					});
				});
			});
	</script>
