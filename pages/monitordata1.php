<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
$limit_per_page = 100;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;
if (empty($_REQUEST['yearPark'])) {
  $selectYear = date("Y");
  $selectPak = 1;
}else {
  $yearPark = $_REQUEST['yearPark'];
  $sprit  = explode("_",$yearPark);
  $selectYear = $sprit[1];
  $selectPak = $sprit[0];
}

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ข้อมูลใบสั่งซื้อ
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบมอนิเตอร์</a></li>
        <li><i class="fa fa-user"></i> ข้อมูลบ้านแดง/บิ๊กเฮด</li>
        <li class="active">ข้อมูลใบสั่งซื้อ</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearchLog" name="formSearchLog" method="post" action="index.php?pages=monitordata1">

        <div class="col-md-3">
        </div>

        <div class="col-md-6">
          <label> กรุณาเลือกปักษ์ </label>
          <div class="input-group input-group-sm">
            <select class="form-control select2 input-group-sm" name="yearPark" id="yearPark">
              <optgroup label="เลือกปักษ์">
                <?php
                  $conn = connectDB_HR();
                  $sql = "SELECT Fortnight_no , Fortnight_year, Fortnight_year+543 as yearSale , CONVERT(varchar,dateadd(yy,543,mindate),105) as MinDate ,CONVERT(varchar,dateadd(yy,543,MaxDate),105) as  MaxDate FROM TSR_Application.dbo.view_Fortnight_Table2 ORDER BY Fortnight_year,Fortnight_no";
                  //echo $sql;
                  $stmt = sqlsrv_query( $conn, $sql );
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
                  <option value="<?=$row['Fortnight_no']."_".$row['Fortnight_year'];?>" <?php if(($selectPak == $row['Fortnight_no']) && ($selectYear == $row['Fortnight_year'])) { echo "selected"; } ?>>ปี พ.ศ.<?=$row['yearSale'];?> / ปักษ์ที่ <?=$row['Fortnight_no'];?> ระหว่างวันที่ <?=$row['MinDate'];?> - <?=$row['MaxDate'];?></option>
                <?php
                    }
                    sqlsrv_close($conn);
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
    </BR>
      <div class="row">

        <div class="col-md-9">
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
                <th rowspan="2" style="text-align: center">ลำดับ</th>
                <th colspan="5" style="text-align: center">RedHouse Data</th>
                <th colspan="5" style="text-align: center">BigHead Data</th>
              </tr>
              <tr>
                <th>เลขที่สัญญา</th>
                <th>งวดที่</th>
                <th>จำนวนเงิน</th>
                <th>รหัสพนักงานขาย</th>
                <th>วันที่</th>
                <th>เลขที่สัญญา</th>
                <th>งวดที่</th>
                <th>จำนวนเงิน</th>
                <th>รหัสพนักงานขาย</th>
                <th>วันที่</th>
              </tr>
              <?php
              $conn = connectDB_TSR();
              $sql_case = "SELECT row_number() OVER (ORDER BY c.effdate ASC) AS rownum,c.CONTNO AS ContNo_RedHouse,c.MODE as Mode_RedHouse,c.PREMIUM as Premium_RedHouse,c.SALECODE as SaleCode_RedHouse,case when datepart(YEAR,c.effdate) > 2300 then CONVERT(varchar,dateadd(YEAR,-543,c.effdate),105) else CONVERT(varchar,c.effdate,105) end as times_RedHouse
              ,b.CONTNO AS ContNo_BigHead ,b.MODE as Mode_BigHead,PAYAMT AS Premium_BigHead,b.SALECODE as SaleCode_BigHead,CONVERT(varchar,b.effdate,105)
              as times_BigHead,case when b.CONTNO IS NULL then '1' else '0' end as error
              from [TSRDATA].[dbo].[MastCont] as c left join [TSR_Application].[dbo].[view_Fortnight_Table2] as f on c.effdate between dateadd(yy,543,f.mindate) and dateadd(yy,543,f.maxdate) left join [TSS_PRD].[Bighead_Mobile].[dbo].[Contract] as b on c.RefNo = b.RefNo AND (b.effdate between f.mindate and f.maxdate) left join [TSS_PRD].[Bighead_Mobile].[dbo].[Payment] as p ON c.RefNo = p.RefNo AND (p.PayDate BETWEEN f.mindate and f.maxdate)
              where f.Fortnight_year = $selectYear AND f.Fortnight_no = $selectPak AND c.STATUS IN ('N','R','L') AND c.TYPE NOT IN ('X','?')";

              //echo $sql_case;
              $num_row = checkNumRow($conn,$sql_case);

              $sql = "SELECT TOP $limit_per_page * FROM (".$sql_case." )AS CAMPAIGN WHERE (rownum >= '".$limit_start."' AND rownum <= '".$limit_end."')   order by CAMPAIGN.rownum";

              $stmt = sqlsrv_query($conn,$sql);

              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                if ($row['error'] == "1") {
                    $styText = "style=\"color:red;\"";
                }else {
                    $styText = "";
                }

              ?>
              <tr <?=$styText?>>
                <td style="text-align: center;"><?=$row['rownum']?></td>
                <td><?=$row['ContNo_RedHouse']?></td>
                <td><?=$row['Mode_RedHouse']?></td>
                <td><?=number_format($row['Premium_RedHouse'])?></td>
                <td><?=$row['SaleCode_RedHouse']?></td>
                <td><?=DateThai($row['times_RedHouse'])?></td>

                <td><?php if (!empty($row['ContNo_BigHead'])) { echo $row['ContNo_BigHead'];}else{ echo "-";}?></td>
                <td><?php if (!empty($row['Mode_BigHead'])) { echo $row['Mode_BigHead'];}else{ echo "-";}?></td>
                <td><?php if (!empty($row['Premium_BigHead'])) { echo number_format($row['Premium_BigHead']);}else{ echo "-";}?></td>
                <td><?php if (!empty($row['SaleCode_BigHead'])) { echo $row['SaleCode_BigHead'];}else{ echo "-";}?></td>
                <td title="...."><?php if (!empty($row['times_BigHead'])) { echo DateThai($row['times_BigHead']);}else{ echo "-";}?></td>
              </tr>
              <?php
                }
               ?>
            </table>
          </div>
          <?php
          //if (isset($startDate) || isset($endDate)) {
           echo pagelimit($_GET['pages'],$num_row,$page,$sql,"","","");
          //}

          ?>
        </div>
        </div>
        <div class="col-md-3">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> ข้อมูลรวม ปี <?php echo $selectYear+543;?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped">
              <!--
              <tr>
                <th style="text-align: center">ปี</th>
                <th>รวมขายสด (บาท)</th>
                <th>รวมขายผ่อน (บาท)</th>
                <th>ยอดขายรวมทั้งปี (บาท)</th>
              </tr>
            -->
              <?php
              $conn = connectDB_TSR();
              $sql_case = "SELECT COUNT(EFFDATE) AS Num from TSRDATA.dbo.MastCont as c left join TSR_Application.dbo.view_Fortnight_Table2 as f on c.effdate between DateAdd(year,+543,F.MinDate) AND DateAdd(year,+543,F.MaxDate) where f.Fortnight_year = $selectYear AND STATUS IN ('N','R','L') AND TYPE NOT IN ('X','?')";

              $stmt = sqlsrv_query($conn,$sql_case);

              while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                $RedHouse = $row1['Num'];
              ?>
              <tr>
                <td> RedHouse Data </td>
                <td><?=number_format($RedHouse)?></td>
              </tr>
              <?php
                }
               ?>
               <?php
               $conn = connectDB_TSR();
               $sql_case = "SELECT COUNT(EFFDATE) AS Num FROM TSS_PRD.Bighead_Mobile.dbo.Contract";

               $stmt = sqlsrv_query($conn,$sql_case);

               while ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                 $BigHead = $row1['Num'];
               ?>
               <tr>
                 <td> BigHead Data</td>
                 <td><?=number_format($BigHead)?></td>
               </tr>
               <?php
                 }
                 if ($RedHouse > $BigHead) {
                   $sum = $RedHouse - $BigHead;
                   $tdt = "style=\"color:red;\"";
                 }else {
                   $sum = $BigHead - $RedHouse;
                   $tdt = "style=\"color:black;\"";
                 }
                ?>
                <tr>
                  <td <?=$tdt;?>> Diff Data </td>
                  <td <?=$tdt;?>><?=number_format($sum)?></td>
                </tr>
            </table>
          </div>

        </div>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
