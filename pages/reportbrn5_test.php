<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);


if (empty($_REQUEST['startDate']) && empty($_REQUEST['endDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,R.DatePayment,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['startDate']))." - ".DateThai(DateEng($_REQUEST['endDate']));
  $WHERE = "AND C.EFFDATE BETWEEN '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59'";
  $top = "";
}

if (!empty($_REQUEST['EmpID'])) {

  if ($_REQUEST['EmpID'] == "7") {
    $WHERE1 = "AND LEFT(C.salecode,2) IN (SELECT DISTINCT SubDepartmentCode
              FROM Bighead_Mobile.dbo.EmployeeDetail
              WHERE PositionCode = 'LineManager' AND
              ProcessType IN ('sale','brn') AND SubDepartmentCode != 'SP')";
  }else {
    $WHERE1 = "AND C.salecode LIKE '".$_REQUEST['EmpID']."%'";
  }
  if (($_REQUEST['LvEmp'] == '4')) {
    $WHERE1 = "AND C.saleteamcode = '".$_REQUEST['EmpID']."'";
  }
}
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportbrn5_test">
        <div class="col-md-2">
          <h4>
            รายงานติดตั้ง
          </h4>
        </div>
        <div class="col-md-2">
          <div class="form-group group-sm">

              <?PHP
              //if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 7)) {
              //แก้เป็นแบบล่างเพราะ เลขา มองเห็นเยอะมาก
              if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 13) || ($_COOKIE['tsr_emp_permit'] == 19)) {
                $level = 6 ;
              }else {
                //$EmpID['0'] = "A06797";
                $sql_case = "SELECT TOP 1 PositionLevel FROM [Bighead_Mobile].[dbo].[Position] WHERE PositionID in (SELECT PositionCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE (EmployeeCode = 'A".substr($_COOKIE['tsr_emp_id'],1,5)."') AND ProcessType = 'BRN') ORDER BY PositionLevel DESC";

                //echo $sql_case;
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  $level = $row['PositionLevel'];
                  }
              }


              switch ($level) {
              case "6":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="7">ทั้งหมด</option>
                <option value="6">สาย/ภาค</option>
                <option value="5">ซุป/สาขา</option>
                <option value="4">ทีม</option>
                <!--<option value="3">หน่วย</option>-->
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              case "5":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="7">ทั้งหมด</option>
                <option value="5">ซุป/สาขา</option>
                <option value="4">ทีม</option>
                <!--<option value="3">หน่วย</option>-->
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              case "4":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="7">ทั้งหมด</option>
                <option value="4">ทีม</option>
                <!--<option value="3">หน่วย</option>-->
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              /*
              case "3":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="7">ทั้งหมด</option>
                <option value="3">หน่วย</option>
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              */
              case "2":
              ?>
              <select class="form-control select2 group-sm" name="LvEmp" id = "LvEmp">
                <option value="7">ทั้งหมด</option>
                <option value="2">พนักงาน</option>
              </select>
              <?php
              break;
              }
              ?>


          </div>
        </div>
        <div class="col-md-3" >
          <select class="form-control select2 group-sm" id="EmpID"  name="EmpID">
            <option id="Emp_list" value="7">ทั้งหมด</option>
          </select>
        </div>
        <div class="col-md-4">
          <div class="input-group input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>

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
          <?php
          echo PrintButton($_COOKIE['tsr_emp_id'],'11','7',$_COOKIE['tsr_emp_permit']);
          ?>
          <!--<a href="http://app.thiensurat.co.th/lkh/rpt.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=11&rpt=7" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>-->
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
              <P><center><B>รายงานติดตั้งประจำวัน</B></center></P>
            </div>

            <?php
            $httpExcelHead = "<P><center><B>รายงานสรุปการเก็บเงิน</B></center></P>";

             ?>

          <div class="box-body table-responsive no-padding">
          <!--<div class="box-body">-->

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ที่</th>
                <th style="text-align: center">วันที่ติดตั้ง</th>
                <th style="text-align: center">ชื่อพนักงานขาย</th>
                <th style="text-align: center">ชื่อหัวหน้าทีม</th>
                <th style="text-align: center">ชื่อซุป</th>
                <th style="text-align: center">สินค้า</th>
                <th style="text-align: center">เก็บเงิน</th>
                <th style="text-align: center">ช่วงเวลา</th>
                <th style="text-align: center">สาย</th>
                <th style="text-align: center">แต้ม</th>
                <th style="text-align: center">ลูกค้า</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
                <th style=\"text-align: center\">ที่</th>
                <th style=\"text-align: center\">วันที่ติดตั้ง</th>
                <th style=\"text-align: center\">ชื่อพนักงานขาย</th>
                <th style=\"text-align: center\">ชื่อหัวหน้าทีม</th>
                <th style=\"text-align: center\">ชื่อซุป</th>
                <th style=\"text-align: center\">สินค้า</th>
                <th style=\"text-align: center\">เก็บเงิน</th>
                <th style=\"text-align: center\">แต้ม</th>
                <th style=\"text-align: center\">ช่วงเวลา</th>
                <th style=\"text-align: center\">สาย</th>
                <th style=\"text-align: center\">ที่อยู่</th>
                <th style=\"text-align: center\">แขวง/ตำบล</th>
                <th style=\"text-align: center\">เขต/อำเภอ</th>
                <th style=\"text-align: center\">จังหวัด</th>
                <th style=\"text-align: center\">รหัสไปรษณีย์</th>
                <th style=\"text-align: center\">เบอร์บ้าน</th>
                <th style=\"text-align: center\">เบอร์มือถือ</th>
                <th style=\"text-align: center\">เบอร์ที่ทำงาน</th>
                <th style=\"text-align: center\">ลูกค้า</th>
              </tr>
            </thead>
            <tbody>";
                $httpExcel2 = "";
                    $orderbyTime = "ORDER BY EFFDATE";
                  $orderbysalecode = "ORDER BY LineWork,EFFDATE ASC";

$sql_select = "SELECT DISTINCT A.RefNo,ContractReferenceNo,A.CONTNO,A.EFFDATE,A.SaleEmployeeCode,A.SaleCode,EmployeeName,A.SaleTeamCode,ProductCode,PaymentStatus,timeSet,LineWork,point
,AddressDetail,SubDistrictName,DistrictName,ProvinceName,Zipcode,TelHome,TelMobile,TelOffice
,E1.FirstName+' '+E1.LastName AS TeamHeadName
,E2.FirstName+' '+E2.LastName AS SubHeadName
,isnull(DAM.IDCard,0) as IDCard
FROM (
SELECT C.ContractReferenceNo , C.RefNo , C.CONTNO ,CONVERT(varchar(20),C.EFFDATE,105) +' '+ CONVERT(varchar(5),C.EFFDATE,108) as EFFDATE
,C.PreSaleSaleCode
,C.SaleEmployeeCode
,C.SaleCode
,C.SaleTeamCode
,ED.EmployeeName
,(SELECT top 1 TeamHeadCode FROM Bighead_Mobile.dbo.EmployeeDetail With(nolock) WHERE EmployeeCode = C.SaleEmployeeCode) AS TeamHeadCode
,(SELECT top 1 SupervisorHeadCode FROM Bighead_Mobile.dbo.EmployeeDetail With(nolock) WHERE EmployeeCode = C.SaleEmployeeCode) AS SupervisorHeadCode
,left(c.salecode,2) as LineWork
,PA.ProductCode
,S.NetAmount
,(SELECT SUM(Amount) FROM Bighead_Mobile.dbo.SalePaymentPeriodPayment With(nolock) WHERE S.SalePaymentPeriodID = SalePaymentPeriodID) AS Amount
,CASE WHEN (SELECT SUM(Amount) FROM Bighead_Mobile.dbo.SalePaymentPeriodPayment With(nolock) WHERE S.SalePaymentPeriodID = SalePaymentPeriodID) IS NULL THEN 'นัดเก็บ' WHEN (SELECT SUM(Amount) FROM Bighead_Mobile.dbo.SalePaymentPeriodPayment WHERE S.SalePaymentPeriodID = SalePaymentPeriodID) = S.NetAmount THEN 'เต็ม' ELSE 'บางส่วน' END AS PaymentStatus
,A.AddressTypeCode
,replace(replace(replace(AddressDetail+' หมู่'+AddressDetail2+' ซอย'+AddressDetail3+' ถนน'+AddressDetail4,' หมู่-',''),' ซอย-',''),' ถนน-','') AS AddressDetail
,SD.SubDistrictName,D.DistrictName,PO.ProvinceName,A.Zipcode
,A.TelHome,A.TelMobile,A.TelOffice
,case
when CAST(EFFDATE as time) >= '00:00:00.000' and  CAST(EFFDATE as time) < '12:00:00.000' then '00.00-12.00'
when CAST(EFFDATE as time) >= '12:00:00.001' and  CAST(EFFDATE as time) < '14:00:00.000' then '12.01-14.00'
when CAST(EFFDATE as time) >= '14:00:00.001' and  CAST(EFFDATE as time) < '17:00:00.000' then '14.01-17.00'
when CAST(EFFDATE as time) >= '17:00:00.001' then '17.01-23.59'
end as timeSet
,point.point
,replace(DC.IDCard,'-','') AS IDCard
FROM Bighead_Mobile.dbo.Contract AS C With(nolock)
INNER JOIN Bighead_Mobile.dbo.DebtorCustomer AS DC With(nolock) ON DC.CustomerID = C.CustomerID
INNER JOIN Bighead_Mobile.dbo.EmployeeDetail AS ED With(nolock) on C.SaleEmployeeCode = ED.EmployeeCode
INNER JOIN Bighead_Mobile.dbo.Product AS P With(nolock) ON P.ProductID = C.ProductID
LEFT JOIN TSRData_Source.dbo.ProductLineAlert AS PA With(nolock) ON PA.ProductCode = P.ProductCode
INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S With(nolock) ON S.RefNo = C.RefNo AND S.PaymentPeriodNumber = 1
INNER JOIN Bighead_Mobile.dbo.Address AS A With(nolock) ON A.RefNo = C.RefNo
INNER JOIN Bighead_Mobile.dbo.SubDistrict AS SD With(nolock) ON SD.SubDistrictCode = A.SubDistrictCode
INNER JOIN Bighead_Mobile.dbo.District AS D With(nolock) ON D.DistrictCode = A.DistrictCode
INNER JOIN Bighead_Mobile.dbo.Province AS PO With(nolock) ON PO.ProvinceCode = A.ProvinceCode
LEFT JOIN LINK_STOCK.TSR_Application.dbo.Penalty_Tempprod_m AS m With(nolock) on m.model = C.MODEL
LEFT JOIN LINK_STOCK.TSR_Application.dbo.Penalty_Com_Point AS point With(nolock) on point.model = m.productid
WHERE c.STATUS IN ('NORMAL','F') AND C.IsMigrate = 0 AND A.AddressTypeCode = 'AddressInstall'
$WHERE $WHERE1
and point.posid = 1 and point.policyid = (SELECT TOP 1 policyid FROM LINK_STOCK.TSR_Application.dbo.Penalty_Com_Point WHERE depid = 1 AND posid = 1 ORDER BY policyid DESC)
) AS A INNER JOIN Bighead_Mobile.dbo.Employee AS E1 With(nolock) ON E1.EmpID = A.TeamHeadCode
INNER JOIN Bighead_Mobile.dbo.Employee AS E2 With(nolock) ON E2.EmpID = A.SupervisorHeadCode
LEFT JOIN TSR_Application.dbo.DebtorAnalyze_Master AS DAM With(nolock) ON DAM.IDCard = A.IDCard AND DAM.CONTNO != A.CONTNO";
/*
$sql_select = "SELECT DISTINCT EFFDATE,EmployeeName,ProductCode,PaymentStatus,timeSet,LineWork,point
,AddressDetail,SubDistrictName,DistrictName,ProvinceName,Zipcode,TelHome,TelMobile,TelOffice
,E1.FirstName+' '+E1.LastName AS TeamHeadName
,E2.FirstName+' '+E2.LastName AS SubHeadName
FROM (
SELECT C.ContractReferenceNo AS RefNo , C.CONTNO ,CONVERT(varchar(20),C.EFFDATE,105) +' '+ CONVERT(varchar(5),C.EFFDATE,108) as EFFDATE
,C.PreSaleSaleCode
,C.SaleEmployeeCode
,ED.EmployeeName
,(SELECT top 1 TeamHeadCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE EmployeeCode = C.SaleEmployeeCode) AS TeamHeadCode
,(SELECT top 1 SupervisorHeadCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE EmployeeCode = C.SaleEmployeeCode) AS SupervisorHeadCode
,left(c.salecode,2) as LineWork
,PA.ProductCode
,S.NetAmount
,(SELECT SUM(Amount) FROM Bighead_Mobile.dbo.SalePaymentPeriodPayment WHERE S.SalePaymentPeriodID = SalePaymentPeriodID) AS Amount
,CASE WHEN (SELECT SUM(Amount) FROM Bighead_Mobile.dbo.SalePaymentPeriodPayment WHERE S.SalePaymentPeriodID = SalePaymentPeriodID) IS NULL THEN 'นัดเก็บ' WHEN (SELECT SUM(Amount) FROM Bighead_Mobile.dbo.SalePaymentPeriodPayment WHERE S.SalePaymentPeriodID = SalePaymentPeriodID) = S.NetAmount THEN 'เต็ม' ELSE 'บางส่วน' END AS PaymentStatus
,A.AddressTypeCode
,AddressDetail+' '+AddressDetail2+' '+AddressDetail3+' '+AddressDetail4 AS AddressDetail
,SD.SubDistrictName,D.DistrictName,PO.ProvinceName,A.Zipcode
,A.TelHome,A.TelMobile,A.TelOffice
,case
when CAST(EFFDATE as time) >= '00:00:00.000' and  CAST(EFFDATE as time) < '12:00:00.000' then '00.00-12.00'
when CAST(EFFDATE as time) >= '12:00:00.001' and  CAST(EFFDATE as time) < '14:00:00.000' then '12.01-14.00'
when CAST(EFFDATE as time) >= '14:00:00.001' and  CAST(EFFDATE as time) < '17:00:00.000' then '14.01-17.00'
when CAST(EFFDATE as time) >= '17:00:00.001' then '17.01-23.59'
end as timeSet
,point.point
FROM Bighead_Mobile.dbo.Contract AS C
INNER JOIN Bighead_Mobile.dbo.EmployeeDetail AS ED on C.SaleEmployeeCode = ED.EmployeeCode
INNER JOIN Bighead_Mobile.dbo.Product AS P ON P.ProductID = C.ProductID
LEFT JOIN TSRData_Source.dbo.ProductLineAlert AS PA ON PA.ProductCode = P.ProductCode
INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S ON S.RefNo = C.RefNo AND S.PaymentPeriodNumber = 1
INNER JOIN Bighead_Mobile.dbo.Address AS A ON A.RefNo = C.RefNo
INNER JOIN Bighead_Mobile.dbo.SubDistrict AS SD ON SD.SubDistrictCode = A.SubDistrictCode
INNER JOIN Bighead_Mobile.dbo.District AS D ON D.DistrictCode = A.DistrictCode
INNER JOIN Bighead_Mobile.dbo.Province AS PO ON PO.ProvinceCode = A.ProvinceCode
LEFT JOIN LINK_STOCK.TSR_Application.dbo.Penalty_Tempprod_m AS m on m.model = C.MODEL
LEFT JOIN LINK_STOCK.TSR_Application.dbo.Penalty_Com_Point AS point on point.model = m.productid
WHERE c.STATUS IN ('NORMAL','F') AND C.IsMigrate = 0 AND A.AddressTypeCode = 'AddressInstall'
$WHERE $WHERE1 and point.posid = 1 and point.policyid = (SELECT TOP 1 policyid FROM LINK_STOCK.TSR_Application.dbo.Penalty_Com_Point WHERE depid = 1 AND posid = 1 ORDER BY policyid DESC)
) AS A INNER JOIN Bighead_Mobile.dbo.Employee AS E1 ON E1.EmpID = A.TeamHeadCode
INNER JOIN Bighead_Mobile.dbo.Employee AS E2 ON E2.EmpID = A.SupervisorHeadCode
";
*/
$sql_case = $sql_select." ".$orderbyTime;

$sql_print = $sql_select." ".$orderbysalecode;

            //  echo $sql_case;
            //  $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
            //  fwrite($file,$sql_case);
            //  fclose($file);

              $conns = connectDB_TSR();
              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),11)";
      				//echo $sql_insert;

      				$params = array($_COOKIE['tsr_emp_id'],$sql_print);
      				//print_r($params);

      				$stmt_insert = sqlsrv_query( $conns, $sql_insert, $params);

      				if( $stmt_insert === false ) {
      					 die( print_r( sqlsrv_errors(), true));
      				}
              sqlsrv_close($conns);
              // เพิ่มลงฐานข้อมูล
              $num_row = checkNumRow($conn,$sql_case);
              $SumTotal = 0 ;

              $i=0;
              $stmt = sqlsrv_query($conn,$sql_case);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
              //  $SumTotal = $SumTotal + $row['totalprice'];
                if ($row['IDCard'] == '0') {
                  $Custom = "ใหม่";
                }else {
                  $Custom = "เก่า";
                }
                $i++;
                $httpExcel2 .= "<tr>
                <td style=\"text-align: center\">".$i."</td>
                <td style=\"text-align: center\">".DateTimeThai($row['EFFDATE'])." น.</td>
                <th style=\"text-align: center\">".$row['EmployeeName']."</td>
                <td style=\"text-align: center\">".$row['TeamHeadName']."</td>
                <td style=\"text-align: center\">".$row['SubHeadName']."</td>
                <td style=\"text-align: center\">".$row['ProductCode']."</td>
                <td style=\"text-align: center\">".$row['PaymentStatus']."</td>
                <td style=\"text-align: center\">".$row['point']."</td>
                <td style=\"text-align: center\">".$row['timeSet']."</td>
                <td style=\"text-align: center\">".$row['LineWork']."</td>
                <td style=\"text-align: left\">".$row['AddressDetail']."</td>
                <td style=\"text-align: center\">".$row['SubDistrictName']."</td>
                <td style=\"text-align: center\">".$row['DistrictName']."</td>
                <td style=\"text-align: center\">".$row['ProvinceName']."</td>
                <td style=\"text-align: center\">".$row['Zipcode']."</td>
                <td style=\"text-align: center\">".$row['TelHome']."</td>
                <td style=\"text-align: center\">".$row['TelMobile']."</td>
                <td style=\"text-align: center\">".$row['TelOffice']."</td>
                <td style=\"text-align: center\">".$Custom."</td>
                </tr>";


                 if (($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2 ) || ($_COOKIE['tsr_emp_permit'] == 6) || ($_COOKIE['tsr_emp_permit'] == 7)){
                   $typeID = "1";
                 }else {
                   $typeID = "0";
                 }
              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td style="text-align: center"><?=DateTimeThai($row['EFFDATE'])?> น.</td>
                <td style="text-align: center"><?=$row['EmployeeName']?></td>
                <td style="text-align: center"><?=$row['TeamHeadName']?></td>
                <td style="text-align: center"><?=$row['SubHeadName']?></td>
                <td style="text-align: center"><?=$row['ProductCode']?></td>
                <td style="text-align: center"><?=$row['PaymentStatus']?></td>
                <td style="text-align: center"><?=$row['timeSet']?></td>
                <td style="text-align: center"><?=$row['LineWork']?></td>
                <td style="text-align: center"><?=$row['point']?></td>
                <td style="text-align: center"><?=$Custom?></td>
              </tr>

              <?php
                }
                $httpExcel3 = "</tbody>
                <tfoot>
                </tfoot>
               </table>";
                $html_file = $html_file = $httpExcel1."".$httpExcel2."".$httpExcel3;
                write_data_for_export_excel($html_file, 'ReportTest');
               ?>
             </tbody>
             <tfoot>
             </tfoot>
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
          <table width="100%">
          <tr>
            <td style="text-align: right" width="10%"><B>รวม</B> </td>
            <td style="text-align: right" width="5%"> </td>
            <td style="text-align: left" width="10%"><?=$num_row;?> รายการ</td>
            <td style="text-align: right"><B> รวมเงิน </B></td>
            <td style="text-align: right" width="15%"></td>
          </tr>
          <!--
          <tr>
            <td style="text-align: right" width="10%"> </td>
            <td style="text-align: right" width="5%"> </td>
            <td style="text-align: left" width="10%"></td>
            <td style="text-align: right"><B> ยอดส่งเงิน </B></td>
            <td style="text-align: right" width="15%"><?=number_format($Sendmoney,2)?></td>
          </tr>
        -->
        </table>
          <a href="export_excel.php?report_type=12"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
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

	<script>

			$(function(){

				//เรียกใช้งาน Select2
				//$(".select2-single").select2();
				//ดึงข้อมูล province จากไฟล์ get_data.php
        /*
				$.ajax({
					url:"pages/getdata_type_search.php",//url:"get_data.php",
					dataType: "json", //กำหนดให้มีรูปแบบเป็น Json
					data:{levelEmp:'A00091'}, //ส่งค่าตัวแปร show_province เพื่อดึงข้อมูล จังหวัด
					success:function(data){
						//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
						$.each(data, function( index, value ) {
							//แทรก Elements ใน id province  ด้วยคำสั่ง append
							  $("#LvEmp").append("<option value='"+ value.id +"'> " + value.name + "</option>");
						});
					}
				});
        */

				//แสดงข้อมูล อำเภอ  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
				$("#LvEmp").change(function(){
					//กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
					var LvEmp = $(this).val();
					$.ajax({
						url:"pages/getdata_type_search_brn_test.php",//url:"get_data.php",
						dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
						data:{LvEmp:LvEmp},//ส่งค่าตัวแปร province_id เพื่อดึงข้อมูล อำเภอ ที่มี province_id เท่ากับค่าที่ส่งไป
						success:function(data){
							//กำหนดให้ข้อมูลใน #amphur เป็นค่าว่าง
							$("#EmpID").text("");
							//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
							$.each(data, function( index, value ) {
								//แทรก Elements ข้อมูลที่ได้  ใน id amphur  ด้วยคำสั่ง append
								  $("#EmpID").append("<option value='"+ value.id +"'> " + value.name + "</option>");
							});
						}
					});
				});
			});
	</script>
