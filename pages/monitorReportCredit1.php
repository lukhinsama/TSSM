<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
$limit_per_page = 20;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;
/*
if (empty($_REQUEST['yearPark'])) {
  $selectYear = date("Y");
  $selectPak = 1;
}else {
  $yearPark = $_REQUEST['yearPark'];
  $sprit  = explode("_",$yearPark);
  $selectYear = $sprit[1];
  $selectPak = $sprit[0];
}
*/

if (!empty($_REQUEST['searchText'])) {
  $_REQUEST['searchDate'] = $_REQUEST['searchText'];
}
if (empty($_REQUEST['searchDate'])) {
   $searchDate = DateThai(date('Y-m-d'));
   $y = date('Y');
   $m = date('m');
   $d = date('d');
   $dateSearch = $d."/".$m."/".$y;
   $WHERE = "AND datediff(DAY,P.DatePayment,GETDATE())=0 ";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['searchDate']));
  $y =  substr($_REQUEST['searchDate'],6,4);
  $m =  substr($_REQUEST['searchDate'],3,2);
  $d =  substr($_REQUEST['searchDate'],0,2);
  $dateSearch = "(".$d."/".$m."/".$y." - ".$d."/".$m."/".$y.")";
  $WHERE = "AND P.DatePayment BETWEEN '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59'";
}

if (!empty($_REQUEST['q']) ) {
  $_REQUEST['TeamCode'] = $_REQUEST['q'];
}

if (!empty($_REQUEST['TeamCode']) ) {
  $SupervisorCode = " AND BH.EmpId in (SELECT EmployeeCode  FROM [TSS_PRD].[Bighead_Mobile].[dbo].[EmployeeDetail] WHERE SupervisorCode = '".$_REQUEST['TeamCode']."' GROUP BY EmployeeCode)";
  $sum1 = "AND AssigneeEmpID in (SELECT EmployeeCode FROM [TSS_PRD].[Bighead_Mobile].[dbo].[EmployeeDetail] WHERE SupervisorCode = '".$_REQUEST['TeamCode']."' GROUP BY EmployeeCode)";
  $sum2 = "AND EmpID in (SELECT EmployeeCode FROM [TSS_PRD].[Bighead_Mobile].[dbo].[EmployeeDetail] WHERE SupervisorCode = '".$_REQUEST['TeamCode']."' GROUP BY EmployeeCode)";
}else {
  $SupervisorCode = "";
  $sum1 = "";
  $sum2 = "";
}
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <div class="col-md-3">
          <h4>
            เทียบการเก็บเงินรายวัน
          </h4>
        </div>
        <div class="col-md-3">
          <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=monitorReportCredit1">
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
              $sql_case = " SELECT [SupervisorCode],
              CASE [SupervisorCode]
            	WHEN '101' THEN 'ทีม A'
            	WHEN '102' THEN 'ทีม B'
            	WHEN '103' THEN 'ทีม D'
            	WHEN '104' THEN 'ทีม E'
            	WHEN '105' THEN 'ทีม F'
            	WHEN '106' THEN 'ทีม H'
              ELSE 'ทีม I'
              END AS TeamCode
              FROM [Bighead_Mobile].[dbo].[EmployeeDetail] AS Emd
              LEFT JOIN [Bighead_Mobile].[dbo].[Employee] AS Em
              ON Emd.EmployeeCode = Em.EmpID
              WHERE SourceSystem = 'Credit' AND saleCode is not null AND (EmployeeTypeCode is not null AND EmployeeTypeCode != '') $supcode
              GROUP BY [SupervisorCode]";

              //echo $sql_case;
              $stmt = sqlsrv_query($conn,$sql_case);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
              ?>
            <option value="<?=$row['SupervisorCode']?>"><?=$row['TeamCode']?></option>
              <?php
                }
                sqlsrv_close($conn);
              ?>
            </optgroup>
          </select>

        </div>
        <div class="col-md-4">

            <div class="input-group input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <!--
              <div class="input-group input-group input-daterange" id="datepicker2">
                      <input type="text" class="form-control" name="startDate" value="<?php if(isset($_REQUEST['startDate'])) {echo $_REQUEST['startDate'];}?>" placeholder="วันเริ่มต้น .." required>
                      <span class="input-group-addon">ถึง</span>
                      <input type="text" class="form-control" name="endDate" value="<?php if(isset($_REQUEST['endDate'])) {echo $_REQUEST['endDate'];}?>" placeholder="วันสิ้นสุด .." required>
                  </div>
                  -->
              <input type="text" class="form-control" name="searchDate" id="datepicker2"  placeholder="กรอกวันที่ .." required>
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>

        <div class="col-md-2">
        <!--  <a href="http://app.thiensurat.co.th/lkh/rpt_lk2.aspx" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>  -- >
        </div>
      </div>

      <!--
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> รายงาน</a></li>
        <li><i class="fa fa-user"></i> รายงาน(ฝ่ายเครดิต)</li>
        <li class="active"> สรุปการเก็บเงินรายวัน </li>
      </ol>
    -->

    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-md-12">
          <?php
            if (!empty($_REQUEST['searchDate'])) {
           ?>
          <div class="box box-info">
            <div class="box-header with-border">
              <P><center><H4>เทียบการเก็บเงินรายวัน</H4></center><center><B>ประจำวันที่ <?=$searchDate;?></B></center></P>
            </div>

          <div class="box-body table-responsive no-padding">

            <table class="table table-hover table-striped">
              <tr>
                <th rowspan="2" colspan="2">พนักงานเก็บเงิน</th>
                <th colspan="3" style="text-align: center">Credit Data</th>
                <th colspan="3" style="text-align: center">BigHead Data</th>
              </tr>
              <tr>
                <th style="text-align: center">ใบเสร็จ</th>
                <th style="text-align: center">สัญญา</th>
                <th style="text-align: center">จำนวนเงิน</th>
                <th style="text-align: center">ใบเสร็จ</th>
                <th style="text-align: center">สัญญา</th>
                <th style="text-align: center">จำนวนเงิน</th>
              </tr>
              <?php
              $conn = connectDB_TSR();

            $sql_case = "SELECT row_number() OVER (ORDER BY BH.CCode ASC) AS rownum
            , BC.AssigneeEmpID AS BCEmpid , BC.AssigneeEmpName As BCName , BC.RefAmt as BCRefNO , BC.DocAmt AS BCRef , BC.amt as BCPayamt , BC.PayDate as BCPayDATE
            , BH.CCode As BHccode ,BH.EmpId as BHEmpid,BH.Name As BHName, BH.Ref As BHRef,BH.RefNO as BHRefNo,BH.PAYAMT AS BHPayamt,BH.PayDate as BHPaydate
            FROM TSR_Application.dbo.vw_BigHead_ChangePayByCustomer AS BC
            LEFT JOIN TSR_Application.dbo.Compare_Sum_Payment_BigHead AS BH
            ON BC.AssigneeEmpID = BH.EmpId AND CONVERT(VARCHAR(10),BC.PayDate,105) = CONVERT(VARCHAR(10),BH.PayDate,105)                         WHERE BC.Paydate Between '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59' $SupervisorCode
            ";

              /*
              $sql_case = "SELECT row_number() OVER (ORDER BY RH.CCode ASC) AS rownum , RH.CCode AS RHccode,RH.Name AS RHname, RH.Ref AS RHref,RH.RefNO AS RHrefno,RH.PAYAMT AS RHpayamt,BH.CCode AS BHccode,BH.Name AS BHname,BH.Ref AS BHref,BH.PAYAMT AS BHpayamt,BH.RefNO AS BHrefno
              FROM
              [TSR_Application].[dbo].[Compare_Sum_Payment_RedHouse] AS RH
              INNER JOIN
              [TSR_Application].[dbo].[Compare_Sum_Payment_BigHead] AS BH
              ON RH.ccode = BH.CCode AND RH.PayDATE = BH.PayDATE
              WHERE
              RH.Paydate Between '".DateEng($_REQUEST['searchDate'])."' AND '".DateEng($_REQUEST['searchDate'])."'
              AND RH.name is not null AND (RH.ref != BH.Ref OR RH.refno != BH.RefNO OR RH.PAYAMT != BH.PAYAMT)";
              */

              //echo $sql_case;
              /*
              $file = fopen("../tsr_SaleReport/pages/sqlText1.txt","w");
              fwrite($file,$sql_case);
              fclose($file);

              $file1 = fopen("../tsr_SaleReport/pages/sqlText3.txt","w");
              fwrite($file1,$dateSearch);
              fclose($file1);
              */

              $num_row = checkNumRow($conn,$sql_case);
              $SumRef = 0;
              $SumRefNO = 0;
              $SumPAYAMT = 0;
              $sql = "SELECT TOP $limit_per_page * FROM (".$sql_case." )AS CAMPAIGN WHERE (rownum >= '".$limit_start."' AND rownum <= '".$limit_end."')   order by CAMPAIGN.rownum";
              //echo $sql_case;
              $stmt = sqlsrv_query($conn,$sql);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                if ($row['BCRef'] != $row['BHRef']) {
                  $RefText = "red";
                }else{
                  $RefText = "black";
                }
                if ($row['BCRefNO'] != $row['BHRefNo']) {
                  $RefNoText = "red";
                }else{
                  $RefNoText = "black";
                }
                if ($row['BCPayamt'] != $row['BHPayamt']) {
                  $PayamtText = "red";
                }else{
                  $PayamtText = "black";
                }

              ?>
              <tr>
                <td><?=$row['BCEmpid']?></td>
                <td><?=$row['BCName']?></td>
                <td style="text-align: right"><font color="<?=$RefText?>"><?=$row['BCRef']?> ใบ</font></td>
                <td style="text-align: right"><font color="<?=$RefNoText?>"><?=$row['BCRefNO']?> ใบ</font></td>
                <td style="text-align: right"><font color="<?=$PayamtText?>"><?=number_format($row['BCPayamt'],2)?></font></td>
                <td style="text-align: right"><font color="<?=$RefText?>"><?=$row['BHRef']?> ใบ</font></td>
                <td style="text-align: right"><font color="<?=$RefNoText?>"><?=$row['BHRefNo']?> ใบ</font></td>
                <td style="text-align: right"><font color="<?=$PayamtText?>"><?=number_format($row['BHPayamt'],2)?></font></td>
              </tr>
              <?php
                }

               ?>
            </table>
          </div>
          <div class="box-footer clearfix">
            <!--
            <ul class="pagination pagination-sm no-margin pull-right">
              <li><a href="#">&laquo;</a></li>
              <li><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">&raquo;</a></li>
            </ul>
            -->
            <!--
          <table width="100%">
          <tr>
            <td style="text-align: center" ><font color="red"><B>รวมรายการเปรียบเทียบที่ไม่เท่ากัน</B></font> จำนวน <?=number_format($num_row)?> รายการ</td>
          </tr>
          </table>
        -->

          </div>
          <?php
          //if (isset($startDate) || isset($endDate)) {
           echo pagelimit($_GET['pages'],$num_row,$page,$_REQUEST['TeamCode'],"","",$_REQUEST['searchDate']);
          //}

          ?>
        </div>
        <div class="box box-info">
          <div class="box-header with-border">
            <B>รายการเก็บเงิน ประจำวันที่ <?=$searchDate;?></B>
          </div>

        <div class="box-body table-responsive no-padding">

          <table class="table table-hover table-striped">
            <tr>
              <th colspan="3" style="text-align: center">Credit Data</th>
              <th colspan="3" style="text-align: center">BigHead Data</th>
            </tr>
            <tr>
              <th style="text-align: center">ใบเสร็จ</th>
              <th style="text-align: center">สัญญา</th>
              <th style="text-align: center">จำนวนเงิน</th>
              <th style="text-align: center">ใบเสร็จ</th>
              <th style="text-align: center">สัญญา</th>
              <th style="text-align: center">จำนวนเงิน</th>
            </tr>
            <tr>
              <?php
                  $sqls1 = "SELECT SUM(RefAmt) as Ref , SUM(DocAmt) as RefNO , SUM(amt) as Payamt
                  FROM [TSR_Application].[dbo].vw_BigHead_ChangePayByCustomer WHERE Paydate Between '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59' ".$sum1."";
                  //echo $sqls1;
                  $stmt = sqlsrv_query($conn,$sqls1);
                  while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
               ?>
              <td style="text-align: right" width="16%"><?=number_format($row1['Ref'])?> ใบ</td>
              <td style="text-align: right" width="16%"><?=number_format($row1['RefNO'])?> ใบ</td>
              <td style="text-align: right" width="16%"><?=number_format($row1['Payamt'],2)?></td>
              <?php
                }
                $sqls2 = "  SELECT SUM(Ref) as Ref , SUM(RefNO) as RefNO , SUM(PAYAMT) as Payamt FROM TSR_Application.dbo.Compare_Sum_Payment_BigHead WHERE Paydate Between '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59' ".$sum2."";
                //echo $sqls2;
                $stmt = sqlsrv_query($conn,$sqls2);
                while ($row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
               ?>
              <td style="text-align: right" width="16%"><?=number_format($row2['Ref'])?> ใบ</td>
              <td style="text-align: right" width="16%"><?=number_format($row2['RefNO'])?> ใบ</td>
              <td style="text-align: right" width="16%"><?=number_format($row2['Payamt'],2)?></td>
              <?php
                }
                sqlsrv_close($conn);
               ?>
            </tr>
          </table>
        </div>
      </div>
        <?php
          }
        ?>
        </div>

      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
