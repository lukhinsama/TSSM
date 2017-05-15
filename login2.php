<?php
include("include/inc-fuction.php");
$conn = connectDB_TSR();

$sql = "SELECT ad_name ,emp_id ,first_name+' '+ last_name as displayname
  FROM [TSR_Application].[dbo].[employee_data]
  where emp_id = '".$_GET['empid']."'";

$stmt = sqlsrv_query($conn,$sql);
if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

  $sql1 = "SELECT top 1 permission FROM [TSR_Application].[dbo].[TSS_M_User] WHERE user_id = '".$row['ad_name']."'";

  $stmt1 = sqlsrv_query($conn,$sql1);
  if ($row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) {
    if (!empty($row1['permission'])) {
      setcookie("tsr_emp_permit", ereg_replace("A","0",$row1['permission']) , time() + (86400 * 30));
    }else {
      setcookie("tsr_emp_permit", ereg_replace("A","0","4") , time() + (86400 * 30));
    }
  }else {
    setcookie("tsr_emp_permit", ereg_replace("A","0","4") , time() + (86400 * 30));
  }
  setcookie("tsr_emp_id", ereg_replace("A","0",$row['emp_id']) , time() + (86400 * 30));
  setcookie("tsr_emp_name", ereg_replace("A","0",$row['displayname']) , time() + (86400 * 30));
  //echo "A Code = ".$row['emp_id']."-".$_COOKIE['tsr_emp_permit'];
  header( "Location:index.php" );
}else{
  header( "Location:login.php" );
  //  echo "B Code = ".$row['emp_id']."-".$_COOKIE['tsr_emp_permit'];
}
sqlsrv_close($conn);
?>
