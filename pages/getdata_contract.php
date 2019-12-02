<?php
//ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$conn = connectDB_BigHead();
$con = connectDB_TSR();
	//ตรวจสอบว่า มีค่า ตัวแปร $_GET['province_id'] เข้ามาหรือไม่  //แสดงรายชืออำเภอ
	if(((isset($_GET['effdate1'])) OR (isset($_GET['effdate2']))) OR (isset($_GET['refno'])) OR (isset($_GET['contno']))){

		if ((isset($_REQUEST['effdate1'])) OR (isset($_REQUEST['effdate2']))) {
			$effdate1 = explode("/",$_REQUEST['effdate1']);
			$effdate2 = explode("/",$_REQUEST['effdate2']);
			$effdate1["2"] = $effdate1["2"]-543;
			$effdate2["2"] = $effdate2["2"]-543;
			$effdatestart = $effdate1["2"]."-".$effdate1["1"]."-".$effdate1["0"];
			$effdateend = $effdate2["2"]."-".$effdate2["1"]."-".$effdate2["0"];
			$sqltextdate = "AND C.EFFDATE BETWEEN '".$effdatestart." 00:00' AND '".$effdateend." 23:59'";
		}else {
			$sqltextdate = "";
		}
		if (isset($_GET['refno'])) {
			$sqltextrefno = "AND C.ContractReferenceNo = '".$_GET['refno']."'";
		}else {
			$sqltextrefno = "";
		}
		if (isset($_GET['contno'])) {
			$sqltextcontno = "AND C.contno = '".$_GET['contno']."'";
		}else {
			$sqltextcontno = "";
		}
		//$json_result[] = ['Refno'=>'','CONTNO'=>'',];
/*
		$sql_case = "SELECT distinct *
FROM (
SELECT C.ContractReferenceNo as Refno
,C.CONTNO as CONTNO
,CONVERT(varchar(20),C.EFFDATE,105)as EFFDATE
,C.PreSaleEmployeeCode
,C.SaleCode
,ft.FortnightNumber,ft.Year
,null as Channel, null as bcode
,C.SaleEmployeeCode
,C.SaleTeamCode
,C.ProductSerialNumber,C.Model,C.MODE
,(select top 1 TotalPrice from Bighead_Mobile.dbo.Package where ProductID in (select ProductID from Bighead_Mobile.dbo.Package where Model = c.Model) AND Status = 1 AND PackageTitle = 'เงินสด') as sales
,C.sales AS credit,C.tradeindiscount,C.totalprice,left(C.[status],1) as status
,CONVERT(varchar(20),C.lastupdateDate,105) AS lastupdateDate
,(SELECT PaymentAmount FROM [Bighead_Mobile].[dbo].[PackagePeriodDetail] where model = c.Model AND paymentperiodnumber = 1) AS FirstPayment
,(SELECT PaymentAmount FROM [Bighead_Mobile].[dbo].[PackagePeriodDetail] where model = c.Model AND paymentperiodnumber = 1) - tradeindiscount AS FirstPaymentPeriod
,(SELECT PaymentAmount FROM [Bighead_Mobile].[dbo].[PackagePeriodDetail] where model = c.Model AND paymentperiodnumber = 2) AS PaymentPeriod
,DC.PrefixName,case when dc.CustomerType = 1 then DC.CompanyName else DC.CustomerName end as CustomerName
,case when dc.CustomerType = 1 then REPLACE(DC.AuthorizedIDCard,'-','') else REPLACE(DC.IDCard,'-','') end as IDCard
,case when dc.CustomerType = 1 then REPLACE(DC.IDCard,'-','') else null end as TaxID
,REPLACE(REPLACE(REPLACE(REPLACE((SELECT AddressDetail+' หมู่ที่'+AddressDetail2+' '+AddressDetail3+' ถนน'+AddressDetail4 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno),'หมู่ที่-',''),'ถนน-',''),' -',''),'_','') AS InstallAddressDetail
,(SELECT SubDistrictName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.SubDistrict as s on s.SubDistrictCode = a.SubDistrictCode WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallSubDistrict
,(SELECT DistrictName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.District as d on d.DistrictCode = a.DistrictCode WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallDistrict
,(SELECT ProvinceName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.Province as p on p.ProvinceCode = a.ProvinceCode WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallProvince
,(SELECT Zipcode FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallZipcode
,REPLACE(REPLACE(REPLACE(REPLACE((SELECT AddressDetail+' หมู่ที่'+AddressDetail2+' '+AddressDetail3+' ถนน'+AddressDetail4 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno),'หมู่ที่-',''),'ถนน-',''),' -',''),'_','') AS IDCardAddressDetail
,(SELECT SubDistrictName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.SubDistrict as s on s.SubDistrictCode = a.SubDistrictCode WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardSubDistrict
,(SELECT DistrictName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.District as d on d.DistrictCode = a.DistrictCode WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardDistrict
,(SELECT ProvinceName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.Province as p on p.ProvinceCode = a.ProvinceCode WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardProvince
,(SELECT Zipcode FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardZipcode
,REPLACE((SELECT TelHome FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno),'-','') AS telHome
,REPLACE((SELECT TelMobile FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno),'-','') AS TelMobile
,REPLACE((SELECT TelOffice FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno),'-','') AS TelOffice,CONVERT(varchar(20),FT.[StartDate],105) +' '+ CONVERT(varchar(5),FT.[StartDate],108) AS [StartDate]
,CONVERT(varchar(20),FT.[EndDate],105) +' '+ CONVERT(varchar(5),FT.[EndDate],108) AS EndDate
FROM TSRData_Source.dbo.vw_ContractSalePrice AS C
INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC  ON c.CustomerID = DC.CustomerID
INNER JOIN Bighead_Mobile.dbo.Employee AS E ON E.empID = C.saleEmployeecode
LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed ON Ed.salecode = C.service
INNER JOIN Bighead_Mobile.dbo.Organization AS OG ON C.organizationCode = OG.organizationCode
INNER JOIN Bighead_Mobile.dbo.Fortnight As Ft ON FT.FortnightID = C.FortnightID
INNER JOIN Bighead_Mobile.dbo.Product AS PD ON PD.ProductID = C.ProductID
where c.STATUS IN ('NORMAL','F') AND C.IsMigrate = 0 $sqltextdate $sqltextrefno $sqltextcontno
) AS A
ORDER BY SaleTeamCode,SaleCode,Refno ASC";
*/

$sql_case = "SELECT distinct *
FROM (
SELECT C.ContractReferenceNo as Refno
,C.CONTNO as CONTNO
,CONVERT(varchar(20),C.EFFDATE,105)as EFFDATE
,C.PreSaleEmployeeCode
,C.SaleCode
,ft.FortnightNumber,ft.Year
,null as Channel, null as bcode
,C.SaleEmployeeCode
,C.SaleTeamCode
,C.ProductSerialNumber,C.Model,C.MODE
,BM.CASH as sales
,C.sales AS credit,C.tradeindiscount,C.totalprice,left(C.[status],1) as status
,CONVERT(varchar(20),C.lastupdateDate,105) AS lastupdateDate
,(SELECT PaymentAmount FROM [Bighead_Mobile].[dbo].[PackagePeriodDetail] where model = c.Model AND paymentperiodnumber = 1) AS FirstPayment
,(SELECT PaymentAmount FROM [Bighead_Mobile].[dbo].[PackagePeriodDetail] where model = c.Model AND paymentperiodnumber = 1) - tradeindiscount AS FirstPaymentPeriod
,(SELECT PaymentAmount FROM [Bighead_Mobile].[dbo].[PackagePeriodDetail] where model = c.Model AND paymentperiodnumber = 2) AS PaymentPeriod
,DC.PrefixName,case when dc.CustomerType = 1 then DC.CompanyName else DC.CustomerName end as CustomerName
,case when dc.CustomerType = 1 then REPLACE(DC.AuthorizedIDCard,'-','') else REPLACE(DC.IDCard,'-','') end as IDCard
,case when dc.CustomerType = 1 then REPLACE(DC.IDCard,'-','') else null end as TaxID
--,REPLACE(REPLACE(REPLACE(REPLACE((SELECT AddressDetail+' หมู่ที่'+AddressDetail2+' '+AddressDetail3+' ถนน'+AddressDetail4 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno),'หมู่ที่-',''),'ถนน-',''),' -',''),'_','') AS InstallAddressDetail
,(SELECT AddressDetail FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallAddressDetail
,(SELECT AddressDetail2 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallAddressDetail2
,(SELECT AddressDetail3 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallAddressDetail3
,(SELECT AddressDetail4 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallAddressDetail4
,(SELECT SubDistrictName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.SubDistrict as s on s.SubDistrictCode = a.SubDistrictCode WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallSubDistrict
,(SELECT DistrictName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.District as d on d.DistrictCode = a.DistrictCode WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallDistrict
,(SELECT ProvinceName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.Province as p on p.ProvinceCode = a.ProvinceCode WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallProvince
,(SELECT Zipcode FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno) AS InstallZipcode
--,REPLACE(REPLACE(REPLACE(REPLACE((SELECT AddressDetail+' หมู่ที่'+AddressDetail2+' '+AddressDetail3+' ถนน'+AddressDetail4 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno),'หมู่ที่-',''),'ถนน-',''),' -',''),'_','') AS IDCardAddressDetail
,(SELECT AddressDetail FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardAddressDetail
,(SELECT AddressDetail2 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardAddressDetail2
,(SELECT AddressDetail3 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardAddressDetail3
,(SELECT AddressDetail4 FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardAddressDetail4
,(SELECT SubDistrictName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.SubDistrict as s on s.SubDistrictCode = a.SubDistrictCode WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardSubDistrict
,(SELECT DistrictName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.District as d on d.DistrictCode = a.DistrictCode WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardDistrict
,(SELECT ProvinceName FROM [Bighead_Mobile].[dbo].Address AS A inner join Bighead_Mobile.dbo.Province as p on p.ProvinceCode = a.ProvinceCode WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardProvince
,(SELECT Zipcode FROM [Bighead_Mobile].[dbo].Address WHERE AddressTypeCode = 'AddressIDCard' AND refno = C.refno) AS IDCardZipcode
,REPLACE((SELECT TelHome FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno),'-','') AS telHome
,REPLACE((SELECT TelMobile FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno),'-','') AS TelMobile
,REPLACE((SELECT TelOffice FROM [Bighead_Mobile].[dbo].[vw_GetAddress] WHERE AddressTypeCode = 'AddressInstall' AND refno = C.refno),'-','') AS TelOffice,CONVERT(varchar(20),FT.[StartDate],105) +' '+ CONVERT(varchar(5),FT.[StartDate],108) AS [StartDate]
,CONVERT(varchar(20),FT.[EndDate],105) +' '+ CONVERT(varchar(5),FT.[EndDate],108) AS EndDate
FROM TSRData_Source.dbo.vw_ContractSalePrice AS C
INNER JOIN TSRData_Source.dbo.vw_DebtorCustomer AS DC  ON c.CustomerID = DC.CustomerID
INNER JOIN Bighead_Mobile.dbo.Employee AS E ON E.empID = C.saleEmployeecode
LEFT JOIN Bighead_Mobile.dbo.EmployeeDetail AS Ed ON Ed.salecode = C.service
INNER JOIN Bighead_Mobile.dbo.Organization AS OG ON C.organizationCode = OG.organizationCode
INNER JOIN Bighead_Mobile.dbo.Fortnight As Ft ON FT.FortnightID = C.FortnightID
INNER JOIN Bighead_Mobile.dbo.Product AS PD ON PD.ProductID = C.ProductID
LEFT JOIN [LINK_STOCK].[TSRDATA].[dbo].[PROMODEL] AS BM on BM.model = C.model
where c.STATUS IN ('NORMAL','F') AND C.IsMigrate = 0 $sqltextdate $sqltextrefno $sqltextcontno
) AS A
ORDER BY SaleTeamCode,SaleCode,Refno ASC";
		//echo $sql_case;

		$stmt = sqlsrv_query($conn,$sql_case);
		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
			$Name = explode(" ",$row['CustomerName']);

			$StartDate = explode("-",$row['StartDate']);
			$EndDate = explode("-",$row['EndDate']);
			$lastupdateDate = explode("-",$row['lastupdateDate']);
			$EFFDATE = explode("-",$row['EFFDATE']);

			$StartDate["2"] = $StartDate["2"]+543;
			$EndDate["2"] = $EndDate["2"]+543;
			$lastupdateDate["2"] = $lastupdateDate["2"]+543;
			$EFFDATE["2"] = $EFFDATE["2"]+543;

			$StartDate1 = $StartDate["0"]."/".$StartDate["1"]."/".$StartDate["2"];
			$EndDate1 = $EndDate["0"]."/".$EndDate["1"]."/".$EndDate["2"];
			$lastupdateDate1 = $lastupdateDate["0"]."/".$lastupdateDate["1"]."/".$lastupdateDate["2"];
			$EFFDATE1 = $EFFDATE["0"]."/".$EFFDATE["1"]."/".$EFFDATE["2"];

			$firstName = $Name["0"];
			$lastName = "";
			for ($i=1; $i < count($Name); $i++) {
				$lastName .= $Name[$i];
			}
			//$lastName = $Name["1"]." ".$Name["2"];
			$json_result[] = ['Refno'=>$row['Refno']
			,'CONTNO'=>$row['CONTNO']
			,'EFFDATE'=>$EFFDATE1
			,'PreSaleEmployeeCode'=>$row['PreSaleEmployeeCode']
			,'SaleCode'=>$row['SaleCode']
			,'Channel'=>$row['Channel']
			,'bcode'=>$row['bcode']
			,'ProductSerialNumber'=>$row['ProductSerialNumber']
			,'Model'=>$row['Model']
			,'MODE'=>$row['MODE']
			,'sales'=>$row['sales']
			,'credit'=>$row['credit']
			,'tradeindiscount'=>$row['tradeindiscount']
			,'totalprice'=>$row['totalprice']
			,'status'=>$row['status']
			,'lastupdateDate'=>$lastupdateDate1
			,'FirstPayment'=>$row['FirstPayment']
			,'FirstPaymentPeriod'=>$row['FirstPaymentPeriod']
			,'PaymentPeriod'=>$row['PaymentPeriod']
			,'PrefixName'=>$row['PrefixName']
			,'FirstName'=>$firstName
			,'LastName'=>$lastName
			,'IDCard'=>$row['IDCard']
			,'TaxID'=>$row['TaxID']
			,'InstallAddressDetail'=>$row['InstallAddressDetail']
			,'InstallAddressDetail2'=>$row['InstallAddressDetail2']
			,'InstallAddressDetail3'=>$row['InstallAddressDetail3']
			,'InstallAddressDetail4'=>$row['InstallAddressDetail4']
			,'InstallSubDistrict'=>$row['InstallSubDistrict']
			,'InstallDistrict'=>$row['InstallDistrict']
			,'InstallProvince'=>$row['InstallProvince']
			,'InstallZipcode'=>$row['InstallZipcode']
			,'IDCardAddressDetail'=>$row['IDCardAddressDetail']
			,'IDCardAddressDetail2'=>$row['IDCardAddressDetail2']
			,'IDCardAddressDetail3'=>$row['IDCardAddressDetail3']
			,'IDCardAddressDetail4'=>$row['IDCardAddressDetail4']
			,'IDCardSubDistrict'=>$row['IDCardSubDistrict']
			,'IDCardDistrict'=>$row['IDCardDistrict']
			,'IDCardProvince'=>$row['IDCardProvince']
			,'IDCardZipcode'=>$row['IDCardZipcode']
			,'telHome'=>$row['telHome']
			,'TelMobile'=>$row['TelMobile']
			,'TelOffice'=>$row['TelOffice']
			,'StartDate'=>$StartDate1
			,'EndDate'=>$EndDate1
			,'IDCardZipcode'=>$row['IDCardZipcode']
			,];
			$sql_insert = "INSERT INTO TSRDATA.dbo.MastTelNo (RefNo,CONTNO,MobilePhone,Telephone,SerialNo) VALUES (?,?,?,?,?)";
			$params = array($row['Refno'],$row['CONTNO'],$row['TelMobile'],$row['telHome'],$row['ProductSerialNumber']);
			$stmt_insert = sqlsrv_query( $con, $sql_insert, $params);
		}
		//INSERT ตารางที่แซมทำใหม่

$sql_insert2 = "INSERT INTO TSRDATA.dbo.MastTelNo
SELECT m.refno,m.contno,m.MOBIE,m.TELEPHONE,c.serialno
FROM TSRDATA.dbo.MastAddr AS m
INNER JOIN TSRDATA.dbo.MastCont AS c ON c.refno = m.refno
LEFT JOIN TSRDATA.dbo.MastTelNo AS t ON t.refno = m.refno
WHERE t.refno IS NULL";
		$stmt_insert2 = sqlsrv_query($con,$sql_insert2);


		echo json_encode($json_result);
	}
sqlsrv_close($con);
sqlsrv_close($conn);

?>
