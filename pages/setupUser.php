<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

if (isset($_REQUEST['empid']) AND isset($_REQUEST['username']) AND isset($_REQUEST['Email']) AND isset($_REQUEST['permit'])) {
  $con = connectDB_BigHead();
      $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_M_User (emp_id,user_id,ad_accountname,permission,userAdd,stamptime,matching)
      VALUES (?,?,?,?,?,GETDATE(),?)";
      $params = array($_REQUEST['empid'],$_REQUEST['username'],$_REQUEST['Email'],$_REQUEST['permit'],$_COOKIE['tsr_emp_id'],$_REQUEST['Matching']);
      $stmt_insert = sqlsrv_query( $con, $sql_insert, $params);
      if( $stmt_insert === false ) {
         die( print_r( sqlsrv_errors(), true));
      }
       sqlsrv_close($con);
}

if (isset($_REQUEST['chackEdit'])) {
    $pointer = $_REQUEST['chackEdit'];
    if ($_REQUEST['updatematch'.$pointer.''] == null) {
      $sql_update = "UPDATE TSR_Application.dbo.TSS_M_User SET permission = ".$_REQUEST['permission'.$pointer.'']." ,matching = NULL , stamptime = GETDATE() WHERE id = ".$pointer."";
    }else {
      $sql_update = "UPDATE TSR_Application.dbo.TSS_M_User SET permission = ".$_REQUEST['permission'.$pointer.'']." ,matching = ".$_REQUEST['updatematch'.$pointer.'']." , stamptime = GETDATE() WHERE id = ".$pointer."";
    }

    //echo $sql_update ;

    $con = connectDB_BigHead();
    $stmt_insert = sqlsrv_query( $con, $sql_update);
    if( $stmt_insert === false ) {
    die( print_r( sqlsrv_errors(), true));
    }
    sqlsrv_close($con);

 }

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">

          <div class="box-header">
            <div class="col-md-8">
              <h3 class="box-title">จัดการข้อมูลสมาชิก</h3>
              <?php
                /*
                $con = connectDB_BigHead();
                $sql = "SELECT ORIGINAL_LOGIN() AS OLogin";
                $stmt = sqlsrv_query($con,$sql);
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                  echo "Login = ".$row['OLogin'];
                }
                sqlsrv_close($con);
                */
               ?>
            </div>
            <div class="col-md-4">
            </div>
            <div class="row">
              <div class="col-md-12">
              <form role="form" data-toggle="validator" id="formAddUser" name="formAddUser" method="post" action="index.php?pages=SetUpUser">
            <div class="box-body table-responsive no-padding">


                  <table class="table table-hover table-striped">
                    <TR>
                      <TH>รหัสพนักงาน</TH>
                      <TH>ชื่อผู้ใช้</TH>
                      <TH>Email</TH>
                      <TH>Matching</TH>
                      <TH>สิทธ์</TH>
                      <TH>SAVE</TH>
                    </TR>
                    <tr>
                      <td><input type="text" class="form-control" name="empid" size="5" placeholder="รหัสพนักงาน"></td>
                      <td><input type="text" class="form-control" name="username" size="10" placeholder="ชื่อผู้ใช้"></td>
                      <td><input type="text" class="form-control" name="Email" size="25" placeholder="Email"></td>
                      <td><input type="text" class="form-control" name="matching" size="5" placeholder="Matching"></td>
                      <td><select class="form-control select2 group-sm" name="permit" >
                      <?php
                      $con = connectDB_BigHead();
                      $sql_select2 = "SELECT id_permit,permitName FROM TSR_Application.dbo.TSSM_GroupUserPermit";

                      //echo $sql_case;
                      $stmt1 = sqlsrv_query($con,$sql_select2);
                      while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                       ?>
                      <option value="<?=$row1['id_permit']?>"><?=$row1['permitName']?></option>
                      <?php
                        }
                         sqlsrv_close($con);
                       ?>
                    </select></td>
                      <td><button type="summit" class="btn btn-block btn-primary"> บันทึก </button></td>
                    </tr>
                  </table>

                </div>
                </form>
            </div>

            </div>
          </div>


          <div class="box-body table-responsive no-padding">
            <form role="form" data-toggle="validator"name="formUpdateUser" method="post" action="index.php?pages=SetUpUser">
            <table id="example2" class="table table-hover table-striped">
              <thead>

              <tr>
                <th style="text-align: center">รหัสพนักงาน</th>
                <th style="text-align: center">ชื่อผู้ใช้</th>
                <th>Email</th>
                <th>Matching</th>
                <th>สิทธ์</th>
                <th>แก้ไข</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $con = connectDB_BigHead();

                $sql = "SELECT id,emp_id,user_id,ad_accountname AS Email,permission,ISNULL(matching,'') as matching,isnull(permitName,'') AS permitName
  FROM TSR_Application.dbo.TSS_M_User AS MU
  LEFT JOIN TSR_Application.dbo.TSSM_GroupUserPermit AS UP ON MU.permission = UP.id_permit order by MU.id";

                //echo $sql;

                $stmt = sqlsrv_query($con,$sql);
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                ?>

                <tr>
                  <td style="text-align: center"><?=$row['emp_id']?></td>
                  <td style="text-align: center"><?=$row['user_id']?></td>
                  <td><?=$row['Email']?></td>
                  <td><input type="text" class="form-control" name="updatematch<?=$row['id']?>" size="5" value="<?=$row['matching']?>"></td>
                  <td>
                    <select class="form-control select2 group-sm" name="permission<?=$row['id']?>">
                    <?php
                    $sql_select2 = "SELECT id_permit,permitName FROM TSR_Application.dbo.TSSM_GroupUserPermit";

                    //echo $sql_case;
                    $stmt1 = sqlsrv_query($con,$sql_select2);
                    while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                     ?>
                    <option value="<?=$row1['id_permit']?>" <?PHP if($row['permission'] == $row1['id_permit']) { ECHO "selected";} ?> ><?=$row1['permitName']?></option>
                    <?php
                      }
                     ?>
                  </select>
                  </td>
                  <td style="text-align: center">
                    <!--<button type="button" class="btn btn-block btn-warning">แก้ไข</button>-->
                    <!--<button type="summit" class="btn btn-block btn-warning"> บันทึก </button></td>-->
                    <input type="radio" name="chackEdit" value="<?=$row['id']?>"> แก้ไข
                </tr>
                <?php
              }
                 ?>

              </tbody>
              <tfoot>
              </tfoot>
              </table>
              <button type="summit" class="btn btn-block btn-warning"> บันทึก </button>
          </form>
          </div>

          <?php
               sqlsrv_close($con);
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
  <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">แก้ไขข้อมูล</h4>
          </div>
          <div class="modal-body">
            <p>Some text in the modal.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">ตกลง</button>
          </div>
        </div>

      </div>
    </div>
<script>
  $(function() {
    $( "#models" ).autocomplete({
      //source: 'search.php'
      source: '../include/inc-autocom.php?types=counto'
    });
  });
</script>
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
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false
    });
  });
</script>
