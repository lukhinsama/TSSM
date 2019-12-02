<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$conn = connectDB_BigHead();

	//ตรวจสอบว่า มีค่า ตัวแปร $_GET['province_id'] เข้ามาหรือไม่  //แสดงรายชืออำเภอ
		if(isset($_GET['TeamCode'])){
		//$json_result[] = ['no'=>'0','salecode'=>'ทั้งหมด','salename'=>'ทั้งหมด','saleteam'=>'ทั้งหมด'];

			$sql_case = "SELECT EmployeeCode,SaleCode,TeamCode,EmployeeName FROM Bighead_Mobile.dbo.EmployeeDetail WHERE TeamCode = '".$_GET['TeamCode']."' AND SaleCode IS NOT NULL ORDER BY EmployeeCode";

			//echo $sql_case;
			$stmt = sqlsrv_query($conn,$sql_case);
			while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				$json_result[] = ['empID'=>$row['EmployeeCode'],'salecode'=>$row['SaleCode'],'teamcode'=>$row['TeamCode'],'empName'=>$row['EmployeeName']];
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
