<?PHP
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
include_once("inc-fuction.php");

$searchTerm = $_GET['term'];

switch ($_GET['types']) {
  case 'agreeWork':
    $conn = connectDB_HR();
    $sql = "SELECT EmpID+'_'+EmpFNameT+' '+EmpLNameT as EmpName FROM [HR_TSR_2016].[dbo].[tbMTEmpMain] WHERE PositionID in (SELECT PositionID FROM HR_TSR_2016.dbo.tbMTEmpMain WHERE EmpID = '".$_GET['EmpID']."' AND EmpFNameT LIKE '%".$searchTerm."%')";
    //echo $sql;

    $stmt = sqlsrv_query( $conn, $sql );

    while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      # code...
      $ret[] = $row["EmpName"];
    }
    echo json_encode($ret);
    //sqlsrv_close($stmt);
    break;

    default:
    # code...
    break;
}


?>
