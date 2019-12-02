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

  /*
  $sql = "EXEC [dbo].[usp_TSR_Assign_UpdateAssignForChangeEmployee]
  @CONTNO = N'".$_REQUEST['contno']."',
  @OldAssigneeEmpID = null,
  @NewAssigneeEmpID = N'".$_REQUEST['AssigneeEmpID']."'";
  */

  $sql = "EXEC [dbo].usp_TSR_Contract_MoveMultipleContractToZone
  @OrganizationCode = 0,
  @CONTNOLIST = '".$_REQUEST['contno']."',
  @ZoneCode= '".$_REQUEST['AssigneeEmpID']."',
  @RemoveAssign = 'Y',
  @AutoAssign = 'Y'";

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

  //หาข้อมูลจาก CArea
  $sqlCArea = "SELECT top 1 CCode,Name,EmpId,ACode,MCode
  FROM [TSRData_Source].[dbo].[CArea]
  WHERE EmpId IN (select top 1 EmployeeCode from Bighead_Mobile.dbo.EmployeeDetail WHERE SaleCode = '".$_REQUEST['AssigneeEmpID']."')";

  $stmtCArea = sqlsrv_query($conn,$sqlCArea);
  while ($CArea = sqlsrv_fetch_array( $stmtCArea, SQLSRV_FETCH_ASSOC)) {
    $CCode = $CArea['CCode'];
    $Name = $CArea['Name'];
    $Empid = $CArea['EmpId'];
    $ACode = $CArea['ACode'];
    $MCode = $CArea['MCode'];
  }
  sqlsrv_close($conn);


  $sql = "EXEC TSR_Application.dbo.sp_DebtorAnalyze_Update_CashCode_CBighead
  @V_refno = '".$_REQUEST['ContRefno']."'";

  $con = connectDB_TSR();
  $stmt1 = sqlsrv_query( $con, $sql );
  if( $stmt === false ) {
       die( print_r( sqlsrv_errors(), true));
  }

  //สโตจาร์ยอู๊ด
  $sql = "EXEC TSR_Application.dbo.Cms_CreditFrom_Trans_BH
          @RefNo = '".$_REQUEST['ContRefno']."',
          @CashCode = '".$CCode."',
          @CashName = '".$Name."',
          @EmpId = '".$Empid."',
          @AreaCode = '".$ACode."',
          @BHCode = '".$MCode."',
          @Remark = 'TSSM',
          @CreateBy = '".$_REQUEST['CreateBy']."'";

          $stmt1 = sqlsrv_query( $con, $sql );
          if( $stmt === false ) {
               die( print_r( sqlsrv_errors(), true));
          }

  $sql = "Exec TSR_Application.dbo.Cms_CashCode_Trans_Log_Save
          @ContNo = '".$_REQUEST['contno']."',
          @RefNo = '".$_REQUEST['ContRefno']."',
          @CashCode = '".$CCode."',
          @CreateBy = '".$_REQUEST['CreateBy']."'";

          $stmt1 = sqlsrv_query( $con, $sql );
          if( $stmt === false ) {
               die( print_r( sqlsrv_errors(), true));
          }

  sqlsrv_close($con);
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
                <th style="text-align: center">เลขที่อ้างอิง</th>
                <th>ชื่อลูกค้า</th>
                <th>งวดที่</th>
                <th>จำนวนเงิน</th>
                <th>พนักงานเก็บเงิน(บ้านแดง)</th>
                <th>พนักงานเก็บเงิน(บิ๊กเฮด)</th>
                <th>แก้ไข</th>
              </tr>

              <?php
                $conn = connectDB_TSR();

                $sql_case = "SELECT distinct top 1 con.contno,decu.customername,con.effdate ,sap.PaymentDueDate
                ,Right('000'+Convert(Varchar,sap.paymentperiodnumber-1),2) AS paymentperiodnumber
                ,sap.netamount ,ass.AssigneeEmpID ,Ed.salecode as salecode,right(Ed.salecode,3) as zone ,em.FirstName+' '+em.LastName AS CreditName
                ,c2.creditname as creditname2,ass.LastUpdateDate,ass.refno as refno
                ,con.ContractReferenceNo AS ContRefno
                ,case when len(con.RefNo) > 9 then 'B' else 'R' end as colorStatus
                ,case when con.service = '00000000' then salecode else con.service end as service
                ,ISNULL((SELECT top 1 P.payamt FROM [TSS_PRD].Bighead_Mobile.dbo.Payment AS P INNER JOIN [TSS_PRD].Bighead_Mobile.dbo.TRIP AS T ON P.tripId = T.tripid where refno = con.refno AND GETDATE() BETWEEN T.startdate and T.enddate),0) AS disStatus
                FROM [TSS_PRD].[Bighead_Mobile].[dbo].vw_Last_Assign as ass WITH(NOLOCK)
                left join [TSS_PRD].[Bighead_Mobile].[dbo].[vw_ContactActive] as con WITH(NOLOCK)
                on ass.RefNo = con.RefNo
                left join [TSS_PRD].[Bighead_Mobile].[dbo].[SalePaymentPeriod] as sap WITH(NOLOCK)
                on con.RefNo = sap.RefNo
                left join [TSS_PRD].TSRData_Source.dbo.vw_DebtorCustomer as decu WITH(NOLOCK)
                on decu.CustomerID=con.CustomerID
                LEFT JOIN [TSS_PRD].Bighead_Mobile.dbo.Employee AS Em WITH(NOLOCK) ON Em.EmpID = ass.AssigneeEmpID
                LEFT JOIN [TSS_PRD].Bighead_Mobile.dbo.EmployeeDetail AS Ed WITH(NOLOCK)
                ON Ed.EmployeeCode = ass.AssigneeEmpID

                LEFT JOIN [TSR_Application].[dbo].[View_Bighead_credit_2] as c2 WITH(NOLOCK)
                ON c2.CONTNO = con.contno

                where
                sap.PaymentComplete = 0
              AND Ed.salecode IS NOT NUll
              and sap.PaymentPeriodNumber in ( SELECT min([PaymentPeriodNumber])
              FROM [TSS_PRD].[Bighead_Mobile].[dbo].[SalePaymentPeriod] WITH(NOLOCK)
              where refno = ass.refno and PaymentComplete = 0
              group by refno)
              $where";

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

                          //$sql1 = "SELECT CCode,mcode,Name,EmpID ,case when ed.SaleCode is null then '-' else ed.SaleCode end as SaleCode ,SupervisorCode FROM [TsrData_source].[dbo].[CArea] AS C LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed ON Ed.EmployeeCode = c.EmpID AND salecode is not null WHERE EmpId is not null AND EmpId != '' AND SupervisorCode is not null ORDER BY ccode";
                          /*
                          $sql1 = "SELECT DISTINCT em.EmpID , em.FirstName + ' ' + em.LastName As CreditName
                          FROM Bighead_Mobile.dbo.Employee AS Em
                          LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed
                          ON Ed.EmployeeCode = em.empID
                          WHERE SourceSystem != 'Sale' ORDER BY EMPID";
                          */

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
                    <input type="hidden" name="ContRefno" value="<?=$row['ContRefno'];?>">
                    <input type="hidden" name="CreateBy" value="<?="A".substr($_COOKIE['tsr_emp_id'],1,5);?>">
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
