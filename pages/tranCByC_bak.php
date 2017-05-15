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
  $where = "AND con.contno = '".$_REQUEST['searchText']."'";
}else {
  $top = "TOP 1";
  $where = "AND con.contno = '11517550'";
}


if (!empty($_REQUEST['contno'])) {
  //echo $_REQUEST['contno'];
  //echo $_REQUEST['AssigneeEmpID'];
  //$conn = connectDB_BigHead();


  $sql = "EXEC [dbo].[usp_TSR_Assign_UpdateAssignForChangeEmployee]
  @CONTNO = N'".$_REQUEST['contno']."',
  @OldAssigneeEmpID = null,
  @NewAssigneeEmpID = N'".$_REQUEST['AssigneeEmpID']."'";
  //echo $sql;

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
            โอนลูกค้ารายสัญญา
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
        <li class="active"> โอนลูกค้ารายสัญญา </li>
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
              <form role="form" data-toggle="validator" id="formSearchEmpHr" name="formSearchEmpHr" method="post" action="index.php?pages=tranCByC">
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
                <th>ชื่อลูกค้า</th>
                <th>งวดที่</th>
                <th>จำนวนเงิน</th>
                <th>พนักงานเก็บเงิน</th>
                <th>แก้ไข</th>
              </tr>

              <?php
                $conn = connectDB_BigHead();
                $sql_case = "SELECT distinct $top
 con.contno,decu.customername,con.effdate
  ,sap.PaymentDueDate
  ,Right('000'+Convert(Varchar,sap.paymentperiodnumber-1),2) AS paymentperiodnumber
  ,sap.netamount
  ,ass.AssigneeEmpID
  ,right(Ed.salecode,3) as zone
  ,em.FirstName+' '+em.LastName AS CreditName

  FROM [Bighead_Mobile].[dbo].[Assign] as ass
  left join [Bighead_Mobile].[dbo].[Contract] as con
  on ass.RefNo = con.RefNo
  left join [Bighead_Mobile].[dbo].[SalePaymentPeriod] as sap
  on con.RefNo = sap.RefNo
  left join [Bighead_Mobile].[dbo].[DebtorCustomer] as decu
  on decu.CustomerID=con.CustomerID

  LEFT JOIN Bighead_Mobile.dbo.Employee AS Em
  ON Em.EmpID = ass.AssigneeEmpID
  LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed
  ON Ed.EmployeeCode = ass.AssigneeEmpID
    where
 sap.PaymentComplete = 0
  AND Ed.salecode IS NOT NUll
  and sap.PaymentPeriodNumber in ( SELECT min([PaymentPeriodNumber])
  FROM [Bighead_Mobile].[dbo].[SalePaymentPeriod]
  where refno = ass.refno and PaymentComplete = 0
  group by refno)
  $where
  order by con.contno";

                //echo $sql_case;
                //$num_row = checkNumRow($conn,$sql_case);

              //  $sql = "SELECT TOP $limit_per_page * FROM (".$sql_case." )AS CAMPAIGN WHERE (rownum >= '".$limit_start."' AND rownum <= '".$limit_end."')   order by CAMPAIGN.rownum";
                //echo $sql;
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>

                <tr>
                  <td style="text-align: center"><?=$row['contno']?></td>
                  <td><?=$row['customername']?></td>
                  <td><?=$row['paymentperiodnumber']?></td>
                  <td><?=$row['netamount']?></td>
                  <td>
                    <select class="form-control select2" name="AssigneeEmpID" style="width: 100%;">
                      <optgroup label="เลือกพนักงานเก็บเงิน">
                        <?php
                          //$conn = connectDB_HR();
                          $sql1 = " SELECT DISTINCT em.EmpID , em.FirstName + ' ' + em.LastName As CreditName
                          FROM Bighead_Mobile.dbo.Employee AS Em
                          LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed
                          ON Ed.EmployeeCode = em.empID
                          WHERE SourceSystem = 'Credit' ORDER BY EMPID";
                          //echo $sql;
                          $stmt1 = sqlsrv_query( $conn, $sql1 );
                          while ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
                        ?>
                          <option value="<?=$row1['EmpID'];?>" <?php if($row1['EmpID']==$row['AssigneeEmpID']){ echo "selected";}?>><?=$row1['CreditName'];?></option>
                        <?php
                            }
                            //sqlsrv_close($conn);
                        ?>
                      </optgroup>
                  </select>

                  </td>
                  <td style="text-align: center"><input type="hidden" name="contno" value="<?=$row['contno'];?>"><button type="summit" class="btn btn-block btn-warning"> บันทึก </button></td>
                </tr>
                <?php
                  }
                  //sqlsrv_close($conn);
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
