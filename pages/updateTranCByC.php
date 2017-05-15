<?PHP

ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

include("../include/inc-fuction.php");


if (!empty($_REQUEST['contno'])) {
  echo $_REQUEST['contno'];
  echo $_REQUEST['AssigneeEmpID'];

  //$conn = connectDB_BigHead();
  //sqlsrv_close($conn);
}
//$_REQUEST['searchCar'];
?>
