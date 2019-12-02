<?php




$serverName = "192.168.110.133";
$connectionInfo = array( "Database"=>"assanee_mobile", "UID"=>"tsrapp", "PWD"=>"6z3sNrCzWp");






$conn = sqlsrv_connect( $serverName, $connectionInfo );
if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true));
}

$sql = "SELECT 
 [ID]
,[EmpID]
,[pass]
,[fcm_key]
,[picture]
,[backgound]
,[lasdate_login]
,[lasdate_logout]
,[themesapp]
,[themes_color1]
,[themes_color2]
,[themes_color3]
,[themes_color4]
,[themes_color5]
,[themes_app1]
,[themes_app2]
,[themes_app3]
,[themes_app4]
,[themes_app5]
,[themes_menu1]
,[themes_menu2]
,[themes_menu3]
,[themes_menu4]
,[themes_menu5]
,[design_app_on_off_all]
,[checklogin]
,[android_device]
,[android_name]
,[android_version]
,[android_vcode]
,[EmployeeName]
,[PositionCode]
,[PositionName]
,[TeamHeadCode]
,[TeamHeadName]
,[TeamName]
,[SupervisorHeadCode]
,[SupervisorHeadName]
,[SupervisorName]
,[SubDepartmentHeadCode]
,[SubDepartmentHeadName]
,[SubDepartmentName]
,[DepartmentHeadCode]
,[DepartmentHeadName]
,[DepartmentName]
,[SubTeamCode]
,[TeamCode]
,[SourceSystem]
,[SALECODE]
,[DepartId]
,[mcode]
,[CashTeamCode]
,[debug_run_error_checker1]
FROM [assanee_mobile].[dbo].[loginapp]  where EmpID='$_GET[EmpID]' ";
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}
$resultA=[];

while( $rslogin = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {






    $result = array(
        'EmployeeName'   		=>  ($rslogin['EmployeeName'] == null) ? '' : $rslogin['EmployeeName'],
        'PositionCode'   		=>  ($rslogin['PositionCode'] == null) ? '' : $rslogin['PositionCode'],
        'PositionName'   		=>  ($rslogin['PositionName'] == null) ? '' : $rslogin['PositionName'],
        'picture'  				=>  ($rslogin['picture'] == null) ? '' : $rslogin['picture'],
        'backgound'   			=>  ($rslogin['backgound'] == null) ? '' : $rslogin['backgound'],
        'TeamHeadCode'   		=>  ($rslogin['TeamHeadCode'] == null) ? '' : $rslogin['TeamHeadCode'],
        'TeamHeadName'   		=>  ($rslogin['TeamHeadName'] == null) ? '' : $rslogin['TeamHeadName'],
        'TeamName'   			=>  ($rslogin['TeamName'] == null) ? '' : $rslogin['TeamName'],
        'SupervisorHeadCode'   	=>  ($rslogin['SupervisorHeadCode'] == null) ? '' : $rslogin['SupervisorHeadCode'],
        'SupervisorHeadName'   	=>  ($rslogin['SupervisorHeadName'] == null) ? '' : $rslogin['SupervisorHeadName'],
        'SupervisorName'   		=>  ($rslogin['SupervisorName'] == null) ? '' : $rslogin['SupervisorName'],
        'SubDepartmentHeadCode' =>  ($rslogin['SubDepartmentHeadCode'] == null) ? '' : $rslogin['SubDepartmentHeadCode'],
        'SubDepartmentHeadName' =>  ($rslogin['SubDepartmentHeadName'] == null) ? '' : $rslogin['SubDepartmentHeadName'],
        'SubDepartmentName'   	=>  ($rslogin['SubDepartmentName'] == null) ? '' : $rslogin['SubDepartmentName'],
        'DepartmentHeadCode'   	=>  ($rslogin['DepartmentHeadCode'] == null) ? '' : $rslogin['DepartmentHeadCode'],
        'DepartmentHeadName'   	=>  ($rslogin['DepartmentHeadName'] == null) ? '' : $rslogin['DepartmentHeadName'],
        'DepartmentName'   		=>  ($rslogin['DepartmentName'] == null) ? '' : $rslogin['DepartmentName'],
        'SubTeamCode'   		=>  ($rslogin['SubTeamCode'] == null) ? '' : $rslogin['SubTeamCode'],
        'TeamCode'   			=>  ($rslogin['TeamCode'] == null) ? '' : $rslogin['TeamCode'],
        'themesapp'   			=>  ($rslogin['themes_app1'] == null) ? '' : $rslogin['themes_app1'],
        'themes_color1'   		=>  ($rslogin['themes_color1'] == null) ? '' : $rslogin['themes_color1'],
        'themes_color2'   		=>  ($rslogin['themes_color2'] == null) ? '' : $rslogin['themes_color2'],
        'themes_color3'   		=>  ($rslogin['themes_color3'] == null) ? '' : $rslogin['themes_color3'],
        'themes_color4'   		=>  ($rslogin['themes_color4'] == null) ? '' : $rslogin['themes_color4'],
        'themes_color5'   		=>  ($rslogin['themes_color5'] == null) ? '' : $rslogin['themes_color5'],
        'themes_app1'   		=>  ($rslogin['themes_app1'] == null) ? '' : $rslogin['themes_app1'],
        'themes_app2'   		=>  ($rslogin['themes_app2'] == null) ? '' : $rslogin['themes_app2'],
        'themes_app3'   		=>  ($rslogin['themes_app3'] == null) ? '' : $rslogin['themes_app3'],
        'themes_app4'   		=>  ($rslogin['themes_app4'] == null) ? '' : $rslogin['themes_app4'],
        'themes_app5'   		=>  ($rslogin['themes_app5'] == null) ? '' : $rslogin['themes_app5'],
        'themes_menu1'   		=>  ($rslogin['themes_menu1'] == null) ? '' : $rslogin['themes_menu1'],
        'themes_menu2'   		=>  ($rslogin['themes_menu2'] == null) ? '' : $rslogin['themes_menu2'],
        'themes_menu3'   		=>  ($rslogin['themes_menu3'] == null) ? '' : $rslogin['themes_menu3'],
        'themes_menu4'   		=>  ($rslogin['themes_menu4'] == null) ? '' : $rslogin['themes_menu4'],
        'themes_menu5'   		=>  ($rslogin['themes_menu5'] == null) ? '' : $rslogin['themes_menu5'],
        'design_app_on_off_all' =>  ($rslogin['design_app_on_off_all'] == null) ? '' : $rslogin['design_app_on_off_all'],
        'SourceSystem'   		=>  ($rslogin['SourceSystem'] == null) ? '' : $rslogin['SourceSystem'],
        'SALECODE'   			=>  ($rslogin['SALECODE'] == null) ? '' : $rslogin['SALECODE'],
        'DepartId'   			=>  ($rslogin['DepartId'] == null) ? '' : $rslogin['DepartId'],
        'mcode'   				=>  ($rslogin['mcode'] == null) ? '' : $rslogin['mcode'],
        'CashTeamCode'   		=>  ($rslogin['CashTeamCode'] == null) ? '' : $rslogin['CashTeamCode'],
        'debug_run_error_checker1'   		=>  '1'	
    );
    array_push($resultA,$result);

    





}
echo json_encode(array('data' => $resultA));
sqlsrv_free_stmt( $stmt);
?>