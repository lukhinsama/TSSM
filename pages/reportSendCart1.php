<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
$limit_per_page = 100;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;

if (empty($_REQUEST['searchDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,LTB.createDate,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['searchDate']));
  $WHERE = "AND LTB.createDate BETWEEN '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59'";
  $top = "";
}

if (empty($_REQUEST['startDate']) && empty($_REQUEST['endDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,LTB.createDate,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['startDate']))." - ".DateThai(DateEng($_REQUEST['endDate']));
  $WHERE = "AND LTB.createDate  BETWEEN '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59'";
  $top = "";
}

if (($_COOKIE['tsr_emp_permit'] == 4 )) {
  if (substr($_COOKIE['tsr_emp_id'],0,1) == "0") {
    $EmpID['0'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    $EmpID['1'] = $_COOKIE['tsr_emp_name'];

    $connss = connectDB_BigHead();
    $sql_Empid = "SELECT SaleCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE EmployeeCode = '".$EmpID['0']."' AND salecode is not null";

    //echo $sql_Empid;

    $stmt = sqlsrv_query($connss,$sql_Empid);
    while ($rowss = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      $EmpID['2'] = $rowss['SaleCode'];
    }

    sqlsrv_close($connss);

    $WHERE .= " AND LTB.salecode = '".$EmpID['2']."'";


  }
}else {
  if (!empty($_REQUEST['searchText'])) {
    $WHERE .= " AND ( LTB.salecode LIKE '%".$_REQUEST['searchText']."%' OR C.CONTNO LIKE '%".$_REQUEST['searchText']."%')";
  }
  if (!empty($_REQUEST['EmpID'])) {
  $EmpID = explode("_",$_REQUEST['EmpID']);
  $WHERE .= " AND LTB.salecode = '".$EmpID['2']."'";
  }
}

  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reprotSendCard1">
        <div class="col-md-3">
          <h4>
            รายงานโอนการ์ด
          </h4>
        </div>
        <div class="col-md-4">
          <?php
          if (($_COOKIE['tsr_emp_permit'] != 4)) {
           ?>
          <div class="form-group group-sm">
            <select class="form-control select2 group-sm" name="EmpID" >
              <optgroup label="พนักงานเก็บเงิน">
                  <option value="0"> ทั้งหมด </option>
                  <?php

                $sql_case = "SELECT SaleCode as mcode,EmployeeName AS Name ,EmployeeCode AS EmpID,case when SaleCode is null then '-' else SaleCode end as SaleCode ,SupervisorCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE  salecode is not null AND SupervisorCode is not null  $supcode ORDER BY mcode";

                //echo $sql_case;
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
              <option value="<?=$row['EmpID']?>_<?=$row['Name']?>_<?=$row['SaleCode']?>_<?=$row['mcode']?>"><?=$row['EmpID']?> (<?=$row['SaleCode']?>) <?=$row['Name']?> </option>
                <?php
                  }
                ?>
              </optgroup>
            </select>
          </div>
          <?php
        }
           ?>
        </div>
        <div class="col-md-4">
          <div class="input-group input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <!--<input type="text" class="form-control" name="searchDate" autocomplete="off" id="datepicker2"  placeholder="กรอกวันที่ .." required>-->

            <div class="input-group input-group input-daterange" id="datepicker2">
                    <input type="text" class="form-control" name="startDate" autocomplete="off" value="<?php if(isset($_REQUEST['startDate'])) {echo $_REQUEST['startDate'];}?>" placeholder="วันเริ่มต้น .." required>
                    <span class="input-group-addon">ถึง</span>
                    <input type="text" class="form-control" name="endDate" autocomplete="off" value="<?php if(isset($_REQUEST['endDate'])) {echo $_REQUEST['endDate'];}?>" placeholder="วันสิ้นสุด .." required>
                </div>

            <div class="input-group-btn">
              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>

        <div class="col-md-1">
          <a href="http://app.thiensurat.co.th/lkh/rpt_lk5.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=9" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
        </div>
        </form>
      </div>

    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-xs-12">
          <?php
            if (!empty($_REQUEST['searchDate']) || ((!empty($_REQUEST['startDate']) && !empty($_REQUEST['endDate'])))) {

           ?>
          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><B>รายงานโอนการ์ด</B></center></P>

            </div>


          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">ชื่อลูกค้า</th>
                <th style="text-align: center">เขตที่รับโอน</th>
                <th style="text-align: center">พนักงานเขตรับโอน</th>
                <th style="text-align: center">ผู้ที่โอน</th>
                <th style="text-align: center">วันเวลาที่โอน</th>
              </tr>
            </thead>

            <tbody>
              <?php
                  $conn = connectDB_BigHead();
                  $SQLSELECT = "SELECT A.*,ISNULL(EM.FirstName+' '+EM.LastName,A.EmpID) AS EmpNAME
                  FROM (
                  SELECT LTB.Createby,
                  CASE WHEN SUBSTRING(LTB.Createby,1,1) = '0' THEN 'A'+ SUBSTRING(LTB.Createby,2,6)
                  ELSE LTB.Createby END AS EmpID,
                  LTB.Contno,CUS.PrefixName+' '+CUS.CustomerName AS CustomerName,LTB.salecode,EMD.EmployeeName
                  ,LTB.createDate,CONVERT(varchar(20),LTB.createDate,105) +' '+ CONVERT(varchar(5),LTB.createDate,108) AS createDates
                  FROM TSRData_Source.dbo.Log_Tranfer_BigHead AS LTB
                  INNER JOIN Bighead_Mobile.dbo.EmployeeDetail AS EMD
                  ON LTB.salecode = EMD.salecode
                  INNER JOIN Bighead_Mobile.dbo.Contract AS CON
                  ON CON.CONTNO = LTB.Contno
                  INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS CUS
                  ON CON.CustomerID = CUS.CustomerID
                  WHERE LTB.CreateBy IS NOT NULL AND LTB.CreateBy <> '' $WHERE
                  ) AS A
                  LEFT JOIN Bighead_Mobile.dbo.Employee AS EM
                  ON A.EmpID = EM.EmpID
                  ORDER BY A.CreateDate DESC";

                  //echo $SQLSELECT;

                  $stmt = sqlsrv_query($conn,$SQLSELECT);
                  $i=0;
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                    $i++;
               ?>

                <tr>
                <td style="text-align: center"><?=$i?></td>
                <td style="text-align: center"><?=$row['Contno']?></td>
                <td><?=$row['CustomerName']?></td>
                <td style="text-align: center"><?=$row['salecode']?></td>
                <td><?=$row['EmployeeName']?></td>
                <td>(<?=$row['EmpID']?>) <?=$row['EmpNAME']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['createDates'])?> น.</td>
              </tr>

              <?php
                  }
               ?>
             </tbody>
             <tfoot>
             </tfoot>
            </table>

          </div>
          <div class="box-footer clearfix">

          <table width="100%">
          <tr>
            <td style="text-align: right" width="10%"><B>รวม</B> </td>
            <td style="text-align: right" width="5%"> </td>
            <td style="text-align: left" width="10%"><?=$num_row;?> ใบ</td>
            <td style="text-align: right"><B> รวมเงิน </B></td>
            <td style="text-align: right" width="15%"><?=number_format($SumTotal,2)?></td>
          </tr>

        </table>
          <a href="export_excel.php?report_type=3"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
          </div>
        </div>
        <?php
          }
          sqlsrv_close($conn);
          sqlsrv_close($conns);
        ?>
        </div>

      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });
  </script>
