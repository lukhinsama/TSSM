<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
if (!empty($_POST['printID'])) {
    $printID = $_POST['printID'];
    $ReceiptCode = "";
    $i = 0 ;
    foreach($printID as $x => $x_value) {
        if ($i != 0) {
          $ReceiptCode .= ",";
        }
        $i++;
        $ReceiptCode .= "'".$x_value."'";
    }

    $WHERE1 = "WHERE R.ReceiptCode IN ($ReceiptCode)";

    $SQLPrint = "SELECT distinct e.empid,
    (select TSRData_Source.dbo.fn_convertDatetothai(R.DatePayment)) as PaymentDueDate,
    R.ReceiptCode
    ,R.ZoneCode,E.FirstName+' '+E.LastName AS EmpName
    ,C.CONTNO ,C.SALES,C.ProductSerialNumber,C.MODEL,C.MODE
    ,P.ProductName
    ,DC.CustomerName
    ,SP.PaymentPeriodNumber , R.TotalPayment AS NetAmount
    ,REPLACE(REPLACE(REPLACE(AD.AddressDetail+ ' หมู่ ' + AD.AddressDetail2 + ' ซอย ' + AD.AddressDetail3 + ' ถนน ' + AD.AddressDetail4
    ,'หมู่ -',' '),'ซอย -',' '),'ถนน -',' ') AS ADDRESS1
    ,' ต. ' +(SELECT SubDistrictName FROM Bighead_Mobile.dbo.SubDistrict WHERE SubDistrictCode = AD.SubDistrictCode)
    + ' อ. ' +(SELECT DistrictName FROM Bighead_Mobile.dbo.District WHERE DistrictCode = AD.DistrictCode)
    + ' จ. ' +(SELECT ProvinceName FROM Bighead_Mobile.dbo.Province WHERE ProvinceCode = AD.ProvinceCode)
    +' รหัสไปรษณีย์ ' + Zipcode AS ADDRESS2
    ,ISNULL(CASE WHEN SP.PaymentComplete = 1 THEN (SELECT SUM(NetAmount) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE refno = C.RefNo AND PaymentPeriodNumber > Sp.PaymentPeriodNumber) ELSE SP.NetAmount - R.TotalPayment + (SELECT SUM(NetAmount) FROM Bighead_Mobile.dbo.SalePaymentPeriod WHERE refno = C.RefNo AND PaymentPeriodNumber > Sp.PaymentPeriodNumber) END,0) AS Balance
    FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R
    INNER JOIN Bighead_Mobile.dbo.Contract AS C ON R.RefNo = C.RefNo
    INNER JOIN Bighead_Mobile.dbo.DebtorCustomer AS DC ON C.CustomerID = DC.CustomerID
    INNER JOIN Bighead_Mobile.dbo.Employee AS E ON R.CreateBy = EmpID
    LEFT JOIN Bighead_Mobile.dbo.Product AS P ON P.ProductID = C.ProductID
    LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPP ON SPP.ReceiptID = R.ReceiptID
    LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS SP ON SPP.SalePaymentPeriodID = SP.SalePaymentPeriodID
    LEFT JOIN Bighead_Mobile.dbo.MigrateReportDailyReceiptB AS B ON B.InvNo = R.ReceiptCode
    LEFT JOIN Bighead_Mobile.dbo.Address AS AD ON AD.RefNo = C.RefNo AND AD.AddressTypeCode = 'AddressPayment'
    $WHERE1";
    //ECHO $SQLPrint;

    $conns = connectDB_TSR();
    // เพิ่มลงฐานข้อมูล
    $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),99)";
    //echo $sql_insert;

    $params = array($_POST['EmpID'],$SQLPrint);
    //print_r($params);

    $stmt_insert = sqlsrv_query( $conns, $sql_insert, $params);

    if( $stmt_insert === false ) {
       die( print_r( sqlsrv_errors(), true));
    }
    sqlsrv_close($conns);

    $conns = connectDB_BigHead();
    foreach($printID as $y => $y_value) {
        // เพิ่มลงฐานข้อมูล
        $sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_LOG_PrintReceipt_BOF (Empid,ReceiptCode,StampTime) VALUES (?,?,GETDATE())";
        //echo $sql_insert;

        $params = array($_POST['EmpID'],$y_value);
        //print_r($params);

        $stmt_insert = sqlsrv_query( $conns, $sql_insert, $params);

        if( $stmt_insert === false ) {
           die( print_r( sqlsrv_errors(), true));
        }
    }
    sqlsrv_close($conns);
    ?>
    <META HTTP-EQUIV="Refresh" CONTENT="0;URL=http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PrintReceipt_D.aspx?empid=<?=$_POST['EmpID']?>">
    <?PHP
  }


if (empty($_REQUEST['searchDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,R.DatePayment,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['searchDate']));
  $WHERE = " R.DatePayment BETWEEN '".DateEng($_REQUEST['searchDate'])." 00:00' AND '".DateEng($_REQUEST['searchDate'])." 23:59'";
  $top = "";
}

if (empty($_REQUEST['startDate']) && empty($_REQUEST['endDate'])) {
  $searchDate = DateThai(date('Y-m-d'));
  $WHERE = " datediff(DAY,R.DatePayment,GETDATE())=0 ";
  $top = "TOP 10";
}else {
  $searchDate = DateThai(DateEng($_REQUEST['startDate']))." - ".DateThai(DateEng($_REQUEST['endDate']));
  $WHERE = " R.DatePayment BETWEEN '".DateEng($_REQUEST['startDate'])." 00:00' AND '".DateEng($_REQUEST['endDate'])." 23:59'";
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

    $WHERE .= " AND R.ZoneCode = '".$EmpID['2']."'";


  }
}else {
  //echo $_REQUEST['EmpID'];
  if (!empty($_REQUEST['EmpID'])) {
    //$EmpID = array('0','-');
    $EmpID = explode("_",$_REQUEST['EmpID']);
    $WHERE .= " AND R.ZoneCode = '".$EmpID['2']."'";
  }

}
  $conn = connectDB_BigHead();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=reportOperPrintReceipt">
        <div class="col-md-3">
          <h4>
            รายงานใบเสร็จรายบุคคล
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
                $sql_case = "SELECT SaleCode as mcode,EmployeeName AS Name ,EmployeeCode AS EmpID,case when SaleCode is null then '-' else SaleCode end as SaleCode ,SupervisorCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE  salecode is not null AND SupervisorCode is not null ORDER BY mcode";

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
          <!--
          <a href="http://app.thiensurat.co.th/lkh/rpt_lk1.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=1" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
        -->
        </div>
        </form>
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

        <div class="col-xs-12">
          <?php
            if (!empty($_REQUEST['searchDate']) || ((!empty($_REQUEST['startDate']) && !empty($_REQUEST['endDate'])))) {

           ?>
          <div class="box box-info">
          <!--<div class="box-body table-responsive no-padding">-->
          <div class="box-body">
            <form role="form" data-toggle="validator" id="formPrint" name="formPrint" method="post" action="index.php?pages=reportOperPrintReceipt">
              <button type="submit" class="btn btn-default"><i class="fa fa-print"></i></button>
            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th style="text-align: center">ลำดับ</th>
                <th style="text-align: center">เลขที่ใบเสร็จ</th>
                <th style="text-align: center">เวลาออกใบเสร็จ</th>
                <th style="text-align: center">งวดที่</th>
                <th style="text-align: center">เลขที่สัญญา</th>
                <th style="text-align: center">ชื่อ - สกุล</th>
                <th style="text-align: center">จำนวนเงิน</th>
                <th style="text-align: center">ค่างวด</th>
                <th style="text-align: right"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onclick="checkAll('chkbox');"></th>

              </tr>
            </thead>
            <tbody>
              <div class="form-group">
              <?php
                $httpExcel2 = "";
              $sql_select = "SELECT ReceiptCode
              ,CONVERT(varchar,PaymentDueDate) as PaymentDueDate
              ,case when count(ReceiptCode) = 1 then convert(varchar,min(PaymentPeriodNumber)) else convert(varchar,min(PaymentPeriodNumber)) + ' - ' + convert(varchar,Max(PaymentPeriodNumber)) end as PaymentPeriodNumber
              ,CONTNO
              ,CustomerName
              ,case when count(ReceiptCode) = 1 then SUM(PAYAMT) else SUM(PAYAMT) end as PAYAMT
              ,case when count(ReceiptCode) = 1 then SUM(PAYAMT) else SUM(PAYAMT) end as PAYAMTS
              ,EmpID
              ,Names
              ,Paydate
              ,PrintName
              ,SaleCode
              , 'รายงานสรุปการเก็บเงิน' AS printHead,PaymentAmount
              from (SELECT DISTINCT ReceiptCode
              ,CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) as PaymentDueDate
              ,Right('000'+Convert(Varchar,S.PaymentPeriodNumber),2) As PaymentPeriodNumber,c.CONTNO AS CONTNO,CustomerName,Sy.Amount AS PAYAMT
              , Em.FirstName + ' ' + Em.LastName AS Names , '".$searchDate."' AS Paydate , '".$_COOKIE['tsr_emp_name']."' AS PrintName,R.CreateBy as EmpID,R.ZoneCode as SaleCode,s.PaymentAmount ";


              $sql_body = " FROM TSRData_Source.dbo.vw_ReceiptWithZone AS R WITH(NOLOCK) LEFT JOIN Bighead_Mobile.dbo.Contract AS C WITH(NOLOCK) ON R.RefNo = C.RefNo LEFT JOIN Bighead_Mobile.dbo.vw_GetCustomer AS GC WITH(NOLOCK) ON C.CustomerID = GC.CustomerID LEFT JOIN SalePaymentPeriodPayment As Sy WITH(NOLOCK) ON R.PaymentID = Sy.PaymentID AND R.ReceiptID = Sy.ReceiptID LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S WITH(NOLOCK) ON S.SalePaymentPeriodID = Sy.SalePaymentPeriodID LEFT JOIN Bighead_Mobile.dbo.Employee AS Em WITH(NOLOCK) ON R.LastUpdateBy = EM.EmpID WHERE $WHERE AND S.SalePaymentPeriodID = Sy.SalePaymentPeriodID AND Sy.Amount > 0 AND R.TypeCode = 0
              ) as result GROUP BY ReceiptCode,PaymentDueDate,CONTNO,CustomerName,EmpID,SaleCode,Names,Paydate,PrintName,PaymentAmount ORDER BY ReceiptCode";

              $sql_case = $sql_select." ".$sql_body;
              //echo $sql_case;

              // เพิ่มลงฐานข้อมูล
              $num_row = checkNumRow($conn,$sql_case);
              $SumTotal = 0 ;

              $i=0;
              $stmt = sqlsrv_query($conn,$sql_case);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $SumTotal = $SumTotal + $row['PAYAMT'];
                $i++;
              ?>

              <tr>
                <td style="text-align: center"><?=$i?></td>
                <td><?=$row['ReceiptCode']?></td>
                <td style="text-align: center"><?=DateTimeThai($row['PaymentDueDate'])?> น.</td>
                <td style="text-align: center"><?=$row['PaymentPeriodNumber']?></td>
                <td style="text-align: center"><?=$row['CONTNO']?></td>
                <td><?=$row['CustomerName']?></td>
                <td style="text-align: right"><?=number_format($row['PAYAMT'],2)?></td>
                <td style="text-align: right"><?=number_format($row['PaymentAmount'],2)?></td>
                <td style="text-align: center"><input type="checkbox" id='chkbox' name="printID[<?=$row['ReceiptCode']?>]" class="flat" value="<?=$row['ReceiptCode']?>"></td>
              </tr>

              <?php
                }
               ?>
               </div>
             </tbody>
             <tfoot>
             </tfoot>
            </table>
            <?php
            if (substr($_COOKIE['tsr_emp_id'],0,1) == "0") {
              $EmpID = "A".substr($_COOKIE['tsr_emp_id'],1,5);
            }else {
              $EmpID = $_COOKIE['tsr_emp_id'];
            }
             ?>
            <input type="hidden" name="EmpID" value="<?=$EmpID?>">
            <button type="submit" class="btn btn-default"><i class="fa fa-print"></i></button>
          </form>
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
            <td style="text-align: left" width="10%"><?=$num_row;?> ใบ</td>
            <td style="text-align: right"><B> รวมเงิน </B></td>
            <td style="text-align: right" width="15%"><?=number_format($SumTotal,2)?></td>
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
        <!--
          <a href="export_excel.php?report_type=3"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
        -->
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
        var oTable =  $('#example2').DataTable({
        //"stateSave": true
        "pageLength": 20,
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": true
      });

      var allPages = oTable.cells( ).nodes( );

      $('#CheckAll').click(function () {
          if ($(this).hasClass('checkAll')) {
              $(allPages).find('input[type="checkbox"]').prop('checked', false);
          } else {
              $(allPages).find('input[type="checkbox"]').prop('checked', true);
          }
          $(this).toggleClass('checkAll');
      })

    });
  </script>
  <script type='text/javascript'>

  function checkAll(id)
  {
  	elm=document.getElementsByTagName('input');
  	for(i=0; i<elm.length ;i++){
  		 if(elm[i].id==id){
  				elm[i].checked = true ;
  		  }
  	   }

  }

  function uncheckAll(id)
  {
  	elm=document.getElementsByTagName('input');
  	for(i=0; i<elm.length ;i++){
  		 if(elm[i].id==id){
  				elm[i].checked = false ;
  		  }
  	   }
  }

  </script>
