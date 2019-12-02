<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        เทียบข้อมูลสัญญา บ้านแดง & บิ๊กเฮด
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบมอนิเตอร์</a></li>
        <li><i class="fa fa-user"></i> ข้อมูลบ้านแดง/บิ๊กเฮด</li>
        <li class="active">ข้อมูลสัญญา</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearchLog" name="formSearchLog" method="post" action="index.php?pages=monitor_contract_rh_and_bh.php">

        <div class="col-md-8">
          <!--
            <label> เลือกเงื่อนไขในการเปรียบเทียบ </label><BR>
            <input type="radio" name="gender" value="male"> เลขที่สัญญา
            <input type="radio" name="gender" value="male"> เลขที่อ้างอิง
            <input type="radio" name="gender" value="male"> จำนวนงวด
            <input type="radio" name="gender" value="male"> แพ็คเก็จ
            <input type="radio" name="gender" value="male"> ราคาเงินสด
            <input type="radio" name="gender" value="male"> ราคาเงินผ่อน<BR>
            <input type="radio" name="gender" value="male"> ราคาสุทธิ
            <input type="radio" name="gender" value="male"> ค่างวดงวดแรก
            <input type="radio" name="gender" value="male"> ส่วนลดงวดแรก
            <input type="radio" name="gender" value="male"> ค่างวดแรกสุทธิ
            <input type="radio" name="gender" value="male"> ค่างวดที่2
            <input type="radio" name="gender" value="male"> รหัสพนักงานขาย
          -->
        </div>
<!--
        <div class="col-md-4">
          <label> เลือกช่วงเวลา </label>
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
      -->
        </div>
        </form>
    </BR>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> รายละเอียด</h3>
              <div class="box-body table-responsive no-padding">
                <table id="example2" class="table table-hover table-striped">
                  <thead>
                  <tr>
                    <th colspan="11">Red House</th>
                    <th colspan="10">Big Head</th>
                  </tr>

                  <tr>
                    <td>ลำดับ</td>

                    <td>เลขที่สัญญา</td>
                    <td>เลขที่อ้างอิง</td>
                    <td>แพ็คเก็จ</td>
                    <td>งวด</td>
                    <td>ราคาสุทธิ</td>
                    <td>ค่างวดแรก</td>
                    <td>ส่วนลด</td>
                    <td>งวดแรกสุทธิ</td>
                    <td>ค่างวด</td>
                    <td>สถานะสัญญา</td>

                    <td>เลขที่สัญญา</td>
                    <td>เลขที่อ้างอิง</td>
                    <td>แพ็คเก็จ</td>
                    <td>งวด</td>
                    <td>ราคาสุทธิ</td>
                    <td>ค่างวดแรก</td>
                    <td>ส่วนลด</td>
                    <td>งวดแรกสุทธิ</td>
                    <td>ค่างวด</td>
                    <td>สถานะสัญญา</td>

                    <td>แก้ไขสัญญา</td>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $con = connectDB_BigHead();

                  $Sql_select ="SELECT TOP 10000 [RH_Refno]
                                  ,[BH_Refno]
                                  ,[RH_Contno]
                                  ,[BH_Contno]
                                  ,[RH_Effdate]
                                  ,[BH_Effdate]
                                  ,[RH_Mode]
                                  ,[BH_Mode]
                                  ,[RH_Serialno]
                                  ,[BH_Serialno]
                                  ,[RH_Model]
                                  ,[BH_Model]
                                  ,[RH_Sales]
                                  ,[BH_Sales]
                                  ,[RH_Credit]
                                  ,[BH_Credit]
                                  ,[RH_TotalPrice]
                                  ,[BH_TotalPrice]
                                  ,[RH_FirstPay]
                                  ,[BH_FirstPay]
                                  ,[RH_DiscountFirst]
                                  ,[BH_DiscountFirst]
                                  ,[RH_FirstPayTotal]
                                  ,[BH_FirstPayTotal]
                                  ,[RH_NextPay]
                                  ,[BH_NextPay]
                                  ,[RH_Salecode]
                                  ,[BH_Salecode]
                                  ,[RH_Status]
                                  ,[BH_Status]
                                  ,RIGHT('00'+CONVERT(VARCHAR,[BH_Mode]),2) AS BH_ModeS
                              FROM [TSRData_Source].[dbo].[vw_Contrace_Compar_RH_and_BH]
                              WHERE (--RH_Contno != BH_Contno
                              --OR RH_Refno != BH_Refno
                              --OR RH_Model != BH_Model
                              --OR RH_TotalPrice != BH_TotalPrice
                              RH_FirstPay != BH_FirstPay
                              OR RH_DiscountFirst != BH_DiscountFirst
                              OR RH_FirstPayTotal != BH_FirstPayTotal
                              OR RH_NextPay != BH_NextPay
                              --OR RH_Status != BH_Status
                            ) AND BH_Active = 1 AND RH_Status != 'R'
                            ORDER BY RH_Refno,RH_Status";
                  $stmt = sqlsrv_query($con,$Sql_select);
                  $i = 1;
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                    if ($row["RH_Contno"] != $row["BH_Contno"]) {
                      $contno = "bgcolor='#FFFF00'";
                    }else {
                      $contno = "";
                    }
                    if ($row["RH_Refno"] != $row["BH_Refno"]) {
                      $refno = "bgcolor='#FFFF00'";
                    }else {
                      $refno = "";
                    }
                    if ($row["RH_Contno"] != $row["BH_Contno"]) {
                      $contno = "bgcolor='#FFFF00'";
                    }else {
                      $contno = "";
                    }
                    if ($row["RH_Model"] != $row["BH_Model"]) {
                      $model = "bgcolor='#FFFF00'";
                    }else {
                      $model = "";
                    }
                    if ($row["RH_Mode"] != $row["BH_ModeS"]) {
                      $mode = "bgcolor='#FFFF00'";
                    }else {
                      $mode = "";
                    }
                    if ($row["RH_TotalPrice"] != $row["BH_TotalPrice"]) {
                      $price = "bgcolor='#FFFF00'";
                    }else {
                      $price = "";
                    }
                    if ($row["RH_FirstPay"] != $row["BH_FirstPay"]) {
                      $firstp = "bgcolor='#FFFF00'";
                    }else {
                      $firstp = "";
                    }
                    if ($row["RH_DiscountFirst"] != $row["BH_DiscountFirst"]) {
                      $disf = "bgcolor='#FFFF00'";
                    }else {
                      $disf = "";
                    }
                    if ($row["RH_FirstPayTotal"] != $row["BH_FirstPayTotal"]) {
                      $firstt = "bgcolor='#FFFF00'";
                    }else {
                      $firstt = "";
                    }
                    if ($row["RH_NextPay"] != $row["BH_NextPay"]) {
                      $nextt = "bgcolor='#FFFF00'";
                    }else {
                      $nextt = "";
                    }
                    if ($row["RH_Status"] != $row["BH_Status"]) {
                      $status = "bgcolor='#FFFF00'";
                    }else {
                      $status = "";
                    }
                  ?>
                  <TR>
                    <TD><?=$i?></TD>

                    <TD <?=$contno?>><?=$row["RH_Contno"]?></TD>
                    <TD <?=$refno?>><?=$row["RH_Refno"]?></TD>
                    <TD <?=$model?>><?=$row["RH_Model"]?></TD>
                    <TD <?=$mode?>><?=$row["RH_Mode"]?></TD>
                    <TD <?=$price?>><?=$row["RH_TotalPrice"]?></TD>
                    <TD <?=$firstp?>><?=$row["RH_FirstPay"]?></TD>
                    <TD <?=$disf?>><?=$row["RH_DiscountFirst"]?></TD>
                    <TD <?=$firstt?>><?=$row["RH_FirstPayTotal"]?></TD>
                    <TD <?=$nextt?>><?=$row["RH_NextPay"]?></TD>
                    <TD <?=$status?>><?=$row["RH_Status"]?></TD>

                    <TD <?=$contno?>><?=$row["BH_Contno"]?></TD>
                    <TD <?=$refno?>><?=$row["BH_Refno"]?></TD>
                    <TD <?=$model?>><?=$row["BH_Model"]?></TD>
                    <TD <?=$mode?>><?=$row["BH_ModeS"]?></TD>
                    <TD <?=$price?>><?=$row["BH_TotalPrice"]?></TD>
                    <TD <?=$firstp?>><?=$row["BH_FirstPay"]?></TD>
                    <TD <?=$disf?>><?=$row["BH_DiscountFirst"]?></TD>
                    <TD <?=$firstt?>><?=$row["BH_FirstPayTotal"]?></TD>
                    <TD <?=$nextt?>><?=$row["BH_NextPay"]?></TD>
                    <TD <?=$status?>><?=$row["BH_Status"]?></TD>
                    <TD><a class="btn btn-danger" href="https://tssm.thiensurat.co.th/index.php?pages=contractedit&searchText=<?=$row["BH_Refno"]?>" target="_blank" role="button">แก้ไข</a></TD>
                  </TR>
                  <?php
                  $i++;
                  }
                  sqlsrv_close($con);
                   ?>
                 </tbody>
                 <tfoot>
                 </tfoot>
                </table>
              </div>
            </div>
        </div>
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
    //  $("#example1").DataTable();
      $('#example2').DataTable({
        "pageLength": 10,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });
  </script>
