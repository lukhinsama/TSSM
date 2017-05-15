<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

$conn = connectDB_TSR();
//echo $_REQUEST['addcountno'];
print_r($_REQUEST['addcountno']) ;

// เก็บ LOG การเพิ่มข้อมูล
$sql_insert = "INSERT INTO TSR_Application.dbo.TSS_AddMastContTOBigHead_SYS (userAdd,contno,addtime,addType) VALUES (?,?,GETDATE(),1)";
//echo $sql_insert;

$stringCountno = "";

foreach($_REQUEST['addcountno'] as $value)
{
	if ($stringCountno == "") {
		$stringCountno = "'".$value."'";
	}else {
		$stringCountno = $stringCountno.",'".$value."'";
	}

	$params = array("030371",$value);
	//print_r($params);

	$stmt_insert = sqlsrv_query( $conn, $sql_insert, $params);
}
echo $stringCountno;


sqlsrv_close($conn);

?>
