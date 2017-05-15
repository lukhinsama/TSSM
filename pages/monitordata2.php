<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
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
        ข้อมูลรายงานเก็บเงิน
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบมอนิเตอร์</a></li>
        <li><i class="fa fa-user"></i> ข้อมูลบ้านแดง/บิ๊กเฮด</li>
        <li class="active">ข้อมูลรายงานเก็บเงิน</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearchLog" name="formSearchLog" method="post" action="index.php?pages=monitordata2">

        <div class="col-md-3">
        </div>

        <div class="col-md-6">
          <label> เลือกพนังงานเก็บเงิน </label>
          <div class="input-group input-group-sm">
            <select class="form-control select2 input-group-sm" name="CreditID">
              <optgroup label="เลือกพนักงานเก็บเงิน">
                <!--<option value="0">เลือกทั้งหมด</option>-->
                <?php
                  $conn = connectDB_BigHead();

                    $sql = "SELECT SaleCode as mcode,EmployeeName AS Name ,EmployeeCode AS EmpID,case when SaleCode is null then '-' else SaleCode end as SaleCode ,SupervisorCode FROM Bighead_Mobile.dbo.EmployeeDetail WHERE  salecode is not null AND SupervisorCode is not null AND (SourceSystem = 'Credit' OR SourceSystem = 'Dept') ORDER BY mcode";

                  //echo $sql;
                  $stmt = sqlsrv_query( $conn, $sql );
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
                  <!--<option value="<?=$row['CCode']?>" <?php if ((!empty($_REQUEST['CreditID'])) && ($_REQUEST['CreditID'] == $row['CCode'])) { echo "selected"; } ?>><?=$row['EmpID']?> <?=$row['Name']?> (<?=$row['CCode']?>) (<?=$row['SaleCode']?>)</option>-->
                  <option value="<?=$row['mcode']?>" <?php if ((!empty($_REQUEST['CreditID'])) && ($_REQUEST['CreditID'] == $row['mcode'])) { echo "selected"; } ?>><?=$row['EmpID']?> <?=$row['Name']?> (<?=$row['mcode']?>)</option>
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
    <?php
      if (!empty($_REQUEST['CreditID'])) {
        if ($_REQUEST['CreditID'] == "0") {
          $where = "where (c1.contno is null or c2.contno is null)";
        }else {
          $where = "where  (c2.mcode = '".$_REQUEST['CreditID']."' or c1.mcode = '".$_REQUEST['CreditID']."')
          and (c1.contno is null or c2.contno is null

          OR c2.cashcode is null
          OR c1.mcode is null OR c2.mcode is null

          OR c2.mcode != c1.mcode
          OR c1.RefNo != c2.RefNo)";
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
                $sql_case = "SELECT count(CONTNO) as num  FROM [TSR_Application].[dbo].[View_Bighead_credit_1_bak]  where mcode = '".$_REQUEST['CreditID']."'";

                //echo $sql_case;
                //$num_row = checkNumRow($conn,$sql_case);

                $stmt = sqlsrv_query($conn,$sql_case);

                if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

                ?>
                  <th colspan="6" style="text-align: center"> ข้อมูลจากบิ๊กเฮด BigHead Data (<?=number_format($row['num'])?>)</th>

                <?php
                  }

                  $sql_case = "SELECT count([CONTNO]) as num FROM [TSR_Application].[dbo].[View_Bighead_credit_2] where mcode = '".$_REQUEST['CreditID']."'";

                  //echo $sql_case;
                  //$num_row = checkNumRow($conn,$sql_case);

                  $stmt = sqlsrv_query($conn,$sql_case);

                  if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                 ?>
                <th colspan="6" style="text-align: center"> ข้อมูลจากบ้านแดง RedHouse Data (<?=number_format($row['num'])?>)</th>
                <?php
                  }
                  sqlsrv_close($conn);
                 ?>
              </tr>
              <tr>
                <th>เลขที่สัญญา</th>
                <th>เลขที่อ้างอิง</th>
                <th>ชื่อลูกค้า</th>
                <th>งวดที่</th>
                <th>ค่างวด</th>
                <th>พนักงานเก็บเงิน</th>

                <th>เลขที่สัญญา</th>
                <th>เลขที่อ้างอิง</th>
                <th>ชื่อลูกค้า</th>
                <th>งวดที่</th>
                <th>ค่างวด</th>
                <th>พนักงานเก็บเงิน</th>
              </tr>

              <?php
              $conn = connectDB_TSR();
              $sql_case = "SELECT row_number() OVER (ORDER BY c2.contno,c1.contno ASC) AS rownum ,c2.contno as contno2 ,c2.customername as CustomerName2 ,c2.PAYPERIOD as paymentperiodnumber2,c2.PREMIUM as NetAmount2, c2.creditname as CreditName2 ,c2.RefNo As RefNo2 ,c1.contno as contno1,c1.CustomerName as CustomerName1,c1.paymentperiodnumber as paymentperiodnumber1 ,c1.NetAmount as NetAmount1 ,c1.CreditName as CreditName1 , c1.RefNo AS RefNo1
              ,c1.mcode,c2.mcode
                FROM [TSR_Application].[dbo].[View_Bighead_credit_2] as c2
                full outer join
                [TSR_Application].[dbo].[View_Bighead_credit_1_bak] as c1
                On c1.RefNo = c2.RefNo
                $where ";

              //echo $sql_case;
              //$num_row = checkNumRow($conn,$sql_case);

              //$sql = "SELECT TOP $limit_per_page * FROM (".$sql_case." )AS CAMPAIGN WHERE (rownum >= '".$limit_start."' AND rownum <= '".$limit_end."')   order by CAMPAIGN.rownum";
              //echo $sql;
              $stmt = sqlsrv_query($conn,$sql_case);
              $contno2 = array("");
              //print_r($contno2);
              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                if (!empty($row['contno2'])) {
                  array_push($contno2,$row['contno2']);
                }

              ?>

              <tr>
                <td style="text-align: center;"><?=$row['rownum']?></td>
                <td><?=$row['contno1']?></td>
                <td><?=$row['RefNo1']?></td>
                <td><?=$row['CustomerName1']?></td>
                <td><?=$row['paymentperiodnumber1']?></td>
                <td><?php if(!empty($row['NetAmount1'])) { echo number_format($row['NetAmount1']) ;} ?></td>
                <td><?=$row['CreditName1']?></td>


                <td><?=$row['contno2']?></td>
                <td><?=$row['RefNo2']?></td>
                <td><?=$row['CustomerName2']?></td>
                <td><?=$row['paymentperiodnumber2']?></td>
                <td><?php if(!empty($row['NetAmount2'])) { echo number_format($row['NetAmount2']) ;} ?></td>
                <td><?=$row['CreditName2']?></td>
              </tr>

              <?php
                }
                sqlsrv_close($conn);
               ?>
            </table>
          </div>
          <?php
          //if (isset($startDate) || isset($endDate)) {
          // echo pagelimit($_GET['pages'],$num_row,$page,"","","",$_REQUEST['CreditID']);
          //}

          ?>
        </div>
        </div>

      </div>
      <!--
      <table class="table table-hover table-striped">
        <form role="form" data-toggle="validator" id="formSearchLog" name="formSearchLog" method="post" action="pages/addMastcont.php">
          <?php
          foreach($contno2 as $value)
          {
            echo '<input type="hidden" name="addcountno[]" value="'. $value. '">';
          }
           ?>
          <tr>
            <td style="text-align: right" width="15%"><button type="summit" class="btn btn-block btn-primary">โอนย้าย บ้านแดง ไป บิ๊กเฮด <?=$num_row;?> รายการ</button></td>
            <td style="text-align: left" width="10%"></td>
            <td style="text-align: right"><B>  </B></td>
            <td style="text-align: right" width="15%"></td>
          </tr>
        </from>
      </table>
    -->
      <?php
          }
       ?>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
