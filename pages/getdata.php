<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$conn = connectDB_TSR();
	//ตรวจสอบว่า มีค่า ตัวแปร $_GET['show_province'] เข้ามาหรือไม่  	//แสดงรายชื่อจังหวัด
	if(isset($_GET['show_province'])){

		//คำสั่ง SQL เลือก id และ  ชื่อจังหวัด
		$sql = "SELECT [SupervisorCode],
		CASE [SupervisorCode]
		WHEN '101' THEN 'ทีม A'
		WHEN '102' THEN 'ทีม B'
		WHEN '103' THEN 'ทีม D'
		WHEN '104' THEN 'ทีม E'
		WHEN '105' THEN 'ทีม F'
		WHEN '106' THEN 'ทีม H'
		ELSE 'ทีม I'
		END AS TeamCode
		FROM [TSS_PRD].[Bighead_Mobile].[dbo].[EmployeeDetail] AS Emd
		LEFT JOIN [TSS_PRD].[Bighead_Mobile].[dbo].[Employee] AS Em
		ON Emd.EmployeeCode = Em.EmpID
		WHERE SourceSystem = 'Credit' AND saleCode is not null AND (EmployeeTypeCode is not null AND EmployeeTypeCode != '')
		GROUP BY [SupervisorCode]";

		//echo $sql;
		$stmt = sqlsrv_query($conn,$sql);
		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

				//เก็บข้อมูลที่ได้ไว้ในตัวแปร Array
				$json_result[] = [
					'id'=>$row['SupervisorCode'],
					'name'=>$row['TeamCode'],
				];
			}

			//ใช้ Function json_encode แปลงข้อมูลในตัวแปร $json_result ให้เป็นรูปแบบ Json
			echo json_encode($json_result);

	}

	//ตรวจสอบว่า มีค่า ตัวแปร $_GET['province_id'] เข้ามาหรือไม่  //แสดงรายชืออำเภอ
	if(isset($_GET['province_id'])){

		//กำหนดให้ตัวแปร $province_id มีค่าเท่ากับ $_GET['province_id]
		$province_id = $_GET['province_id'];

		if ($province_id != "0") {
			$WHERE = "AND Ed.SupervisorCode = '".$province_id."'";
		}else {
			$WHERE = "";
		}

		//คำสั่ง SQL เลือก AMPHUR_ID และ  AMPHUR_NAME ที่มี PROVINCE_ID เท่ากับ $province_id
		$sql = "SELECT CCode,Name,EmpID ,case when ed.SaleCode is null then '-' else ed.SaleCode end as SaleCode FROM [TSS_PRD].[TsrData_source].[dbo].[CArea] AS C LEFT JOIN [TSS_PRD].Bighead_Mobile.dbo.EmployeeDetail AS Ed ON Ed.EmployeeCode = c.EmpID AND salecode is not null WHERE EmpId is not null AND EmpId != '' $WHERE ORDER BY ccode ";

		$stmt = sqlsrv_query($conn,$sql);
		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

				//เก็บข้อมูลที่ได้ไว้ในตัวแปร Array
				$json_result[] = [
					'id'=>$row['EmpID']."_".$row['Name']."_".$row['CCode']."_".$row['SaleCode']."_".$row['EmpID'],
					'name'=>$row['EmpID']." (".$row['CCode'].") ".$row['Name'],
				];
			}

			//ใช้ Function json_encode แปลงข้อมูลในตัวแปร $json_result ให้เป็นรูปแบบ Json
			echo json_encode($json_result);


	}


sqlsrv_close($conn);

?>
