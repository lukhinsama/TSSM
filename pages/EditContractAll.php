<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
$EmpID = ConvertEmpIDInsertA($_COOKIE['tsr_emp_id']);

$con = connectDB_BigHead();

if (isset($_REQUEST['updateRecreptid'])) {

  $sql_updatereceript = "EXEC TSRData_Source.dbo.SP_TSSM_UpdateReceiptAll
  @ReceiptID = '".$_REQUEST['updateRecreptid']."',
	@EmpID = '".$_REQUEST['edituser']."'";

  $stmt = sqlsrv_query( $con, $sql_updatereceript );
  if( $stmt === false ) {

    echo '<script language="javascript">';
    echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
    echo '</script>';
  }else {
    echo '<script language="javascript">';
    echo 'alert("อัพเดดใบเสร็จย้อนหลัง สำเร็จ !!")';
    echo '</script>';
  }
}


if (isset($_GET["voidid"]) AND isset($_REQUEST["searchText"])) {

  $sql_void = "EXEC TSRData_Source.dbo.sp_TSSM_VoidReceipt
  @ContNo = '".$_GET['searchText']."',
  @ContractReferenceNo = '".$_GET['contref']."',
  @ReceiptCode = '".$_GET['voidid']."',
  @EmpID = '".$EmpID."'";

  $stmt = sqlsrv_query( $con, $sql_void );
  if( $stmt === false ) {

    echo '<script language="javascript">';
    echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
    echo '</script>';
  }else {
    echo '<script language="javascript">';
    echo 'alert("ยกเลิกใบเสร็จ สำเร็จ !!")';
    echo '</script>';
  }
}

if (isset($_GET["ContNo"]) AND isset($_GET["amount"]) AND isset($_GET["contref"])) {

    $sql_add = "EXEC TSRData_Source.dbo.SP_TSSM_CreateReceiptTransfer
    @EmpID = '".$EmpID."',
  	@Contno = '".$_GET["ContNo"]."',
  	@ContractReferenceNo = '".$_GET["contref"]."',
  	@PayTran =  '".$_GET["amount"]."'";

    $stmt = sqlsrv_query( $con, $sql_add );
    if( $stmt === false ) {
      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("ออกใบเสร็จ สำเร็จ !!")';
      echo '</script>';
    }
}

if((isset($_POST["OldContno"])) OR (isset($_POST["OldContractReferenceNo"]))){
/*
echo $EmpID."<BR>";
echo $_POST["OldContractReferenceNo"]."<BR>";
echo $_POST["OldContno"]."<BR>";
echo $_POST["OldContractStatus"]."<BR>";
echo $_POST["OldSerialNumber"]."<BR>";
echo $_POST["OldCredit"]."<BR>";
echo $_POST["OldFirstPeriod"]."<BR>";
echo $_POST["OldTradeInDiscount"]."<BR>";
echo $_POST["OldNextPeriod"]."<BR>";
*/

  if (isset($_POST["chkContractStatus"])) {
    $sql_update1 = "EXEC TSRData_Source.dbo.SP_TSSM_EditStatusInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @ContractReferenceNo = '".$_POST['OldContractReferenceNo']."',
    @OldContractStatus = '".$_POST['OldContractStatus']."',
    @NewContractStatus = '".$_POST['ContractStatus']."'";

    $stmt1 = sqlsrv_query( $con, $sql_update1 );
    if( $stmt1 === false ) {

      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }

  if (isset($_POST["chkContractReferenceNo"])) {
    /*
    $sql_update = "EXEC TSRData_Source.dbo.SP_TSSM_EditContractStatusInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @OldContractReferenceNo = '".$_POST['OldContractReferenceNo']."',
    @NewContractReferenceNo = '".$_POST['ContractReferenceNo']."'";
    */
    $sql_update = "EXEC TSRData_Source.dbo.SP_TSSM_EditContractReferenceNoInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @OldContractReferenceNo = '".$_POST['OldContractReferenceNo']."',
    @NewContractReferenceNo = '".$_POST['ContractReferenceNo']."'";
    $stmt = sqlsrv_query( $con, $sql_update );
    if( $stmt === false ) {
      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }

  if (isset($_POST["chkContno"])) {
    $sql_update = "EXEC TSRData_Source.dbo.SP_TSSM_EditContNoInContract
    @EmpID = '".$EmpID."',
    @OldContNo = '".$_POST['OldContno']."',
    @NewContNo = '".$_POST['CONTNO']."',
    @ContractReferenceNo = '".$_POST['OldContractReferenceNo']."'";

    $stmt = sqlsrv_query( $con, $sql_update );
    if( $stmt === false ) {
      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }

  if (isset($_POST["chkPackage"])) {
    $sql_update = "EXEC TSRData_Source.dbo.SP_TSSM_EditPackageInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @ContractReferenceNo = '".$_POST['OldContractReferenceNo']."',

    @NewPackage = '".$_POST['ContractPackage']."',
    @OldPackage = '".$_POST['OldPackage']."'";

    $stmt = sqlsrv_query( $con, $sql_update );
    if( $stmt === false ) {
      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }

  if (isset($_POST["chkSerialNumber"])) {
    $sql_update = "EXEC TSRData_Source.dbo.SP_TSSM_EditContractSerialNumberInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @ContractReferenceNo = '".$_POST['OldContractReferenceNo']."',

    @OldSerialNumber = '".$_POST['OldSerialNumber']."',
    @NewSerialNumber = '".$_POST['ProductSerialNumber']."'";

    $stmt = sqlsrv_query( $con, $sql_update );
    if( $stmt === false ) {
      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }

  if (isset($_POST["chkCredit"])) {
    $sql_update = "EXEC TSRData_Source.dbo.SP_TSSM_EditContractSalesPriceInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @ContractReferenceNo = '".$_POST['OldContractReferenceNo']."',

    @OldSalesPrice = '".$_POST['OldCredit']."',
    @NewSalesPrice = '".$_POST['Credit']."'";

    $stmt = sqlsrv_query( $con, $sql_update );
    if( $stmt === false ) {
      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }

  if (isset($_POST["chkFirstPeriod"])) {
    $sql_update = "EXEC TSRData_Source.dbo.SP_TSSM_EditContractFirstPeriodInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @ContractReferenceNo = '".$_POST['OldContractReferenceNo']."',

    @OldFirstPeriod = '".$_POST['OldFirstPeriod']."',
    @NewFirstPeriod = '".$_POST['FirstPeriod']."'";

    $stmt = sqlsrv_query( $con, $sql_update );
    if( $stmt === false ) {
      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }

  if (isset($_POST["chkTradeInDiscount"])) {
    $sql_update = "EXEC TSRData_Source.dbo.SP_TSSM_EditContractDiscountInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @ContractReferenceNo = '".$_POST['OldContractReferenceNo']."',

    @OldDiscount = '".$_POST['OldTradeInDiscount']."',
    @NewDiscount = '".$_POST['TradeInDiscount']."'";

    $stmt = sqlsrv_query( $con, $sql_update );
    if( $stmt === false ) {
      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }

  if (isset($_POST["chkNextPeriod"])) {
    $sql_update = "EXEC TSRData_Source.dbo.SP_TSSM_EditContractNextPeriodInContract
    @EmpID = '".$EmpID."',
    @ContNo = '".$_POST['OldContno']."',
    @ContractReferenceNo = '".$_POST['OldContractReferenceNo']."',

    @OldNextPeriod = '".$_POST['OldNextPeriod']."',
    @NewNextPeriod = '".$_POST['NextPeriod']."'";

    $stmt = sqlsrv_query( $con, $sql_update );
    if( $stmt === false ) {
      echo '<script language="javascript">';
      echo 'alert("พบปัญหาในการบันทึกข้อมูล !!")';
      echo '</script>';
    }else {
      echo '<script language="javascript">';
      echo 'alert("บันทึกข้อมูลสำเร็จ !!")';
      echo '</script>';
    }
  }

}




 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
      <div class="row">
        <div class="col-md-3">
          <h4>
            ข้อมูลเลขที่สัญญา
          </h4>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-4">

        </div>

      </div>
      <ol class="breadcrumb">
        <li><a href="index.php?pages=info"><i class="fa fa-user"></i> งานระบบ</a></li>
        <li class="active">ข้อมูลสัญญา</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="col-md-8">
              <h3 class="box-title"></h3>
            </div>
            <!--<div class="box-tools">-->
            <div class="col-md-4">
              <form role="form" data-toggle="validator" name="formSearchEmpHr" method="post" action="index.php?pages=contractedit">
              <div class="input-group input-group-sm">
                <input type="text" name="searchText" class="form-control pull-right" id="counto" required placeholder="เลขที่สัญญา , เลขอ้างอิง , เลขเครื่อง" value="<?php if(!empty($_REQUEST['searchText'])){ echo $_REQUEST['searchText'];}?>">

                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
              </form>
            </div>
          </div>
          <!-- /.box-header -->
          <?php
            if ((isset($_REQUEST['searchText']))) {
          ?>
          <div class="box-body table-responsive no-padding">
            <form role="form" data-toggle="validator" name="formUpdate" method="post" action="index.php?pages=contractedit">
            <table class="table table-hover table-striped">
              <thead>
              <tr>
                <th colspan="3">ข้อมูลสัญญา</th>
              </tr>
              </thead>
              <?php
              $sql_select ="SELECT TOP 1 C.Effdate,CONVERT(varchar(20),C.EFFDATE,105) +' '+ CONVERT(varchar(5),C.EFFDATE,108) as EFFDATE2,C.ContractReferenceNo,C.CONTNO,C.ProductID,C.Mode,C.STATUS,ConSt.StatusName,C.StatusCode
,C.Model,C.ProductSerialNumber
,(select top 1 TotalPrice from Bighead_Mobile.dbo.Package where ProductID in (select ProductID from Bighead_Mobile.dbo.Package where Model = C.Model) AND Status = 1 AND PackageTitle = 'เงินสด') as Sales
,C.Sales AS Credit,C.TotalPrice,C.TradeInDiscount
,DC.PrefixCode,DC.PrefixName,DC.CustomerName,DC.idCard,DC.CompanyName,DC.AuthorizedIDCard
,REPLACE(REPLACE(REPLACE(REPLACE((SELECT AddressDetail+' หมู่ที่'+AddressDetail2+' '+AddressDetail3+' ถนน'+AddressDetail4
FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno),'หมู่ที่-',''),'ถนน-',''),' -',''),'_','')+' '+(SELECT SubDistrictName
FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.SubDistrict as s on s.SubDistrictCode = a.SubDistrictCode
WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno)+' '+(SELECT DistrictName FROM [Bighead_Mobile].[dbo].Address AS A
inner join Bighead_Mobile.dbo.District as d on d.DistrictCode = a.DistrictCode WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno)+' '+(SELECT ProvinceName FROM [Bighead_Mobile].[dbo].Address AS A
inner join Bighead_Mobile.dbo.Province as p on p.ProvinceCode = a.ProvinceCode WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno)+' '+(SELECT Zipcode FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS AddressInstall
,REPLACE(REPLACE(REPLACE(REPLACE((SELECT AddressDetail+' หมู่ที่'+AddressDetail2+' '+AddressDetail3+' ถนน'+AddressDetail4
FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno),'หมู่ที่-',''),'ถนน-',''),' -',''),'_','')+' '+(SELECT SubDistrictName FROM [Bighead_Mobile].[dbo].Address AS A
inner join Bighead_Mobile.dbo.SubDistrict as s on s.SubDistrictCode = a.SubDistrictCode
WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno)+' '+(SELECT DistrictName FROM [Bighead_Mobile].[dbo].Address AS A
inner join Bighead_Mobile.dbo.District as d on d.DistrictCode = a.DistrictCode WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno)+' '+(SELECT ProvinceName FROM [Bighead_Mobile].[dbo].Address AS A
inner join Bighead_Mobile.dbo.Province as p on p.ProvinceCode = a.ProvinceCode WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno)+' '+(SELECT Zipcode FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS AddressIDCard
,REPLACE(REPLACE(REPLACE(REPLACE((SELECT AddressDetail+' หมู่ที่'+AddressDetail2+' '+AddressDetail3+' ถนน'+AddressDetail4
FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressPayment' AND refno = C.refno),'หมู่ที่-',''),'ถนน-',''),' -',''),'_','')+' '+(SELECT SubDistrictName FROM [Bighead_Mobile].[dbo].Address AS A
inner join Bighead_Mobile.dbo.SubDistrict as s on s.SubDistrictCode = a.SubDistrictCode
WHERE AddressTypeCode = 'AddressPayment' AND refno = C.refno)+' '+(SELECT DistrictName FROM [Bighead_Mobile].[dbo].Address AS A
inner join Bighead_Mobile.dbo.District as d on d.DistrictCode = a.DistrictCode WHERE AddressTypeCode = 'AddressPayment' AND refno = C.refno)+' '+(SELECT ProvinceName FROM [Bighead_Mobile].[dbo].Address AS A
inner join Bighead_Mobile.dbo.Province as p on p.ProvinceCode = a.ProvinceCode WHERE AddressTypeCode = 'AddressPayment' AND refno = C.refno)+' '+(SELECT Zipcode FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressPayment' AND refno = C.refno) AS AddressPayment
,P.ProductName
,(SELECT PaymentAmount FROM Bighead_Mobile.dbo.SalePaymentPeriod where RefNo = c.RefNo AND PaymentPeriodNumber = 1) AS FirstPeriod
,(SELECT NetAmount FROM Bighead_Mobile.dbo.SalePaymentPeriod where RefNo = c.RefNo AND PaymentPeriodNumber = 1) AS FirstPeriodPayment
,(SELECT PaymentAmount FROM Bighead_Mobile.dbo.SalePaymentPeriod where RefNo = c.RefNo AND PaymentPeriodNumber = 2) AS NextPeriod
FROM Bighead_Mobile.dbo.Contract AS C
LEFT JOIN Bighead_Mobile.dbo.DebtorCustomer AS DC ON C.customerid = DC.customerid
LEFT JOIN Bighead_Mobile.dbo.address As A ON A.refno = C.refno
LEFT JOIN Bighead_Mobile.dbo.Product AS P ON P.ProductID = C.ProductID
LEFT JOIN  Bighead_Mobile.dbo.ContractStatus AS ConSt ON ConSt.StatusCode = C.StatusCode
WHERE C.CONTNO = '".$_REQUEST['searchText']."' OR C.ContractReferenceNo = '".$_REQUEST['searchText']."' OR C.ProductSerialNumber = '".$_REQUEST['searchText']."'
ORDER BY isActive DESC";

              //$con = connectDB_BigHead();
              $stmt = sqlsrv_query($con,$sql_select);

              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
               ?>
              <tr>
                <td align="right"><B>วันที่ทำสัญญา</B></td><td colspan="2"><?=DateTimeThai($row['EFFDATE2'])?> น.</td>
              </tr>
              <tr>
                <td align="right"><B>เลขที่อ้างอิง</B></td><td colspan="2">
                  <p class="text-red"><input type="checkbox" name="chkContractReferenceNo"> แก้ไขข้อมูลเลขที่อ้างอิง</p>
                  <input type="hidden" name="OldContractReferenceNo" value="<?=$row['ContractReferenceNo']?>">
                  <input class="form-control" type="text" name="ContractReferenceNo" value="<?=$row['ContractReferenceNo']?>" maxlength = "9" style="width: 70%;"></td>
              </tr>
              <tr>
                <td align="right"><B>เลขที่สัญญา</B></td><td colspan="2">
                  <p class="text-red"><input type="checkbox" name="chkContno"> แก้ไขข้อมูลเลขที่สัญญา</p>
                  <input type="hidden" name="OldContno" value="<?=$row['CONTNO']?>">
                  <input class="form-control" type="text" name="CONTNO" value="<?=$row['CONTNO']?>" maxlength = "9" style="width: 70%;"></td>
              </tr>
              <tr>
                <td align="right"><B>สถานะสัญญา</B></td><td colspan="2">
                  <p class="text-red"><input type="checkbox" name="chkContractStatus"> แก้ไขข้อมูลสถานะสัญญา</p>
                  <input type="hidden" name="OldContractStatus" value="<?=$row['STATUS']?>">
                  <select class="form-control select2 select2-hidden-accessible" name="ContractStatus" style="width: 70%;" tabindex="-1" aria-hidden="true">
                    <option <?php if ($row['STATUS'] == "NORMAL") { echo "selected" ;} ?> value="NORMAL" >สถานะปกติ
                    </option>
                    <option <?php if ($row['STATUS'] == "T") { echo "selected" ;} ?> value="T" >สถานะถอดเครื่อง
                    </option>
                    <option <?php if ($row['STATUS'] == "DEAD") { echo "selected" ;} ?> value="DEAD" >สถานะลูกค้าตาย
                    </option>
                    <option <?php if ($row['STATUS'] == "HouseFire") { echo "selected" ;} ?> value="HouseFire" >สถานะไฟไหม้
                    </option>
                    <option <?php if ($row['STATUS'] == "F") { echo "selected" ;} ?> value="F" >สถานะชำระครบ
                    </option>
                    <option <?php if ($row['STATUS'] == "VOID") { echo "selected" ;} ?> value="VOID" >สถานะยกเลิกสัญญา
                    </option>
                    <option <?php if ($row['STATUS'] == "DRAFT") { echo "selected" ;} ?> value="DRAFT" >สถานะสัญญาไม่สมบูรณ์
                    </option>
                  </select>
                </td>
              </tr>
              <tr>
                <td align="right"><B>ขั้นตอนการทำสัญญา</B></td><td colspan="2"><?=$row['StatusCode']?>-<?=$row['StatusName']?></td>
              </tr>
              <tr>
                <td align="right"><B>ชื่อลูกค้า</B></td><td colspan="2">
                  <!--<p class="text-red"><input type="checkbox" name="chkCustomerName"> แก้ไขข้อมูลชื่อลูกค้า</p>-->
                  <input type="hidden" name="OldCustomerName" value="<?=$row['CustomerName']?>">
                  <input class="form-control" type="text" name="CustomerName" value="<?=$row['CustomerName']?>" maxlength = "9" style="width: 70%;"></td>
                  </select>
              </tr>
              <tr>
                <td align="right"><B>เลขที่บัตร</B></td><td colspan="2">
                  <!--<p class="text-red"><input type="checkbox" name="chkidCard"> แก้ไขข้อมูลเลขบัตรประชาชน</p>-->
                  <input type="hidden" name="OldidCard" value="<?=$row['idCard']?>">
                  <input class="form-control" type="text" name="idCard" value="<?=$row['idCard']?>" maxlength = "9" style="width: 70%;"></td>
                </td>
              </tr>
              <?php
                if ($row['CompanyName'] != NULL) {
               ?>
              <tr>
                <td align="right"><B>คำนำหน้าบริษัท</B></td><td colspan="2">
                  <!--<p class="text-red"><input type="checkbox" name="chkPrefixName"> แก้ไขข้อมูลชื่อลูกค้า</p>-->
                  <input type="hidden" name="OldPrefixName" value="<?=$row['PrefixName']?>">
                  <!--<input class="form-control" type="text" name="PrefixName" value="<?=$row['PrefixName']?>" maxlength = "9" style="width: 70%;">-->
                  <select class="form-control select2 select2-hidden-accessible" name="PrefixName" style="width: 70%;" tabindex="-1" aria-hidden="true">
                    <option <?php if ($row['PrefixName'] == "ห้างหุ้นส่วน") { echo "selected" ;} ?> value="corporate" >ห้างหุ้นส่วน
                    </option>
                    <option <?php if ($row['PrefixName'] == "ห้างหุ้นส่วนจำกัด") { echo "selected" ;} ?> value="010" >ห้างหุ้นส่วนจำกัด
                    </option>
                    <option <?php if ($row['PrefixName'] == "บริษัท") { echo "selected" ;} ?> value="company" >บริษัท
                    </option>
                  </select>
                  </td>
              </tr>
              <tr>
                <td align="right"><B>ชื่อบริษัท</B></td><td colspan="2">
                  <!--<p class="text-red"><input type="checkbox" name="chkCompanyName"> แก้ไขข้อมูลชื่อลูกค้า</p>-->
                  <input type="hidden" name="OldCompanyName" value="<?=$row['CompanyName']?>">
                  <input class="form-control" type="text" name="CompanyName" value="<?=$row['CompanyName']?>" maxlength = "9" style="width: 70%;"></td>
                  </select>
              </tr>
              <tr>
                <td align="right"><B>เลขที่เสียภาษี</B></td><td colspan="2">
                  <!--<p class="text-red"><input type="checkbox" name="chkAuthorizedIDCard"> แก้ไขข้อมูลเลขบัตรประชาชน</p>-->
                  <input type="hidden" name="OldAuthorizedIDCard" value="<?=$row['AuthorizedIDCard']?>">
                  <input class="form-control" type="text" name="AuthorizedIDCard" value="<?=$row['AuthorizedIDCard']?>" maxlength = "9" style="width: 70%;"></td>
                </td>
              </tr>
              <?php
                }
               ?>
              <tr>
                <td align="right"><B>ที่อยู่บัตร</B></td><td colspan="2"><?=$row['AddressIDCard']?></td>
              </tr>
              <tr>
                <td align="right"><B>ที่ติดตั้ง</B></td><td colspan="2"><?=$row['AddressInstall']?></td>
              </tr>
              <tr>
                <td align="right"><B>ที่เก็บเงิน</B></td><td colspan="2"><?=$row['AddressPayment']?></td>
              </tr>
              <tr>
                <td align="right"><B>สินค้า</B></td><td colspan="2"><?=$row['ProductName']?></td>
              </tr>
              <tr>
                <td align="right"><B>รุ่น</B></td><td colspan="2">
                  <p class="text-red"><input type="checkbox" name="chkPackage"> แก้ไขข้อมูลแพ็กเกด</p>
                  <input type="hidden" name="OldPackage" value="<?=$row['Model']?>">
                  <select class="form-control select2 select2-hidden-accessible" name="ContractPackage" style="width: 70%;" tabindex="-1" aria-hidden="true">
                    <?php
                    /*
                      $sqlSelect2 = "SELECT PackageCode,PackageTitle FROM Bighead_Mobile.dbo.Package WHERE ProductID = '".$row['ProductID']."' ORDER BY PackageCode";
                    */
                      $sqlSelect2 = "SELECT PackageCode,PackageTitle FROM Bighead_Mobile.dbo.Package ORDER BY PackageCode";

                      $stmt = sqlsrv_query($con,$sqlSelect2);
                      while ($opt = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                     ?>
                    <option <?php if ($row['Model'] == $opt['PackageCode']) { echo "selected" ;} ?>  value="<?=$opt['PackageCode']?>" > <?=$opt['PackageCode']?> <?=$opt['PackageTitle']?></option>
                    <?php
                      }
                     ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td align="right"><B>จำนวนงวด</B></td><td colspan="2"><?=$row['Mode']?></td>
              </tr>
              <tr>
                <td align="right"><B>เลขเครื่อง</B></td><td colspan="2">
                  <p class="text-red"><input type="checkbox" name="chkSerialNumber"> แก้ไขข้อมูลหมายเลขเครื่อง</p>
                  <input type="hidden" name="OldSerialNumber" value="<?=$row['ProductSerialNumber']?>">
                  <input class="form-control" type="text" name="ProductSerialNumber" value="<?=$row['ProductSerialNumber']?>" maxlength = "10" style="width: 70%;"></td>
              </tr>
              <tr>
                <td align="right"><B>ราคาเงินสด</B></td><td colspan="2"><?=number_format($row['Sales'])?></td>
              </tr>
              <tr>
                <td align="right"><B>ราคาขาย</B></td><td colspan="2">
                  <p class="text-red"><input type="checkbox" name="chkCredit"> แก้ไขข้อมูลราคาขาย</p>
                  <input type="hidden" name="OldCredit" value="<?=$row['Credit']?>">
                  <input class="form-control" type="text" name="Credit" value="<?=$row['Credit']?>" style="width: 70%;"></td>
              </tr>
              <tr>
                <td align="right"><B>ค่างวดแรก</B></td><td colspan="2">
                  <p class="text-red"><input type="checkbox" name="chkFirstPeriod"> แก้ไขข้อมูลค่างวดแรก</p>
                  <input type="hidden" name="OldFirstPeriod" value="<?=$row['FirstPeriod']?>">
                  <input class="form-control" type="text" name="FirstPeriod" value="<?=$row['FirstPeriod']?>" style="width: 70%;"></td>
              </tr>
              <tr>
                <td align="right"><B>ส่วนลดงวดแรก</td><td colspan="2">
                  <p class="text-red"><input type="checkbox" name="chkTradeInDiscount"> แก้ไขข้อมูลส่วนลดงวดแรก</p>
                  <input type="hidden" name="OldTradeInDiscount" value="<?=$row['TradeInDiscount']?>">
                  <input class="form-control" type="text" name="TradeInDiscount" value="<?=$row['TradeInDiscount']?>" style="width: 70%;"></td>
              </tr>
              <tr>
                <td align="right"><B>ราคาสุทธิ</td><td colspan="2"><?=number_format($row['TotalPrice'])?></td>
              </tr>
              <tr>
                <td align="right"><B>งวดแรกสุทธิ</td><td colspan="2"><?=number_format($row['FirstPeriodPayment'])?></td>
              </tr>
              <tr>
                <td align="right"><B>งวดต่อไป</td><td colspan="2">
                  <p class="text-red"><input type="checkbox" name="chkNextPeriod"> แก้ไขข้อมูลงวดต่อไป</p>
                  <input type="hidden" name="OldNextPeriod" value="<?=$row['NextPeriod']?>">
                  <input class="form-control" type="text" name="NextPeriod" value="<?=$row['NextPeriod']?>" style="width: 70%;"></td>
              </tr>
              <?php
                }
               ?>
               <TR>
                 <TD></TD>
                 <TD colspan="2">
                   <input type="hidden" name="searchText" value="<?=$_REQUEST['searchText']?>">
                   <button type="submit" class="btn btn-warning">บันทึก</button>
                 </TD>
               </TR>
            </table>

          </from>
          </div>
<h4 class="box-title"> รายละเอียดใบเสร็จ</h4>
          <div class="box-body table-responsive no-padding">

<form role="form" data-toggle="validator" name="formUpdateReceipt" method="post" action="index.php?pages=contractedit">

            <table id="example2" class="table table-hover table-striped">
              <thead>
              <tr>
                <th>งวดที่</th>
                <th>เลขที่ใบเสร็จ</th>
                <th>วันที่ออกใบเสร็จ</th>
                <th>ชื่อลูกค้า</th>
                <th>ค่างวด</th>
                <th>จำนวนเงิน</th>
                <th>ชำระเงิน</th>
                <th>เขตเก็บเงิน</th>
                <th>พนักงาน</th>
                <th>สถานะ</th>
                <th>ยกเลิก</th>
                <th>ออกใบเสร็จ</th>
              </tr>
              </thead>

              <tbody>
              <?php

              $sql_select ="SELECT
              C.ContractReferenceNo,C.CONTNO,DC.CustomerName,E.FirstName+' '+E.LastName AS EmpName
,SP.SalePaymentPeriodID,SP.PaymentPeriodNumber,SP.PaymentAmount,Sp.Discount,SP.NetAmount,SP.PaymentComplete
,SPP.Amount,P.CashCode,P.EmpID,R2.TotalPayment
,R.ReceiptID,R.ReceiptCode,R.DatePayment,CONVERT(varchar(20),R.DatePayment,105) +' '+ CONVERT(varchar(5),R.DatePayment,108) as DayPay
FROM Bighead_Mobile.dbo.Contract AS C
INNER JOIN Bighead_Mobile.dbo.SalePaymentPeriod AS SP ON SP.RefNo = C.RefNo
LEFT JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC ON DC.CustomerID = C.CustomerID
LEFT JOIN Bighead_Mobile.dbo.SalePaymentPeriodPayment AS SPP ON SPP.SalePaymentPeriodID = SP.SalePaymentPeriodID
LEFT JOIN Bighead_Mobile.dbo.Payment AS P ON P.PaymentID = SPP.PaymentID
LEFT JOIN Bighead_Mobile.dbo.Receipt AS R ON R.RefNo = C.RefNo AND R.ReceiptID = SPP.ReceiptID AND R.PaymentID = P.PaymentID
LEFT JOIN Bighead_Mobile.dbo.ReceiptVoid AS R2 ON R2.RefNo = C.RefNo AND R2.ReceiptID = SPP.ReceiptID AND R2.PaymentID = P.PaymentID
LEFT JOIN Bighead_Mobile.dbo.Employee AS E ON E.EmpID = p.EmpID
WHERE (C.CONTNO = '".$_REQUEST['searchText']."' OR C.ContractReferenceNo = '".$_REQUEST['searchText']."')
ORDER BY Sp.PaymentPeriodNumber";

              //$con = connectDB_BigHead();
              $stmt = sqlsrv_query($con,$sql_select);

              while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

                if ($row["Amount"] === null) {
                  $statusPay = "";
                  $Paystatus = "";
                  $trclass = "";
                  $disVoid = "disabled";
                  $disEdit = "disabled";
                }elseif ($row["Amount"] == 0) {
                  if ($row["NetAmount"] == $row["Amount"]) {
                    $statusPay = "เต็ม";
                  }else {
                    $statusPay = "บางส่วน";
                  }
                  $Paystatus = "ยกเลิก";
                  $trclass = "danger";
                  $row["Amount"] = "(".$row["TotalPayment"].")";
                  $disVoid = "disabled";
                  $disEdit = "disabled";
                }else {
                  if ($row["NetAmount"] == $row["Amount"]) {
                    $statusPay = "เต็ม";
                    $trclass = "success";
                    $disVoid = "";
                    $disEdit = "";
                  }else {
                    $statusPay = "บางส่วน";
                    $trclass = "warning";
                    $disVoid = "";
                    $disEdit = "";
                  }
                  if ($row["PaymentComplete"] == 1) {
                    $Paystatus = "ครบ";
                  }else {
                    $Paystatus = "ไม่ครบ";
                  }
                }

               ?>

              <tr class="<?=$trclass?>">
                <td><?=$row["PaymentPeriodNumber"]?></td>
                <td><?=$row['ReceiptCode']?></td>
                <td><?=$row["DayPay"]?></td>
                <td><?=$row["CustomerName"]?></td>
                <td><?=$row["NetAmount"]?></td>
                <td><?=$row['Amount']?></td>
                <td><?=$statusPay?></td>
                <td><?=$row["CashCode"]?></td>
                <td><?=$row["EmpName"]?></td>
                <td><?=$Paystatus?></td>
                <td><a class="btn btn-danger" href="https://tssm.thiensurat.co.th/index.php?pages=contractedit&voidid=<?=$row["ReceiptCode"]?>&searchText=<?=$row["CONTNO"]?>&contref=<?=$row["ContractReferenceNo"]?>" target="_self" role="button" <?=$disVoid?> >ยกเลิก</a></td>
                <!--<td><a class="btn btn-warning" href="https://tssm.thiensurat.co.th/index.php?pages=contractedit&addRecreptid=<?=$row["CONTNO"]?>&adduser=<?=$EmpID?>&amount=<?=$row["Amount"]?>&contref=<?=$row["ContractReferenceNo"]?>" target="_self" role="button" <?=$disVoid?> >ออกใบเสร็จ</a></td>-->
                <td><a class="btn btn-warning" href="https://tssm.thiensurat.co.th/index.php?pages=contractedit&updateRecreptid=<?=$row["ReceiptID"]?>&edituser=<?=$EmpID?>" target="_self" role="button" <?=$disVoid?> >อัพเดดใบเสร็จ</a></td>
              </tr>

              <?php
                }
               ?>
             </tbody>

             <tfoot>
             </tfoot>
            </table>
</form>

          </div>

          <?php
              }
              sqlsrv_close($con);
           ?>
          <!-- /.box-body -->

        </div>
        <!-- /.box -->
      </div>
    </div>
    <!-- /.row -->
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
        "pageLength": 30,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
      });
    });
  </script>
