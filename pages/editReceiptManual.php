<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

if (!empty($_REQUEST['ID'])) {

  //echo $sql;

  $conn = connectDB_BigHead();
  $EmpID = "A".substr($_COOKIE['tsr_emp_id'],1);
    //INSERT LOG
    $sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_Log_All (PageLog,OldData,NewData,StatusData,UserEdit) VALUES ('editReceiptManual',?,?,?,?)";
    $NewData = $_REQUEST['ManualVolumeNo']."_".$_REQUEST['ManualRunningNo'];
    $params = array($_REQUEST['OldData'],$NewData,$_REQUEST['StatusData'],$EmpID);
    $stmt_insert = sqlsrv_query( $conn, $sql_insert, $params);
    if( $stmt_insert === false ) {
       die( print_r( sqlsrv_errors(), true));
    }else {
      //UPDATE
      $sql_update = "UPDATE Bighead_Mobile.dbo.ManualDocument SET ManualVolumeNo = ?,ManualRunningNo = ? WHERE DocumentID = ?";
      $params = array($_REQUEST['ManualVolumeNo'],$_REQUEST['ManualRunningNo'],$_REQUEST['ID']);
      $stmt_update = sqlsrv_query( $conn, $sql_update, $params);
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
            แก้ไขเลขที่ใบเสร็จมือ
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
              <h3 class="box-title">แก้ไขเลขที่ใบเสร็จมือ</h3>
            </div>
            <!--<div class="box-tools">-->
            <div class="col-md-4">
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=editReceiptManual">
              <div class="input-group input-group-sm">
                <input type="text" name="ReceiptCode" class="form-control pull-right" id="ReceiptCode" required placeholder="ค้นหา เลขที่ใบเสร็จ" value="<?php if(!empty($_REQUEST['ReceiptCode'])){ echo $_REQUEST['ReceiptCode'];}?>">

                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
              </form>
            </div>
          </div>
          <!-- /.box-header -->
          <?php
            if (isset($_REQUEST['ReceiptCode'])) {
          ?>
          <div class="box-body table-responsive no-padding">
            <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=editReceiptManual">
            <table class="table table-hover table-striped">
              <tr>
                <th style="text-align: center">เลขที่ใบเสร็จเครื่อง</th>
                <th style="text-align: center">เลขที่เล่มใบเสร็จมือ</th>
                <th style="text-align: center">เลขที่ใบเสร็จมือ</th>
                <th style="text-align: center">เวลาบันทึกใบเสร็จมือ</th>
                <th style="text-align: center">แก้ไข</th>
                <th style="text-align: center">ลบ</th>
              </tr>

              <?php
                $conn = connectDB_BigHead();

                $sql_case = "SELECT M.DocumentID,M.ManualVolumeNo,M.ManualRunningNo,R.ReceiptCode,R.DatePayment
                ,CONVERT(varchar(20),M.CreatedDate,105) +' '+ CONVERT(varchar(5),M.CreatedDate,108) as CreatedDate
FROM Bighead_Mobile.dbo.ManualDocument AS M
INNER JOIN Bighead_Mobile.dbo.Receipt AS R ON M.DocumentNumber = R.ReceiptID AND M.isActive = 1
WHERE R.ReceiptCode = '".$_REQUEST['ReceiptCode']."'";

                //echo $sql_case;

                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>

                <tr>
                  <td style="text-align: center"><?=$row['ReceiptCode']?></td>
                  <td style="text-align: center">
                    <input class="form-control" type="number" name="ManualVolumeNo" maxlength="5" size="5" VALUE ="<?=$row['ManualVolumeNo']?>"></td>
                  <td style="text-align: center">
                    <input class="form-control" type="number" name="ManualRunningNo" maxlength="5" size="5" VALUE ="<?=$row['ManualRunningNo']?>"></td>
                  <td style="text-align: center"><?=DateTimeThai($row['CreatedDate'])?> น.</td>
                  <td style="text-align: center">
                    <input type="hidden" name="ID" value="<?=$row['DocumentID'];?>">
                    <input type="hidden" name="StatusData" value="EDIT">
                    <input type="hidden" name="OldData" value="<?=$row['DocumentID'];?>_<?=$row['ManualVolumeNo'];?>_<?=$row['ManualRunningNo']?>">
                    <button type="summit" class="btn btn-block btn-warning"> แก้ไข </button></td>
                  <td style="text-align: center"><a href="https://tssm.thiensurat.co.th/pages/deleteReceipManual.php?ID=<?=$row['DocumentID'];?>&OldData=<?=$row['DocumentID'];?>_<?=$row['ManualVolumeNo'];?>_<?=$row['ManualRunningNo']?>"><button type="button" class="btn btn-block btn-danger"> ลบ </button></a>
                  </td>
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
  function myFunction() {
      confirm("แน่ใจนะ ว่าจะลบข้อมูลนี้ !!");
  }
  </script>
<script>
  $(function() {
    $( "#models" ).autocomplete({
      //source: 'search.php'
      source: '../include/inc-autocom.php?types=counto'
    });
  });
  $('#myLink').click(function(){ MyFunction(); return false; });
</script>
