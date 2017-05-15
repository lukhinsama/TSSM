<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
$limit_per_page = 100;
$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
$limit_start = (($page - 1) * $limit_per_page) + 1;
$limit_end = ($page) * $limit_per_page;
if (!empty($_REQUEST['searchText'])) {
  $_REQUEST['CreditID'] = $_REQUEST['searchText'];
}
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        เทียบข้อมูลการเก็บเงิน
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบมอนิเตอร์</a></li>
        <li><i class="fa fa-user"></i> ข้อมูลบ้านแดง/บิ๊กเฮด</li>
        <li class="active">เทียบข้อมูลการเก็บเงิน</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearchLog" name="formSearchLog" method="post" action="index.php?pages=monitordata3">

        <div class="col-md-3">
          <label> เลือกวันที่เก็บเงิน </label>
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

          </div>
        </div>

        <div class="col-md-4">
          <label> เลือกพนังงานเก็บเงิน </label>
          <div class="input-group input-group-sm">
            <select class="form-control select2 input-group-sm" name="CreditID">
              <optgroup label="เลือกพนักงานเก็บเงิน">
                <!--<option value="0">เลือกทั้งหมด</option>-->
                <?php
                  $conn = connectDB_BigHead();
                  $sql = "SELECT DISTINCT em.EmpID,ca.CCode,EmployeeName
                  FROM Bighead_Mobile.dbo.Employee AS Em
                  LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed
                  ON Ed.EmployeeCode = Em.EmpID
                  LEFT JOIN TSRData_Source.dbo.CArea AS ca
                  ON ca.EmpId = Em.EmpID

                  WHERE Ed.SourceSystem = 'Credit' AND ca.ccode is not null
                  ORDER BY Em.EmpID";
                  //echo $sql;
                  $stmt = sqlsrv_query( $conn, $sql );
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
                  <option value="<?=$row['CCode']?>" <?php if ((!empty($_REQUEST['CreditID'])) && ($_REQUEST['CreditID'] == $row['CCode'])) { echo "selected"; } ?>><?=$row['EmpID']?> <?=$row['EmployeeName']?> (<?=$row['CCode']?>)</option>
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
        <div class="col-md-5">

        </div>
        </form>
      </div>

<script>
      $("#cmbChoseEmployee option:selected").val('A00075');

                  $("#cmbChoseEmployee option:selected").val('A00075').trigger('change');

                  $("#cmbChoseEmployee").each(function () {
                      if ($(this).val() == "A00075") {
                          $(this).val("A00075");
                          $(this).value = "A00075";
                      }
                  }).prop('selected', true);
      </script>
    </BR>
    <?php
      if (!empty($_REQUEST['CreditID'])) {
        if ($_REQUEST['CreditID'] == "0") {
          $where = "where (c1.contno is null or c2.contno is null)";
        }else {
          $where = "where  (c2.cashcode = '".$_REQUEST['CreditID']."' or c1.ccode = '".$_REQUEST['CreditID']."')
          and (c1.contno is null or c2.contno is null)";
        }
     ?>
      <div class="row">

        <div class="col-md-12">
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
                <?php
                $conn = connectDB_TSR();
                $sql_case = "SELECT count(CONTNO) as num  FROM [TSR_Application].[dbo].[View_Bighead_credit_2]  where cashcode = '".$_REQUEST['CreditID']."'";

                //echo $sql_case;
                //$num_row = checkNumRow($conn,$sql_case);

                $stmt = sqlsrv_query($conn,$sql_case);

                if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

                ?>
                <th colspan="5" style="text-align: center">RedHouse Data (<?=number_format($row['num'])?>)</th>
                <?php
                  }

                  $sql_case = "SELECT count([CONTNO]) as num FROM [TSR_Application].[dbo].[View_Bighead_credit_1] where [CCode] = '".$_REQUEST['CreditID']."'";

                  //echo $sql_case;
                  //$num_row = checkNumRow($conn,$sql_case);

                  $stmt = sqlsrv_query($conn,$sql_case);

                  if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                 ?>
                <th colspan="5" style="text-align: center">BigHead Data (<?=number_format($row['num'])?>)</th>
                <?php
                  }
                  sqlsrv_close($conn);
                 ?>
              </tr>
              <tr>
                <th>เลขที่สัญญา</th>
                <th>ชื่อลูกค้า</th>
                <th>งวดที่</th>
                <th>ค่างวด</th>
                <th>พนักงานเก็บเงิน</th>

                <th>เลขที่สัญญา</th>
                <th>ชื่อลูกค้า</th>
                <th>งวดที่</th>
                <th>ค่างวด</th>
                <th>พนักงานเก็บเงิน</th>
              </tr>

              <?php
              $conn = connectDB_TSR();
              $sql_case = "SELECT top 1000 row_number() OVER (ORDER BY c2.contno,c1.contno ASC) AS rownum ,c2.contno as contno2 ,c2.customername as CustomerName2 ,c2.PAYPERIOD as paymentperiodnumber2,c2.PREMIUM as NetAmount2, c2.creditname as CreditName2 ,c1.contno as contno1,c1.CustomerName as CustomerName1,c1.paymentperiodnumber as paymentperiodnumber1 ,c1.NetAmount as NetAmount1 ,c1.CreditName as CreditName1
                FROM [TSR_Application].[dbo].[View_Bighead_credit_2] as c2
                full outer join
                [TSR_Application].[dbo].[View_Bighead_credit_1] as c1
                On c2.contno = c1.contno and (c2.cashcode = c1.ccode)
                $where";

              //echo $sql_case;
              $num_row = checkNumRow($conn,$sql_case);

              $sql = "SELECT TOP $limit_per_page * FROM (".$sql_case." )AS CAMPAIGN WHERE (rownum >= '".$limit_start."' AND rownum <= '".$limit_end."')   order by CAMPAIGN.rownum";

              $stmt = sqlsrv_query($conn,$sql);

              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

              ?>

              <tr>
                <td style="text-align: center;"><?=$row['rownum']?></td>
                <td><?=$row['contno2']?></td>
                <td><?=$row['CustomerName2']?></td>
                <td><?=$row['paymentperiodnumber2']?></td>
                <td><?php if(!empty($row['NetAmount2'])) { echo number_format($row['NetAmount2']) ;} ?></td>
                <td><?=$row['CreditName2']?></td>

                <td><?=$row['contno1']?></td>
                <td><?=$row['CustomerName1']?></td>
                <td><?=$row['paymentperiodnumber1']?></td>
                <td><?php if(!empty($row['NetAmount1'])) { echo number_format($row['NetAmount1']) ;} ?></td>
                <td><?=$row['CreditName1']?></td>
              </tr>

              <?php
                }
                sqlsrv_close($conn);
               ?>
            </table>
          </div>
          <?php
          //if (isset($startDate) || isset($endDate)) {
           echo pagelimit($_GET['pages'],$num_row,$page,$sql,"","",$_REQUEST['CreditID']);
          //}

          ?>
        </div>
        </div>

      </div>
      <?php
          }
       ?>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
