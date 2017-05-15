<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
$limit_per_page = 50;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;

if (!empty($_REQUEST['TeamCode']) ) {
  $teamcode = explode("_",$_REQUEST['TeamCode']);
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
    $WHERE2 = " AND EmployeeCode = '".$EmpID['0']."'";
    $WHERE = " WHERE Ed.EmployeeCode = '".$EmpID['0']."'";
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
          <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportdept6">
            <select class="form-control select2 group-sm" name="TeamCode" >
              <optgroup label="ทีม">
                <?php
                $conn = connectDB_BigHead();

                if ($_COOKIE['tsr_emp_id'] == 'ZDP001') {
                  $supcode = "AND SupervisorCode = '80101'";
                }elseif ($_COOKIE['tsr_emp_id'] == 'ZDP002') {
                  $supcode = "AND SupervisorCode = '80102'";
                }else {
                  $supcode = '';
                  ?>
                  <option value="0"> ทั้งหมด </option>
                  <?php
                }

                $sql_case = "SELECT [SupervisorCode],
                CASE [SupervisorCode]
                WHEN '80101' THEN 'ทีม 1'
                WHEN '80102' THEN 'ทีม 2'
                ELSE 'ทีมอื่นๆ'
                END AS TeamCode
                FROM [Bighead_Mobile].[dbo].[EmployeeDetail] AS Emd
                LEFT JOIN [Bighead_Mobile].[dbo].[Employee] AS Em
                ON Emd.EmployeeCode = Em.EmpID
                WHERE SourceSystem = 'Credit' AND PositionCode LIKE 'dept%' AND saleCode is not null
                AND (EmployeeTypeCode is not null AND EmployeeTypeCode != '') $supcode
                GROUP BY [SupervisorCode]";

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
        </div>
        <div class="col-md-4">

            <div class="input-group input-group">

              <select class="form-control select2 group-sm" name="tip" >
                <optgroup label="เลือกเดือน">
                  <?php
                  $conn1 = connectDB_BigHead();


                  $sql_case = "SELECT TripID,CONVERT(VARCHAR(10),StartDate,105) AS StartDate, CONVERT(VARCHAR(10),StartDate,20) As StartDate1 ,CONVERT(VARCHAR(10),EndDate,105) AS EndDate, CONVERT(VARCHAR(10),EndDate,20) as EndDate2,TripYearNumber,YearTH,TripNumber FROM Bighead_Mobile.dbo.vw_GetTripAll  WHERE TripYearNumber > '201610' AND StartDate < GETDATE() ORDER BY TripYearNumber DESC";
                  //echo $sql_case;
                  $stmt = sqlsrv_query($conn1,$sql_case);

                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  ?>
                <option value="<?=$row['TripID']?>_<?=$row['TripYearNumber']?>_<?=$row['StartDate']?>_<?=$row['EndDate']?>_<?=$row['StartDate1']?>_<?=$row['EndDate2']?>">ระหว่างวันที่ <?=DateThai($row['StartDate'])?> - <?=DateThai($row['EndDate'])?> </option>
                  <?php
                    }
                    sqlsrv_close($conn1);
                  ?>
                </optgroup>
              </select>
              <div class="input-group-btn">
                <input type="hidden" name="TeamCode" value="0">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-2">
          <a href="http://app.thiensurat.co.th/lkh/rpt_lk4.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=6" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
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
             if (date('d') < 20) {
               $DateBe = "AND cast(PayDate as date) BETWEEN cast('".$tip[4]."' as date) AND cast('".$tip[5]."' as date)";
               $dateTo1 = "'".$tip[4]."' AS StartDate";
               $dateTo2 = "'".$tip[5]."' AS StopDate";
             }else {
               $DateBe = "AND cast(PayDate as date) BETWEEN cast('2016-'+ CONVERT(varchar, CONVERT(varchar, DATEPART(mm, GETDATE())) +'-21' as date) AND cast('2016-'+ CONVERT(varchar, DATEPART(mm, DateAdd(month, + 1, GETDATE()))) +'-20' as date)";
               $dateTo1 = "'".$tip[4]."' AS StartDate";
               $dateTo2 = "'".$tip[5]."' AS StopDate";
             }
             $printHead = "รายงานการวิ่งงานรายทริป  ระหว่างวันที่ ".DateThai($tip[2])." - ".DateThai($tip[3])." ".$teamcode[1]." ".$teamcode[2]." ";
               /*
            $sql_case = "SELECT Trip.SERVICE,ed.EmployeeName
            , count(ContractCount) as StartContCount
            ,isnull(convert(int,right(ed.SaleCode,3)),0) as Zone
            ,ed.SupervisorCode
            ,(SELECT COUNT(RefNo) AS RefNo FROM (SELECT DISTINCT RefNo FROM [Bighead_Mobile].[dbo].[Payment] WHERE CashCode = Trip.SERVICE AND TripID = '".$tip[0]."' AND PAYAMT > 0) AS A) AS sumCont
            ,(SELECT COUNT(RefNo) AS RefNo FROM [Bighead_Mobile].[dbo].[Payment] WHERE CashCode = Trip.SERVICE AND TripID = '".$tip[0]."' AND PAYAMT > 0) AS sumReceipt
            ,$dateTo1
            ,$dateTo2
            ,isnull((SELECT SUM(PAYAMT) AS PAYAMT FROM [Bighead_Mobile].[dbo].[Payment] WHERE CashCode = Trip.SERVICE AND TripID = '".$tip[0]."'),0) As sumPay
            ,'1' As pointer
            FROM (
            SELECT contract.SERVICE,contno AS ContractCount
            FROM Contract
            INNER JOIN SalePaymentPeriod ON Contract.RefNo = SalePaymentPeriod.RefNo
            WHERE        (SalePaymentPeriod.TripID = '".$tip[0]."' )
            AND (Contract.STATUS IN ('NORMAL', 'F')) AND (Contract.service not like 'Y%' AND Contract.service not like '0%')
            GROUP BY CONTNO,SERVICE) AS Trip
            left join Bighead_Mobile.dbo.EmployeeDetail as ed on Trip.service = ed.salecode
            WHERE  ed.SourceSystem != 'sale' $WHERE and ed.SaleCode is not null AND ed.EmployeeCode not like 'Z%'
            group by ed.EmployeeName,ed.SaleCode,ed.SupervisorCode,trip.SERVICE ";
            */

            $sql_case = "SELECT Trip.SERVICE,ed.EmployeeName
            , count(ContractCount) as StartContCount
            ,isnull(convert(int,right(ed.SaleCode,3)),0) as Zone
            ,ed.SupervisorCode
            ,(SELECT COUNT(RefNo) AS RefNo FROM (SELECT DISTINCT RefNo FROM [Bighead_Mobile].[dbo].[Payment] WHERE CashCode = Trip.SERVICE AND TripID = '".$tip[0]."' AND PAYAMT > 0) AS A) AS sumCont
            ,(SELECT COUNT(RefNo) AS RefNo FROM [Bighead_Mobile].[dbo].[Payment] WHERE CashCode = Trip.SERVICE AND TripID = '".$tip[0]."' AND PAYAMT > 0) AS sumReceipt
            ,$dateTo1
            ,$dateTo2
            ,isnull((SELECT SUM(PAYAMT) AS PAYAMT FROM [Bighead_Mobile].[dbo].[Payment] WHERE CashCode = Trip.SERVICE AND TripID = '".$tip[0]."'),0) As sumPay
            ,'1' As pointer
            ,'".$printHead."' as printHead
            FROM (SELECT contract.SERVICE,contract.contno AS ContractCount
              FROM Contract
              INNER JOIN SalePaymentPeriod
              ON Contract.RefNo = SalePaymentPeriod.RefNo
              INNER JOIN Payment ON Contract.RefNo = Payment.RefNo
              WHERE (Contract.status in ('NORMAL') OR (Contract.STATUS = 'F' AND Payment.TripID = '".$tip[0]."'))
              AND (Contract.service not like 'Y%' AND Contract.service not like '0%')
              GROUP BY contract.CONTNO,SERVICE) AS Trip
            left join Bighead_Mobile.dbo.EmployeeDetail as ed on Trip.service = ed.salecode
            WHERE  ed.SourceSystem != 'sale' $WHERE and PositionCode LIKE 'dept%'
            group by ed.EmployeeName,ed.SaleCode,ed.SupervisorCode,trip.SERVICE ";

            ?>

            <div class="box box-info">
              <div class="box-header with-border">
                <B>รายงานการวิ่งงานรายทริป <B>ระหว่างวันที่ <?=DateThai($tip[2])?> - <?=DateThai($tip[3])?></B></B>
              </div>
              <?php
              $httpExcelHead = "<B>รายงานการวิ่งงานรายทริป <B>ระหว่างวันที่ ".DateThai($tip[2])." - ".DateThai($tip[3])."</B></B>";
              $httpExcel1 = "<table width = \"100%\">
                <thead>
                <tr>
                  <td></td><td></td>
                  <th style=\"text-align: center\">ยอดการ์ด</th>
                  <th style=\"text-align: center\">การ์ดเก็บได้</th>
                  <th style=\"text-align: center\">การ์ดเก็บได้ %</th>
                  <th style=\"text-align: center\">การ์ดเก็บไม่ได้</th>
                  <th style=\"text-align: center\">การ์ดเก็บไม่ได้ %</th>
                  <th style=\"text-align: center\">ยอดใบเสร็จ</th>
                  <th style=\"text-align: center\">จำนวนเงินเก็บได้</th>
                </tr>
                </thead>
                <tbody>";
                $httpExcel2 = "";

               ?>
            <div class="box-body table-responsive no-padding">

              <table class="table table-hover table-striped">
                <tr>
                  <th style="text-align: center">ยอดการ์ด</th>
                  <th style="text-align: center">การ์ดเก็บได้</th>
                  <th style="text-align: center">การ์ดเก็บได้ %</th>
                  <th style="text-align: center">การ์ดเก็บไม่ได้</th>
                  <th style="text-align: center">การ์ดเก็บไม่ได้ %</th>
                  <th style="text-align: center">ยอดใบเสร็จ</th>
                  <th style="text-align: center">จำนวนเงินเก็บได้</th>
                </tr>
                <?php

                    $sql = "SELECT SUM(StartContCount) as totalStartContCount,sum(sumCont) as totalContno,sum(sumReceipt) as totalReceipt,sum(sumPay) as totalPay FROM (".$sql_case." ) AS Total";
                    //echo $sql;

                    $stmt = sqlsrv_query($conn,$sql);
                    while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                      if(((($row['totalStartContCount']-$row['totalContno'])/$row['totalStartContCount'])*100)<0){
                        $percenTotalMaiDai = "0";
                      }else {
                        $percenTotalMaiDai = (($row['totalStartContCount']-$row['totalContno'])/$row['totalStartContCount'])*100;
                      }
                          if (($row['totalStartContCount']-$row['totalContno']) < 0) {
                            $notcount = "0";
                          }else{
                            $notcount = number_format($row['totalStartContCount']-$row['totalContno']);
                          }
                      $httpExcel2 .= "<tr>
                        <td></td><td></td>
                        <td style=\"text-align: center\">".number_format($row['totalStartContCount'])."</td>
                        <td style=\"text-align: center\">".number_format($row['totalContno'])."</td>
                        <td style=\"text-align: center\">".number_format(($row['totalContno']/$row['totalStartContCount'])*100,2)." %</td>
                        <td style=\"text-align: center\">".$notcount."</td>
                        <td style=\"text-align: center\">".number_format($percenTotalMaiDai,2)." %</td>
                        <td style=\"text-align: center\">".number_format($row['totalReceipt'])."</td>
                        <td style=\"text-align: right\">".number_format($row['totalPay'],2)."</td>
                      </tr>";
                 ?>
                 <tr>
                   <td style="text-align: center"><?=number_format($row['totalStartContCount'])?></td>
                   <td style="text-align: center"><?=number_format($row['totalContno'])?></td>
                   <td style="text-align: center"><?=number_format(($row['totalContno']/$row['totalStartContCount'])*100,2)?> %</td>
                   <td style="text-align: center"><?php if (($row['totalStartContCount']-$row['totalContno']) < 0) { echo "0";}else{ echo number_format($row['totalStartContCount']-$row['totalContno']);}?></td>
                   <td style="text-align: center"><?=number_format($percenTotalMaiDai,2)?> %</td>
                   <td style="text-align: center"><?=number_format($row['totalReceipt'])?></td>
                   <td style="text-align: center"><?=number_format($row['totalPay'],2)?></td>
                 </tr>
                <?php
                  }

                 ?>
              </table>
            </div>
            </div>
            <?php

            $httpExcel3 = "</tbody>
            <tfoot>
            </tfoot>
           </table>";
            $html_file1 = $httpExcelHead."".$httpExcel1."".$httpExcel2."".$httpExcel3;
            //write_data_for_export_excel($html_file1, 'ReportCreditPar2');

            //echo $html_file;
             ?>

          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><H4>รายงานการวิ่งงานรายทริป</H4></center>
                <center><B>ระหว่างวันที่ <?=DateThai($tip[2])?> - <?=DateThai($tip[3])?></B></center>
                <center><B><?=$teamcode[1]?> <?=$teamcode[2]?></B></center></P>
            </div>

          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">
            <table id="example2" class="table table-hover table-striped" width="100%">
              <thead>
                <?php
                $httpExcel1 = "<table width = \"100%\">
                  <thead>
                  <tr>
                    <th style=\"text-align: center\">เขต</th>
                    <th style=\"text-align: center\">ชื่อ - สกุล</th>
                    <th style=\"text-align: center\">ยอดการ์ด</th>
                    <th style=\"text-align: center\">การ์ดเก็บได้</th>
                    <th style=\"text-align: center\">การ์ดเก็บได้ %</th>
                    <th style=\"text-align: center\">การ์ดเก็บไม่ได้</th>
                    <th style=\"text-align: center\">การ์ดเก็บไม่ได้ %</th>
                    <th style=\"text-align: center\">ยอดใบเสร็จ</th>
                    <th style=\"text-align: center\">จำนวนเงินเก็บได้</th>
                  </tr>
                  </thead>
                  <tbody>";
                  $httpExcel2 = "";

                 ?>
            <tr>
              <th style="text-align: center">เขต</th>
              <th style="text-align: center">ชื่อ - สกุล</th>
              <th style="text-align: center">ยอดการ์ด</th>
              <th style="text-align: center">การ์ดเก็บได้</th>
              <th style="text-align: center">การ์ดเก็บได้ %</th>
              <th style="text-align: center">การ์ดเก็บไม่ได้</th>
              <th style="text-align: center">การ์ดเก็บไม่ได้ %</th>
              <th style="text-align: center">ยอดใบเสร็จ</th>
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
                if(((($row['StartContCount']-$row['sumCont'])/$row['StartContCount'])*100)<0){
                  $percenMaiDai = "0";
                }else {
                  $percenMaiDai = (($row['StartContCount']-$row['sumCont'])/$row['StartContCount'])*100;
                }

              if (($row['StartContCount']-$row['sumCont']) < 0) {
                $notcount = "0";
              }else{
                $notcount = number_format($row['StartContCount']-$row['sumCont']);
              }

                $httpExcel2 .= "<tr>
                  <td style=\"text-align: center\">".$row['Zone']."</td>
                  <td>".$row['EmployeeName']."</td>
                  <td style=\"text-align: center\">".$row['StartContCount']."</td>
                  <td style=\"text-align: center\">".$row['sumCont']."</td>
                  <td style=\"text-align: center\">".number_format(($row['sumCont']/$row['StartContCount'])*100,2)." %</td>
                  <td style=\"text-align: center\">".$notcount."</td>
                  <td style=\"text-align: center\">".number_format($percenMaiDai,2)." %</td>
                  <td style=\"text-align: center\">".$row['sumReceipt']."</td>
                  <td style=\"text-align: right\">".number_format($row['sumPay'],2)."</td>
                </tr>";
             ?>
            <tr>
              <td style="text-align: center"><?=$row['Zone']?></td>
              <td><?=$row['EmployeeName']?></td>
              <td style="text-align: center"><?=$row['StartContCount']?></td>
              <td style="text-align: center"><!--<a href="#" onClick="js_popup('pages/testDeteilLog1.php?EmpID=<?=$row['AssigneeEmpID']?>&StartDate=<?=$row['StartDate']?>&EndDate=<?=$row['StopDate']?>',783,600); return false;" title="">--><?=$row['sumCont']?><!--</a></font>--></td>
              <td style="text-align: center"><?=number_format(($row['sumCont']/$row['StartContCount'])*100,2)?> %</td>
              <td style="text-align: center"><?php if (($row['StartContCount']-$row['sumCont']) < 0) { echo "0";}else{ echo $row['StartContCount']-$row['sumCont'];}?></td>
              <td style="text-align: center"><?=number_format($percenMaiDai,2)?> %</td>
              <td style="text-align: center"><?=$row['sumReceipt']?></td>
              <td style="text-align: right"><?=number_format($row['sumPay'],2)?></td>
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
              <?php
              $httpExcel3 = "</tbody>
              <tfoot>
              </tfoot>
             </table>";
              $html_file2 = $httpExcel1."".$httpExcel2."".$httpExcel3;
              $html_file = $html_file1."<BR>".$html_file2;
              write_data_for_export_excel($html_file, 'ReportCreditPar2');

              //echo $html_file;
               ?>
          <div class="box-footer clearfix">
            <a href="export_excel.php?report_type=2"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
          </div>

        </div>

        <?php
          sqlsrv_close($conn);
          }

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
  <script language="javascript">
function js_popup(theURL,width,height) { //v2.0
	leftpos = (screen.availWidth - width) / 2;
    	toppos = (screen.availHeight - height) / 2;
  	window.open(theURL, "viewdetails","width=" + width + ",height=" + height + ",left=" + leftpos + ",top=" + toppos);
}
</script>
