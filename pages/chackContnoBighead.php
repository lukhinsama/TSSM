<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
$limit_per_page = 100;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;

if ((isset($_REQUEST['searchText']))) {
  $top = "";
  $searchText = $_REQUEST['searchText'];
  $where = "WHERE co.contno = '".$_REQUEST['searchText']."'";
}else {
  $top = "TOP 1";
  $where = "WHERE co.contno = '11517550'";
}


if (!empty($_REQUEST['refno'])) {
  /*
  $sql = "EXEC Bighead_Mobile.[dbo].usp_TSR_Contract_ChangeToNormal
  @ContNo = N'".$_REQUEST['contno']."' , @RefNo = N'".$_REQUEST['refno']."'";
  */
  $sql = "
      Declare @ContNo varchar(10)
      Declare @RefNo varchar(80)
      Declare @Status varchar(10)
      Declare @User varchar(10)
      Declare @isActive int

      set @ContNo = '".$_REQUEST['contno']."'
      set @RefNo= '".$_REQUEST['refno']."'
      set @User= '".$_COOKIE['tsr_emp_id']."'

      SELECT  @isActive = isActive FROM Bighead_Mobile.dbo.Contract WHERE  CONTNO = @ContNo AND RefNo = @RefNo

      IF (@isActive = 1)
      BEGIN
      UPDATE Bighead_Mobile.dbo.Contract SET isActive = 0 WHERE RefNo = @RefNo
      END
      ELSE
      BEGIN
      UPDATE Bighead_Mobile.dbo.Contract SET isActive = 1 WHERE RefNo = @RefNo
      END

      INSERT INTO TSRData_Source.dbo.TSSM_Log_UpdateContractIsActive (UserUpdate,DateUpdate,RefNo,OldDataIsActive) VALUES (@User,GETDATE(),@RefNo,@isActive)
      ";

  $conn = connectDB_BigHead();
  $stmt1 = sqlsrv_query( $conn, $sql );
  if( $stmt === false ) {
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
            ตรวจสอบข้อมูลสัญญา
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>


      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบจัดการข้อมูล</a></li>
        <li><i class="fa fa-user"></i> ระบบการเก็บเงิน</li>
        <li class="active"> ตรวจสอบข้อมูลสัญญา </li>
      </ol>


    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="col-md-8">
              <h3 class="box-title">ข้อมูลเลขที่สัญญา</h3>
            </div>
            <!--<div class="box-tools">-->
            <div class="col-md-4">
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=chackContnoBighead">
              <div class="input-group input-group-sm">
                <input type="text" name="searchText" class="form-control pull-right" id="counto" required placeholder="ค้นหา เลขที่สัญญา" value="<?php if(!empty($searchText)){ echo $searchText;}?>">

                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
              </form>
            </div>
          </div>
          <!-- /.box-header -->
          <?php
            if ((isset($_REQUEST['searchText']))) {
          ?>
          <div class="box-body table-responsive no-padding">
            <!--<form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=chackContnoBighead">-->
            <table class="table table-hover table-striped">
              <tr>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">เลขที่อ้างอิง</th>
                <th style="text-align: center">เลขเครื่อง</th>
                <th>ชื่อลูกค้า</th>
                <th>สถานะสัญญา</th>
                <th>เขตเก็บเงิน</th>
                <th>พนักงานเก็บเงิน</th>
                <th>สถานะ</th>
                <th>เปลี่ยนสถานะเป็น</th>
              </tr>

              <?php
                $conn = connectDB_BigHead();
                $sql_case = "SELECT RefNo,CONTNO,STATUS,isActive,SERVICE,CustomerName,EmployeeName
                ,co.ContractReferenceNo AS ContRefno,ProductSerialNumber
                ,CASE WHEN isMigrate = 0 then 'B' WHEN isMigrate = 1 AND LEN(co.RefNo) > 20 THEN 'M' ELSE 'R' END as colorStatus
                FROM [Bighead_Mobile].[dbo].[Contract] AS Co WITH(NOLOCK)
                LEFT JOIN TSRData_Source.dbo.vw_DebtorCustomer AS Dc WITH(NOLOCK)
                ON Co.CustomerID = Dc.CustomerID
                LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed WITH(NOLOCK)
                ON Co.SERVICE = Ed.SaleCode $where";

                //echo $sql_case;
                //$num_row = checkNumRow($conn,$sql_case);

              //  $sql = "SELECT TOP $limit_per_page * FROM (".$sql_case." )AS CAMPAIGN WHERE (rownum >= '".$limit_start."' AND rownum <= '".$limit_end."')   order by CAMPAIGN.rownum";
                //echo $sql;
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>

                <tr>
                  <td style="text-align: center"><?=$row['CONTNO']?></td>
                  <?php
                  if ($row['colorStatus'] == 'B') {
                    ?>
                    <td style="text-align: center"><p class="text-primary"><?=$row['ContRefno']?></p></td>
                    <?php
                  }else if($row['colorStatus'] == 'M'){
                    ?>
                  <td style="text-align: center"><p class="text-success"><?=$row['ContRefno']?></p></td>
                  <?PHP
                  }else {
                    ?>
                  <td style="text-align: center"><p class="text-danger"><?=$row['ContRefno']?></p></td>
                  <?PHP
                  }
                 ?>
                  <td style="text-align: center"><?=$row['ProductSerialNumber']?></td>
                  <td><?=$row['CustomerName']?></td>
                  <td><?=$row['STATUS']?></td>
                  <td><?=$row['SERVICE']?></td>
                  <td><?=$row['EmployeeName']?></td>
                  <td><?php if (($row['isActive'] == "1") || ($row['isActive'] == true)){echo "ใช้งาน";}else{echo "ไม่ใช้งาน";};?></td>
                  <!--
                  <td style="text-align: center">
                    <input type="hidden" name="contno" value="<?=$row['CONTNO'];?>">
                    <input type="hidden" name="refno" value="<?=$row['RefNo'];?>">
                    <button type="summit" class="btn btn-block btn-danger"><?php if (($row['isActive'] == "1") || ($row['isActive'] == true)){echo "ไม่ใช้งาน";}else{echo "ใช้งาน";};?></button>
                  </td>
                -->
                  <td>
                    <a href="index.php?pages=chackContnoBighead&refno=<?=$row['RefNo'];?>&searchText=<?=$row['CONTNO'];?>&contno=<?=$row['CONTNO'];?>" class="btn btn-block btn-danger" role="button"><?php if (($row['isActive'] == "1") || ($row['isActive'] == true)){echo "ไม่ใช้งาน";}else{echo "ใช้งาน";};?></a>
                  </td>
                </tr>
                <?php
                  }
                  //sqlsrv_close($conn);
                 ?>


            </table>
          <!--</form>-->
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
