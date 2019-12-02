<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$con = connectDB_BigHead();
$sql = "SELECT AmphoeID,AmphoeName,ChangwatID FROM TSRData_Source.dbo.TSSM_AmphoeGPS WHERE ChangwatID = '".$_GET['province']."'";
		//echo $sql;
		$json_result[] = ['id'=>'0','name'=>'อำเภอ/เขต',];
		$stmt = sqlsrv_query($con,$sql);
		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
			$json_result[] = ['id'=>$row['AmphoeID'],'name'=>$row['AmphoeName'],];
		}

		echo json_encode($json_result);
sqlsrv_close($con);
?>
