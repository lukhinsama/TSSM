<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);

include_once("../include/inc-fuction.php");

if (isset($_GET["id"])) {
  //ECHO $_REQUEST["id"];
  $EmpID = "A".substr($_COOKIE['tsr_emp_id'],1);
  $conn = connectDB_BigHead();
  $SQL = "UPDATE TSRData_Source.dbo.TSSM_SaleOuting SET Active = 0 , UpdateUser = '".$EmpID."' WHERE id = ".$_GET["id"]."";
  //echo $sql_insert;
  //$params = array($_REQUEST['TeamCode'],$_REQUEST['BranchCode'],$startDate,$endDate,$EmpID);
  //print_r($params);
  $stmt_insert = sqlsrv_query( $conn, $SQL);
  sqlsrv_close($conn);
  header("Location: https://tssm.thiensurat.co.th/index.php?pages=addOutingTrip");
}else {
  ECHO "ส่งค่ามาผิดเงื่อนไข นะจ๊ะ";
}
?>
