<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$con = connectDB_BigHead();
$sql = "SELECT DISTINCT TambonID,TambonName FROM TSRData_Source.dbo.TSSM_TambonGPS WHERE AmphoeID = '".$_GET['distict']."'";
		//echo $sql;
		$json_result[] = ['id'=>'0','name'=>'ตำบล/แขวง',];
		$stmt = sqlsrv_query($con,$sql);
		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
			$json_result[] = ['id'=>$row['TambonID'],'name'=>$row['TambonName'],];
		}

		echo json_encode($json_result);
sqlsrv_close($con);
?>
