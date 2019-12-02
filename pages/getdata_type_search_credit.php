<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$conn = connectDB_BigHead();
	//ตรวจสอบว่า มีค่า ตัวแปร $_GET['show_province'] เข้ามาหรือไม่  	//แสดงรายชื่อจังหวัด
	if(isset($_GET['levelEmp'])){
		$json_result[] = ['id'=>'7','name'=>'ฝ่าย',];
		$json_result[] = ['id'=>'6','name'=>'สาย/ภาค',];
		$json_result[] = ['id'=>'5','name'=>'ชุป/จังหวัด',];
		$json_result[] = ['id'=>'4','name'=>'ทีม',];
		$json_result[] = ['id'=>'3','name'=>'หน่วย',];
		$json_result[] = ['id'=>'2','name'=>'พนักงาน',];


			//ใช้ Function json_encode แปลงข้อมูลในตัวแปร $json_result ให้เป็นรูปแบบ Json
			echo json_encode($json_result);
	}


	//$EmpID = "A06797";
	$EmpID = "A".substr($_COOKIE['tsr_emp_id'],1,5);


	//ตรวจสอบว่า มีค่า ตัวแปร $_GET['province_id'] เข้ามาหรือไม่  //แสดงรายชืออำเภอ
	if(isset($_GET['LvEmp'])){

		$json_result[] = ['id'=>'0','name'=>'ทั้งหมด',];

		switch ($_GET['LvEmp']) {
		case 6:
			$sql_case = "SELECT EmployeeCode,SubDepartmentName+' '+EmployeeName AS Pos FROM Bighead_Mobile.dbo.EmployeeDetail WHERE PositionCode = 'CreditLineManager' AND ProcessType = 'credit' ORDER BY Pos";

			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['id'=>$row['EmployeeCode'],'name'=>$row['Pos'],];
			}
        break;
    case 5:
		  if ($_COOKIE['tsr_emp_permit'] == 1 || $_COOKIE['tsr_emp_permit'] == 2 || ($_COOKIE['tsr_emp_permit'] == 13)) {
				$sql_case = "SELECT EmployeeCode,SupervisorName+' '+ EmployeeName AS Pos FROM Bighead_Mobile.dbo.EmployeeDetail WHERE PositionCode = 'CreditSupervisor' AND ProcessType = 'credit' ORDER BY Pos";
			}else {
				$sql_case = "SELECT EmployeeCode,SupervisorName+' '+ EmployeeName
				AS Pos FROM Bighead_Mobile.dbo.EmployeeDetail WHERE PositionCode = 'CreditSupervisor' AND ProcessType = 'credit' AND EmployeeCode IN ( SELECT DISTINCT EmployeeCodeLV5 FROM [TSRData_Source].[dbo].[vw_EmployeeDataParentcredit] WHERE (EmployeeCodeLV2 = '".$EmpID."' OR EmployeeCodeLV3 = '".$EmpID."' OR EmployeeCodeLV4 = '".$EmpID."' OR EmployeeCodeLV5 = '".$EmpID."' OR EmployeeCodeLV6 = '".$EmpID."' OR ParentEmployeeCode = '".$EmpID."') AND EmployeeCodeLV1 IS NOT NULL AND SaleCode IS NOT NULL AND SaleCode != EmployeeCodeLV1)
					ORDER BY Pos";
			}
			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['id'=>$row['EmployeeCode'],'name'=>$row['Pos'],];
			}
        break;
    case 4:
			if ($_COOKIE['tsr_emp_permit'] == 1 || $_COOKIE['tsr_emp_permit'] == 2 || ($_COOKIE['tsr_emp_permit'] == 13)) {
				$sql_case = "SELECT EmployeeCode,TeamName+' '+ EmployeeName AS Pos
				FROM Bighead_Mobile.dbo.EmployeeDetail WHERE PositionCode = 'CreditTeamLeader' AND ProcessType = 'credit' ORDER BY Pos";
			}else {
				$sql_case = "SELECT EmployeeCode,TeamName+' '+ EmployeeName AS Pos FROM Bighead_Mobile.dbo.EmployeeDetail WHERE PositionCode = 'CreditTeamLeader' AND ProcessType = 'credit'
				AND EmployeeCode IN ( SELECT DISTINCT EmployeeCodeLV4 FROM [TSRData_Source].[dbo].[vw_EmployeeDataParentcredit] WHERE (EmployeeCodeLV2 = '".$EmpID."' OR EmployeeCodeLV3 = '".$EmpID."' OR EmployeeCodeLV4 = '".$EmpID."'
					OR EmployeeCodeLV5 = '".$EmpID."' OR EmployeeCodeLV6 = '".$EmpID."' OR ParentEmployeeCode = '".$EmpID."') AND EmployeeCodeLV1 IS NOT NULL AND SaleCode IS NOT NULL AND SaleCode != EmployeeCodeLV1)
					ORDER BY Pos";
			}
			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['id'=>$row['EmployeeCode'],'name'=>$row['Pos'],];
			}
        break;
		case 3:
			if ($_COOKIE['tsr_emp_permit'] == 1 || $_COOKIE['tsr_emp_permit'] == 2 || ($_COOKIE['tsr_emp_permit'] == 13)) {
				$sql_case = "SELECT EmployeeCode,SubTeamName+' '+ EmployeeName AS Pos
				FROM Bighead_Mobile.dbo.EmployeeDetail WHERE PositionCode = 'CreditSubTeamLeader' AND ProcessType = 'credit' ORDER BY Pos";
			}else {
				$sql_case = "SELECT EmployeeCode,SubTeamName+' '+ EmployeeName AS Pos
				FROM Bighead_Mobile.dbo.EmployeeDetail WHERE PositionCode = 'CreditSubTeamLeader' AND ProcessType = 'credit' AND EmployeeCode IN ( SELECT DISTINCT EmployeeCodeLV3 FROM [TSRData_Source].[dbo].[vw_EmployeeDataParentcredit] WHERE (EmployeeCodeLV2 = '".$EmpID."' OR EmployeeCodeLV3 = '".$EmpID."'
					OR EmployeeCodeLV4 = '".$EmpID."' OR EmployeeCodeLV5 = '".$EmpID."' OR EmployeeCodeLV6 = '".$EmpID."' OR ParentEmployeeCode = '".$EmpID."') AND EmployeeCodeLV1 IS NOT NULL AND SaleCode IS NOT NULL AND SaleCode != EmployeeCodeLV1) ORDER BY Pos";
			}
			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['id'=>$row['EmployeeCode'],'name'=>$row['Pos'],];
			}
				break;
		default:
			if ($_COOKIE['tsr_emp_permit'] == 1 || $_COOKIE['tsr_emp_permit'] == 2 || ($_COOKIE['tsr_emp_permit'] == 13)) {
				$sql_case = "SELECT EmployeeCode,SaleCode+' '+ EmployeeName AS Pos
				FROM Bighead_Mobile.dbo.EmployeeDetail WHERE PositionCode = 'Credit' AND ProcessType = 'Credit' ORDER BY Pos";
			}else {
				$sql_case = "SELECT EmployeeCode,SaleCode+' '+ EmployeeName AS Pos
				FROM Bighead_Mobile.dbo.EmployeeDetail WHERE PositionCode = 'Credit' AND ProcessType = 'credit' AND EmployeeCode IN ( SELECT DISTINCT EmployeeCodeLV2 FROM [TSRData_Source].[dbo].[vw_EmployeeDataParentcredit]
				WHERE (EmployeeCodeLV2 = '".$EmpID."' OR EmployeeCodeLV3 = '".$EmpID."' OR EmployeeCodeLV4 = '".$EmpID."' OR EmployeeCodeLV5 = '".$EmpID."' OR EmployeeCodeLV6 = '".$EmpID."' OR ParentEmployeeCode = '".$EmpID."')
				AND EmployeeCodeLV1 IS NOT NULL AND SaleCode IS NOT NULL AND SaleCode != EmployeeCodeLV1) ORDER BY Pos";
			}
			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['id'=>$row['EmployeeCode'],'name'=>$row['Pos'],];
			}
				break;
		}
		/*
		$json_result[] = ['id'=>'5','name'=>'1',];
		$json_result[] = ['id'=>'4','name'=>'2',];
		$json_result[] = ['id'=>'3','name'=>'3',];
		$json_result[] = ['id'=>'2','name'=>'4',];
		$json_result[] = ['id'=>'1','name'=>'5',];
		*/
		echo json_encode($json_result);
	}

sqlsrv_close($conn);

?>
