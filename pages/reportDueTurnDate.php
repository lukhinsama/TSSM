<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        รายงานลูกค้าที่ครบกำหนดเปลี่ยนสารกรอง
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> ระบบมอนิเตอร์</a></li>
        <li><i class="fa fa-user"></i> รายงานลูกค้า</li>
        <li class="active">ครบกำหนดเปลี่ยนสารกรอง</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <form role="form" data-toggle="validator" id="formSearchLog" name="SearchMonth" method="post" action="index.php?pages=reportdueturndate">

        <div class="col-md-3">
        </div>

        <div class="col-md-6">
          <label> เลือกเดือน </label>
          <div class="input-group input-group-sm">
            <select class="form-control select2 input-group-sm" name="SearchMonth">
              <optgroup label="เลือกพนักงานเก็บเงิน">
                <option value="0">- เลือกเดือน -</option>
                <?php
                  $con = connectDB_BigHead();

                    $sql = "SELECT ID,MonthFullName,YearFullName FROM TSRData_Source.dbo.TSSM_ContractDueDateMonthName
                    ORDER BY id DESC";

                  //echo $sql;
                  $stmt = sqlsrv_query( $con, $sql );
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
                  <option value="<?=$row['ID']?>"> <?=$row['YearFullName']?> <?=$row['MonthFullName']?></option>
                <?php
                    }
                    sqlsrv_close($con);
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
      <BR>
      <?php
      if (isset($_POST['SearchMonth']) AND $_POST['SearchMonth'] != '0') {
        $con = connectDB_BigHead();

          $sql = "SELECT MonthFullName,MonthName,YearFullName,YearName FROM TSRData_Source.dbo.TSSM_ContractDueDateMonthName WHERE id = '".$_POST['SearchMonth']."'";

        //echo $sql;
        $stmt = sqlsrv_query( $con, $sql );
        $row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
       ?>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> จำนวนเครื่องกรองน้ำที่ต้องเปลี่ยน <?=$row2['MonthFullName']?> <?=$row2['YearFullName']?></h3>
            </div>
            <div class="box-body table-responsive no-padding">
              <table id="example1" class="table table-hover table-striped">
                <thead>
                  <tr>
                    <th style="text-align: center">ฝ่าย</th>
                    <th style="text-align: center">เครื่องกรองน้ำSA</th>
                    <th style="text-align: center">เครื่องกรองน้ำUA</th>
                    <th style="text-align: center">เครื่องกรองน้ำUV</th>
                    <th style="text-align: center">เครื่องกรองน้ำRO</th>
                    <th style="text-align: center">จำนวนรวม</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $con = connectDB_BigHead();
                    $SQL = "SELECT depnameActive
                          ,(SELECT COUNT(refno) FROM [TSRData_Source].[dbo].[TSSM_ContractDueTurnDate] WHERE DATEDIFF(MONTH,DueTurnDate,GETDATE()) <2 AND depnameActive = C.depnameActive AND kindname = 'เครื่องกรองน้ำSA') AS SA
                          ,(SELECT COUNT(refno) FROM [TSRData_Source].[dbo].[TSSM_ContractDueTurnDate] WHERE DATEDIFF(MONTH,DueTurnDate,GETDATE()) <2 AND depnameActive = C.depnameActive AND kindname = 'เครื่องกรองน้ำเซฟรุ่นUF') AS UF
                          ,(SELECT COUNT(refno) FROM [TSRData_Source].[dbo].[TSSM_ContractDueTurnDate] WHERE DATEDIFF(MONTH,DueTurnDate,GETDATE()) <2 AND depnameActive = C.depnameActive AND kindname = 'เครื่องกรองน้ำUV') AS UV
                          ,(SELECT COUNT(refno) FROM [TSRData_Source].[dbo].[TSSM_ContractDueTurnDate] WHERE DATEDIFF(MONTH,DueTurnDate,GETDATE()) <2 AND depnameActive = C.depnameActive AND kindname = 'เครื่องกรองน้ำRO') AS RO
                          ,count(refno) as TotalNum
                          FROM [TSRData_Source].[dbo].[TSSM_ContractDueTurnDate] AS C
                          WHERE DATEPART(MONTH,DueTurnDate) = '".$row2['MonthName']."' AND DATEPART(YEAR,DueTurnDate) = '".$row2['YearName']."'
                          GROUP BY depnameActive
                          order by depnameActive";
                    $stmt = sqlsrv_query($con,$SQL);
                    $SumTotal = 0;
                    $SumSA = 0;
                    $SumUF = 0;
                    $SumUV = 0;
                    $SumRO = 0;
                    while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                   ?>
                  <tr>
                    <td style="text-align: left"><?=$row['depnameActive']?></td>
                    <td style="text-align: right"><?=number_format($row['SA'],0)?></td>
                    <td style="text-align: right"><?=number_format($row['UF'],0)?></td>
                    <td style="text-align: right"><?=number_format($row['UV'],0)?></td>
                    <td style="text-align: right"><?=number_format($row['RO'],0)?></td>
                    <td style="text-align: right"><?=number_format($row['TotalNum'],0)?></td>
                  </tr>
                  <?php
                      $SumSA = $SumSA + $row['SA'];
                      $SumUF = $SumUF + $row['UF'];
                      $SumUV = $SumUV + $row['UV'];
                      $SumRO = $SumRO + $row['RO'];
                      $SumTotal = $SumTotal + $row['TotalNum'];
                    }
                   ?>
                   <tr>
                     <td style="text-align: right">รวม</td>
                     <td style="text-align: right"><?=number_format($SumSA,0)?></td>
                     <td style="text-align: right"><?=number_format($SumUF,0)?></td>
                     <td style="text-align: right"><?=number_format($SumUV,0)?></td>
                     <td style="text-align: right"><?=number_format($SumRO,0)?></td>
                     <td style="text-align: right"><?=number_format($SumTotal,0)?></td>
                   </tr>
                </tbody>
                <tfoot>
                </tfoot>
             </table>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <form role="form" data-toggle="validator" id="formSearchLog" name="formSearchLog" method="post" action="index.php?pages=reportdueturndate">

        <div class="col-md-3">
        </div>

        <div class="col-md-6">

          <div class="input-group input-group-sm">
            <select class="form-control select2 input-group-sm" name="searchText">
              <optgroup label="เลือกทีม">
                <option value="0">- เลือกฝ่าย -</option>
                <?php
                  $con = connectDB_TSR();

                    $sql = "SELECT DepnameActive
                            FROM (
                              SELECT C.EmpIdActive,C.DepnameActive,E.FirstName,E.LastName ,(SELECT Team FROM TSR_Application.dbo.Bill_Get_Last_Sale(c.EmpIdActive)) AS TeamCode
                              FROM TSS_PRD.[TSRData_Source].[dbo].[TSSM_ContractDueTurnDate] AS C
                              LEFT JOIN TSS_PRD.Bighead_Mobile.dbo.Employee AS E ON C.EmpIdActive = E.EmpID
                              WHERE DepnameActive IN ('ขาย','บริหารสาขา')
                            AND DATEPART(MONTH,DueTurnDate) = '".$row2['MonthName']."' AND DATEPART(YEAR,DueTurnDate) = '".$row2['YearName']."'
                            ) AS A
                            GROUP BY DepnameActive
                            ORDER BY DepnameActive";

                  //echo $sql;
                  $stmt = sqlsrv_query( $con, $sql );
                  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                ?>
                  <option value="<?=$row['DepnameActive']?>">ฝ่าย <?=$row['DepnameActive']?></option>
                <?php
                    }
                    sqlsrv_close($con);
                ?>
              </optgroup>
            </select>
            <input type = "hidden" name = "SearchMonth" value="<?=$_POST['SearchMonth']?>">
            <div class="input-group-btn">
              <button type="summit" class="btn btn-block btn-primary">ค้นหา</button>

            </div>
          </div>

        </div>
        <div class="col-md-3">

          <a href="http://app.thiensurat.co.th/lkh/rpt.aspx?id=<?=$_COOKIE['tsr_emp_id']?>&type=20&rpt=10" target="_blank" class="btn btn-default"> <i class="fa fa-print"></i> </a>
        </div>
        </form>
      </div>

      <?php
        if (!empty($_POST['searchText'])) {
            $SearchText = "WHERE DepnameActive = '".$_POST['searchText']."'";
          }else {
            $SearchText = "";
          }
       ?>
    </BR>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> รายละเอียด </h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          <div class="box-body table-responsive no-padding">
            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th>สังกัด</th>
                <th>รหัสขาย</th>
                <th>ชื่อพนักงาน</th>
                <th>เลขที่สัญญา</th>
                <th>วันที่ติดตั้ง</th>
                <th>หมายเลขเครื่อง</th>
                <th>สินค้า</th>
                <th>ชื่อลูกค้า</th>
                <th>เบอร์โทร</th>
                <th>ที่อยู่</th>
              </tr>
              </thead>
              <tbody>
              <?php
              $httpExcelHead = "<P><center><B>รายงานรายงานเปลี่ยนสารกรอง </B></center></P>";

              $httpExcel1 = "<table width = \"100%\">
              <thead>
              <tr>
                <th style=\"text-align: center\">สังกัด</th>
                <th style=\"text-align: center\">รหัสขาย</th>
                <th style=\"text-align: center\">ชื่อพนักงาน</th>
                <th style=\"text-align: center\">เลขที่สัญญา</th>
                <th style=\"text-align: center\">วันที่ติดตั้ง</th>
                <th style=\"text-align: center\">หมายเลขเครื่อง</th>
                <th style=\"text-align: center\">สินค้า</th>
                <th style=\"text-align: center\">ชื่อลูกค้า</th>
                <th style=\"text-align: center\">เบอร์โทร</th>
                <th style=\"text-align: center\">ที่อยู่</th>
              </tr>
              </thead>
              <tbody>";

              $httpExcel2 = "";
              /*
              $file = fopen("../tsr_SaleReport/pages/sqlText.txt","w");
              fwrite($file,$sql_print);
              fclose($file);
              */
              $con = connectDB_TSR();
              $sql_case = "SELECT *
FROM (
SELECT C.CONTNO ,CONVERT(varchar(20),C.EFFDATE,105) as EFFDATE2,C.SERIALNO,C.MODEL,C.kindname,C.Prename+''+C.Fname+' '+C.Lname AS CustomerName,
C.ADDRMAIL1,C.ADDRMAIL2,C.ADDRMAIL3,C.ADDRMAIL4,C.ADDRMAILZI,C.TELEPHONE,isnull(C.MOBIE,'') AS Mobile
,C.SaleCodeActive,C.EmpIdActive,E.FirstName,E.LastName,C.DepnameActive
,(SELECT Team FROM TSR_Application.dbo.Bill_Get_Last_Sale(c.EmpIdActive)) AS TeamCode
FROM TSS_PRD.[TSRData_Source].[dbo].[TSSM_ContractDueTurnDate] AS C
LEFT JOIN TSS_PRD.Bighead_Mobile.dbo.Employee AS E ON C.EmpIdActive = E.EmpID
WHERE DepnameActive IN ('ขาย','บริหารสาขา')
AND DATEPART(MONTH,DueTurnDate) = '".$row2['MonthName']."' AND DATEPART(YEAR,DueTurnDate) = '".$row2['YearName']."'
) AS A $SearchText
--WHERE LEFT(salecodeActive,2) IN ('AB','JB','NB')
ORDER BY TeamCode,SaleCodeActive";
              //echo $sql_case;
              $stmt = sqlsrv_query( $con, $sql_case );

              // เพิ่มลงฐานข้อมูล
              $sql_insert = "INSERT INTO TSR_Application.dbo.TSS_ReportCredit_2_sys (Empid,SQLtext,addtime,rpttype) VALUES (?,?,GETDATE(),20)";
              //echo $sql_insert;

              $params = array($_COOKIE['tsr_emp_id'],$sql_case);
              //print_r($params);

              $stmt_insert = sqlsrv_query( $con, $sql_insert, $params);

              if( $stmt_insert === false ) {
                 die( print_r( sqlsrv_errors(), true));
              }


              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

                $httpExcel2 .= "<tr>
                  <td style=\"text-align: center\">".$row['TeamCode']."</td>
                  <td style=\"text-align: center\">".$row['SaleCodeActive']."</td>
                  <td style=\"text-align: center\">".$row['FirstName']." ".$row['LastName']."</td>
                  <td style=\"text-align: center\">".$row['CONTNO']."</td>
                  <td style=\"text-align: center\">".$row['EFFDATE2']."</td>
                  <td style=\"text-align: center\">".$row['SERIALNO']."</td>
                  <td style=\"text-align: center\">".$row['kindname']."</td>
                  <td style=\"text-align: center\">".$row['CustomerName']."</td>
                  <td style=\"text-align: center\">".$row['TELEPHONE'].",".$row['Mobile']."</td>
                  <td style=\"text-align: center\">".$row['ADDRMAIL1']."".$row['ADDRMAIL2']."".$row['ADDRMAIL3']."".$row['ADDRMAIL4']."".$row['ADDRMAILZI']."</td>
                </tr>";

              ?>
              <tr>
                <td style="text-align: center;"><?=$row['TeamCode']?></td>
                <td style="text-align: center;"><?=$row['SaleCodeActive']?></td>
                <td style="text-align: left;"><?=$row['FirstName']?> <?=$row['LastName']?></td>
                <td style="text-align: center;"><?=$row['CONTNO']?></td>
                <td style="text-align: center;"><?=$row['EFFDATE2']?></td>
                <td style="text-align: center;"><?=$row['SERIALNO']?></td>
                <td><?=$row['kindname']?></td>
                <td><?=$row['CustomerName']?></td>
                <td><?=$row['TELEPHONE']?> <?=$row['Mobile']?></td>
                <td><?=$row['ADDRMAIL1']?> <?=$row['ADDRMAIL2']?> <?=$row['ADDRMAIL3']?> <?=$row['ADDRMAIL4']?> <?=$row['ADDRMAILZI']?></td>
              </tr>
              <?php
                }
                sqlsrv_close($con);

                $httpExcel3 = "</tbody>
                <tfoot>
                </tfoot>
               </table>";
                $html_file = $html_file = $httpExcelHead."".$httpExcel1."".$httpExcel2."".$httpExcel3;
                write_data_for_export_excel($html_file, 'reportDueTurnDate');

               ?>
               </tbody>
               <tfoot>
               </tfoot>
            </table>
            <a href="export_excel.php?report_type=10"><img src="http://app.thiensurat.co.th/tsr_car/image/excel-icon.png" width="35" height="auto"> </a>
          </div>
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
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script>
    $(function () {
      $("#example1").DataTable({
        "pageLength": 20,
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": false
      });
      $('#example2').DataTable({
        "pageLength": 10,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": true
      });
    });
  </script>
