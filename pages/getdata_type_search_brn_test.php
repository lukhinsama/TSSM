<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$conn = connectDB_BigHead();
	//ตรวจสอบว่า มีค่า ตัวแปร $_GET['show_province'] เข้ามาหรือไม่  	//แสดงรายชื่อจังหวัด
	if(isset($_GET['levelEmp'])){
		$json_result[] = ['id'=>'6','name'=>'สาย/ภาค',];
		$json_result[] = ['id'=>'5','name'=>'ซุป/สาขา',];
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
			$sql_case = "SELECT SubDepartmentCode AS EmployeeCode,SubDepartmentName+' '+EmployeeName AS Pos FROM Bighead_Mobile.dbo.EmployeeDetail  WHERE PositionCode = 'LineManager' AND ProcessType IN ('sale','brn','on','ib') AND SubDepartmentCode != 'SP' ORDER BY Pos";

			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['id'=>$row['EmployeeCode'],'name'=>$row['Pos'],];
			}
        break;
    case 5:

				$sql_case = "SELECT DISTINCT SupervisorCode AS EmployeeCode,SupervisorName+' '+ EmployeeName AS Pos FROM Bighead_Mobile.dbo.EmployeeDetail  WHERE PositionCode = 'Supervisor' AND ProcessType IN ('sale','brn','on','ib') ORDER BY Pos";

			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['id'=>$row['EmployeeCode'],'name'=>$row['Pos'],];
			}
        break;
    case 4:
				$sql_case = "SELECT DISTINCT TeamCode AS EmployeeCode,TeamName+' '+ EmployeeName AS Pos FROM Bighead_Mobile.dbo.EmployeeDetail  WHERE PositionCode = 'SaleLeader' AND ProcessType IN ('sale','brn','on','ib') ORDER BY Pos";

			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['id'=>$row['EmployeeCode'],'name'=>$row['Pos'],];
			}
        break;
		case 3:
				$sql_case = "SELECT DISTINCT replace(SubTeamCode,'-','') AS EmployeeCode,SubTeamName+' '+ EmployeeName AS Pos FROM Bighead_Mobile.dbo.EmployeeDetail  WHERE PositionCode = 'SubTeamLeader' AND ProcessType IN ('sale','brn','on','ib') ORDER BY Pos";

			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['id'=>$row['EmployeeCode'],'name'=>$row['Pos'],];
			}
				break;
		default:
				$sql_case = " SELECT DISTINCT Salecode AS EmployeeCode,Salecode+' '+ EmployeeName AS Pos
 FROM Bighead_Mobile.dbo.EmployeeDetail
 WHERE PositionCode = 'Sale' AND ProcessType IN ('sale','brn','on','ib') ORDER BY Pos";
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
