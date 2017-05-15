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

  /*
  $sql_update = "EXEC [dbo].[usp_TSR_Assign_UpdateAssignForChangeEmployee]
  @CONTNO = N'".$_REQUEST['contno']."',
  @OldAssigneeEmpID = null,
  @NewAssigneeEmpID = N'".$_REQUEST['AssigneeEmpID']."'";
  */
  //echo $sql;


  $sql_update = "EXEC [dbo].usp_TSR_Contract_MoveMultipleContractToZone
  @OrganizationCode = 0,
  @CONTNOLIST = '".$_REQUEST['contno']."',
  @ZoneCode= '".$_REQUEST['AssigneeEmpID']."',
  @RemoveAssign = 'Y',
  @AutoAssign = 'Y'";


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
        โอนลูกค้าร้านค้า
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบจัดการข้อมูล</a></li>
        <li><i class="fa fa-user"></i> ระบบเก็บเงิน</li>
        <li class="active">โอนลูกค้าร้านค้า</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!--
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearchLog" name="formSearchLog" method="post" action="index.php?pages=monitordata2">

        <div class="col-md-3">
        </div>

        <div class="col-md-6">
          <label> เลือกพนังงานเก็บเงิน </label>
          <div class="input-group input-group-sm">
            <select class="form-control select2 input-group-sm" name="CreditID">
              <optgroup label="เลือกพนักงานเก็บเงิน">

                <?php
                /*
                  $conn = connectDB_BigHead();
                  $sql = "SELECT DISTINCT em.EmpID,ca.CCode,EmployeeName
                  FROM Bighead_Mobile.dbo.Employee AS Em
                  LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed
                  ON Ed.EmployeeCode = Em.EmpID
                  LEFT JOIN TSRData_Source.dbo.CArea AS ca
                  ON ca.EmpId = Em.EmpID

                  WHERE Ed.SourceSystem = 'Credit' AND (ca.ccode is not null OR em.EmpID = 'Z00001')
                  ORDER BY Em.EmpID";
                  //echo $sql;
                  $stmt = sqlsrv_query( $conn, $sql );
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  */
                ?>
                  <option value="<?=$row['CCode']?>" <?php if ((!empty($_REQUEST['CreditID'])) && ($_REQUEST['CreditID'] == $row['CCode'])) { echo "selected"; } ?>><?=$row['EmpID']?> <?=$row['EmployeeName']?> (<?=$row['CCode']?>)</option>
                <?php
                /*
                    }
                    sqlsrv_close($conn);
                    */
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
    -->
    </BR>

      <div class="row">

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
                  <th>จำนวนเงิน</th>
                  <th>พนักงานเก็บเงิน(บ้านแดง)</th>
                  <th>พนักงานเก็บเงิน(บิ๊กเฮด)</th>
                  <th>แก้ไข</th>
                </tr>

                <?php
                  $conn = connectDB_TSR();
                  /*
                  $sql_case = "SELECT row_number() OVER (ORDER BY creditname2 DESC) AS rownum ,* FROM (
                  SELECT distinct con.contno,decu.customername,con.effdate ,sap.PaymentDueDate ,Right('000'+Convert(Varchar,sap.paymentperiodnumber-1),2) AS paymentperiodnumber ,sap.netamount ,ass.AssigneeEmpID ,right(Ed.salecode,3) as zone ,em.FirstName+' '+em.LastName AS CreditName ,c2.creditname as creditname2
                  FROM [TSS_PRD].[Bighead_Mobile].[dbo].[Assign] as ass left join [TSS_PRD].[Bighead_Mobile].[dbo].[Contract] as con on ass.RefNo = con.RefNo left join [TSS_PRD].[Bighead_Mobile].[dbo].[SalePaymentPeriod] as sap on con.RefNo = sap.RefNo
                  left join [TSS_PRD].[Bighead_Mobile].[dbo].[DebtorCustomer] as decu on decu.CustomerID=con.CustomerID
                  LEFT JOIN [TSS_PRD].Bighead_Mobile.dbo.Employee AS Em ON Em.EmpID = ass.AssigneeEmpID LEFT JOIN [TSS_PRD].Bighead_Mobile.dbo.EmployeeDetail AS Ed ON Ed.EmployeeCode = ass.AssigneeEmpID LEFT JOIN [TSR_Application].[dbo].[View_Bighead_credit_2] as c2 ON c2.CONTNO = con.contno
                  where sap.PaymentComplete = 0 AND Ed.salecode IS NOT NUll and sap.PaymentPeriodNumber in ( SELECT min([PaymentPeriodNumber])
                  FROM [TSS_PRD].[Bighead_Mobile].[dbo].[SalePaymentPeriod] where refno = ass.refno and PaymentComplete = 0 group by refno) AND ass.AssigneeEmpID = 'Z00001'
                  )as a ";
                  */

                  $sql_case = "SELECT  row_number() OVER (ORDER BY c2.creditName DESC) AS rownum ,c1.contno
                        ,c1.customername
                        ,c1.effdate
                        ,PaymentDueDate
                        ,paymentperiodnumber
                        ,netamount
                        ,AssigneeEmpID
                        ,zone
                        ,c1.CreditName as CreditName
                        ,c2.creditName as creditname2
                    FROM [TSR_Application].[dbo].[View_Bighead_credit_1] as c1 WITH(NOLOCK)
                    inner join [TSR_Application].[dbo].[View_Bighead_credit_2] as c2 WITH(NOLOCK)
                    on c1.RefNo = c2.RefNo
                    where AssigneeEmpID = 'Z00001'";

                  //echo $sql_case;
                  $num_row = checkNumRow($conn,$sql_case);

                  $sql = "SELECT TOP $limit_per_page * FROM (".$sql_case." )AS CAMPAIGN WHERE (rownum >= '".$limit_start."' AND rownum <= '".$limit_end."')   order by CAMPAIGN.rownum";
                  //echo $sql;
                  $stmt = sqlsrv_query($conn,$sql);
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  ?>
                  <form role="form" data-toggle="validator"name="formupdate" method="post" action="index.php?pages=tranCByCs">
                  <tr>
                    <td style="text-align: center"><?=$row['contno']?></td>
                    <td><?=$row['customername']?></td>
                    <td><?=$row['paymentperiodnumber']?></td>
                    <td><?=$row['netamount']?></td>
                    <td><?=$row['creditname2']?></td>
                    <td>
                      <select class="form-control select2" name="AssigneeEmpID" style="width: 100%;">
                        <optgroup label="เลือกพนักงานเก็บเงิน">
                          <?php
                            $conns = connectDB_BigHead();
                            /*
                            $sql1 = " SELECT DISTINCT em.EmpID , em.FirstName + ' ' + em.LastName As CreditName
                            FROM Bighead_Mobile.dbo.Employee AS Em
                            LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed
                            ON Ed.EmployeeCode = em.empID
                            WHERE SourceSystem != 'Sale' ORDER BY EMPID";
                            */

                            $sql1 = "SELECT DISTINCT em.EmpID ,ed.SaleCode, em.FirstName + ' ' + em.LastName As CreditName
                            FROM Bighead_Mobile.dbo.Employee AS Em WITH(NOLOCK)
                            LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed WITH(NOLOCK)
                            ON Ed.EmployeeCode = em.empID
                            WHERE SourceSystem != 'Sale' ORDER BY EMPID";
                            
                            //echo $sql;
                            $stmt1 = sqlsrv_query( $conns, $sql1 );
                            while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                          ?>
                            <option value="<?=$row1['SaleCode'];?>" <?php if($row1['EmpID']==$row['AssigneeEmpID']){ echo "selected";}?>><?=$row1['CreditName'];?></option>
                          <?php
                              }
                              sqlsrv_close($conns);
                          ?>
                        </optgroup>
                    </select>

                    </td>
                    <td style="text-align: center"><input type="hidden" name="contno" value="<?=$row['contno'];?>"><button type="summit" class="btn btn-block btn-warning"> บันทึก </button></td>
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
