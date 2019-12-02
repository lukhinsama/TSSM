<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

if (!empty($_REQUEST['serialNo'])) {

  //echo $sql;

  $conn = connectDB_BigHead();

    $sql_insert = "UPDATE Bighead_Mobile.dbo.ProductStock SET ProductSerialNumber = ?,LastUpdateBy = ? ,LastUpdateDate = GETDATE() WHERE ProductSerialNumber = ?";
    //echo $sql_insert;
    $serialNo = $_REQUEST['serialNo']."-";

    $params = array($serialNo,$_COOKIE['tsr_emp_id'],$_REQUEST['serialNo']);
    //print_r($params);


    $stmt_insert = sqlsrv_query( $conn, $sql_insert, $params);

    if( $stmt_insert === false ) {
       die( print_r( sqlsrv_errors(), true));
    }
  
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
            ดึงเครื่องกลับ
          </h4>
        </div>
        <div class="col-md-3">
        </div>
        <div class="col-md-4">
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="col-md-8">
              <h3 class="box-title">ดึงเครื่องกลับ</h3>
            </div>
            <!--<div class="box-tools">-->
            <div class="col-md-4">
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=reuseproduct">
              <div class="input-group input-group-sm">
                <input type="text" name="serialnumber" class="form-control pull-right" id="serialnumber" required placeholder="ค้นหา เลขเครื่อง" value="<?php if(!empty($_REQUEST['serialnumber'])){ echo $_REQUEST['serialnumber'];}?>">

                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
              </form>
            </div>
          </div>
          <!-- /.box-header -->
          <?php
            if (isset($_REQUEST['serialnumber'])) {
          ?>
          <div class="box-body table-responsive no-padding">
            <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=reuseproduct">
            <table class="table table-hover table-striped">
              <tr>
                <th style="text-align: center">เลขเครื่อง</th>
                <th style="text-align: center">ทีม</th>
                <th style="text-align: center">สถานะ</th>
                <th style="text-align: center">สแกนโดย</th>
                <th style="text-align: center">ดึงเครื่อง</th>
              </tr>

              <?php
                $conn = connectDB_BigHead();

                $sql_case = "SELECT ProductSerialNumber, OrganizationCode, ProductID, Type, TeamCode, Status, ImportDate, ScanDate, ScanByTeam, SyncedDate, CreateDate, CreateBy, LastUpdateDate, LastUpdateBy FROM Bighead_Mobile.dbo.ProductStock WHERE        (ProductSerialNumber = '".$_REQUEST['serialnumber']."')";

                //echo $sql_case;

                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>

                <tr>
                  <td><?=$row['ProductSerialNumber']?></td>
                  <td><?=$row['TeamCode']?></td>
                  <td><?=$row['Status']?></td>
                  <td><?=$row['CreateBy']?></td>
                  <td></td>
                  <td style="text-align: center">
                    <input type="hidden" name="serialNo" value="<?=$row['ProductSerialNumber'];?>">
                    <button type="summit" class="btn btn-block btn-warning"> ดีงเครื่อง </button></td>
                </tr>
                <?php
                  }
                  sqlsrv_close($conn);
                 ?>


            </table>
          </form>
          </div>
          <?php
              }
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

<script>
  $(function() {
    $( "#models" ).autocomplete({
      //source: 'search.php'
      source: '../include/inc-autocom.php?types=counto'
    });
  });
</script>
