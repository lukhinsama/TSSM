<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
$limit_per_page = 20;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;



if (!empty($_REQUEST['contno'])) {
  //echo "contno >>>> ".$_REQUEST['contno'];
  //echo "EmpID >>>>> ".$_REQUEST['AssigneeEmpID'];
  //$conn = connectDB_BigHead();


  $sql_update = "EXEC [dbo].[usp_TSR_Assign_UpdateAssignForChangeEmployee]
  @CONTNO = N'".$_REQUEST['contno']."',
  @OldAssigneeEmpID = null,
  @NewAssigneeEmpID = N'".$_REQUEST['AssigneeEmpID']."'";
  //echo $sql;

  $conn = connectDB_BigHead();
  $stmt1 = sqlsrv_query( $conn, $sql_update );
  if( $stmt1 === false ) {
       die( print_r( sqlsrv_errors(), true));
  }
  sqlsrv_close($conn);

}
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ตรวจสอบวันนัดเก็บเงิน
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบจัดการข้อมูล</a></li>
        <li><i class="fa fa-user"></i> ระบบเก็บเงิน</li>
        <li class="active"> ตรวจสอบวันนัดเก็บเงิน </li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="row">
          <form role="form" data-toggle="validator" id="formSearchLog" name="formSearchLog" method="post" action="index.php?pages=monitordata2">

          <div class="col-md-3">
          </div>

          <div class="col-md-6">
            <label> เลือกพนังงานเก็บเงิน </label>
            <div class="input-group input-group-sm">
              <select class="form-control select2 input-group-sm" name="CreditID">
                <optgroup label="เลือกพนักงานเก็บเงิน">
                  <!--<option value="0">เลือกทั้งหมด</option>-->
                  <?php
                    $conn = connectDB_BigHead();
                    $sql = "SELECT DISTINCT em.EmpID,ca.CCode,EmployeeName
                    FROM Bighead_Mobile.dbo.Employee AS Em
                    LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed
                    ON Ed.EmployeeCode = Em.EmpID
                    LEFT JOIN TSRData_Source.dbo.CArea AS ca
                    ON ca.EmpId = Em.EmpID

                    WHERE Ed.SourceSystem = 'Credit' AND ca.ccode is not null
                    ORDER BY Em.EmpID";
                    //echo $sql;
                    $stmt = sqlsrv_query( $conn, $sql );
                    while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  ?>
                    <option value="<?=$row['CCode']?>" <?php if ((!empty($_REQUEST['CreditID'])) && ($_REQUEST['CreditID'] == $row['CCode'])) { echo "selected"; } ?>><?=$row['EmpID']?> <?=$row['EmployeeName']?> (<?=$row['CCode']?>)</option>
                  <?php
                      }
                      sqlsrv_close($conn);
                  ?>
                </optgroup>
              </select>
              <div class="input-group-btn">
                <button type="summit" class="btn btn-block btn-primary">ค้นหา</button>
              </div>
            </div>

          </div>
          <div class="col-md-3">

          </div>
          </form>
        </div>
        </BR>
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> รายละเอียด</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body table-responsive no-padding">

              <table class="table table-hover table-striped">
                <tr>
                  <th style="text-align: center">เลขที่สัญญา</th>
                  <th>ชื่อลูกค้า</th>
                  <th>งวดที่</th>
                  <th>วันที่นัดเก็บเงิน</th>
                </tr>

                <?php
                  $conn = connectDB_BigHead();
                  $sql_case = "SELECT row_number() OVER (ORDER BY onDays,ContNo asc) AS rownum,ContNo, CustomerName, PaymentPeriodNumber , CONVERT(VARCHAR(11),onDays,106) AS onDays
                FROM(
                  SELECT ContNo, CustomerName, min(PaymentPeriodNumber) as PaymentPeriodNumber , min(onDays) as onDays
                  from (
                  SELECT Con.ContNo, Dcm.CustomerName , Spp.PaymentPeriodNumber as PaymentPeriodNumber, Spp.PaymentAppointmentDate AS onDays
                  FROM [Bighead_Mobile].[dbo].[Assign] AS Ass
                  LEFT JOIN [Bighead_Mobile].[dbo].[SalePaymentPeriod] As Spp
                  ON Ass.[ReferenceID] = Spp.SalePaymentPeriodID
                  LEFT JOIN [Bighead_Mobile].[dbo].[Contract] AS Con
                  ON Con.RefNo = Spp.RefNO
                  LEFT JOIN [Bighead_Mobile].[dbo].[DebtorCustomer] AS Dcm
                  ON Dcm.CustomerID = Con.CustomerID
                  WHERE AssigneeEmpID = 'A36661'
                  AND Spp.PaymentComplete = 0
                  group by ContNo, CustomerName, Spp.PaymentAppointmentDate , PaymentPeriodNumber
                  ) as a
                  group by ContNo, CustomerName
                  ) as b";

                  //echo $sql_case;
                  $num_row = checkNumRow($conn,$sql_case);

                  $sql = "SELECT TOP $limit_per_page * FROM (".$sql_case." )AS CAMPAIGN WHERE (rownum >= '".$limit_start."' AND rownum <= '".$limit_end."')   order by CAMPAIGN.rownum";
                  //echo $sql;
                  $stmt = sqlsrv_query($conn,$sql);
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  ?>
                  <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=tranCByCs">
                  <tr>
                    <td style="text-align: center"><?=$row['ContNo']?></td>
                    <td><?=$row['CustomerName']?></td>
                    <td><?=$row['PaymentPeriodNumber']?></td>
                    <td><?=$row['onDays']?></td>
                  </tr>
                  </form>
                  <?php
                      }
                   ?>

              </table>

            </div>
          <?php
          //if (isset($startDate) || isset($endDate)) {
           echo pagelimit($_GET['pages'],$num_row,$page,"","","","");
          //}

          ?>
        </div>
        </div>

      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
