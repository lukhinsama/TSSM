<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include("../include/inc-fuction.php");
 ?>
 <!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Blank Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="http://app.thiensurat.co.th/tsr_monitor_app_sale/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="http://app.thiensurat.co.th/tsr_monitor_app_sale/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="http://app.thiensurat.co.th/tsr_monitor_app_sale/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!--<body class="hold-transition skin-blue sidebar-mini">-->
<body class="skin-blue-light">
<!-- Site wrapper -->
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ข้อมูลการเก็บเงินระหว่างวันที่
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> </h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
              </div>
            </div>
            <!--
            <div class="box-body chart-responsive">
              <div class="chart" id="chart1" style="height: 300px;"></div>
            </div>
            -->
            <!-- /.box-body -->
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> รายละเอียดการเก็บเงิน</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
              </div>
            </div>
            <div class="box-body">
              <table id="example2" class="table table-hover table-striped" width="100%">
                <thead>

              <tr>
                <th style="text-align: center">งวดที่</th>
                <th style="text-align: center">การ์ดเก็บได้</th>
              </tr>
                </thead>
                <tbody>
                <?php
                $sql_case = "SELECT count(PaymentPeriodNumber) as num,PaymentPeriodNumber
from(
select
case when convert(int,PaymentPeriodNumber) between 1 AND 3 then 'งวดที่ 1 - 3'
when convert(int,PaymentPeriodNumber) between 4 AND 6 then  'งวดที่ 3 - 6'
else 'งวดที่ 9 ขึ้นไป' end as PaymentPeriodNumber
from (
SELECT  ReceiptCode ,CONVERT(varchar,PaymentDueDate) as PaymentDueDate
,case when count(ReceiptCode) = 1 then convert(varchar,min(PaymentPeriodNumber)) else convert(varchar,min(PaymentPeriodNumber)) + ' - ' + convert(varchar,Max(PaymentPeriodNumber)) end as PaymentPeriodNumber ,CONTNO ,CustomerName
,case when count(ReceiptCode) = 1 then SUM(PAYAMT) else SUM(PAYAMT) end as PAYAMT
,case when count(ReceiptCode) = 1 then SUM(PAYAMT) else SUM(PAYAMT) end as PAYAMTS
,EmpID ,Names ,Paydate ,PrintName ,SaleCode from (SELECT DISTINCT ReceiptCode
,CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) as PaymentDueDate ,Right('000'+Convert(Varchar,S.PaymentPeriodNumber),2) As PaymentPeriodNumber,c.CONTNO AS CONTNO
,CustomerName,Sy.Amount AS PAYAMT
,ISNULL ((select SendAmount from [Bighead_Mobile].[dbo].SendMoney WITH(NOLOCK)
WHERE SaveTransactionNoDate is not null AND CreateBy = em.EmpID AND cast(SaveTransactionNoDate as date)
BETWEEN cast('' as date) AND cast('' as date) ),0) as Sendmoney, Ed.FirstName + ' ' + Ed.LastName AS Names , '8 ธ.ค. 2559 - 8 ธ.ค. 2559' AS Paydate , 'อดิศร ชมดง' AS PrintName ,Em.EmpID,case when ed.SaleCode is null then '-' else ed.SaleCode end as SaleCode FROM Bighead_Mobile.dbo.Receipt AS R WITH(NOLOCK) LEFT JOIN Bighead_Mobile.dbo.Contract AS C WITH(NOLOCK) ON R.RefNo = C.RefNo LEFT JOIN Bighead_Mobile.dbo.vw_GetCustomer AS GC WITH(NOLOCK) ON C.CustomerID = GC.CustomerID LEFT JOIN SalePaymentPeriodPayment As Sy WITH(NOLOCK) ON R.PaymentID = Sy.PaymentID AND R.ReceiptID = Sy.ReceiptID LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS S WITH(NOLOCK) ON S.SalePaymentPeriodID = Sy.SalePaymentPeriodID LEFT JOIN Bighead_Mobile.dbo.Employee AS Em WITH(NOLOCK) ON R.LastUpdateBy = EM.EmpID LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed WITH(NOLOCK) ON Ed.EmployeeCode = EM.EmpID AND Ed.SourceSystem = 'Credit' WHERE R.DatePayment BETWEEN '".$_REQUEST['StartDate']." 00:00' AND '".$_REQUEST['EndDate']." 23:59' AND R.LastUpdateBy = '".$_REQUEST['EmpID']."' AND S.SalePaymentPeriodID = Sy.SalePaymentPeriodID AND Sy.Amount > 0 and ed.SaleCode is not null ) as result GROUP BY ReceiptCode,PaymentDueDate,CONTNO,CustomerName,EmpID,SaleCode,Names,Paydate,PrintName
) as a)asb
group by PaymentPeriodNumber ORDER BY PaymentPeriodNumber";
                //echo $sql_case;

                /*
                $file1 = fopen("../tsr_SaleReport/pages/sqlText3.txt","w");
                fwrite($file1,$dateSearch);
                fclose($file1);
                */
                $conn = connectDB_BigHead();
                $conns = connectDB_TSR();
                // เพิ่มลงฐานข้อมูล
                $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,[SQLtext],addtime,rpttype) VALUES (?,?,GETDATE(),8)";
        				//echo $sql_insert;

        				$params = array($_COOKIE['tsr_emp_id'],$sql_insert);
        				//print_r($params);

        				$stmt_insert = sqlsrv_query( $conns, $sql_insert, $params);

        				if( $stmt_insert === false ) {
        					 die( print_r( sqlsrv_errors(), true));
        				}


              //  $num_row = checkNumRow($conn,$sql_case);
                $SumRef = 0;
                $SumRefNO = 0;
                $SumPAYAMT = 0;



                //echo $sql_case;
                $park = array();
                $kaisod = array();
                $stmt = sqlsrv_query($conn,$sql_case);
                while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                  array_push($park,$row['PaymentPeriodNumber']);
                  array_push($kaisod,$row['num']);

                ?>

              <?php

                  /*
                  if(((($row['StartContCount']-$row['sumCont'])/$row['StartContCount'])*100)<0){
                    $percenMaiDai = "0";
                  }else {
                    $percenMaiDai = (($row['StartContCount']-$row['sumCont'])/$row['StartContCount'])*100;
                  }*/
               ?>
              <tr>
                <td style="text-align: center"><?=$row['PaymentPeriodNumber']?></td>
                <td style="text-align: center"><?=$row['num']?></td>
              </tr>
                <?php

                  }

                 ?>
               </tbody>
               <tfoot>
               </tfoot>
              </table>
            </div>
        </div>
        </div>
      </div>



    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script>
  $(function () {
    $('#chart1').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: 'รายงานยอดขาย'
        },
        subtitle: {
            text: 'ประจำปี พ.ศ. '
        },
        xAxis: {
            title: {
                text: 'รายปักษ์'
            },
            categories: [
              <?php
              /*
              $i = 0 ;

              while ($i < count($park)) {
                # code...
                if ($i != 0) {
                  echo ",";
                }
                echo $park[$i];
                $i++;
              }

              //print_r($kaisod)
              */
              ['8000000','8000000','8000000']
              ?>
            ]
        },
        yAxis: {
            title: {
                text: 'ยอดขาย (บาท)'
            },
            labels: {
                formatter: function () {
                    return this.value + '';
                }
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'ขายสด',
            data: [
              <?php
              $i = 0 ;

              while ($i < count($kaisod)) {
                # code...
                if ($i != 0) {
                  echo ",";
                }
                echo $kaisod[$i];
                $i++;
              }

              //print_r($kaisod)
              ?>
            ]
        }, {
            name: 'ขายผ่อน',
            data: [
              <?php
              $i = 0 ;

              while ($i < count($kaisod)) {
                # code...
                if ($i != 0) {
                  echo ",";
                }
                echo $kaisod[$i];
                $i++;
              }

              //print_r($kaisod)
              ?>]
        } , {
            name: 'เป้าหมาย',
            data: [8000000,8000000,8000000]
        }],
        colors : ['#D87A80','#5A75EF','#E1B980']
    });
  });
  </script>
<!-- jQuery 2.2.0 -->
<script src="http://app.thiensurat.co.th/tsr_monitor_app_sale/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="http://app.thiensurat.co.th/tsr_monitor_app_sale/bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="http://app.thiensurat.co.th/tsr_monitor_app_sale/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="http://app.thiensurat.co.th/tsr_monitor_app_sale/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="http://app.thiensurat.co.th/tsr_monitor_app_sale/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="http://app.thiensurat.co.th/tsr_monitor_app_sale/dist/js/demo.js"></script>
</body>
</html>
