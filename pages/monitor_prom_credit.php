<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
$EmpID = ConvertEmpIDInsertA($_COOKIE['tsr_emp_id']);

//$con = connectDB_BigHead();

if((isset($_POST["OldContno"])) OR (isset($_POST["OldContractReferenceNo"]))){
/*
echo $EmpID."<BR>";
echo $_POST["OldContractReferenceNo"]."<BR>";
echo $_POST["OldContno"]."<BR>";
echo $_POST["OldContractStatus"]."<BR>";
echo $_POST["OldSerialNumber"]."<BR>";
echo $_POST["OldCredit"]."<BR>";
echo $_POST["OldFirstPeriod"]."<BR>";
echo $_POST["OldTradeInDiscount"]."<BR>";
echo $_POST["OldNextPeriod"]."<BR>";
*/
/*
  if (isset($_POST["chkContractStatus"])) {
    $sql_update1 = "EXEC TSRData_Source.dbo.SP_TSSM_EditStatusInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @ContractReferenceNo = '".$_POST['OldContractReferenceNo']."',
    @OldContractStatus = '".$_POST['OldContractStatus']."',
    @NewContractStatus = '".$_POST['ContractStatus']."'";

    $stmt1 = sqlsrv_query( $con, $sql_update1 );
    if( $stmt1 === false ) {

      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }
*/
}


 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
      <div class="row">
        <div class="col-md-3">
          <h4>
            พนักงานเครดิต
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> งานระบบ</a></li>
        <li class="active">มอนิเตอร์พนักงานใช้งานระบบ</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <!-- /.box-header -->
          <?php

          ?>
          <div class="box-body table-responsive no-padding">

              <table id="example2" class="table table-hover table-striped">
              <thead>
                <?php
                $sql_select ="SELECT OStatus,COUNT(EmployeeCode) AS NUM
FROM (
  SELECT DISTINCT EmployeeCode,EmployeeName
  ,(SELECT TOP 1 CASE WHEN SubDepartmentCode IS NULL THEN 'MANAGER' WHEN TeamCode IS NOT NULL THEN TeamCode WHEN SupervisorCode IS NOT NULL THEN SupervisorCode WHEN SubDepartmentCode IS NOT NULL THEN SubDepartmentCode END AS SaleCode FROM [Bighead_Mobile].[dbo].[EmployeeDetail] WHERE EmployeeCode = ED.EmployeeCode ORDER BY SubDepartmentCode,SupervisorCode,TeamCode DESC) AS SaleCode
  ,CASE WHEN L.EmpID IS NULL THEN '0' ELSE '1' END AS OStatus
    FROM [Bighead_Mobile].[dbo].[EmployeeDetail] AS ED
    LEFT JOIN assanee_mobile.dbo.loginapp AS l ON ED.EmployeeCode = L.empid AND L.lasdate_login is not null
  WHERE  ProcessType = 'Credit'

) AS C
GROUP BY OStatus ORDER By OStatus";

                $con = connectDB_BigHead();
                $stmt = sqlsrv_query($con,$sql_select);

                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  $Onum[$row["OStatus"]] = $row["NUM"];
                  }
                  $total = $Onum['1']+$Onum['0'] ;
                  $percen1 = ($Onum['1']*100)/$total;
                  $percen2 = ($Onum['0']*100)/$total;
                 ?>
                <tr>
                  <TH colspan="5" align ="center" >พนักงานเข้าระบบ จำนวน <?=$Onum['1']?> คน คิดเป็น <?=number_format($percen1)?>%</TH>
                  <TH colspan="5" align ="center">พนักงานที่ไม่เข้าระบบ จำนวน <?=$Onum['0']?> คน คิดเป็น <?=number_format($percen2)?>% </TH>
                </tr>
              </thead>
              <tbody>
                <tr>
              <?php

              $sql_select ="SELECT DISTINCT EmployeeCode,EmployeeName
,(SELECT TOP 1 CASE WHEN SubDepartmentCode IS NULL THEN 'MANAGER' WHEN TeamCode IS NOT NULL THEN TeamCode WHEN SupervisorCode IS NOT NULL THEN SupervisorCode WHEN SubDepartmentCode IS NOT NULL THEN SubDepartmentCode END AS SaleCode FROM [Bighead_Mobile].[dbo].[EmployeeDetail] WHERE EmployeeCode = ED.EmployeeCode ORDER BY SubDepartmentCode,SupervisorCode,TeamCode DESC) AS SaleCode
,CASE WHEN L.EmpID IS NULL AND L1.EmpID IS NULL THEN '0' WHEN L.EmpID IS NULL AND L1.EmpID IS NOT NULL THEN '1' ELSE '2' END StatusLogin
  FROM [Bighead_Mobile].[dbo].[EmployeeDetail] AS ED
  LEFT JOIN assanee_mobile.dbo.loginapp AS l ON ED.EmployeeCode = L.empid AND L.fcm_key!='NULL'
  LEFT JOIN assanee_mobile.dbo.loginapp AS l1 ON ED.EmployeeCode = L1.empid AND L1.lasdate_login is not null
WHERE  ProcessType = 'Credit'
ORDER BY SaleCode";

              $con = connectDB_BigHead();
              $stmt = sqlsrv_query($con,$sql_select);
              $i = 1;
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                if ($row["StatusLogin"] === '0') {
                  $trclass = "danger";
                }elseif ($row["StatusLogin"] === '1') {
                  $trclass = "warning";
                }else {
                  $trclass = "success";
                }
               ?>
                <td class="<?=$trclass?>"><a class="test" href="#" data-toggle="tooltip" title="<?=$row["EmployeeName"]?> - <?=$row["SaleCode"]?>"><?=$row["EmployeeCode"]?></a></td>
              <?php
                if ($i == 10) {
                ?>
              </TR><TR>
                <?php
                $i = 0;
                }
                $i++;
                }
               ?>
              </tr>
             </tbody>
             <tfoot>
             </tfoot>
            </table>
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
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script>
    $(function () {
    //  $("#example1").DataTable();
      $('#example2').DataTable({
        "pageLength": 30,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });
  </script>
  <style>
  /* Tooltip */
  .test + .tooltip > .tooltip-inner {
      background-color: #73AD21;
      color: #FFFFFF;
      border: 1px solid green;
      padding: 15px;
      font-size: 20px;
  }
  /* Tooltip on top */
  .test + .tooltip.top > .tooltip-arrow {
      border-top: 5px solid green;
  }
  /* Tooltip on bottom */
  .test + .tooltip.bottom > .tooltip-arrow {
      border-bottom: 5px solid blue;
  }
  /* Tooltip on left */
  .test + .tooltip.left > .tooltip-arrow {
      border-left: 5px solid red;
  }
  /* Tooltip on right */
  .test + .tooltip.right > .tooltip-arrow {
      border-right: 5px solid black;
  }
  </style>
  <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
