<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

if (!empty($_REQUEST['contno'])) {
/*
  $sql = "EXEC [dbo].usp_TSR_Contract_MoveMultipleContractToZone
  @OrganizationCode = 0,
  @CONTNOLIST = '".$_REQUEST['contno']."',
  @ZoneCode= '".$_REQUEST['AssigneeEmpID']."',
  @RemoveAssign = 'Y',
  @AutoAssign = 'Y'";
*/
  //echo $sql;



  $conn = connectDB_BigHead();
  $stmt1 = sqlsrv_query( $conn, $sql );
  if( $stmt === false ) {
       die( print_r( sqlsrv_errors(), true));
  }else {

    $sql_insert = "INSERT INTO TSRData_Source.dbo.Log_Tranfer_BigHead (SaleCode,Contno,CreateDate,CreateBy,ErrorStatus,errormsg) VALUES (?,?,GETDATE(),?,0,?)";
    //echo $sql_insert;

    $params = array($_REQUEST['AssigneeEmpID'],$_REQUEST['contno'],$_REQUEST['CreateBy'],$_SERVER['REMOTE_ADDR']);
    //print_r($params);


    $stmt_insert = sqlsrv_query( $conn, $sql_insert, $params);

    if( $stmt_insert === false ) {
       die( print_r( sqlsrv_errors(), true));
    }
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
            Assignงวดแรก
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>

      <!--
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบจัดการข้อมูล</a></li>
        <li><i class="fa fa-user"></i> ระบบการเก็บเงิน</li>
        <li class="active"> โอนลูกค้ารายสัญญา </li>
      </ol>
    -->

    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="col-md-8">
              <h3 class="box-title">Assignงวดแรก</h3>
            </div>
            <!--<div class="box-tools">-->
            <div class="col-md-4">
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=tranContractFirst">
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
            <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=tranCByC">
            <table class="table table-hover table-striped">
              <tr>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">เลขที่อ้างอิง</th>
                <th>ชื่อลูกค้า</th>
                <th>งวดที่</th>
                <th>จำนวนเงิน</th>
                <th>พนักงานเก็บเงิน(บ้านแดง)</th>
                <th>พนักงานเก็บเงิน(บิ๊กเฮด)</th>
                <th>แก้ไข</th>
              </tr>

              <?php
                $conn = connectDB_BigHead();

                $sql_case = "";

                //echo $sql_case;

                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>

                <tr>
                  <td style="text-align: center"><?=$row['contno']?></td>
                  <?php
                  if ($row['colorStatus'] == 'B') {
                    ?>
                    <td style="text-align: center"><p class="text-primary"><?=$row['ContRefno']?></p></td>
                    <?php
                  }else {
                    ?>
                  <td style="text-align: center"><p class="text-danger"><?=$row['ContRefno']?></p></td>
                  <?PHP
                  }
                 ?>
                  <td><?=$row['customername']?></td>
                  <td><?=$row['paymentperiodnumber']?></td>
                  <td><?=$row['netamount']?></td>
                  <td><?=$row['creditname2']?></td>
                  <td>
                    <select class="form-control select2" name="AssigneeEmpID" style="width: 100%;">
                      <optgroup label="เลือกพนักงานเก็บเงิน">
                        <?php
                          $conns = connectDB_BigHead();

                          $sql1 = "SELECT DISTINCT em.EmpID ,ed.SaleCode, em.FirstName + ' ' + em.LastName As CreditName
                          FROM Bighead_Mobile.dbo.Employee AS Em WITH(NOLOCK)
                          LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed WITH(NOLOCK)
                          ON Ed.EmployeeCode = em.empID AND Ed.SaleCode is not null
                          WHERE SourceSystem != 'Sale' ORDER BY EMPID";

                          //echo $sql;
                          $stmt1 = sqlsrv_query( $conns, $sql1 );
                          while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                        ?>
                          <option value="<?=$row1['SaleCode'];?>" <?php if($row1['SaleCode']==$row['salecode']){ echo "selected";}?>>(<?=$row1['SaleCode']?>) <?=$row1['CreditName']?></option>
                        <?php
                            }
                            sqlsrv_close($conns);
                        ?>
                      </optgroup>
                  </select>

                  </td>
                  <td style="text-align: center">
                    <input type="hidden" name="contno" value="<?=$row['contno'];?>">
                    <input type="hidden" name="CreateBy" value="<?=$_COOKIE['tsr_emp_id'];?>">
                    <button type="summit" class="btn btn-block btn-warning" <?php /*if ($row['disStatus'] >= $row['netamount']) {echo "disabled";}*/?>> บันทึก </button></td>
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
