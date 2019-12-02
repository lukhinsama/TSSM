<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

include_once("../include/inc-fuction.php");

if (isset($_REQUEST["ID"])) {
  //ECHO $_REQUEST["id"];
  $EmpID = "A".substr($_COOKIE['tsr_emp_id'],1);
  $conn = connectDB_BigHead();

  $sql_insert = "INSERT INTO TSRData_Source.dbo.TSSM_Log_All (PageLog,OldData,StatusData,UserEdit) VALUES ('editReceiptManual',?,'DELETE',?)";
  $params = array($_REQUEST['OldData'],$EmpID);
  $stmt_insert = sqlsrv_query( $conn, $sql_insert, $params);
  if( $stmt_insert === false ) {
     die( print_r( sqlsrv_errors(), true));
  }else {
    //DELETE
    $sql_update = "UPDATE Bighead_Mobile.dbo.ManualDocument SET ManualVolumeNo = NULL,ManualRunningNo = NULL WHERE DocumentID = ?";
    $params = array($_REQUEST['ID']);
    $stmt_update = sqlsrv_query( $conn, $sql_update, $params);
  }
  sqlsrv_close($conn);
  header("Location: https://tssm.thiensurat.co.th/index.php?pages=editReceiptManual");
}else {
  ECHO "ส่งค่ามาผิดเงื่อนไข นะจ๊ะ";
}
?>
