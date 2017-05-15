<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
$limit_per_page = 50;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;

if (!empty($_REQUEST['TeamCode']) ) {
  $teamcode = explode("_",$_REQUEST['TeamCode']);
  //$WHERE .= " AND Emd.SupervisorCode = '".$_REQUEST['TeamCode']."'";
  $WHERE = " AND Ed.SupervisorCode = '".$teamcode[0]."' ";
  $WHERE2 = " AND SupervisorCode = '".$teamcode[0]."' ";
}else {
  $WHERE = "";
  $WHERE2 = "";
}


if (($_COOKIE['tsr_emp_permit'] == 4 )) {
  if (substr($_COOKIE['tsr_emp_id'],0,1) == "0") {
    $EmpID['0'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
    $EmpID['1'] = $_COOKIE['tsr_emp_name'];
    $WHERE = " AND Ed.EmployeeCode = '".$EmpID['0']."'";
    $WHERE2 = " AND EmployeeCode = '".$EmpID['0']."'";
  }
}

if (!empty($_REQUEST['tip']) ) {
  $tip = explode("_",$_REQUEST['tip']);
}

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <div class="col-md-3">
          <h4>
            รายงานการวิ่งงานรายทริป
          </h4>
        </div>
        <div class="col-md-3">
          <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportcredit6_test">
            <?php
              if (($_COOKIE['tsr_emp_permit'] != 4 )) {
             ?>
          <select class="form-control select2 group-sm" name="TeamCode" >
            <optgroup label="ทีม">

              <?php
              $conn = connectDB_BigHead();

              if ($_COOKIE['tsr_emp_id'] == 'ZCR001') {
                $supcode = "AND SupervisorCode = '101'";
              }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR002') {
                $supcode = "AND SupervisorCode = '102'";
              }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR003') {
                $supcode = "AND SupervisorCode = '103'";
              }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR004') {
                $supcode = "AND SupervisorCode = '104'";
              }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR005') {
                $supcode = "AND SupervisorCode = '105'";
              }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR006') {
                $supcode = "AND SupervisorCode = '106'";
              }elseif ($_COOKIE['tsr_emp_id'] == 'ZCR007') {
                $supcode = "AND SupervisorCode = '107'";
              }else {
                $supcode = '';
                ?>
                <option value="0"> ทั้งหมด </option>
                <?php
              }

              $sql_case = "SELECT [SupervisorCode],
              CASE [SupervisorCode]
            	WHEN '101' THEN 'ทีม A'
            	WHEN '102' THEN 'ทีม B'
              WHEN '104' THEN 'ทีม C'
            	WHEN '103' THEN 'ทีม D'
            	WHEN '105' THEN 'ทีม F'
            	WHEN '106' THEN 'ทีม H'
              ELSE 'ทีม I'
              END AS TeamCode
              ,'ผู้จัดการ '+EmployeeName as EmployeeName
              FROM [Bighead_Mobile].[dbo].[EmployeeDetail] AS Emd
              LEFT JOIN [Bighead_Mobile].[dbo].[Employee] AS Em
              ON Emd.EmployeeCode = Em.EmpID
              WHERE PositionCode = 'CreditSupervisor' AND (EmployeeTypeCode is not null AND EmployeeTypeCode != '')  $supcode
              ORDER BY [SupervisorCode]";

              //echo $sql_case;
              $stmt = sqlsrv_query($conn,$sql_case);

              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
              ?>
            <option value="<?=$row['SupervisorCode']?>_<?=$row['TeamCode']?>_<?=$row['EmployeeName']?>"><?=$row['TeamCode']?></option>
              <?php
                }
                sqlsrv_close($conn);
              ?>
            </optgroup>
          </select>
          <?php
        }
           ?>
        </div>
        <div class="col-md-4">

            <div class="input-group input-group">

              <select class="form-control select2 group-sm" name="tip" >
                <optgroup label="เลือกเดือน">
                  <?php
                  $conn = connectDB_TSR();

                  //  if (date('d') < 21) {
                 ?>
                    <option value="0">เดือนปัจจุบัน</option>
                  <?php
                //}

                  $sql_case = "SELECT monthss,[Years],[Mouths], 'ระหว่างวันที่ 21/'+ '' + convert(varchar,(monthss-1)) + '/' + convert(varchar,([Years]+543)) + ' ถึงวันที่ ' + '20/'+ '' + convert(varchar,(monthss)) + '/' + convert(varchar,([Years]+543)) AS Tips FROM [TSR_Application].[dbo].[BigHead_Log_Credit_By_Mount] GROUP BY monthss,[Years],[Mouths]";

                  //echo $sql_case;
                  $stmt = sqlsrv_query($conn,$sql_case);

                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  ?>
                <option value="<?=$row['Years']?>_<?=$row['monthss']?>"><?=$row['Tips']?></option>
                  <?php
                    }
                    sqlsrv_close($conn);
                  ?>
                </optgroup>
              </select>
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-2">
          <a href="http://app.thiensurat.co.th/lkh/rpt_lk3.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=6" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
        </div>
      </div>


    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-md-12">
          <?php
            if ((!empty($_REQUEST['TeamCode'])) || ($_REQUEST['TeamCode'] === '0')) {
           ?>
           <?php
            $conn = connectDB_BigHead();

            if (empty($_REQUEST['tip']) ) {
              /*
             $sql_case = "SELECT [EmpID]
             ,ed.[EmployeeName]
  		      ,convert(int,RIGHT(ed.salecode,3)) AS Zone
             --,'ประจำเดือน พฤษจิกายน พ.ศ.2559' AS [Mouths]
             ,[Years]
             ,[1]
             ,[2]
             ,[3]
             ,[4]
             ,[5]
             ,[6]
             ,[7]
             ,[8]
             ,[9]
             ,[10]
             ,[11]
             ,[12]
             ,[13]
             ,[14]
             ,[15]
             ,[16]
             ,[17]
             ,[18]
             ,[19]
             ,[20]
             ,[pointer]
             ,case when pointer = '2' then '' else [StartContCount] end as [StartContCount]
             ,case when pointer = '2' then [sumPay] else [sumCont] end sumCountPay
  		         ,[sumCont]
             ,[sumPay]
             ,ed.SupervisorCode
             ,'".$teamcode[1]." ". $teamcode[2]."' AS LabalTeam
             ,case when isnull(StartContCount,0) = 0 then 0 else (isnull(convert(numeric(18,2),sumCont),0)/isnull(convert(numeric(18,2),StartContCount),0))*100 end as percencount

             FROM [Bighead_Mobile].[dbo].[vw_ReportCreditByTrip_2] AS Rct
             LEFT JOIN [Bighead_Mobile].[dbo].[EmployeeDetail] AS Ed
             ON Rct.EmpID = Ed.EmployeeCode
             WHERE ed.SaleCode is not null AND ed.SourceSystem <> 'sale' $WHERE ORDER BY EmpID,pointer asc";
             */
             if (date('d') < 20) {
               $DateBe = "AND cast(PayDate as date) BETWEEN cast('2016-'+ CONVERT(varchar, DATEPART(mm, DateAdd(month, - 1, GETDATE()))) +'-21' as date) AND cast('2016-'+ CONVERT(varchar, DATEPART(mm, GETDATE())) +'-20' as date)";
             }else {
               $DateBe = "AND cast(PayDate as date) BETWEEN cast('2016-'+ CONVERT(varchar, CONVERT(varchar, DATEPART(mm, GETDATE())) +'-21' as date) AND cast('2016-'+ CONVERT(varchar, DATEPART(mm, DateAdd(month, + 1, GETDATE()))) +'-20' as date)";
             }
             $sql_case = "SELECT AssigneeEmpID,EmployeeName,SupervisorCode,isnull(convert(int,right(e.SaleCode,3)),0) as Zone,CountRefno As StartContCount,(SELECT COUNT(RefNo) AS RefNo FROM [Bighead_Mobile].[dbo].[Payment]
            WHERE empId = c.AssigneeEmpID $DateBe) As sumCont
            ,'2016-'+ CONVERT(varchar, DATEPART(mm, DateAdd(month, - 1, GETDATE()))) +'-21' as StartDate
            ,'2016-'+ CONVERT(varchar, DATEPART(mm, GETDATE())) +'-20'AS StopDate
            ,(SELECT SUM(PAYAMT) AS PAYAMT
            FROM [Bighead_Mobile].[dbo].[Payment]
            WHERE empId = c.AssigneeEmpID $DateBe) As sumPay
            ,'1' As pointer
            FROM [Bighead_Mobile].[dbo].[vw_CountActiveAssignPeriod] as c
            LEFT JOIN [Bighead_Mobile].[dbo].[EmployeeDetail] As e
            on c.AssigneeEmpID = e.EmployeeCode AND e.SourceSystem = 'Credit' and SaleCode is not null
            WHERE AssigneeEmpID <> 'Z00001' $WHERE2";

           }else {

             $sql_case = "SELECT [EmpID]
              ,[EmployeeName]
              ,[Zone]
              ,[Mouths]
              ,[monthss]
              ,[Years]
              ,[1]
              ,[2]
              ,[3]
              ,[4]
              ,[5]
              ,[6]
              ,[7]
              ,[8]
              ,[9]
              ,[10]
              ,[11]
              ,[12]
              ,[13]
              ,[14]
              ,[15]
              ,[16]
              ,[17]
              ,[18]
              ,[19]
              ,[20]
              ,[pointer]
              ,[StartContCount]
              ,[sumCountPay]
              ,[sumCont]
              ,[sumPay]
              ,[SupervisorCode]
              ,'' as [LabalTeam]
              ,[percencount] FROM  [LINK_STOCK].[TSR_Application].[dbo].[BigHead_Log_Credit_By_Mount] WHERE monthss = ".$tip[1]." AND Years = ".$tip[0]."  $WHERE2 ORDER BY EmpID,pointer asc";
           }
            ?>

          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><H4>รายงานการวิ่งงานรายทริป</H4></center><center><B><?=$teamcode[1]?> <?=$teamcode[2]?></B></center></P>
            </div>

          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">
            <table id="example2" class="table table-hover table-striped" width="100%">
              <thead>

            <tr>
              <th style="text-align: center">เขต</th>
              <th style="text-align: center">ชื่อ - สกุล</th>
              <th style="text-align: center">ยอดการ์ด</th>
              <th style="text-align: center">การ์ดเก็บได้</th>
              <th style="text-align: center">การ์ดเก็บได้ %</th>
              <th style="text-align: center">การ์ดเก็บไม่ได้</th>
              <th style="text-align: center">การ์ดเก็บไม่ได้ %</th>
              <th style="text-align: center">จำนวนเงินเก็บได้</th>
            </tr>
              </thead>
              <tbody>
              <?php

              //echo $sql_case;

              $file = fopen("../tsr_SaleReport/pages/sqlText1.txt","w");
              fwrite($file,$sql_case);
              fclose($file);
              /*
              $file1 = fopen("../tsr_SaleReport/pages/sqlText3.txt","w");
              fwrite($file1,$dateSearch);
              fclose($file1);
              */
              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),6)";
      				//echo $sql_insert;

      				$params = array($_COOKIE['tsr_emp_id'],$sql_case);
      				//print_r($params);

      				$stmt_insert = sqlsrv_query( $conns, $sql_insert, $params);

      				if( $stmt_insert === false ) {
      					 die( print_r( sqlsrv_errors(), true));
      				}


            //  $num_row = checkNumRow($conn,$sql_case);
              $SumRef = 0;
              $SumRefNO = 0;
              $SumPAYAMT = 0;

              //echo $sql;
              $stmt = sqlsrv_query($conn,$sql_case);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

              ?>

            <?php
              if ($row['pointer'] == '1') {
             ?>
            <tr>
              <td style="text-align: center"><?=$row['Zone']?></td>
              <td><?=$row['EmployeeName']?></td>
              <td style="text-align: center"><?=$row['StartContCount']?></td>
              <td style="text-align: center"><?=$row['sumCont']?></td>
              <td style="text-align: center"><?=number_format(($row['sumCont']/$row['StartContCount'])*100,2)?> %</td>
              <td style="text-align: center"><?=$row['StartContCount']-$row['sumCont']?></td>
              <td style="text-align: center"><?=number_format((($row['StartContCount']-$row['sumCont'])/$row['StartContCount'])*100,2)?> %</td>
              <td style="text-align: center"><?=number_format($row['sumPay'],2)?></td>
            </tr>
              <?php
            }
                }

               ?>
             </tbody>
             <tfoot>
             </tfoot>
            </table>
          </div>
          <div class="box-footer clearfix">

          </div>

        </div>

        <?php
          }
          sqlsrv_close($conn);
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
